<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/SecondChance');
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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