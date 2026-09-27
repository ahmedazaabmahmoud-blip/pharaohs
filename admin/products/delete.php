<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";


// Get Product ID

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {

    header("Location: index.php?error=delete_failed");

    exit;
}


// Get Product

$stmt = $conn->prepare("
    SELECT id, image
    FROM products
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 0) {

    $stmt->close();

    header("Location: index.php?error=delete_failed");

    exit;
}


$product = $result->fetch_assoc();

$stmt->close();


// Delete Product

$stmt = $conn->prepare("
    DELETE FROM products
    WHERE id = ?
");

$stmt->bind_param("i", $id);


if ($stmt->execute()) {

    $stmt->close();


    // Delete Product Image

    if (!empty($product["image"])) {

        $image_path =
            __DIR__
            . "/../../uploads/products/"
            . $product["image"];


        if (file_exists($image_path)) {

            unlink($image_path);

        }

    }


    header("Location: index.php?success=deleted");

    exit;
}


$stmt->close();


header("Location: index.php?error=delete_failed");

exit;