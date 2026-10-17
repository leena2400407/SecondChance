<?php
$servername = "localhost";
$USERNAME = "root";
$PASSWORD = "";
$DB = "second-chance";

$conn = mysqli_connect($servername, $USERNAME, $PASSWORD, $DB);

if (!$conn) {
    die("Database connection failed.");
}

mysqli_set_charset($conn, "utf8mb4");
?>