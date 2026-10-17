<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

requireAdmin();

$currentUser = currentUser();

$allUsers = [];
$totalAdmins = 0;
$totalStudents = 0;
$errorMessage = '';

try {
    $sql = '
        SELECT id, fname, lname, email, phone, role, created_at
        FROM users
        ORDER BY id DESC
    ';

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $allUsers[] = $row;

        if ($row['role'] === 'admin') {
            $totalAdmins++;
        } else {
            $totalStudents++;
        }
    }

    mysqli_free_result($result);
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    $errorMessage = 'Could not load users. Please try again later.';
}

$totalUsers = count($allUsers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | Second Chance</title>

    <!-- Shared navbar styles -->
    <link rel="stylesheet" href="assets/index.css">

    <style>
        .admin-page {
            max-width: 1240px;
            margin: 0 auto;
            padding: 40px 24px 70px;
        }

        .admin-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .admin-heading h1 {
            margin: 8px 0;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            line-height: 1.2;
            color: #22223b;
        }

        .admin-heading p {
            color: #4a4e69;
        }

        .admin-eyebrow {
            color: #4a4e69;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .admin-account {
            padding: 10px 16px;
            border: 1px solid #c9ada7;
            border-radius: 30px;
            background: #f2e9e4;
            color: #22223b;
            font-size: 14px;
        }

        .admin-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .admin-stat {
            padding: 26px;
            background: #fff;
            border: 1px solid #e3dce1;
            border-radius: 16px;
        }

        .admin-stat-label {
            color: #4a4e69;
            font-size: 14px;
        }

        .admin-stat-number {
            margin: 10px 0;
            color: #22223b;
            font-size: 36px;
            font-weight: 800;
            line-height: 1.2;
        }

        .admin-stat-description {
            color: #4a4e69;
            font-size: 13px;
        }

        .admin-panel {
            overflow: hidden;
            background: #fff;
            border: 1px solid #e3dce1;
            border-radius: 16px;
        }

        .admin-panel-heading {
            padding: 24px;
            border-bottom: 1px solid #e3dce1;
        }

        .admin-panel-heading h2 {
            margin-bottom: 6px;
            color: #22223b;
            font-size: 20px;
        }

        .admin-panel-heading p {
            color: #4a4e69;
            font-size: 14px;
        }

        .admin-table-wrapper {
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            min-width: 850px;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        .admin-table th {
            padding: 16px 20px;
            background: #f8f5f3;
            color: #4a4e69;
            font-size: 12px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .admin-table td {
            padding: 18px 20px;
            border-top: 1px solid #eee8ec;
            color: #22223b;
            vertical-align: middle;
            overflow-wrap: anywhere;
        }

        .admin-table tbody tr:hover {
            background: #faf7f5;
        }

        .admin-role {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .admin-role-admin {
            background: #22223b;
            color: #fff;
        }

        .admin-role-user {
            background: #eae5e9;
            color: #4a4e69;
        }

        .admin-empty {
            padding: 35px;
            text-align: center;
            color: #4a4e69;
        }

        .admin-error {
            padding: 16px;
            margin-bottom: 24px;
            border: 1px solid #f0bcc3;
            border-radius: 10px;
            background: #fff0f1;
            color: #a32236;
        }

        .admin-back {
            display: inline-block;
            margin-top: 24px;
            color: #4a4e69;
            font-weight: 600;
        }

        .admin-back:hover {
            text-decoration: underline;
        }

        @media (max-width: 700px) {
            .admin-page {
                padding: 28px 16px 50px;
            }

            .admin-stats {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .admin-stat {
                padding: 20px;
            }

            .admin-panel-heading {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="admin-page">

    <div class="admin-heading">
        <div>
            <span class="admin-eyebrow">
                Second Chance Administration
            </span>

            <h1>Admin Dashboard</h1>

            <p>
                View registered accounts and platform user totals.
            </p>
        </div>

        <div class="admin-account">
            Admin: <?= escape($currentUser['full_name']) ?>
        </div>
    </div>

    <?php if ($errorMessage !== ''): ?>

        <div class="admin-error" role="alert">
            <?= escape($errorMessage) ?>
        </div>

    <?php else: ?>

        <!-- Statistics -->
        <section class="admin-stats" aria-label="Account statistics">

            <div class="admin-stat">
                <div class="admin-stat-label">
                    Total Accounts
                </div>

                <div class="admin-stat-number">
                    <?= $totalUsers ?>
                </div>

                <div class="admin-stat-description">
                    All registered accounts
                </div>
            </div>

            <div class="admin-stat">
                <div class="admin-stat-label">
                    Student Accounts
                </div>

                <div class="admin-stat-number">
                    <?= $totalStudents ?>
                </div>

                <div class="admin-stat-description">
                    Accounts with the user role
                </div>
            </div>

            <div class="admin-stat">
                <div class="admin-stat-label">
                    Administrators
                </div>

                <div class="admin-stat-number">
                    <?= $totalAdmins ?>
                </div>

                <div class="admin-stat-description">
                    Accounts with the admin role
                </div>
            </div>

        </section>

        <!-- Users -->
        <section class="admin-panel" aria-labelledby="users-heading">

            <div class="admin-panel-heading">
                <h2 id="users-heading">Registered Users</h2>

                <p>
                    Account details, with the newest accounts first.
                </p>
            </div>

            <?php if (empty($allUsers)): ?>

                <p class="admin-empty">
                    No registered users found.
                </p>

            <?php else: ?>

                <div class="admin-table-wrapper"
                     tabindex="0"
                     role="region"
                     aria-label="Registered users table">

                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Role</th>
                                <th scope="col">Created At</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($allUsers as $user): ?>

                                <tr>
                                    <td>
                                        #<?= escape($user['id']) ?>
                                    </td>

                                    <td>
                                        <?= escape($user['fname']) ?>
                                    </td>

                                    <td>
                                        <?= escape($user['lname']) ?>
                                    </td>

                                    <td>
                                        <?= escape($user['email']) ?>
                                    </td>

                                    <td>
                                        <?= escape(
                                            $user['phone'] ?: 'Not provided'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?php if ($user['role'] === 'admin'): ?>

                                            <span class="admin-role admin-role-admin">
                                                Admin
                                            </span>

                                        <?php else: ?>

                                            <span class="admin-role admin-role-user">
                                                User
                                            </span>

                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?= escape(
                                            $user['created_at'] ?? 'Not available'
                                        ) ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>

            <?php endif; ?>

        </section>

    <?php endif; ?>

    <a href="<?= BASE_URL ?>/index.php" class="admin-back">
        ← Back to Home
    </a>

</main>

<script src="assets/index.js"></script>

</body>
</html>