<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";


/*
|--------------------------------------------------------------------------
| Get Service ID
|--------------------------------------------------------------------------
*/

$service_id = (int) ($_GET["id"] ?? 0);


if ($service_id <= 0) {

    header("Location: index.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| Get Service
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT id, image
    FROM services
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $service_id);

$stmt->execute();

$result = $stmt->get_result();

$service = $result->fetch_assoc();

$stmt->close();


/*
|--------------------------------------------------------------------------
| Service Not Found
|--------------------------------------------------------------------------
*/

if (!$service) {

    header("Location: index.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| Delete Image
|--------------------------------------------------------------------------
*/

if (!empty($service["image"])) {

    $image_path =
        __DIR__
        . "/../../uploads/services/"
        . $service["image"];


    if (file_exists($image_path)) {

        unlink($image_path);

    }

}


/*
|--------------------------------------------------------------------------
| Delete Service
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM services
    WHERE id = ?
");

$stmt->bind_param(
    "i",
    $service_id
);


if ($stmt->execute()) {

    $stmt->close();

    header(
        "Location: index.php?success=deleted"
    );

    exit;

}


$stmt->close();


header("Location: index.php?error=delete_failed");

exit;