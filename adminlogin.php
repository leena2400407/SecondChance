<?php
/**
 * Administrator Console - Second Chance Portal
 * Matches phpMyAdmin schema: id, fname, lname, email, phone, role, created_at
 */

session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// Strict Admin Gate
requireAdmin('login.php?type=admin');

$currentUser = currentUser();

// Fetch users
$usersQuery = "SELECT * FROM users ORDER BY id ASC";
$usersResult = mysqli_query($conn, $usersQuery);
$allUsers = [];
$totalAdmins = 0;
$totalStudents = 0;

if ($usersResult) {
    while ($row = mysqli_fetch_assoc($usersResult)) {
        $allUsers[] = $row;
        if ($row['role'] === 'admin') {
            $totalAdmins++;
        } else {
            $totalStudents++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard - Second Chance</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <?php include __DIR__ . '/includes/toast.php'; ?>
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <div class="main-wrapper">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2>Administrator Console 🛡️</h2>
                <p style="color: var(--text-muted);">Manage platform accounts and oversee university portal security</p>
            </div>
            <div>
                <span class="user-badge badge-admin" style="font-size: 0.9rem; padding: 0.4rem 0.85rem;">
                    Admin: <?php echo htmlspecialchars($currentUser['name']); ?>
                </span>
            </div>
        </div>

        <!-- Metrics Overview -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div class="spec-box" style="margin: 0; background: var(--white); border-left-color: var(--primary-dark);">
                <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Total Registered Accounts</div>
                <div style="font-size: 2rem; font-weight: 800; color: var(--primary-dark);"><?php echo count($allUsers); ?></div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">In `users` table</div>
            </div>

            <div class="spec-box" style="margin: 0; background: var(--white); border-left-color: var(--primary-slate);">
                <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Student Users (role = 'user')</div>
                <div style="font-size: 2rem; font-weight: 800; color: var(--primary-slate);"><?php echo $totalStudents; ?></div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Active campus students</div>
            </div>

            <div class="spec-box" style="margin: 0; background: var(--white); border-left-color: #E2A65E;">
                <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Administrators (role = 'admin')</div>
                <div style="font-size: 2rem; font-weight: 800; color: #E2A65E;"><?php echo $totalAdmins; ?></div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">System administrators</div>
            </div>
        </div>

        <!-- Users Table -->
        <div style="background: var(--white); border-radius: var(--radius-md); padding: 1.5rem; box-shadow: var(--shadow-card); border: 1px solid var(--border-light); overflow-x: auto;">
            <h3 style="margin-bottom: 1rem; color: var(--primary-dark);">Registered Users (`users` table)</h3>
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-light); background: var(--input-bg);">
                        <th style="padding: 0.75rem 1rem;">ID</th>
                        <th style="padding: 0.75rem 1rem;">First Name (fname)</th>
                        <th style="padding: 0.75rem 1rem;">Last Name (lname)</th>
                        <th style="padding: 0.75rem 1rem;">Email Address</th>
                        <th style="padding: 0.75rem 1rem;">Phone</th>
                        <th style="padding: 0.75rem 1rem;">Role</th>
                        <th style="padding: 0.75rem 1rem;">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allUsers as $u): ?>
                        <tr style="border-bottom: 1px solid var(--border-light);">
                            <td style="padding: 0.75rem 1rem; font-weight: bold; color: var(--primary-dark);">
                                #<?php echo htmlspecialchars($u['id']); ?>
                            </td>
                            <td style="padding: 0.75rem 1rem; font-weight: 600;">
                                <?php echo htmlspecialchars($u['fname']); ?>
                            </td>
                            <td style="padding: 0.75rem 1rem; font-weight: 600;">
                                <?php echo htmlspecialchars($u['lname']); ?>
                            </td>
                            <td style="padding: 0.75rem 1rem; color: var(--primary-slate);">
                                <?php echo htmlspecialchars($u['email']); ?>
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                <?php echo htmlspecialchars($u['phone'] ?? 'N/A'); ?>
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="user-badge badge-admin">admin</span>
                                <?php else: ?>
                                    <span class="user-badge badge-student">user</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 0.75rem 1rem; font-size: 0.8rem; color: var(--text-muted);">
                                <?php echo htmlspecialchars($u['created_at'] ?? 'N/A'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>
