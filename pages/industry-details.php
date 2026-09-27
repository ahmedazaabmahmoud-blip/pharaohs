<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/functions.php";


/*
|--------------------------------------------------------------------------
| Get Industry Slug
|--------------------------------------------------------------------------
*/

$slug = trim($_GET["slug"] ?? "");

if ($slug === "") {

    http_response_code(404);

    exit("Industry not found.");
}


/*
|--------------------------------------------------------------------------
| Get Industry
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM industries
    WHERE slug = ?
    AND status = 'active'
    LIMIT 1
");

if (!$stmt) {

    http_response_code(500);

    exit("Unable to load industry.");
}

$stmt->bind_param("s", $slug);

$stmt->execute();

$result = $stmt->get_result();

$industry = $result->fetch_assoc();

$stmt->close();


/*
|--------------------------------------------------------------------------
| Industry Not Found
|--------------------------------------------------------------------------
*/

if (!$industry) {

    http_response_code(404);

    exit("Industry not found.");
}


/*
|--------------------------------------------------------------------------
| SEO
|--------------------------------------------------------------------------
*/

$page_title =
    $industry["name"] .
    " | Pharaohs Integrated Business";

$page_description =
    !empty($industry["short_description"])
        ? $industry["short_description"]
        : "Explore " .
          $industry["name"] .
          " products, sourcing and export opportunities from Egypt.";

$page_keywords =
    $industry["name"] .
    ", Egypt, Egyptian products, sourcing, export, trading, Pharaohs Integrated Business";


/*
|--------------------------------------------------------------------------
| Industry Image
|--------------------------------------------------------------------------
*/

$industry_image = trim(
    $industry["image"] ?? ""
);


/*
|--------------------------------------------------------------------------
| Fallback Images
|--------------------------------------------------------------------------
*/

$fallback_images = [

    "agriculture-food" =>
        "assets/images/industries/food.webp",

    "dates-agricultural-products" =>
        "assets/images/industries/food.webp",

    "medical-supplies" =>
        "assets/images/industries/medical.webp",

    "construction-contracting" =>
        "assets/images/industries/construction.webp",

    "industrial-products" =>
        "assets/images/industries/industrial.webp",

    "it-technology" =>
        "assets/images/industries/technology.webp",

    "general-trading" =>
        "assets/images/industries/general.webp",

    "other-exportable-products" =>
        "assets/images/industries/general.webp"
];


if ($industry_image === "") {

    $industry_image =
        $fallback_images[$industry["slug"] ?? ""]
        ?? "assets/images/industries/general.webp";
}


/*
|--------------------------------------------------------------------------
| Convert Image Path
|--------------------------------------------------------------------------
*/

if (
    !preg_match(
        '/^https?:\/\//i',
        $industry_image
    )
) {

    $industry_image =
        SITE_URL .
        ltrim($industry_image, "/");
}


/*
|--------------------------------------------------------------------------
| Get Categories
|--------------------------------------------------------------------------
*/

$categories = [];

$stmt = $conn->prepare("
    SELECT *
    FROM categories
    WHERE industry_id = ?
    AND status = 'active'
    ORDER BY sort_order ASC, name ASC
");

if ($stmt) {

    $stmt->bind_param(
        "i",
        $industry["id"]
    );

    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {

        $categories[] = $row;
    }

    $stmt->close();
}


/*
|--------------------------------------------------------------------------
| Get Products
|--------------------------------------------------------------------------
*/

$products = [];

$stmt = $conn->prepare("
    SELECT
        p.*,
        c.name AS category_name,
        c.slug AS category_slug

    FROM products p

    INNER JOIN categories c
        ON c.id = p.category_id

    WHERE c.industry_id = ?
    AND c.status = 'active'
    AND p.status = 'active'

    ORDER BY
        p.sort_order ASC,
        p.name ASC
");

if ($stmt) {

    $stmt->bind_param(
        "i",
        $industry["id"]
    );

    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {

        $products[] = $row;
    }

    $stmt->close();
}


/*
|--------------------------------------------------------------------------
| Page CSS
|--------------------------------------------------------------------------
*/

$current_css = "industry-details.css";


/*
|--------------------------------------------------------------------------
| Header
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../includes/header.php";

?>

<div class="industry-details-page">


<!-- =========================================================
     HERO
     ========================================================= -->

<section class="industry-details-hero">

    <div
        class="industry-details-hero-bg"
        style="background-image: url('<?= htmlspecialchars($industry_image) ?>');"
    ></div>

    <div class="industry-details-hero-overlay"></div>


    <div class="container">

        <div class="industry-details-hero-content">


            <a
                href="<?= SITE_URL ?>pages/industries.php"
                class="industry-details-hero-back"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Back to Industries

            </a>


            <span class="industry-details-hero-label">
                INDUSTRY &amp; SECTOR
            </span>


            <h1>
                <?= htmlspecialchars($industry["name"]) ?>
            </h1>


            <?php if (!empty($industry["short_description"])): ?>

                <p>
                    <?= htmlspecialchars(
                        $industry["short_description"]
                    ) ?>
                </p>

            <?php endif; ?>

        </div>

    </div>

</section>


<!-- =========================================================
     INTRO
     ========================================================= -->

<section class="industry-details-intro section-padding">

    <div class="container">

        <div class="industry-details-intro-grid">


            <div class="industry-details-intro-content">

                <span class="industry-details-section-label">
                    ABOUT THIS INDUSTRY
                </span>


                <h2>

                    <?= htmlspecialchars(
                        $industry["name"]
                    ) ?>

                    <span>
                        From Egypt.
                    </span>

                </h2>


                <?php if (!empty($industry["description"])): ?>

                    <div class="industry-details-description">

                        <?= nl2br(
                            htmlspecialchars(
                                $industry["description"]
                            )
                        ) ?>

                    </div>


                <?php elseif (!empty($industry["short_description"])): ?>

                    <p>
                        <?= htmlspecialchars(
                            $industry["short_description"]
                        ) ?>
                    </p>

                    <p>
                        Through our sourcing, trading and export
                        capabilities, we explore suitable Egyptian
                        products and suppliers based on each client's
                        requirements and market needs.
                    </p>


                <?php else: ?>

                    <p>
                        We explore opportunities within this sector
                        by identifying suitable Egyptian products,
                        suppliers and business solutions for local
                        and international requirements.
                    </p>

                <?php endif; ?>

            </div>


            <div class="industry-details-intro-image">

                <img
                    src="<?= htmlspecialchars($industry_image) ?>"
                    alt="<?= htmlspecialchars($industry["name"]) ?> from Egypt"
                    loading="lazy"
                    decoding="async"
                >

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     CATEGORIES
     ========================================================= -->

<?php if (!empty($categories)): ?>

    <section class="industry-details-scope section-padding">

        <div class="container">


            <div class="industry-details-heading">

                <div>

                    <span class="industry-details-section-label">
                        CATEGORIES
                    </span>

                    <h2>
                        Areas We
                        <span>Can Support</span>
                    </h2>

                </div>


                <p>
                    Explore the categories currently associated
                    with this industry.
                </p>

            </div>


            <div class="industry-details-items">

                <?php foreach ($categories as $index => $category): ?>

                    <article class="industry-details-item">


                        <span class="industry-details-item-number">

                            <?= str_pad(
                                $index + 1,
                                2,
                                "0",
                                STR_PAD_LEFT
                            ) ?>

                        </span>


                        <div class="industry-details-item-content">

                            <h3>
                                <?= htmlspecialchars(
                                    $category["name"]
                                ) ?>
                            </h3>


                            <?php if (!empty($category["description"])): ?>

                                <p>
                                    <?= htmlspecialchars(
                                        $category["description"]
                                    ) ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        </div>

    </section>

<?php endif; ?>


<!-- =========================================================
     PRODUCTS
     ========================================================= -->

<?php if (!empty($products)): ?>

    <section class="industry-details-products section-padding">

        <div class="container">


            <div class="industry-details-heading">

                <div>

                    <span class="industry-details-section-label">
                        PRODUCTS
                    </span>

                    <h2>
                        Products Available
                        <span>From Egypt</span>
                    </h2>

                </div>


                <p>
                    Products currently available through our
                    sourcing and trading network.
                </p>

            </div>


            <div class="industry-details-products-grid">


                <?php foreach ($products as $product): ?>

                    <?php

                    /*
                    |--------------------------------------------------------------------------
                    | Product Image
                    |--------------------------------------------------------------------------
                    */

                    $product_image = trim(
                        $product["image"] ?? ""
                    );


                    if ($product_image === "") {

                        $product_image =
                            SITE_URL .
                            "assets/images/products/product-placeholder.webp";

                    } elseif (
                        !preg_match(
                            '/^https?:\/\//i',
                            $product_image
                        )
                    ) {

                        /*
                        | Local product images
                        */

                        $product_image =
                            SITE_URL .
                            "uploads/products/" .
                            ltrim(
                                $product_image,
                                "/"
                            );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Product Name
                    |--------------------------------------------------------------------------
                    */

                    $product_name =
                        trim(
                            $product["name"] ??
                            "Product"
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Product Slug
                    |--------------------------------------------------------------------------
                    */

                    $product_slug =
                        trim(
                            $product["slug"] ?? ""
                        );

                    ?>

                    <article class="industry-details-product-card">


                        <div class="industry-details-product-image">

                            <img
                                src="<?= htmlspecialchars($product_image) ?>"
                                alt="<?= htmlspecialchars($product_name) ?>"
                                loading="lazy"
                                decoding="async"
                            >

                        </div>


                        <div class="industry-details-product-content">


                            <?php if (!empty($product["category_name"])): ?>

                                <span>

                                    <?= htmlspecialchars(
                                        $product["category_name"]
                                    ) ?>

                                </span>

                            <?php endif; ?>


                            <h3>
                                <?= htmlspecialchars(
                                    $product_name
                                ) ?>
                            </h3>


                            <?php if (!empty($product["short_description"])): ?>

                                <p>
                                    <?= htmlspecialchars(
                                        $product["short_description"]
                                    ) ?>
                                </p>

                            <?php endif; ?>


                            <?php if ($product_slug !== ""): ?>

                                <a
                                    href="<?= SITE_URL ?>pages/product-details.php?slug=<?= urlencode($product_slug) ?>"
                                    class="industry-details-product-link"
                                >

                                    View Product

                                    <i class="fa-solid fa-arrow-right"></i>

                                </a>

                            <?php endif; ?>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        </div>

    </section>

<?php endif; ?>


<!-- =========================================================
     OUR ROLE
     ========================================================= -->

<section class="industry-details-role section-padding">

    <div class="container">

        <div class="industry-details-role-grid">


            <div class="industry-details-role-content">

                <span class="industry-details-section-label">
                    OUR ROLE
                </span>


                <h2>

                    From Local Sources

                    <span>
                        To Global Opportunities.
                    </span>

                </h2>


                <p>
                    We help clients explore the Egyptian market,
                    identify suitable products and suppliers,
                    coordinate sourcing requirements and support
                    the export process when applicable.
                </p>


                <p>
                    Every requirement is evaluated based on product
                    specifications, quantity, destination and
                    commercial feasibility.
                </p>

            </div>


            <div class="industry-details-role-list">


                <div class="industry-details-role-item">

                    <span class="industry-details-role-icon">

                        <i class="fa-solid fa-magnifying-glass"></i>

                    </span>


                    <div>

                        <h3>
                            Market Research
                        </h3>

                        <p>
                            Exploring suitable products and potential
                            sources within the Egyptian market.
                        </p>

                    </div>

                </div>


                <div class="industry-details-role-item">

                    <span class="industry-details-role-icon">

                        <i class="fa-solid fa-boxes-stacked"></i>

                    </span>


                    <div>

                        <h3>
                            Sourcing &amp; Procurement
                        </h3>

                        <p>
                            Connecting requirements with suitable
                            suppliers and available products.
                        </p>

                    </div>

                </div>


                <div class="industry-details-role-item">

                    <span class="industry-details-role-icon">

                        <i class="fa-solid fa-globe"></i>

                    </span>


                    <div>

                        <h3>
                            Export Support
                        </h3>

                        <p>
                            Supporting international buyers with
                            sourcing and export coordination.
                        </p>

                    </div>

                </div>


                <div class="industry-details-role-item">

                    <span class="industry-details-role-icon">

                        <i class="fa-solid fa-handshake"></i>

                    </span>


                    <div>

                        <h3>
                            Business Coordination
                        </h3>

                        <p>
                            Helping connect the right requirements
                            with the right business opportunities.
                        </p>

                    </div>

                </div>


            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     CTA
     ========================================================= -->

<section class="industry-details-cta">

    <div class="container">

        <div class="industry-details-cta-content">

            <span>
                FROM EGYPT TO THE WORLD
            </span>


            <h2>

                Looking For Products

                <strong>
                    From Egypt?
                </strong>

            </h2>


            <p>
                Tell us what you are looking for and let us explore
                the available products, suppliers and sourcing
                possibilities for your requirement.
            </p>


            <div class="industry-details-cta-actions">


                <a
                    href="<?= SITE_URL ?>request-quote.php"
                    class="btn btn-primary"
                >
                    Request a Quote
                </a>


                <a
                    href="<?= SITE_URL ?>pages/industries.php"
                    class="btn btn-outline"
                >
                    Explore Industries
                </a>


            </div>

        </div>

    </div>

</section>


</div>

<?php

require_once __DIR__ . "/../includes/footer.php";

?>
