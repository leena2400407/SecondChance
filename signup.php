<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$fname = '';
$lname = '';
$email = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim((string) ($_POST['fname'] ?? ''));
    $lname = trim((string) ($_POST['lname'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if (!validToken()) {
        $errors[] = 'Please refresh the page and try again.';
    }

    if ($fname === '' || mb_strlen($fname) > 50) {
        $errors[] = 'First name is required and must not exceed 50 characters.';
    }

    if ($lname === '' || mb_strlen($lname) > 50) {
        $errors[] = 'Last name is required and must not exceed 50 characters.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($email) > 255) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($phone !== ''
        && !preg_match('/\A[0-9+() .-]{7,20}\z/', $phone)) {
        $errors[] = 'Please enter a valid phone number.';
    }

    if (strlen($password) < 8 || strlen($password) > 72) {
        $errors[] = 'Password must be between 8 and 72 bytes.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        try {
            $stmt = mysqli_prepare(
                $conn,
                'SELECT id FROM users WHERE email = ?'
            );

            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            $emailExists = mysqli_stmt_num_rows($stmt) > 0;
            mysqli_stmt_close($stmt);

            if ($emailExists) {
                $errors[] = 'This email is already registered.';
            } else {
                $hashedPassword = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $phoneValue = $phone === '' ? null : $phone;

                // Role uses the database default: user.
                $stmt = mysqli_prepare(
                    $conn,
                    'INSERT INTO users
                     (fname, lname, email, password, phone)
                     VALUES (?, ?, ?, ?, ?)'
                );

                mysqli_stmt_bind_param(
                    $stmt,
                    'sssss',
                    $fname,
                    $lname,
                    $email,
                    $hashedPassword,
                    $phoneValue
                );

                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                header('Location: login.php?registered=1');
                exit;
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                $errors[] = 'This email is already registered.';
            } else {
                error_log($e->getMessage());
                $errors[] = 'Could not create your account. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Second Chance</title>
    <link rel="stylesheet" href="assets/auth.css">
</head>
<body>

<main class="auth-card">
    <a class="brand" href="index.php">Second Chance</a>

    <h1>Create your account</h1>
    <p class="subtitle">Buy, sell, and give university items a second chance.</p>

    <?php if (!empty($errors)): ?>
        <div class="message error" role="alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= escape($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="csrf_token"
               value="<?= escape($_SESSION['csrf_token']) ?>">

        <div class="row">
            <div class="field">
                <label for="fname">First name</label>
                <input type="text" id="fname" name="fname"
                       value="<?= escape($fname) ?>"
                       maxlength="50" autocomplete="given-name" required>
            </div>

            <div class="field">
                <label for="lname">Last name</label>
                <input type="text" id="lname" name="lname"
                       value="<?= escape($lname) ?>"
                       maxlength="50" autocomplete="family-name" required>
            </div>
        </div>

        <div class="field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email"
                   value="<?= escape($email) ?>"
                   maxlength="255" autocomplete="email" required>
        </div>

        <div class="field">
            <label for="phone">Phone number (optional)</label>
            <input type="tel" id="phone" name="phone"
                   value="<?= escape($phone) ?>"
                   maxlength="20" autocomplete="tel">
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   minlength="8" autocomplete="new-password" required>
            <small>Use at least 8 characters.</small>
        </div>

        <div class="field">
            <label for="confirm_password">Confirm password</label>
            <input type="password" id="confirm_password"
                   name="confirm_password"
                   minlength="8" autocomplete="new-password" required>
        </div>

        <button type="submit">Create Account</button>
    </form>

    <p class="bottom-text">
        Already have an account? <a href="login.php">Log in</a>
    </p>
</main>

</body>
</html>
