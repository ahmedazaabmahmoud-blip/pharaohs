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
| Get Investment ID
|--------------------------------------------------------------------------
*/

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {

    header("Location: index.php?error=delete_failed");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Investment
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT id, image
    FROM investment_opportunities
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

$investment = $result->fetch_assoc();

$stmt->close();


/*
|--------------------------------------------------------------------------
| Delete Investment
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM investment_opportunities
    WHERE id = ?
");

$stmt->bind_param("i", $id);


if ($stmt->execute()) {

    $stmt->close();


    /*
    |--------------------------------------------------------------------------
    | Delete Investment Image
    |--------------------------------------------------------------------------
    */

    if (!empty($investment["image"])) {

        $image_path =
            __DIR__
            . "/../../uploads/investments/"
            . $investment["image"];


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