<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/SecondChance');
}
const SESSION_IDLE_SECONDS = 900; // 15 minutes

function clearLoginSession()
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    session_destroy();
}

$isActivityRequest =
    ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'
    && ($_POST['session_action'] ?? '') === 'activity'
    && realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__;

if (!empty($_SESSION['user_id'])) {
    $lastActivity = (int) ($_SESSION['last_activity'] ?? 0);

    if (
        $lastActivity <= 0
        || time() - $lastActivity >= SESSION_IDLE_SECONDS
    ) {
        clearLoginSession();

        if ($isActivityRequest) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['expired' => true]);
        } else {
            header(
                'Location: ' . BASE_URL .
                '/login.php?message=session_expired'
            );
        }

        exit;
    }

    if (!$isActivityRequest) {
        $_SESSION['last_activity'] = time();
    }

    header('Cache-Control: no-store, private');
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if ($isActivityRequest) {
    header('Content-Type: application/json');
    header('Cache-Control: no-store');

    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['expired' => true]);
        exit;
    }

    if (!validToken()) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    $_SESSION['last_activity'] = time();

    echo json_encode([
        'expires' => (time() + SESSION_IDLE_SECONDS) * 1000
    ]);

    exit;
}

function escape($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

function isLoggedIn()
{
    return !empty($_SESSION['user_id']);
}

function isAdmin()
{
    return isLoggedIn()
        && ($_SESSION['role'] ?? '') === 'admin';
}

function validToken()
{
    return isset($_POST['csrf_token'])
        && is_string($_POST['csrf_token'])
        && hash_equals(
            $_SESSION['csrf_token'],
            $_POST['csrf_token']
        );
}

function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

function requireAdmin()
{
    requireLogin();

    if (!isAdmin()) {
        http_response_code(403);
        exit('Access denied. Administrators only.');
    }
}

function currentUser()
{
    if (!isLoggedIn()) {
        return null;
    }

    return [
        'id'        => $_SESSION['user_id'],
        'fname'     => $_SESSION['fname'] ?? '',
        'full_name' => $_SESSION['full_name'] ?? 'User',
        'name'      => $_SESSION['full_name'] ?? 'User',
        'role'      => $_SESSION['role'] ?? 'user'
    ];
}
function formText($key)
{
    return is_string($_POST[$key] ?? null)
        ? trim($_POST[$key])
        : '';
}

function formPassword($key)
{
    return is_string($_POST[$key] ?? null)
        ? $_POST[$key]
        : '';
}

function validPersonName($name)
{
    return mb_strlen($name) >= 2
        && mb_strlen($name) <= 50
        && preg_match(
            "/\A\p{L}[\p{L}\p{M} '\x{2019}-]*\z/u",
            $name
        );
}

function validPhoneNumber($phone)
{
    return (bool) preg_match(
        '/\A\+?[0-9]{7,15}\z/',
        $phone
    );
}

function validNewPassword($password)
{
    return strlen($password) >= 8
        && strlen($password) <= 72
        && preg_match('/[a-zA-Z]/', $password)
        && preg_match('/[0-9]/', $password);
}

function sessionTimerMarkup()
{
    if (!isLoggedIn()) {
        return;
    }

    $settings = json_encode([
        'expires' => (
            (int) $_SESSION['last_activity']
            + SESSION_IDLE_SECONDS
        ) * 1000,
        'base' => BASE_URL,
        'token' => $_SESSION['csrf_token']
    ]);

    echo '<script>window.secondChanceSession = '
        . $settings . ';</script>';
    ?>
    <script>
    (() => {
        if (window.secondChanceTimerLoaded) return;
        window.secondChanceTimerLoaded = true;

        const settings = window.secondChanceSession;
        let expires = settings.expires;
        let lastPing = 0;
        let pending = false;

        function expire() {
            window.location.replace(
                settings.base
                + '/login.php?message=session_expired'
            );
        }

        async function activity(event) {
            if (
                !event.isTrusted
                || pending
                || Date.now() - lastPing < 30000
            ) {
                return;
            }

            if (Date.now() >= expires) {
                expire();
                return;
            }

            pending = true;
            lastPing = Date.now();

            try {
                const response = await fetch(
                    settings.base + '/includes/auth.php',
                    {
                        method: 'POST',
                        credentials: 'same-origin',
                        body: new URLSearchParams({
                            session_action: 'activity',
                            csrf_token: settings.token
                        })
                    }
                );

                if (response.status === 401) {
                    expire();
                    return;
                }

                if (response.ok) {
                    const data = await response.json();
                    expires = data.expires;
                }
            } catch (error) {
                // PHP still checks expiry on the next request.
            } finally {
                pending = false;
            }
        }

        ['pointerdown', 'keydown', 'scroll'].forEach(type => {
            document.addEventListener(
                type,
                activity,
                { passive: true }
            );
        });

        setInterval(() => {
            if (Date.now() >= expires) {
                expire();
            }
        }, 1000);

        document.addEventListener('visibilitychange', () => {
            if (!document.hidden && Date.now() >= expires) {
                expire();
            }
        });
    })();
    </script>
    <?php
}