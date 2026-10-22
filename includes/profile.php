
<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$errors = [];
$user = null;
$fname = '';
$lname = '';
$phone = '';
$success = $_SESSION['profile_success'] ?? '';

unset($_SESSION['profile_success']);

$userId = (int) $_SESSION['user_id'];

try {
    require_once __DIR__ . '/includes/db.php';

    $stmt = mysqli_prepare(
        $conn,
        'SELECT id, fname, lname, email, phone, password, created_at
         FROM users WHERE id = ? LIMIT 1'
    );

    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);

    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    mysqli_stmt_close($stmt);

    if (!$user) {
        clearLoginSession();
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }

    $fname = $user['fname'];
    $lname = $user['lname'];
    $phone = $user['phone'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = formText('action');

        if (!validToken()) {
            $errors[] = 'Please refresh the page and try again.';
        } elseif ($action === 'details') {
            $fname = trim(formText('fname'));
            $lname = trim(formText('lname'));
            $phone = trim(formText('phone'));

            // Same server-side validation used by signup.
            if (!validPersonName($fname)) {
                $errors[] =
                    'First name must contain 2 to 50 characters: letters, spaces, apostrophes or hyphens.';
            }

            if (!validPersonName($lname)) {
                $errors[] =
                    'Last name must contain 2 to 50 characters: letters, spaces, apostrophes or hyphens.';
            }

            if ($phone !== '' && !validPhoneNumber($phone)) {
                $errors[] =
                    'Phone number must contain 7 to 15 digits, with an optional leading +.';
            }

            if (!$errors) {
                $phoneValue = $phone === '' ? null : $phone;

                // Email is intentionally not included in this update.
                $stmt = mysqli_prepare(
                    $conn,
                    'UPDATE users
                     SET fname = ?, lname = ?, phone = ?
                     WHERE id = ?'
                );

                mysqli_stmt_bind_param(
                    $stmt,
                    'sssi',
                    $fname,
                    $lname,
                    $phoneValue,
                    $userId
                );

                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $_SESSION['fname'] = $fname;
                $_SESSION['full_name'] = $fname . ' ' . $lname;

                $_SESSION['profile_success'] =
                    'Your profile has been updated successfully.';

                header('Location: ' . BASE_URL . '/profile.php');
                exit;
            }
        } elseif ($action === 'password') {
            $current = formPassword('current_password');
            $new = formPassword('new_password');
            $confirm = formPassword('confirm_password');

            if (
                $current === ''
                || strlen($current) > 72
                || strpos($current, "\0") !== false
                || !is_string($user['password'])
                || !password_verify($current, $user['password'])
            ) {
                $errors[] = 'Your current password is incorrect.';
            }

            if (
                strpos($new, "\0") !== false
                || !validNewPassword($new)
            ) {
                $errors[] =
                    'Password must contain 8 to 72 bytes, including an English letter and a number.';
            }

            if ($new !== $confirm) {
                $errors[] = 'New passwords do not match.';
            }

            if (
                !$errors
                && password_verify($new, $user['password'])
            ) {
                $errors[] =
                    'Choose a password different from your current password.';
            }

            if (!$errors) {
                $newHash = password_hash($new, PASSWORD_DEFAULT);
                $oldHash = $user['password'];

                // Do not overwrite a password changed by another request.
                $stmt = mysqli_prepare(
                    $conn,
                    'UPDATE users
                     SET password = ?
                     WHERE id = ? AND BINARY password = ?'
                );

                mysqli_stmt_bind_param(
                    $stmt,
                    'sis',
                    $newHash,
                    $userId,
                    $oldHash
                );

                mysqli_stmt_execute($stmt);
                $changed = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);

                if ($changed === 1) {
                    session_regenerate_id(true);
                    $_SESSION['last_activity'] = time();

                    $_SESSION['profile_success'] =
                        'Your password has been changed successfully.';

                    header('Location: ' . BASE_URL . '/profile.php');
                    exit;
                }

                $errors[] =
                    'Your account changed during this request. Please refresh and try again.';
            }
        } else {
            $errors[] = 'Invalid profile action.';
        }
    }
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());

    $errors[] =
        'Could not load or update your profile. Please try again.';
}

$displayName = $user
    ? $user['fname'] . ' ' . $user['lname']
    : 'My Profile';

$initial = $user
    ? mb_strtoupper(
        mb_substr($user['fname'], 0, 1, 'UTF-8'),
        'UTF-8'
    )
    : '?';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Profile | Second Chance</title>

    <link rel="stylesheet" href="assets/index.css">
    <link rel="stylesheet" href="assets/profile.css">
</head>
<body>

<?php require __DIR__ . '/includes/navbar.php'; ?>

<main class="sc-profile">
    <header class="sc-profile-header">
        <span class="sc-profile-eyebrow">SECOND CHANCE ACCOUNT</span>
        <h1>My Profile</h1>
        <p class="intro">
            Manage your personal information, password, and saved products.
        </p>
    </header>

    <?php if ($success !== ''): ?>
        <div class="sc-profile-notice" role="status">
            <?= escape($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="sc-profile-notice error" role="alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= escape($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($user): ?>

    <div class="sc-profile-grid">

        <aside class="sc-profile-card sc-profile-summary">
            <div class="sc-profile-avatar">
                <?= escape($initial) ?>
            </div>

            <h2><?= escape($displayName) ?></h2>

            <p class="sc-profile-email">
                <?= escape($user['email']) ?>
            </p>

            <span class="sc-profile-member">
                Campus Member
            </span>

            <a class="sc-profile-button" href="seller.php">
                My Listings
            </a>

            <a class="sc-profile-button secondary-button"
               href="#wishlist">
                My Wishlist
            </a>
        </aside>

        <div class="sc-profile-main">

            <section class="sc-profile-card"
                     aria-labelledby="detailsHeading">

                <div class="sc-profile-section-heading">
                    <span class="sc-profile-section-icon">01</span>
                    <div>
                        <h2 id="detailsHeading">Personal Information</h2>
                        <p>Keep your account details up to date.</p>
                    </div>
                </div>

                <form method="post" action="profile.php">
                    <input type="hidden"
                           name="csrf_token"
                           value="<?= escape($_SESSION['csrf_token']) ?>">

                    <input type="hidden" name="action" value="details">

                    <div class="sc-profile-row">
                        <div class="field">
                            <label for="fname">First name</label>
                            <input
                                type="text"
                                id="fname"
                                name="fname"
                                value="<?= escape($fname) ?>"
                                minlength="2"
                                maxlength="50"
                                autocomplete="given-name"
                                required
                            >
                        </div>

                        <div class="field">
                            <label for="lname">Last name</label>
                            <input
                                type="text"
                                id="lname"
                                name="lname"
                                value="<?= escape($lname) ?>"
                                minlength="2"
                                maxlength="50"
                                autocomplete="family-name"
                                required
                            >
                        </div>
                    </div>

                    <div class="field">
                        <label for="email">Email address</label>

                        <input
                            type="email"
                            id="email"
                            value="<?= escape($user['email']) ?>"
                            readonly
                            aria-describedby="emailHint"
                        >

                        <small id="emailHint">
                            Your email cannot be changed from your profile.
                        </small>
                    </div>

                    <div class="field">
                        <label for="phone">Phone number (optional)</label>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="<?= escape($phone) ?>"
                            maxlength="16"
                            pattern="\+?[0-9]{7,15}"
                            autocomplete="tel"
                            aria-describedby="phoneHint"
                        >

                        <small id="phoneHint">
                            Use 7 to 15 digits, with an optional leading +.
                        </small>
                    </div>

                    <button class="sc-profile-button" type="submit">
                        Save Changes
                    </button>
                </form>
            </section>

            <section class="sc-profile-card"
                     aria-labelledby="passwordHeading">

                <div class="sc-profile-section-heading">
                    <span class="sc-profile-section-icon">02</span>
                    <div>
                        <h2 id="passwordHeading">Change Password</h2>
                        <p>Choose a secure password for your account.</p>
                    </div>
                </div>

                <form method="post" action="profile.php">
                    <input type="hidden"
                           name="csrf_token"
                           value="<?= escape($_SESSION['csrf_token']) ?>">

                    <input type="hidden" name="action" value="password">

                    <div class="field">
                        <label for="current_password">Current password</label>
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            maxlength="72"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <div class="field">
                        <label for="new_password">New password</label>
                        <input
                            type="password"
                            id="new_password"
                            name="new_password"
                            maxlength="72"
                            autocomplete="new-password"
                            aria-describedby="passwordHint"
                            required
                        >

                        <small id="passwordHint">
                            Use 8 to 72 bytes, including an English letter
                            and a number.
                        </small>
                    </div>

                    <div class="field">
                        <label for="confirm_password">
                            Confirm new password
                        </label>

                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            maxlength="72"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button class="sc-profile-button" type="submit">
                        Change Password
                    </button>
                </form>
            </section>

        </div>
    </div>

    <section class="sc-profile-card sc-wishlist-section"
             id="wishlist"
             aria-labelledby="wishlistHeading">

        <div class="sc-profile-section-heading">
            <span class="sc-profile-section-icon">03</span>
            <div>
                <h2 id="wishlistHeading">My Wishlist</h2>
                <p>Products you have saved for later.</p>
            </div>
        </div>

        <div id="wishlistGrid" class="sc-wishlist-grid"
             aria-live="polite">
            <p class="sc-wishlist-loading">Loading your saved products...</p>
        </div>
    </section>

    <?php endif; ?>
</main>

<?php sessionTimerMarkup(); ?>

<script src="assets/script.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('wishlistGrid');

    if (!grid) return;

    const wishlistKey = 'secondChanceWishlist';

    function getWishlistIds() {
        try {
            const saved = JSON.parse(
                localStorage.getItem(wishlistKey) || '[]'
            );

            return Array.isArray(saved)
                ? [...new Set(saved.map(Number).filter(Number.isFinite))]
                : [];
        } catch (error) {
            return [];
        }
    }

    function escapeHTML(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function renderWishlist() {
        const products = window.SC_PRODUCTS || [];
        const ids = getWishlistIds();

        const savedProducts = ids
            .map(id => products.find(product => Number(product.id) === id))
            .filter(Boolean);

        if (savedProducts.length === 0) {
            grid.innerHTML = `
                <div class="sc-wishlist-empty">
                    <div class="sc-wishlist-empty-icon">♡</div>
                    <h3>Your wishlist is empty</h3>
                    <p>Explore the marketplace and tap the heart
                       on products you want to save.</p>
                    <a class="sc-profile-button"
                       href="index.php#showcaseSection">
                        Explore Products
                    </a>
                </div>
            `;
            return;
        }

        grid.innerHTML = savedProducts.map(product => `
            <article class="sc-wishlist-item">
                <button type="button"
                        class="sc-wishlist-remove"
                        data-remove-id="${Number(product.id)}"
                        aria-label="Remove ${escapeHTML(product.title)} from wishlist"
                        title="Remove from wishlist">
                    &times;
                </button>

                <button type="button"
                        class="sc-wishlist-product"
                        data-open-id="${Number(product.id)}">
                    <img src="${escapeHTML(product.image)}"
                         alt="${escapeHTML(product.title)}"
                         loading="lazy">

                    <div class="sc-wishlist-info">
                        <span class="sc-wishlist-category">
                            ${escapeHTML(product.majorName)}
                        </span>

                        <h3>${escapeHTML(product.title)}</h3>

                        <p class="sc-wishlist-price">
                            ${Number(product.price)} EGP
                        </p>

                        <span class="sc-wishlist-view">
                            View product →
                        </span>
                    </div>
                </button>
            </article>
        `).join('');
    }

    grid.addEventListener('click', function (event) {
        const removeButton = event.target.closest('[data-remove-id]');

        if (removeButton) {
            const id = Number(removeButton.dataset.removeId);
            const ids = getWishlistIds().filter(savedId => savedId !== id);

            localStorage.setItem(wishlistKey, JSON.stringify(ids));
            renderWishlist();

            if (typeof window.showToast === 'function') {
                window.showToast('Removed from your wishlist.');
            }

            return;
        }

        const productButton = event.target.closest('[data-open-id]');

        if (productButton) {
            const id = Number(productButton.dataset.openId);
            const product = (window.SC_PRODUCTS || [])
                .find(item => Number(item.id) === id);

            if (!product) return;

            localStorage.setItem('selectedProduct', JSON.stringify(product));

            window.location.href = 'product-details.php?id=' + id;
        }
    });

    window.addEventListener('storage', function (event) {
        if (event.key === wishlistKey) renderWishlist();
    });

    renderWishlist();
});
</script>

</body>
</html>
