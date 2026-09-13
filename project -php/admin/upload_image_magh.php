<?php

// =====================================================
// تنظیمات
// =====================================================

$uploadDir = __DIR__ . "/uploads/";


// =====================================================
// تنظیم خروجی JSON
// =====================================================

header('Content-Type: application/json; charset=utf-8');


// =====================================================
// فقط درخواست POST
// =====================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        'error' => [
            'message' => 'درخواست نامعتبر است.'
        ]
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


// =====================================================
// بررسی وجود فایل
// =====================================================

if (
    !isset($_FILES['upload']) ||
    $_FILES['upload']['error'] !== UPLOAD_ERR_OK
) {

    http_response_code(400);

    echo json_encode([
        'error' => [
            'message' => 'تصویری دریافت نشد.'
        ]
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


$file = $_FILES['upload'];


// =====================================================
// محدودیت حجم
// =====================================================

$maxSize = 5 * 1024 * 1024;

if ($file['size'] > $maxSize) {

    http_response_code(400);

    echo json_encode([
        'error' => [
            'message' => 'حجم تصویر نباید بیشتر از 5 مگابایت باشد.'
        ]
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


// =====================================================
// بررسی واقعی بودن تصویر
// =====================================================

$imageInfo = getimagesize($file['tmp_name']);

if ($imageInfo === false) {

    http_response_code(400);

    echo json_encode([
        'error' => [
            'message' => 'فایل انتخاب شده یک تصویر معتبر نیست.'
        ]
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


// =====================================================
// فرمت‌های مجاز
// =====================================================

$allowedTypes = [

    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp'

];


$mimeType = $imageInfo['mime'];


if (!isset($allowedTypes[$mimeType])) {

    http_response_code(400);

    echo json_encode([
        'error' => [
            'message' => 'فرمت تصویر مجاز نیست. فقط JPG، PNG و WEBP مجاز هستند.'
        ]
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


$extension = $allowedTypes[$mimeType];


// =====================================================
// ساخت پوشه uploads در صورت نبودن
// =====================================================

if (!is_dir($uploadDir)) {

    if (!mkdir($uploadDir, 0777, true)) {

        http_response_code(500);

        echo json_encode([
            'error' => [
                'message' => 'پوشه uploads ساخته نشد.'
            ]
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }
}


// =====================================================
// ساخت نام تصادفی
// =====================================================

$fileName =
    'editor_' .
    bin2hex(random_bytes(12)) .
    '.' .
    $extension;


// =====================================================
// مسیر ذخیره واقعی فایل
// =====================================================

$filePath = $uploadDir . $fileName;


// =====================================================
// انتقال فایل
// =====================================================

if (!move_uploaded_file(
    $file['tmp_name'],
    $filePath
)) {

    http_response_code(500);

    echo json_encode([
        'error' => [
            'message' => 'ذخیره تصویر انجام نشد.'
        ]
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


// =====================================================
// ساخت URL صحیح
// =====================================================
//
// upload_image_magh.php در ریشه پروژه است.
// uploads هم در ریشه پروژه است.
//
// بنابراین URL تصویر:
//
// /نام-پروژه/uploads/filename
//
// به صورت خودکار از مسیر فعلی سایت ساخته می‌شود.
// =====================================================

$scriptPath = $_SERVER['SCRIPT_NAME'];

// حذف نام فایل upload_image_magh.php
$projectPath = dirname($scriptPath);

// حذف اسلش اضافی
$projectPath = rtrim($projectPath, '/');


// اگر فایل در ریشه پروژه باشد:
// /project -php/upload_image_magh.php
//
// projectPath می‌شود:
// /project -php

$imageUrl =
    $projectPath .
    '/uploads/' .
    rawurlencode($fileName);


// =====================================================
// پاسخ موفق به CKEditor
// =====================================================

echo json_encode([

    'url' => $imageUrl

], JSON_UNESCAPED_UNICODE);

exit;

?>
