<?php

require_once __DIR__ . "/../config/database.php";


/*
|--------------------------------------------------------------------------
| Get Website Setting
|--------------------------------------------------------------------------
*/

function get_setting($key, $default = "")
{
    global $conn;

    if (!$conn) {
        return $default;
    }

    $stmt = $conn->prepare(
        "SELECT setting_value 
         FROM settings 
         WHERE setting_key = ? 
         LIMIT 1"
    );

    if (!$stmt) {
        return $default;
    }

    $stmt->bind_param("s", $key);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {

        $row = $result->fetch_assoc();

        return $row["setting_value"] ?? $default;
    }

    return $default;
}