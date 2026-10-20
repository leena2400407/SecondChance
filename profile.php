<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$errors = [];
$user = null;
$fname = '';
$lname = '';
$phone = '';
$action = '';
$userId = (int) $_SESSION['user_id'];
$success = $_SESSION['profile_success'] ?? '';
unset($_SESSION['profile_success']);

try {
    require_once __DIR__ . '/includes/db.php';
    $stmt = mysqli_prepare($conn,
        'SELECT id, fname, lname, email, phone, password, created_at FROM users WHERE id = ? LIMIT 1');
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
            $fname = formText('fname');
            $lname = formText('lname');
            $phone = formText('phone');
            if (!validPersonName($fname)) {
                $errors[] = 'First name must contain 2 to 50 characters: letters, spaces, apostrophes or hyphens.';
            }
            if (!validPersonName($lname)) {
                $errors[] = 'Last name must contain 2 to 50 characters: letters, spaces, apostrophes or hyphens.';
            }
            if ($phone !== '' && !validPhoneNumber($phone)) {
                $errors[] = 'Phone must contain 7 to 15 digits, with an optional leading +.';
            }
            if (!$errors) {
                $phoneValue = $phone === '' ? null : $phone;
                $stmt = mysqli_prepare($conn,
                    'UPDATE users SET fname = ?, lname = ?, phone = ? WHERE id = ?');
                mysqli_stmt_bind_param($stmt, 'sssi', $fname, $lname, $phoneValue, $userId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $_SESSION['fname'] = $fname;
                $_SESSION['full_name'] = $fname . ' ' . $lname;
                $_SESSION['profile_success'] = 'Your profile has been updated.';
                header('Location: ' . BASE_URL . '/profile.php');
                exit;
            }
        } elseif ($action === 'password') {
            $current = formPassword('current_password');
            $new = formPassword('new_password');
            $confirm = formPassword('confirm_password');
            if ($current === '' || strlen($current) > 72 || strpos($current, "\0") !== false
                || !is_string($user['password']) || !password_verify($current, $user['password'])) {
                $errors[] = 'Your current password is incorrect.';
            }
            if (strpos($new, "\0") !== false || !validNewPassword($new)) {
                $errors[] = 'New password must contain 8 to 72 bytes, including an English letter and a number, with no null bytes.';
            }
            if ($new !== $confirm) {
                $errors[] = 'New passwords do not match.';
            }
            if (!$errors && password_verify($new, $user['password'])) {
                $errors[] = 'Choose a password different from your current password.';
            }
            if (!$errors) {
                $hash = password_hash($new, PASSWORD_DEFAULT);
                // Compare the old hash too, so a concurrent password change cannot be overwritten.
                $oldHash = $user['password'];
                $stmt = mysqli_prepare($conn,
                    'UPDATE users SET password = ? WHERE id = ? AND BINARY password = ?');
                mysqli_stmt_bind_param($stmt, 'sis', $hash, $userId, $oldHash);
                mysqli_stmt_execute($stmt);
                $changed = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                if ($changed === 1) {
                    session_regenerate_id(true);
                    $_SESSION['last_activity'] = time();
                    $_SESSION['profile_success'] = 'Your password has been changed.';
                    header('Location: ' . BASE_URL . '/profile.php');
                    exit;
                }
                $errors[] = 'Your account changed during this request. Please refresh and try again.';
            }
        } else {
            $errors[] = 'Invalid profile action.';
        }
    }
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    $errors[] = 'Could not load or update your profile. Please try again.';
}
$displayName = $user ? $user['fname'] . ' ' . $user['lname'] : 'My Profile';
$initial = $user ? mb_strtoupper(mb_substr($user['fname'], 0, 1, 'UTF-8'), 'UTF-8') : '?';
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
    <h1>My Profile</h1>
    <p class="intro">Manage your personal information and account password.</p>
    <?php if ($success !== ''): ?>
        <div class="sc-profile-notice" role="status"><?= escape($success) ?></div>
    <?php endif; ?>
    <?php if ($errors): ?>
        <div class="sc-profile-notice error" role="alert"><ul>
        <?php foreach ($errors as $error): ?><li><?= escape($error) ?></li><?php endforeach; ?>
        </ul></div>
    <?php endif; ?>
    <?php if ($user): ?>
    <div class="sc-profile-grid">
        <aside class="sc-profile-card sc-profile-summary">
            <div class="sc-profile-avatar"><?= escape($initial) ?></div>
            <h2><?= escape($displayName) ?></h2>
            <p><?= escape($user['email']) ?></p>
            <a class="sc-profile-button" href="seller.php">My Listings</a>
        </aside>
        <div>
            <section class="sc-profile-card" aria-labelledby="detailsHeading">
                <h2 id="detailsHeading">Personal information</h2>
                <form method="post" action="profile.php">
                    <input type="hidden" name="csrf_token" value="<?= escape($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="action" value="details">
                    <div class="sc-profile-row">
                        <div class="field"><label for="fname">First name</label><input id="fname" name="fname" value="<?= escape($fname) ?>" minlength="2" maxlength="50" autocomplete="given-name" required></div>
                        <div class="field"><label for="lname">Last name</label><input id="lname" name="lname" value="<?= escape($lname) ?>" minlength="2" maxlength="50" autocomplete="family-name" required></div>
                    </div>
                    <div class="field"><label for="email">Email address</label><input type="email" id="email" value="<?= escape($user['email']) ?>" readonly aria-describedby="emailHint"><small id="emailHint">Your sign-in email cannot be changed here.</small></div>
                    <div class="field"><label for="phone">Phone number (optional)</label><input type="tel" id="phone" name="phone" value="<?= escape($phone) ?>" maxlength="16" pattern="\+?[0-9]{7,15}" autocomplete="tel" aria-describedby="phoneHint"><small id="phoneHint">Use 7 to 15 digits, with an optional leading +.</small></div>
                    <button class="sc-profile-button" type="submit">Save Changes</button>
                </form>
            </section>
            <section class="sc-profile-card" aria-labelledby="passwordHeading">
                <h2 id="passwordHeading">Change password</h2>
                <form method="post" action="profile.php">
                    <input type="hidden" name="csrf_token" value="<?= escape($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="action" value="password">
                    <div class="field"><label for="current_password">Current password</label><input type="password" id="current_password" name="current_password" autocomplete="current-password" required></div>
                    <div class="field"><label for="new_password">New password</label><input type="password" id="new_password" name="new_password" maxlength="72" autocomplete="new-password" aria-describedby="passwordHint" required><small id="passwordHint">8 to 72 UTF-8 bytes, including an English letter and a number.</small></div>
                    <div class="field"><label for="confirm_password">Confirm new password</label><input type="password" id="confirm_password" name="confirm_password" maxlength="72" autocomplete="new-password" required></div>
                    <button class="sc-profile-button" type="submit">Change Password</button>
                </form>
            </section>
        </div>
    </div>
    <?php endif; ?>
</main>
<?php sessionTimerMarkup(); ?>
</body>
</html>
