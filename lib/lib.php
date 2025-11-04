<?php


// ===================
//  Origin 
// ==================

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


// =========================
// Helper funksiyalar
// =========================



function currentPage()
{
    return basename($_SERVER['PHP_SELF']);
}


function isActive($pages, $class = "active")
{
    if (!is_array($pages)) {
        $pages = [$pages];
    }
    return in_array(currentPage(), $pages) ? $class : "";
}

function isExpanded($pages, $class = "is-expanded")
{
    if (!is_array($pages)) {
        $pages = [$pages];
    }
    return in_array(currentPage(), $pages) ? $class : "";
}


function sql($text)
{
    global $conn;

    $sql = mysqli_query($conn, $text);

    if (!$sql) {
        die("SQL xətası: " . mysqli_error($conn));
    }

    if (stripos(trim($text), 'SELECT') === 0) {
        return mysqli_fetch_all($sql, MYSQLI_ASSOC);
    }

    return $sql;
}





function vd($data)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_PRETTY_PRINT, JSON_UNESCAPED_UNICODE);
    die;
}


function getColors($index)
{

    $colors = [
        "primary",
        "secondary",
        "success",
        "danger",
        "warning",
        "info",
    ];

    $index = $index % count($colors);

    return $colors[$index];
}


function singleImg($img)
{
    $uploadDir = __DIR__ . '\..\uploads';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!isset($_FILES[$img])) return false;

    $name = $_FILES[$img]['name'];
    $tmpDir = $_FILES[$img]['tmp_name'];
    $error = $_FILES[$img]['error'];

    $allowExt = ['jpg', 'jpeg', 'webp', 'png'];
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    if ($error !== UPLOAD_ERR_OK) return false;
    if (!in_array($ext, $allowExt)) return false;

    $newName =  'uploads' . '/' . uniqid() . '_' . $name;

    if (move_uploaded_file($tmpDir, $newName)) {
        return $newName;
    } else {
        echo "Failed to move file from $tmpDir to $newName";
        return false;
    }
}




function fill($data)
{
    return isset($data) && $data !== null ? $data : '';
}



function currentUser($text)
{
    return $_SESSION[$text];
}
