<?php

require_once __DIR__ . "/../../config/config.php";

$page_title = $page_title ?? "Dashboard";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($page_title) ?> | Pharaohs Admin</title>

    <link rel="stylesheet" href="<?= ADMIN_URL ?>assets/css/admin.css">

</head>

<body>

<div class="admin-layout">