<?php
session_start();

require_once __DIR__ . '/includes/auth.php';

clearLoginSession();

header(
    'Location: ' . BASE_URL
    . '/login.php?message=logged_out'
);

exit;
?>