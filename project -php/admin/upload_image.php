<?php

header('Content-Type: application/json; charset=utf-8');

if (
    !isset($_FILES['upload']) ||
    $_FILES['upload']['error'] !== UPLOAD_ERR_OK
) {

    http_response_code(400);

    echo json_encode([
        'error' => [
            'message' => 'آپلود تصویر انجام نشد.'
        ]
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


$extension = strtolower(
    pathinfo(
        $_FILES['upload']['name'],
        PATHINFO_EXTENSION
    )
);


$allowed = [
    'jpg',
    'jpeg',
    'png',
    'gif',
    'webp'
];


if (!in_array($extension, $allowed, true)) {

    http_response_code(400);

    echo json_encode([
        'error' => [
            'message' => 'فرمت تصویر مجاز نیست.'
        ]
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


$newName = 'content_' . uniqid() . '.' . $extension;


$uploadDir = __DIR__ . '/../up/';


if (!is_dir($uploadDir)) {

    mkdir(
        $uploadDir,
        0777,
        true
    );

}


if (!move_uploaded_file(
    $_FILES['upload']['tmp_name'],
    $uploadDir . $newName
)) {

    http_response_code(500);

    echo json_encode([
        'error' => [
            'message' => 'ذخیره تصویر انجام نشد.'
        ]
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


echo json_encode([
    'url' => '/project%20-php/up/' . $newName
], JSON_UNESCAPED_SLASHES);