
<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/functions.php";

/*
|--------------------------------------------------------------------------
| Get Product Slug
|--------------------------------------------------------------------------
*/

$slug = trim($_GET["slug"] ?? "");

if ($slug === "") {
    http_response_code(404);
    exit("Product not found.");
}

/*
|--------------------------------------------------------------------------
| Get Product
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        p.*,
        c.name AS category_name,
        c.slug AS category_slug,
        c.industry_id,
        i.name AS industry_name,
        i.slug AS industry_slug
    FROM products p
    LEFT JOIN categories c
        ON c.id = p.category_id
        AND c.status = 'active'
    LEFT JOIN industries i
        ON i.id = c.industry_id
        AND i.status = 'active'
    WHERE p.slug = ?
    AND p.status = 'active'
    LIMIT 1
");

$stmt->bind_param("s", $slug);
$stmt->execute();

$result = $stmt->get_result();
$product = $result->fetch_assoc();

$stmt->close();

/*
|--------------------------------------------------------------------------
| Product Not Found
|--------------------------------------------------------------------------
*/

if (!$product) {
    http_response_code(404);
    exit("Product not found.");
}

/*
|--------------------------------------------------------------------------
| SEO
|--------------------------------------------------------------------------
*/

$product_name = trim($product["name"] ?? "");

$page_title =
    $product_name .
    " | Pharaohs Integrated Business";

$page_description = !empty($product["short_description"])
    ? $product["short_description"]
    : "Explore " .
      $product_name .
      " from Egypt through Pharaohs Integrated Business.";

$page_keywords =
    $product_name .
    ", Egyptian products, products from Egypt, sourcing, export, trading, Pharaohs Integrated Business";

/*
|--------------------------------------------------------------------------
| Product Image
|--------------------------------------------------------------------------
*/

$product_image = trim($product["image"] ?? "");

if ($product_image === "") {

    $product_image =
        SITE_URL . "assets/images/products/product-placeholder.webp";

} elseif (preg_match('/^https?:\/\//i', $product_image)) {

    // External image URL

} else {

    // Uploaded product image

    $product_image =
        SITE_URL .
        "uploads/products/" .
        ltrim($product_image, "/");
}

/*
|--------------------------------------------------------------------------
| Export Availability
|--------------------------------------------------------------------------
*/

$export_available =
    (int) ($product["export_available"] ?? 0) === 1;

/*
|--------------------------------------------------------------------------
| Origin Country
|--------------------------------------------------------------------------
|
| Some old records may contain 0 / 1 instead of a country name.
| Do not display these values as the country.
|
*/

$origin_country = trim($product["origin_country"] ?? "");

if (
    $origin_country === "" ||
    $origin_country === "0" ||
    $origin_country === "1"
) {
    $origin_country = "Egypt";
}

/*
|--------------------------------------------------------------------------
| Page CSS
|--------------------------------------------------------------------------
*/

$current_css = "product-details.css";

/*
|--------------------------------------------------------------------------
| Header
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../includes/header.php";

?>

<div class="product-details-page">

    <!-- =====================================================
         HERO
    ====================================================== -->

    <section
        class="product-details-hero"
        style="--product-hero-image: url('<?= htmlspecialchars(
            $product_image,
            ENT_QUOTES,
            'UTF-8'
        ) ?>');"
    >

        <div class="container">

            <div class="product-details-hero-content">

                <a
                    href="<?= SITE_URL ?>pages/products.php"
                    class="product-details-hero-back"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Products
                </a>

                <?php if (!empty($product["category_name"])): ?>

                    <span class="product-details-hero-label">

                        <?= htmlspecialchars(
                            strtoupper($product["category_name"]),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </span>

                <?php else: ?>

                    <span class="product-details-hero-label">
                        PRODUCT
                    </span>

                <?php endif; ?>

                <h1>
                    <?= htmlspecialchars(
                        $product_name,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </h1>

                <?php if (!empty($product["short_description"])): ?>

                    <p>
                        <?= htmlspecialchars(
                            $product["short_description"],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </p>

                <?php endif; ?>

            </div>

        </div>

    </section>


    <!-- =====================================================
         PRODUCT OVERVIEW
    ====================================================== -->

    <section class="product-details-overview section-padding">

        <div class="container">

            <div class="product-details-overview-grid">

                <!-- IMAGE -->

                <div class="product-details-image">

                    <img
                        src="<?= htmlspecialchars(
                            $product_image,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        alt="<?= htmlspecialchars(
                            $product_name,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        loading="eager"
                        decoding="async"
                    >

                </div>


                <!-- CONTENT -->

                <div class="product-details-content">

                    <span class="product-details-section-label">
                        PRODUCT OVERVIEW
                    </span>

                    <h2>
                        <?= htmlspecialchars(
                            $product_name,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </h2>


                    <?php if (!empty($product["description"])): ?>

                        <div class="product-details-description">

                            <?= nl2br(
                                htmlspecialchars(
                                    $product["description"],
                                    ENT_QUOTES,
                                    'UTF-8'
                                )
                            ) ?>

                        </div>

                    <?php elseif (!empty($product["short_description"])): ?>

                        <p>
                            <?= htmlspecialchars(
                                $product["short_description"],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>

                        <p>
                            We can support sourcing and supply
                            requirements for this product based on
                            specifications, quantity, destination
                            and commercial requirements.
                        </p>

                    <?php else: ?>

                        <p>
                            This product is available for sourcing
                            and business requirements through our
                            network of Egyptian suppliers and
                            business partners.
                        </p>

                    <?php endif; ?>


                    <!-- PRODUCT META -->

                    <div class="product-details-meta">

                        <?php if (!empty($product["category_name"])): ?>

                            <div class="product-details-meta-item">

                                <span>
                                    CATEGORY
                                </span>

                                <strong>
                                    <?= htmlspecialchars(
                                        $product["category_name"],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </strong>

                            </div>

                        <?php endif; ?>


                        <?php if (!empty($origin_country)): ?>

                            <div class="product-details-meta-item">

                                <span>
                                    ORIGIN
                                </span>

                                <strong>
                                    <?= htmlspecialchars(
                                        $origin_country,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </strong>

                            </div>

                        <?php endif; ?>


                        <div class="product-details-meta-item">

                            <span>
                                EXPORT
                            </span>

                            <strong
                                class="<?= $export_available
                                    ? "available"
                                    : "not-available" ?>"
                            >

                                <?= $export_available
                                    ? "Available"
                                    : "Currently Unavailable" ?>

                            </strong>

                        </div>

                    </div>


                    <!-- CTA -->

                    <div class="product-details-actions">

                        <a
                            href="<?= SITE_URL ?>request-quote.php?product=<?= urlencode(
                                $product_name
                            ) ?>"
                            class="btn btn-primary"
                        >
                            Request This Product
                        </a>

                        <a
                            href="<?= SITE_URL ?>pages/contact.php"
                            class="btn btn-outline"
                        >
                            Contact Us
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         ADDITIONAL INFORMATION
    ====================================================== -->

    <?php if (!empty($product["additional_info"]) && $product["additional_info"] !== "0"): ?>

        <section class="product-details-info section-padding">

            <div class="container">

                <div class="product-details-info-grid">

                    <div class="product-details-info-heading">

                        <span class="product-details-section-label">
                            ADDITIONAL INFORMATION
                        </span>

                        <h2>
                            Product
                            <span>Details</span>
                        </h2>

                    </div>


                    <div class="product-details-info-content">

                        <?= nl2br(
                            htmlspecialchars(
                                $product["additional_info"],
                                ENT_QUOTES,
                                'UTF-8'
                            )
                        ) ?>

                    </div>

                </div>

            </div>

        </section>

    <?php endif; ?>


    <!-- =====================================================
         BUSINESS CONTEXT
    ====================================================== -->

    <?php if (
        !empty($product["industry_name"]) ||
        !empty($product["category_name"])
    ): ?>

        <section class="product-details-context section-padding">

            <div class="container">

                <div class="product-details-context-grid">


                    <div class="product-details-context-content">

                        <span class="product-details-section-label">
                            BUSINESS CONTEXT
                        </span>

                        <h2>
                            Part of Our
                            <span>Egyptian Sourcing Network.</span>
                        </h2>

                        <p>
                            We work with different product categories
                            and industries to connect international
                            requirements with suitable Egyptian
                            suppliers and products.
                        </p>

                        <p>
                            Availability, specifications, quantities
                            and export requirements are evaluated
                            according to each request.
                        </p>

                    </div>


                    <div class="product-details-context-links">


                        <?php if (
                            !empty($product["industry_name"]) &&
                            !empty($product["industry_slug"])
                        ): ?>

                            <a
                                href="<?= SITE_URL ?>pages/industry-details.php?slug=<?= urlencode(
                                    $product["industry_slug"]
                                ) ?>"
                                class="product-details-context-link"
                            >

                                <span>
                                    INDUSTRY
                                </span>

                                <strong>
                                    <?= htmlspecialchars(
                                        $product["industry_name"],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </strong>

                                <i class="fa-solid fa-arrow-right"></i>

                            </a>

                        <?php endif; ?>


                        <?php if (
                            !empty($product["category_name"]) &&
                            !empty($product["category_slug"])
                        ): ?>

                            <a
                                href="<?= SITE_URL ?>pages/products.php?category=<?= urlencode(
                                    $product["category_slug"]
                                ) ?>#products-list"
                                class="product-details-context-link"
                            >

                                <span>
                                    CATEGORY
                                </span>

                                <strong>
                                    <?= htmlspecialchars(
                                        $product["category_name"],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </strong>

                                <i class="fa-solid fa-arrow-right"></i>

                            </a>

                        <?php endif; ?>


                    </div>

                </div>

            </div>

        </section>

    <?php endif; ?>


    <!-- =====================================================
         FINAL CTA
    ====================================================== -->

    <section class="product-details-cta">

        <div class="container">

            <div class="product-details-cta-content">

                <span>
                    FROM EGYPT TO THE WORLD
                </span>

                <h2>
                    Looking For
                    <strong>
                        <?= htmlspecialchars(
                            $product_name,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>?
                    </strong>
                </h2>

                <p>
                    Tell us your required quantity, specifications
                    and destination and let us explore the available
                    sourcing and export possibilities.
                </p>


                <div class="product-details-cta-actions">

                    <a
                        href="<?= SITE_URL ?>request-quote.php?product=<?= urlencode(
                            $product_name
                        ) ?>"
                        class="btn btn-primary"
                    >
                        Request a Quote
                    </a>

                    <a
                        href="<?= SITE_URL ?>pages/products.php"
                        class="btn btn-outline"
                    >
                        Explore Products
                    </a>

                </div>

            </div>

        </div>

    </section>

</div>


<?php

require_once __DIR__ . "/../includes/footer.php";

?>

