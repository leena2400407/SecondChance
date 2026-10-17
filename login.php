<?php
/**
 * Log In Page (Student & Administrator)
 * Second Chance University Portal
 * Author: Karim
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

$activeTab = (isset($_GET['type']) && $_GET['type'] === 'admin') ? 'admin' : 'student';
$errors    = [];
$email     = '';
$notice    = '';

if (isset($_GET['message']) && $_GET['message'] === 'logged_out') {
    $notice = "You have been logged out successfully.";
} elseif (isset($_GET['error']) && $_GET['error'] === 'login_required') {
    $errors['auth'] = "Please log in to access your account.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $loginType = $_POST['login_type'] ?? 'student';
    $activeTab = $loginType;

    // Validation
    if (empty($email)) {
        $errors['email'] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email format.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    if (empty($errors)) {
        $escapedEmail = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT * FROM users WHERE email = '$escapedEmail' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Password verification
            $passwordMatches = false;
            if (password_verify($password, $user['password'])) {
                $passwordMatches = true;
            } elseif ($password === $user['password']) {
                $passwordMatches = true;
            }

            if ($passwordMatches) {
                $fullName = trim(($user['fname'] ?? '') . ' ' . ($user['lname'] ?? ''));
                if (empty($fullName)) {
                    $fullName = $user['email'];
                }

                // If logging in through Admin Portal tab, check admin role (role == 'admin')
                if ($loginType === 'admin') {
                    if ($user['role'] !== 'admin') {
                        $errors['auth'] = "Access denied: This portal is restricted to administrators.";
                    } else {
                        session_regenerate_id(true);
                        $_SESSION['user_id'] = (int)$user['id'];
                        $_SESSION['fname']   = $user['fname'];
                        $_SESSION['lname']   = $user['lname'];
                        $_SESSION['name']    = $fullName;
                        $_SESSION['email']   = $user['email'];
                        $_SESSION['phone']   = $user['phone'] ?? '';
                        $_SESSION['role']    = 'admin';

                        $_SESSION['toast'] = [
                            'type'               => 'welcome_back',
                            'title'              => 'Welcome Back, Administrator!',
                            'message'            => 'Successfully authenticated as ' . htmlspecialchars($fullName) . '.',
                            'show_email_preview' => false
                        ];

                        setFlashMessage('success', "Welcome to the Administration Console, " . htmlspecialchars($fullName) . ".");
                        header("Location: admin.php");
                        exit();
                    }
                } else {
                    // Standard Student Login
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = (int)$user['id'];
                    $_SESSION['fname']   = $user['fname'];
                    $_SESSION['lname']   = $user['lname'];
                    $_SESSION['name']    = $fullName;
                    $_SESSION['email']   = $user['email'];
                    $_SESSION['phone']   = $user['phone'] ?? '';
                    $_SESSION['role']    = $user['role'];

                    $_SESSION['toast'] = [
                        'type'               => 'welcome_back',
                        'title'              => 'Welcome Back!',
                        'message'            => 'Hello, ' . htmlspecialchars($fullName) . '! You have successfully logged in.',
                        'show_email_preview' => false
                    ];

                    setFlashMessage('success', "Welcome back, " . htmlspecialchars($fullName) . "!");
                    
                    if ($user['role'] === 'admin') {
                        header("Location: admin.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                }
            }
 else {
                $errors['auth'] = "Invalid email or password.";
            }
        } else {
            $errors['auth'] = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - Second Chance</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <div class="main-wrapper">
        <div class="auth-wrapper">
            <div class="auth-card">
                
                <!-- Segmented Tabs: Student vs Admin -->
                <div class="auth-tabs">
                    <button type="button" 
                            id="tabBtnStudent" 
                            onclick="setAuthType('student')" 
                            class="auth-tab <?php echo ($activeTab === 'student') ? 'active' : ''; ?>">
                        Student Log In
                    </button>
                    <button type="button" 
                            id="tabBtnAdmin" 
                            onclick="setAuthType('admin')" 
                            class="auth-tab <?php echo ($activeTab === 'admin') ? 'active' : ''; ?>">
                        🛡️ Admin Portal
                    </button>
                </div>

                <div class="auth-header">
                    <div id="headerIcon" class="auth-header-icon">
                        <?php echo ($activeTab === 'admin') ? '🛡️' : '🎓'; ?>
                    </div>
                    <h2 id="headerTitle">
                        <?php echo ($activeTab === 'admin') ? 'Administrator Access' : 'Welcome Back'; ?>
                    </h2>
                    <p id="headerSubtitle">
                        <?php echo ($activeTab === 'admin') ? 'Authorized university staff portal' : 'Log in to your campus account'; ?>
                    </p>
                </div>

                <?php if (!empty($notice)): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($notice); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors['auth'])): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($errors['auth']); ?>
                    </div>
                <?php endif; ?>

                <div id="login-client-error" class="alert alert-danger" style="display: none;"></div>

                <form method="POST" action="login.php" id="loginForm" novalidate>
                    <input type="hidden" name="login_type" id="loginTypeInput" value="<?php echo htmlspecialchars($activeTab); ?>">

                    <!-- Email Field -->
                    <div class="form-group">
                        <label class="form-label" for="loginEmail">
                            <span id="emailLabelText"><?php echo ($activeTab === 'admin') ? 'Admin Email' : 'University Email'; ?></span> 
                            <span class="required">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="loginEmail" 
                               class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                               placeholder="<?php echo ($activeTab === 'admin') ? 'admin@secondchance.com' : 'e.g. student@miuegypt.edu.eg'; ?>" 
                               value="<?php echo htmlspecialchars($email); ?>" 
                               required>
                        <div class="invalid-feedback">
                            <?php echo $errors['email'] ?? ''; ?>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label class="form-label" for="loginPassword">
                            Password <span class="required">*</span>
                        </label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   name="password" 
                                   id="loginPassword" 
                                   class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                   placeholder="Enter your password" 
                                   required>
                            <button type="button" class="password-toggle-btn" data-target="loginPassword">Show</button>
                        </div>
                        <div class="invalid-feedback">
                            <?php echo $errors['password'] ?? ''; ?>
                        </div>
                    </div>

                    <button type="submit" id="loginSubmitBtn" class="btn btn-primary btn-block" style="margin-top: 1.5rem;">
                        <?php echo ($activeTab === 'admin') ? 'Authenticate as Admin' : 'Log In'; ?>
                    </button>
                </form>

                <div class="auth-footer">
                    <span id="footerText">Don't have an account yet?</span> 
                    <a href="signup.php" id="footerLink">Sign Up here</a>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/script.js"></script>
    <script>
        function setAuthType(type) {
            const tabStudent = document.getElementById('tabBtnStudent');
            const tabAdmin   = document.getElementById('tabBtnAdmin');
            const typeInput  = document.getElementById('loginTypeInput');
            const icon       = document.getElementById('headerIcon');
            const title      = document.getElementById('headerTitle');
            const subtitle   = document.getElementById('headerSubtitle');
            const emailLabel = document.getElementById('emailLabelText');
            const emailInput = document.getElementById('loginEmail');
            const submitBtn  = document.getElementById('loginSubmitBtn');

            typeInput.value = type;

            if (type === 'admin') {
                tabStudent.classList.remove('active');
                tabAdmin.classList.add('active');
                icon.textContent = '🛡️';
                title.textContent = 'Administrator Access';
                subtitle.textContent = 'Authorized university staff portal';
                emailLabel.textContent = 'Admin Email';
                emailInput.placeholder = 'admin@secondchance.com';
                submitBtn.textContent = 'Authenticate as Admin';
                submitBtn.style.backgroundColor = '#22223B';
            } else {
                tabAdmin.classList.remove('active');
                tabStudent.classList.add('active');
                icon.textContent = '🎓';
                title.textContent = 'Welcome Back';
                subtitle.textContent = 'Log in to your campus account';
                emailLabel.textContent = 'University Email';
                emailInput.placeholder = 'e.g. student@miuegypt.edu.eg';
                submitBtn.textContent = 'Log In';
                submitBtn.style.backgroundColor = 'var(--primary-dark)';
            }
        }
    </script>
</body>
</html>