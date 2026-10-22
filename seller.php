<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
require_once __DIR__ . '/includes/db.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$sellerId = (int) $_SESSION['user_id'];
$errors = [];
$products = [];
$stats = ['available' => 0, 'reserved' => 0, 'sold' => 0, 'hidden' => 0];
$total = 0;
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 12;
$query = is_string($_GET['q'] ?? null) ? mb_substr(trim($_GET['q']), 0, 100) : '';
$status = is_string($_GET['status'] ?? null) ? $_GET['status'] : '';
$labels = ['available' => 'Available', 'reserved' => 'Reserved', 'sold' => 'Sold', 'hidden' => 'Hidden'];

if (!isset($labels[$status])) {
    $status = '';
}

$flash = $_SESSION['seller_flash'] ?? '';
unset($_SESSION['seller_flash']);

function sellerStatement($sql, $types = '', $values = [])
{
    global $conn;

    $stmt = mysqli_prepare($conn, $sql);

    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$values);
    }

    mysqli_stmt_execute($stmt);
    return $stmt;
}

function sellerRows($sql, $types = '', $values = [])
{
    $stmt = sellerStatement($sql, $types, $values);
    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}

// Display only local product-upload paths; do not accept arbitrary URLs.
function sellerImage($path)
{
    if (!is_string($path)) {
        return null;
    }

    $path = str_replace('\\', '/', $path);

    if (!preg_match('~\Auploads/(?:products/)?[a-zA-Z0-9_-]+\.(?:jpg|jpeg|png|webp)\z~i', $path)) {
        return null;
    }

    return is_file(__DIR__ . '/' . $path) ? $path : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inTransaction = false;

    try {
        if (!validToken()) {
            throw new RuntimeException('Your form expired. Refresh the page and try again.');
        }

        $id = filter_var(
            $_POST['product_id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        $action = $_POST['action'] ?? '';

        if (!$id || !in_array($action, ['hide', 'restore', 'sold', 'relist'], true)) {
            throw new RuntimeException('Choose a valid product action.');
        }

        mysqli_begin_transaction($conn);
        $inTransaction = true;

        $owned = sellerRows(
            'SELECT id, status FROM products WHERE id = ? AND seller_id = ? FOR UPDATE',
            'ii',
            [$id, $sellerId]
        );

        if (!$owned) {
            throw new RuntimeException('This listing does not belong to your account.');
        }

        $current = $owned[0]['status'];

        if ($action === 'hide' && $current !== 'available') {
            throw new RuntimeException('Only available products can be hidden.');
        }

        if ($action === 'restore' && $current !== 'hidden') {
            throw new RuntimeException('Only hidden products can be restored.');
        }

        if ($action === 'sold' && !in_array($current, ['available', 'hidden'], true)) {
            throw new RuntimeException('Only available or hidden listings can be marked as sold.');
        }
        if ($action === 'relist' && $current !== 'sold') {
            throw new RuntimeException('Only sold listings can be listed again.');
        }
        $nextStatus = ['hide' => 'hidden', 'restore' => 'available', 'sold' => 'sold', 'relist' => 'available'][$action];

        $stmt = sellerStatement(
            'UPDATE products SET status = ? WHERE id = ? AND seller_id = ? AND status = ?',
            'siis',
            [$nextStatus, $id, $sellerId, $current]
        );

        $changed = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        if ($changed !== 1) {
            throw new RuntimeException('The listing was not updated. Refresh and try again.');
        }

        mysqli_commit($conn);
        $inTransaction = false;

        $_SESSION['seller_flash'] = [
            'hide' => 'Listing hidden successfully.',
            'restore' => 'Listing is available again.',
            'sold' => 'Listing marked as sold.',
            'relist' => 'Listing is available again.'
        ][$action];

        header('Location: seller.php');
        exit;
    } catch (Throwable $e) {
        if ($inTransaction) {
            mysqli_rollback($conn);
        }

        if ($e instanceof mysqli_sql_exception) {
            error_log($e->getMessage());
            $errors[] = 'Could not update the listing. Confirm that products.status supports available, hidden and sold, then try again. The PHP error log contains the database error.';
        } else {
            $errors[] = $e->getMessage();
        }
    }
}

try {
    $columnRows = sellerRows('SHOW COLUMNS FROM products');
    $columns = array_column($columnRows, 'Field');

    // Accept common product-name columns while the schema is being aligned.
    $titleColumn = null;

    foreach (['title', 'product_title', 'name', 'product_name'] as $candidate) {
        if (in_array($candidate, $columns, true)) {
            $titleColumn = $candidate;
            break;
        }
    }

    $missing = array_diff(['id', 'seller_id', 'category_id', 'price', 'status'], $columns);

    if (!$titleColumn || $missing) {
        throw new RuntimeException(
            'The products table needs a product-name column and the id, seller_id, category_id, price, and status columns. Check its Structure in phpMyAdmin.'
        );
    }

    $counts = sellerRows(
        'SELECT status, COUNT(*) AS quantity FROM products WHERE seller_id = ? GROUP BY status',
        'i',
        [$sellerId]
    );

    foreach ($counts as $count) {
        if (isset($stats[$count['status']])) {
            $stats[$count['status']] = (int) $count['quantity'];
        }
    }

    $where = ' WHERE p.seller_id = ?';
    $types = 'i';
    $values = [$sellerId];

    if ($query !== '') {
        $where .= ' AND p.`' . $titleColumn . '` LIKE ?';
        $types .= 's';
        $values[] = '%' . $query . '%';
    }

    if ($status !== '') {
        $where .= ' AND p.status = ?';
        $types .= 's';
        $values[] = $status;
    }

    $total = (int) sellerRows(
        'SELECT COUNT(*) AS quantity FROM products p' . $where,
        $types,
        $values
    )[0]['quantity'];

    $pages = max(1, (int) ceil($total / $perPage));
    $page = min($page, $pages);
    $offset = ($page - 1) * $perPage;

    $imageField = in_array('image', $columns, true) ? 'p.image' : 'NULL';
    $dateField = in_array('created_at', $columns, true) ? 'p.created_at' : 'NULL';

    $products = sellerRows(
        'SELECT p.id, p.`' . $titleColumn . '` AS title, p.price, p.status,
                ' . $imageField . ' AS image, ' . $dateField . ' AS created_at,
                c.name AS category
         FROM products p
         LEFT JOIN categories c ON c.id = p.category_id' . $where . '
         ORDER BY p.id DESC
         LIMIT ' . $perPage . ' OFFSET ' . $offset,
        $types,
        $values
    );
} catch (Throwable $e) {
    error_log($e->getMessage());
    $errors[] = $e instanceof mysqli_sql_exception
        ? 'Could not load your listings. Check the products and categories tables.'
        : $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Listings | Second Chance</title>
    <link rel="stylesheet" href="assets/seller.css?v=20261018-seller2">
    <link rel="stylesheet" href="assets/index.css">
</head>
<body>
    <?php require __DIR__ . '/includes/navbar.php'; ?>

    <main class="dashboard">
        <header class="page-heading">
            <div>
                <p class="eyebrow">Your seller space</p>
                <h1>My Listings</h1>
                <p class="subtitle">Hello, <?= escape($_SESSION['fname'] ?? 'Student') ?>. Give your gear another semester.</p>
            </div>
            <a class="button" href="product-form.php">+ List an Item</a>
        </header>

        <?php if ($flash !== ''): ?>
            <p class="message success" role="status"><?= escape($flash) ?></p>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="message error" role="alert">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= escape($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <section class="stats" aria-label="Your listing totals">
                <?php foreach ($labels as $key => $label): ?>
                    <a class="stat" href="?status=<?= $key ?>">
                        <span><?= $label ?></span>
                        <strong><?= $stats[$key] ?></strong>
                    </a>
                <?php endforeach; ?>
            </section>

            <form class="filters" method="get">
                <div class="search-field">
                    <label for="search">Find a listing</label>
                    <input id="search" type="search" name="q" value="<?= escape($query) ?>" maxlength="100" placeholder="Search your products…">
                </div>
                <div>
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All statuses</option>
                        <?php foreach ($labels as $key => $label): ?>
                            <option value="<?= $key ?>" <?= $status === $key ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Search</button>
                <a class="reset" href="seller.php">Reset</a>
            </form>

            <div class="section-heading">
                <h2>Your campus collection</h2>
                <span><?= $total ?> matching listing<?= $total === 1 ? '' : 's' ?></span>
            </div>

            <?php if (!$products): ?>
                <section class="empty">
                    <span class="empty-symbol" aria-hidden="true">↗</span>
                    <h2><?= array_sum($stats) === 0 ? 'Your first listing starts here.' : 'No matching listings.' ?></h2>
                    <p><?= array_sum($stats) === 0 ? 'Have a book, calculator, or lab kit you no longer use? Give it a second chance.' : 'Try another search or reset your filters.' ?></p>
                    <a class="button" href="<?= array_sum($stats) === 0 ? 'product-form.php' : 'seller.php' ?>"><?= array_sum($stats) === 0 ? 'List your first item' : 'Show all my listings' ?></a>
                </section>
            <?php else: ?>
                <section class="products" aria-label="Your products">
                    <?php foreach ($products as $product): ?>
                        <?php $image = sellerImage($product['image']); ?>
                        <article class="product-card">
                            <a class="product-photo" href="product-details.php?id=<?= (int) $product['id'] ?>" aria-label="View <?= escape($product['title']) ?>">
                                <?php if ($image): ?>
                                    <img src="<?= escape($image) ?>" alt="<?= escape($product['title']) ?>" loading="lazy">
                                <?php else: ?>
                                    <span class="photo-placeholder">No photo</span>
                                <?php endif; ?>
                                <span class="badge <?= escape($product['status']) ?>"><?= escape($labels[$product['status']] ?? $product['status']) ?></span>
                            </a>
                            <div class="product-content">
                                <p class="category"><?= escape($product['category'] ?? 'Uncategorized') ?></p>
                                <h3><a href="product-details.php?id=<?= (int) $product['id'] ?>"><?= escape($product['title']) ?></a></h3>
                                <strong class="price"><?= number_format((float) $product['price'], 2) ?> <small>EGP</small></strong>
                                <p class="meta">Listing #<?= (int) $product['id'] ?><?php if ($product['created_at']): ?> · <?= escape(substr($product['created_at'], 0, 10)) ?><?php endif; ?></p>

                                <div class="listing-actions">
                                    <a class="button secondary" href="product-details.php?id=<?= (int) $product['id'] ?>">View details</a>
                                    <?php if ($product['status'] !== 'reserved'): ?>
                                        <a class="button secondary" href="product-form.php?id=<?= (int) $product['id'] ?>">Edit listing</a>
                                    <?php endif; ?>
                                    <?php
                                    $actions = [
                                        'available' => ['hide' => 'Hide listing', 'sold' => 'Mark as Sold'],
                                        'hidden' => ['restore' => 'Make available', 'sold' => 'Mark as Sold'],
                                        'sold' => ['relist' => 'List again'],
                                        'reserved' => []
                                    ][$product['status']] ?? [];
                                    ?>
                                    <?php foreach ($actions as $action => $label): ?>
                                        <form method="post" action="seller.php" class="product-action" data-confirm="<?= escape($action === 'sold' ? 'Mark this item as sold?' : ($action === 'relist' ? 'Make this sold item available to buyers again?' : '')) ?>">
                                            <input type="hidden" name="csrf_token" value="<?= escape($_SESSION['csrf_token']) ?>">
                                            <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                                            <input type="hidden" name="action" value="<?= escape($action) ?>">
                                            <button class="<?= $action === 'sold' ? 'sold-button' : 'secondary' ?>" type="submit"><?= escape($label) ?></button>
                                        </form>
                                    <?php endforeach; ?>
                                </div>
                                <?php if ($product['status'] === 'reserved'): ?>
                                    <p class="status-note">This listing is reserved. Resolve its order before changing it.</p>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </section>

                <nav class="pagination" aria-label="Listing pages">
                    <?php if ($page > 1): ?>
                        <a href="?<?= escape(http_build_query(['q' => $query, 'status' => $status, 'page' => $page - 1])) ?>">← Previous</a>
                    <?php endif; ?>
                    <span>Page <?= $page ?> of <?= $pages ?></span>
                    <?php if ($page < $pages): ?>
                        <a href="?<?= escape(http_build_query(['q' => $query, 'status' => $status, 'page' => $page + 1])) ?>">Next →</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </main>
<script>
document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', event => {
        if (form.dataset.confirm && !window.confirm(form.dataset.confirm)) event.preventDefault();
    });
});

</script>
<script src="assets/script.js"></script>
<?php sessionTimerMarkup(); ?>
</body>
</html>