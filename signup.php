<?php
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header(
        'Location: ' .
        (isAdmin() ? 'adminlogin.php' : 'index.php')
    );
    exit;
}

$errors = [];
$fname = '';
$lname = '';
$email = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = formText('fname');
    $lname = formText('lname');
    $email = formText('email');
    $phone = formText('phone');
    $password = formPassword('password');
    $confirmPassword = formPassword('confirm_password');

    // Check the form's CSRF token.
    if (!validToken()) {
        $errors[] = 'Please refresh the page and try again.';
    }

    // Validate names.
    if (!validPersonName($fname)) {
        $errors[] =
            'First name must contain 2 to 50 characters: '
            . 'letters, spaces, apostrophes or hyphens.';
    }

    if (!validPersonName($lname)) {
        $errors[] =
            'Last name must contain 2 to 50 characters: '
            . 'letters, spaces, apostrophes or hyphens.';
    }

    // Validate email.
    if (
        !filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($email) > 255
    ) {
        $errors[] = 'Please enter a valid email address.';
    }

    // Phone is optional, but must be valid when entered.
    if ($phone !== '' && !validPhoneNumber($phone)) {
        $errors[] =
            'Phone number must contain 7 to 15 digits, '
            . 'with an optional leading +.';
    }

    // Validate password.
    if (!validNewPassword($password)) {
        $errors[] =
            'Password must contain 8 to 72 bytes, '
            . 'including an English letter and a number.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        require_once __DIR__ . '/includes/db.php';

        try {
            // Check whether the email is already registered.
            $stmt = mysqli_prepare(
                $conn,
                'SELECT id FROM users WHERE email = ? LIMIT 1'
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

                // Users cannot select their own role.
                $role = 'user';

                $stmt = mysqli_prepare(
                    $conn,
                    'INSERT INTO users
                     (fname, lname, email, password, phone, role)
                     VALUES (?, ?, ?, ?, ?, ?)'
                );

                mysqli_stmt_bind_param(
                    $stmt,
                    'ssssss',
                    $fname,
                    $lname,
                    $email,
                    $hashedPassword,
                    $phoneValue,
                    $role
                );

                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // Replace the registration token after success.
                $_SESSION['csrf_token'] =
                    bin2hex(random_bytes(32));

                header('Location: login.php?registered=1');
                exit;
            }
        } catch (mysqli_sql_exception $e) {
            if ((int) $e->getCode() === 1062) {
                $errors[] = 'This email is already registered.';
            } else {
                error_log($e->getMessage());

                $errors[] =
                    'Could not create your account. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Sign Up | Second Chance</title>

    <link rel="stylesheet" href="assets/auth.css">
</head>

<body>

<main class="auth-card">
    <a class="brand" href="index.php">Second Chance</a>

    <h1>Create your account</h1>

    <p class="subtitle">
        Buy, sell, and give university items a second chance.
    </p>

    <?php if (!empty($errors)): ?>
        <div class="message error" role="alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= escape($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="signupForm" method="post">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= escape($_SESSION['csrf_token']) ?>"
        >

        <div class="row">
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
                name="email"
                value="<?= escape($email) ?>"
                maxlength="255"
                autocomplete="email"
                required
            >
        </div>

        <div class="field">
            <label for="phone">Phone number (optional)</label>

            <input
                type="tel"
                id="phone"
                name="phone"
                value="<?= escape($phone) ?>"
                maxlength="16"
                autocomplete="tel"
                aria-describedby="phoneHint"
            >

            <small id="phoneHint">
                Use 7 to 15 digits, with an optional leading +.
            </small>
        </div>

        <div class="field">
            <label for="password">Password</label>

            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                maxlength="72"
                autocomplete="new-password"
                aria-describedby="passwordHint"
                required
            >

            <small id="passwordHint">
                Use at least 8 characters,add numbers and characters.
            </small>
        </div>

        <div class="field">
            <label for="confirm_password">Confirm password</label>

            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                minlength="8"
                maxlength="72"
                autocomplete="new-password"
                required
            >
        </div>

        <button type="submit">Create Account</button>
    </form>

    <p class="bottom-text">
        Already have an account?
        <a href="login.php">Log in</a>
    </p>
</main>

<script>
(() => {
    const form = document.getElementById('signupForm');
    const fields = form.elements;

    const namePattern = /^\p{L}[\p{L}\p{M} '\u2019-]*$/u;
    const phonePattern = /^\+?[0-9]{7,15}$/;

    function validateSignup() {
        [
            'fname',
            'lname',
            'email',
            'phone',
            'password',
            'confirm_password'
        ].forEach(name => {
            fields[name].setCustomValidity('');
        });

        // Validate names after removing surrounding spaces.
        ['fname', 'lname'].forEach(name => {
            const value = fields[name].value.trim();
            const length = [...value].length;

            if (
                length < 2
                || length > 50
                || !namePattern.test(value)
            ) {
                fields[name].setCustomValidity(
                    'Use 2 to 50 characters: letters, spaces, '
                    + 'apostrophes or hyphens.'
                );
            }
        });

        // HTML type="email" checks the email format.
        // PHP checks it again on the server.
        const email = fields.email.value.trim();

        if (new TextEncoder().encode(email).length > 255) {
            fields.email.setCustomValidity(
                'Email must not exceed 255 bytes.'
            );
        }

        const phone = fields.phone.value.trim();

        if (phone !== '' && !phonePattern.test(phone)) {
            fields.phone.setCustomValidity(
                'Use 7 to 15 digits, with an optional leading +.'
            );
        }

        // Match PHP's UTF-8 byte limit.
        const password = fields.password.value;
        const passwordBytes =
            new TextEncoder().encode(password).length;

        if (
            passwordBytes < 8
            || passwordBytes > 72
            || !/[a-zA-Z]/.test(password)
            || !/[0-9]/.test(password)
        ) {
            fields.password.setCustomValidity(
                'Use 8 to 72 bytes, including an English '
                + 'letter and a number.'
            );
        }

        if (password !== fields.confirm_password.value) {
            fields.confirm_password.setCustomValidity(
                'Passwords do not match.'
            );
        }
    }

    form.addEventListener('input', validateSignup);

    form.addEventListener('submit', event => {
        validateSignup();

        if (!form.reportValidity()) {
            event.preventDefault();
        }
    });
})();
</script>

</body>
</html>
