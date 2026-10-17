<?php
/**
 * Navigation Bar - Second Chance
 * Author: Karim
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/auth.php';

$logged_in = isLoggedIn();
$user      = currentUser();
?>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="nav-brand">
            <div class="brand-icon">2C</div>
            <div>
                <div class="brand-title">Second Chance</div>
                <div class="brand-subtitle">University Portal</div>
            </div>
        </a>

        <ul class="nav-menu">
            <?php if ($logged_in): ?>
                <li>
                    <span class="user-badge <?php echo isAdmin() ? 'badge-admin' : 'badge-student'; ?>">
                        <?php echo isAdmin() ? 'Administrator' : 'Student'; ?>
                    </span>
                </li>
                <li>
                    <a href="viewprofile.php" class="nav-link" style="font-weight: 600;">
                        <?php echo htmlspecialchars($user['name'] ?? 'User'); ?>
                    </a>
                </li>
                <li>
                    <a href="logout.php" class="btn btn-outline" style="padding: 0.35rem 0.85rem; font-size: 0.85rem; border-color: var(--soft-rose); color: var(--cream-bg);">
                        Log Out
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="login.php" class="nav-link">Log In</a>
                </li>
                <li>
                    <a href="signup.php" class="btn btn-primary" style="padding: 0.45rem 1.1rem; font-size: 0.88rem; background-color: var(--soft-rose); color: var(--primary-dark); font-weight: 700;">
                        Sign Up
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
