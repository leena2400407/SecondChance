<?php
/**
 * Sign Up Page (Student Registration)
 * Second Chance University Portal
 */

session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: admin.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

$errors = [];
$fname  = '';
$lname  = '';
$email  = '';
$phone  = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname            = trim($_POST['fname'] ?? '');
    $lname            = trim($_POST['lname'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $phone            = trim($_POST['phone'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // First Name Validation
    if (empty($fname)) {
        $errors['fname'] = "First name is required.";
    }

    // Last Name Validation
    if (empty($lname)) {
        $errors['lname'] = "Last name is required.";
    }

    // Email Validation
    if (empty($email)) {
        $errors['email'] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email format.";
    } else {
        // Check if email is already registered
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $errors['email'] = "This email is already registered. Please log in.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Password Validation
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters long.";
    }

    // Confirm Password Validation
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    // Process Registration if No Errors
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role           = 'user'; // Default registration role

        $insertSql = "INSERT INTO users (fname, lname, email, phone, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $insertSql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $fname, $lname, $email, $phone, $hashedPassword, $role);

            if (mysqli_stmt_execute($stmt)) {
                $newUserId = mysqli_insert_id($conn);
                $fullName  = trim("$fname $lname");

                // Auto-login newly registered user
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int)$newUserId;
                $_SESSION['fname']   = $fname;
                $_SESSION['lname']   = $lname;
                $_SESSION['name']    = $fullName;
                $_SESSION['email']   = $email;
                $_SESSION['phone']   = $phone;
                $_SESSION['role']    = $role;

                $_SESSION['toast'] = [
                    'type'    => 'welcome',
                    'title'   => 'Account Created!',
                    'message' => 'Welcome to Second Chance, ' . htmlspecialchars($fullName) . '!'
                ];

                if (function_exists('setFlashMessage')) {
                    setFlashMessage('success', "Welcome to Second Chance, " . htmlspecialchars($fullName) . "!");
                }

                header("Location: index.php");
                exit();
            } else {
                $errors['auth'] = "Failed to create account. Please try again.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors['auth'] = "Database error. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Second Chance</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <div class="main-wrapper">
        <div class="auth-wrapper">
            <div class="auth-card">

                <div class="auth-header">
                    <div class="auth-header-icon">🎓</div>
                    <h2>Create an Account</h2>
                    <p>Join the Second Chance campus marketplace</p>
                </div>

                <?php if (!empty($errors['auth'])): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($errors['auth']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="signup.php" id="signupForm" novalidate>
                    
                    <!-- First & Last Name Grid -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label" for="fname">First Name <span class="required">*</span></label>
                            <input type="text" 
                                   name="fname" 
                                   id="fname" 
                                   class="form-control <?php echo isset($errors['fname']) ? 'is-invalid' : ''; ?>" 
                                   placeholder="John" 
                                   value="<?php echo htmlspecialchars($fname); ?>" 
                                   required>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['fname'] ?? ''); ?></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="lname">Last Name <span class="required">*</span></label>
                            <input type="text" 
                                   name="lname" 
                                   id="lname" 
                                   class="form-control <?php echo isset($errors['lname']) ? 'is-invalid' : ''; ?>" 
                                   placeholder="Doe" 
                                   value="<?php echo htmlspecialchars($lname); ?>" 
                                   required>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['lname'] ?? ''); ?></div>
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label class="form-label" for="email">University Email <span class="required">*</span></label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                               placeholder="student@miuegypt.edu.eg" 
                               value="<?php echo htmlspecialchars($email); ?>" 
                               required>
                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['email'] ?? ''); ?></div>
                    </div>

                    <!-- Phone Field -->
                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" 
                               placeholder="+20 100 000 0000" 
                               value="<?php echo htmlspecialchars($phone); ?>">
                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['phone'] ?? ''); ?></div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label class="form-label" for="password">Password <span class="required">*</span></label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                   placeholder="At least 8 characters" 
                                   required>
                            <button type="button" class="password-toggle-btn" data-target="password">Show</button>
                        </div>
                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['password'] ?? ''); ?></div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label class="form-label" for="confirm_password">Confirm Password <span class="required">*</span></label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   name="confirm_password" 
                                   id="confirm_password" 
                                   class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                   placeholder="Re-enter your password" 
                                   required>
                            <button type="button" class="password-toggle-btn" data-target="confirm_password">Show</button>
                        </div>
                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['confirm_password'] ?? ''); ?></div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1.5rem;">
                        Create Account
                    </button>
                </form>

                <div class="auth-footer">
                    <span>Already have an account?</span> 
                    <a href="login.php">Log In here</a>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>
