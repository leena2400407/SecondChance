<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = "localhost";
$USERNAME = "root";
$PASSWORD = "";
$DB = "second-chance";

try {
    $conn = mysqli_connect(
        $servername,
        $USERNAME,
        $PASSWORD,
        $DB
    );

    mysqli_set_charset($conn, "utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());

    http_response_code(500);
    exit("Database connection failed. Please try again later.");
}
?>