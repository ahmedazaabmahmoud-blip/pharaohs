
<?php

$page_title = "Products | Pharaohs Integrated Business";

$page_description =
    "Explore products and sourcing opportunities from Egypt across food, agriculture, dates, medical supplies, industrial products and more.";

$page_keywords =
    "Egyptian products, products from Egypt, Medjool dates, medical supplies, industrial products, Egypt sourcing, export from Egypt";

$current_css = "products.css";
$current_js  = "products.js";

require_once __DIR__ . "/../includes/header.php";


// =========================================================
// GET ACTIVE CATEGORIES
// =========================================================

$categories = [];

$categories_query = "
    SELECT
        id,
        name,
        slug,
        description,
        image,
        sort_order
    FROM categories
    WHERE status = 'active'
    ORDER BY sort_order ASC, id ASC
";

$categories_result = $conn->query($categories_query);

if ($categories_result) {

    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}


// =========================================================
// GET ACTIVE PRODUCTS
// =========================================================

$products = [];

$products_query = "
    SELECT
        p.id,
        p.category_id,
        p.name,
        p.slug,
        p.short_description,
        p.image,
        p.origin_country,
        p.export_available,
        p.featured,
        p.sort_order,

        c.name AS category_name,
        c.slug AS category_slug

    FROM products p

    INNER JOIN categories c
        ON p.category_id = c.id
        AND c.status = 'active'

    WHERE p.status = 'active'

    ORDER BY
        p.sort_order ASC,
        p.featured DESC,
        p.id DESC
";

$products_result = $conn->query($products_query);

if ($products_result) {

    while ($row = $products_result->fetch_assoc()) {
        $products[] = $row;
    }
}


// =========================================================
// CATEGORY ICON
// =========================================================

function get_product_category_icon($slug)
{
    $icons = [
        'dates'                 => '🌴',
        'medical-supplies'      => '⚕',
        'agricultural-products' => '🌾',
        'industrial-products'   => '⚙',
        'food-products'         => '🍃',
        'other-products'        => '📦'
    ];

    return $icons[$slug] ?? '◈';
}


// =========================================================
// PRODUCT IMAGE
// =========================================================

function get_product_image($image)
{
    if (empty($image)) {
        return SITE_URL . "assets/images/products/product-placeholder.webp";
    }

    // External image URL
    if (preg_match('/^https?:\/\//i', $image)) {
        return $image;
    }

    // Local product image
    return SITE_URL . "uploads/products/" . ltrim($image, "/");
}

?>

<main class="products-page">


    <!-- =====================================================
         HERO
         ===================================================== -->

    <section class="products-page-hero">

        <div class="products-page-hero-bg"></div>

        <div class="container">

            <div class="products-page-hero-content">

                <span class="products-page-hero-label">
                    PRODUCTS & SOURCING
                </span>

                <h1>
                    Products & Solutions
                </h1>

                <p>
                    Explore products sourced from Egypt and discover
                    reliable opportunities across multiple industries.
                </p>

            </div>

        </div>

    </section>


    <!-- =====================================================
         INTRO
         ===================================================== -->

    <section class="products-page-intro">

        <div class="container">

            <div class="products-page-section-header">

                <div class="products-page-section-heading">

                    <span class="products-page-section-label">
                        OUR PRODUCTS
                    </span>

                    <h2>
                        Sourcing Products. Connecting Markets.
                    </h2>

                </div>

                <div class="products-page-section-description">

                    <p>
                        We source and supply products from Egypt
                        for international buyers, distributors,
                        businesses and partners across different
                        sectors.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         CATEGORIES
         ===================================================== -->

    <section class="products-page-categories">

        <div class="container">

            <div class="products-page-section-header">

                <div class="products-page-section-heading">

                    <span class="products-page-section-label">
                        PRODUCT CATEGORIES
                    </span>

                    <h2>
                        Explore Our Product Categories
                    </h2>

                </div>

                <div class="products-page-section-description">

                    <p>
                        Our sourcing capabilities cover food,
                        agriculture, dates, medical supplies,
                        industrial products and other exportable
                        products from Egypt.
                    </p>

                </div>

            </div>


            <?php if (!empty($categories)): ?>

                <div class="products-page-category-grid">

                    <?php foreach ($categories as $category): ?>

                        <?php
                        $category_slug = $category['slug'];
                        $category_name = $category['name'];
                        ?>

                        <article class="products-page-category-card">

                            <div class="products-page-category-icon">

                                <?= htmlspecialchars(
                                    get_product_category_icon($category_slug),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </div>

                            <div class="products-page-category-content">

                                <h3>
                                    <?= htmlspecialchars(
                                        $category_name,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </h3>


                                <?php if (!empty($category['description'])): ?>

                                    <p>
                                        <?= htmlspecialchars(
                                            $category['description'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </p>

                                <?php endif; ?>


                                <a
                                    href="#products-list"
                                    class="products-page-category-link products-category-filter"
                                    data-category="<?= htmlspecialchars(
                                        $category_slug,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                                >
                                    Explore Products
                                    <span>→</span>
                                </a>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="products-page-empty">

                    <p>
                        Product categories will be available soon.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>


    <!-- =====================================================
         PRODUCTS
         ===================================================== -->

    <section
        class="products-page-products"
        id="products-list"
    >

        <div class="container">

            <div class="products-page-section-header">

                <div class="products-page-section-heading">

                    <span class="products-page-section-label">
                        AVAILABLE PRODUCTS
                    </span>

                    <h2>
                        Products We Can Source & Supply
                    </h2>

                </div>

                <div class="products-page-section-description">

                    <p>
                        Browse our current product portfolio.
                        If you are looking for a specific product
                        from Egypt, you can also send us a sourcing request.
                    </p>

                </div>

            </div>


            <!-- =================================================
                 FILTERS
                 ================================================= -->

            <?php if (!empty($categories)): ?>

                <div class="products-page-filters">

                    <button
                        type="button"
                        class="products-page-filter active"
                        data-filter="all"
                        aria-pressed="true"
                    >
                        All Products
                    </button>


                    <?php foreach ($categories as $category): ?>

                        <button
                            type="button"
                            class="products-page-filter"
                            data-filter="<?= htmlspecialchars(
                                $category['slug'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                            aria-pressed="false"
                        >
                            <?= htmlspecialchars(
                                $category['name'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>
                        </button>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 PRODUCT GRID
                 ================================================= -->

            <?php if (!empty($products)): ?>

                <div class="products-page-product-grid">

                    <?php foreach ($products as $product): ?>

                        <?php

                        $product_name = $product['name'];

                        $product_slug = $product['slug'];

                        $category_slug =
                            $product['category_slug'] ?? '';

                        $product_image = get_product_image(
                            $product['image'] ?? ''
                        );

                        ?>

                        <article
                            class="products-page-product-card"
                            data-category="<?= htmlspecialchars(
                                $category_slug,
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                        >

                            <!-- PRODUCT IMAGE -->

                            <div class="products-page-product-image">

                                <img
                                    src="<?= htmlspecialchars(
                                        $product_image,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                                    alt="<?= htmlspecialchars(
                                        $product_name,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                                    loading="lazy"
                                    decoding="async"
                                >

                            </div>


                            <!-- PRODUCT CONTENT -->

                            <div class="products-page-product-content">

                                <h3>
                                    <?= htmlspecialchars(
                                        $product_name,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </h3>


                                <?php if (!empty($product['short_description'])): ?>

                                    <p>
                                        <?= htmlspecialchars(
                                            $product['short_description'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>
                                    </p>

                                <?php endif; ?>


                                <a
                                    href="<?= htmlspecialchars(
                                        SITE_URL . 'pages/product-details.php?slug=' . urlencode($product_slug),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                                    class="products-page-product-link"
                                >
                                    View Product
                                    <span>→</span>
                                </a>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>


                <!-- NO RESULTS -->

                <div
                    class="products-page-no-results"
                    id="products-no-results"
                    hidden
                >
                    <p>
                        No products found in this category.
                    </p>
                </div>

            <?php else: ?>

                <div class="products-page-empty">

                    <p>
                        Products will be available soon.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>


    <!-- =====================================================
         SOURCING CTA
         ===================================================== -->

    <section class="products-page-sourcing">

        <div class="container">

            <div class="products-page-sourcing-inner">

                <div class="products-page-sourcing-content">

                    <span class="products-page-section-label">
                        CAN'T FIND WHAT YOU NEED?
                    </span>

                    <h2>
                        Tell Us What You Are Looking For
                    </h2>

                    <p>
                        If you are looking for a specific product
                        from Egypt, send us your requirements.
                        Our team can search, source and coordinate
                        the supply and export process.
                    </p>

                </div>


                <div class="products-page-sourcing-action">

                    <a
                        href="<?= SITE_URL; ?>pages/request-quote.php"
                        class="btn btn-primary"
                    >
                        Request a Quote
                    </a>

                </div>

            </div>

        </div>

    </section>


</main>


<?php

require_once __DIR__ . "/../includes/footer.php";

?>

