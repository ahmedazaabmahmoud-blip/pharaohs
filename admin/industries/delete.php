<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";


// Get Industry ID

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: index.php?error=delete_failed");
    exit;
}


// Get Industry

$stmt = $conn->prepare("
    SELECT id, image
    FROM industries
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();


// Industry Not Found

if ($result->num_rows === 0) {

    $stmt->close();

    header("Location: index.php?error=delete_failed");

    exit;
}


$industry = $result->fetch_assoc();

$stmt->close();


// Delete Industry

$stmt = $conn->prepare("
    DELETE FROM industries
    WHERE id = ?
");

$stmt->bind_param("i", $id);


if ($stmt->execute()) {

    $stmt->close();


    // Delete Image

    if (!empty($industry["image"])) {

        $image_path =
            __DIR__
            . "/../../uploads/industries/"
            . $industry["image"];


        if (file_exists($image_path)) {

            unlink($image_path);

        }

    }


    header(
        "Location: index.php?success=deleted"
    );

    exit;

}


// Delete Failed

$stmt->close();

header(
    "Location: index.php?error=delete_failed"
);

exit;