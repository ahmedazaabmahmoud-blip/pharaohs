<?php

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/functions.php";

$page_title = $page_title ?? "Pharaohs Integrated Business";

$page_description = $page_description ?? "Pharaohs Integrated Business is an Egyptian multi-activity company specializing in international export, sourcing, procurement, general trading, contracting, IT solutions and business development.";

$page_keywords = $page_keywords ?? "Pharaohs Integrated Business, Egypt export, Egyptian products, sourcing from Egypt, international trade, procurement Egypt, general trading, contracting, IT solutions";

$current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($page_title) ?></title>

    <meta
        name="description"
        content="<?= htmlspecialchars($page_description) ?>"
    >

    <meta
        name="keywords"
        content="<?= htmlspecialchars($page_keywords) ?>"
    >

    <meta name="author" content="Pharaohs Integrated Business">

    <meta name="robots" content="index, follow">

    <meta
        property="og:title"
        content="<?= htmlspecialchars($page_title) ?>"
    >

    <meta
        property="og:description"
        content="<?= htmlspecialchars($page_description) ?>"
    >

    <meta property="og:type" content="website">

    <meta
        property="og:url"
        content="<?= htmlspecialchars($current_url) ?>"
    >

    <meta
        property="og:site_name"
        content="Pharaohs Integrated Business"
    >

    <link
        rel="canonical"
        href="<?= htmlspecialchars($current_url) ?>"
    >

    <!-- Google Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- Global CSS -->

    <link
        rel="stylesheet"
        href="<?= SITE_URL ?>assets/css/style.css"
    >

    <link
        rel="stylesheet"
        href="<?= SITE_URL ?>assets/css/responsive.css"
    >

    <link
        rel="stylesheet"
        href="<?= SITE_URL ?>assets/css/components.css"
    >

    <!-- Page CSS -->

    <?php if (!empty($current_css)): ?>

        <link
            rel="stylesheet"
            href="<?= SITE_URL ?>assets/css/<?= htmlspecialchars($current_css) ?>"
        >

    <?php endif; ?>

</head>

<body>

<?php require_once __DIR__ . "/navbar.php"; ?>

<main>