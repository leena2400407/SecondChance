<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (!validToken()) {
        $errors[] = 'Please refresh the page and try again.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($email) > 255
        || $password === '') {
        $errors[] = 'Please enter your email and password correctly.';
    }

    if (empty($errors)) {
        try {
            $stmt = mysqli_prepare(
                $conn,
                'SELECT id, fname, lname, password, role
                 FROM users WHERE email = ? LIMIT 1'
            );

            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            mysqli_stmt_close($stmt);

            if ($user
                && password_verify($password, $user['password'])) {
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['full_name'] =
                    $user['fname'] . ' ' . $user['lname'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                header('Location: index.php');
                exit;
            } else {
                $errors[] = 'Incorrect email or password.';
            }
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage());
            $errors[] = 'Could not log in. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Second Chance</title>
    <link rel="stylesheet" href="assets/auth.css">
</head>
<body>

<main class="auth-card">
    <a class="brand" href="index.php">Second Chance</a>

    <h1>Welcome back</h1>
    <p class="subtitle">Log in to explore, buy, and list your items.</p>

    <?php if (isset($_GET['registered'])): ?>
        <div class="message success" role="status">
            Account created successfully. You can now log in.
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="message error" role="alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= escape($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (($_GET['message'] ?? '') === 'logged_out'): ?>
        <div class="message success" role="status">
         You have logged out successfully.
        </div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="csrf_token"
               value="<?= escape($_SESSION['csrf_token']) ?>">

        <div class="field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email"
                   value="<?= escape($email) ?>"
                   maxlength="255" autocomplete="username" required>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   autocomplete="current-password" required>
        </div>

        <button type="submit">Log In</button>
    </form>

    <p class="bottom-text">
        New here? <a href="signup.php">Create an account</a>
    </p>
</main>

</body>
</html>
