<?php
// Shared database and upload helpers for the seller editor and real product details.
function listingRows($sql, $types = '', $values = [])
{
    global $conn;
    $stmt = mysqli_prepare($conn, $sql);
    if ($types !== '') mysqli_stmt_bind_param($stmt, $types, ...$values);
    mysqli_stmt_execute($stmt);
    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}
function listingColumns()
{
    static $columns;
    if ($columns === null) $columns = array_column(listingRows('SHOW COLUMNS FROM products'), null, 'Field');
    return $columns;
}
function listingTitleColumn()
{
    $columns = listingColumns();
    foreach (['title', 'product_title', 'name', 'product_name'] as $name) {
        if (isset($columns[$name])) return $name;
    }
    throw new RuntimeException('The products table needs a title column.');
}
function listingImage($path)
{
    if (!is_string($path)) return null;
    $path = str_replace('\\', '/', $path);
    if (!preg_match('~\Auploads/(?:products/)?[a-zA-Z0-9_-]+\.(?:jpg|jpeg|png|webp)\z~i', $path)) return null;
    return is_file(dirname(__DIR__) . '/' . $path) ? $path : null;
}
function listingUpload($file)
{
    if (!is_array($file) || !isset($file['error']) || is_array($file['error'])) throw new RuntimeException('Invalid image upload.');
    if ($file['error'] === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('The image could not be uploaded. Check the PHP upload limit and try again.');
    if (!is_uploaded_file($file['tmp_name']) || filesize($file['tmp_name']) > 5 * 1024 * 1024) throw new RuntimeException('Choose an image no larger than 5 MB.');
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $extension = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
    $info = @getimagesize($file['tmp_name']);
    if (!$extension || !$info || $info[0] > 12000 || $info[1] > 12000) throw new RuntimeException('Choose a valid JPG, PNG or WEBP image (up to 12,000 pixels per side).');
    $directory = dirname(__DIR__) . '/uploads/products';
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) throw new RuntimeException('The product upload folder could not be created.');
    $relative = 'uploads/products/' . bin2hex(random_bytes(16)) . '.' . $extension;
    if (!move_uploaded_file($file['tmp_name'], dirname(__DIR__) . '/' . $relative)) throw new RuntimeException('The image could not be saved. Check upload-folder permissions.');
    return $relative;
}
function listingText($key)
{
    return is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
}