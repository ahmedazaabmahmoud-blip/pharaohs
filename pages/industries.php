<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/functions.php";


/*
|--------------------------------------------------------------------------
| SEO
|--------------------------------------------------------------------------
*/

$page_title = "Industries & Sectors | Pharaohs Integrated Business";

$page_description = "Explore the industries and sectors served by Pharaohs Integrated Business, including agriculture, dates, medical supplies, construction, industrial products, technology, general trading and other exportable products.";

$page_keywords = "Egypt industries, Egyptian products, agriculture Egypt, dates Egypt, medical supplies Egypt, industrial products Egypt, construction Egypt, IT technology Egypt, general trading Egypt, export from Egypt";


/*
|--------------------------------------------------------------------------
| Get Active Industries
|--------------------------------------------------------------------------
*/

$industries = [];

$stmt = $conn->prepare("
    SELECT *
    FROM industries
    WHERE status = 'active'
    ORDER BY sort_order ASC, name ASC
");

if ($stmt) {

    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $industries[] = $row;
    }

    $stmt->close();
}


/*
|--------------------------------------------------------------------------
| Industry Images
|--------------------------------------------------------------------------
|
| Fallback images are used when no image is stored
| in the database.
|
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


/*
|--------------------------------------------------------------------------
| Page CSS
|--------------------------------------------------------------------------
*/

$current_css = "industries.css";


/*
|--------------------------------------------------------------------------
| Header
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../includes/header.php";

?>

<div class="industries-page">


<!-- =========================================================
     HERO
     ========================================================= -->

<section class="industries-page-hero">

    <div class="industries-page-hero-bg"></div>

    <div class="industries-page-hero-overlay"></div>

    <div class="container">

        <div class="industries-page-hero-content">

            <span class="industries-page-hero-label">
                INDUSTRIES &amp; SECTORS
            </span>

            <h1>
                Connecting Opportunities
                <span>Across Different Industries</span>
            </h1>

            <p>
                Our multi-activity approach allows us to work across
                different sectors, products and business requirements,
                connecting Egyptian capabilities with local and
                international opportunities.
            </p>

        </div>

    </div>

</section>


<!-- =========================================================
     INTRO + INDUSTRIES
     ========================================================= -->

<section class="industries-page-intro section-padding">

    <div class="container">

        <div class="industries-page-heading">

            <div>

                <span class="industries-page-section-label">
                    OUR BUSINESS SCOPE
                </span>

                <h2>
                    More Than
                    <span>One Industry.</span>
                </h2>

            </div>

            <p>
                We do not limit our business model to one product
                or one sector. We explore opportunities across
                different industries and connect requirements with
                suitable Egyptian products, suppliers and solutions.
            </p>

        </div>


        <!-- =====================================================
             INDUSTRIES GRID
             ===================================================== -->

        <?php if (!empty($industries)): ?>

            <div class="industries-page-grid">

                <?php foreach ($industries as $index => $industry): ?>

                    <?php

                    /*
                    |--------------------------------------------------------------------------
                    | Industry Image
                    |--------------------------------------------------------------------------
                    */

                    $industry_image = trim(
                        $industry["image"] ?? ""
                    );

                    if ($industry_image === "") {

                        $industry_image =
                            $fallback_images[$industry["slug"] ?? ""]
                            ?? "assets/images/industries/general.webp";
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Convert Local Image Path To Absolute URL
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !preg_match(
                            '/^https?:\/\//i',
                            $industry_image
                        )
                    ) {

                        $industry_image =
                            SITE_URL . ltrim(
                                $industry_image,
                                "/"
                            );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Industry Number
                    |--------------------------------------------------------------------------
                    */

                    $industry_number = str_pad(
                        $index + 1,
                        2,
                        "0",
                        STR_PAD_LEFT
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Industry Name
                    |--------------------------------------------------------------------------
                    */

                    $industry_name =
                        trim($industry["name"] ?? "Industry");


                    /*
                    |--------------------------------------------------------------------------
                    | Industry Slug
                    |--------------------------------------------------------------------------
                    */

                    $industry_slug =
                        trim($industry["slug"] ?? "");


                    /*
                    |--------------------------------------------------------------------------
                    | Industry Description
                    |--------------------------------------------------------------------------
                    */

                    $industry_description =
                        trim(
                            $industry["short_description"] ?? ""
                        );

                    if ($industry_description === "") {

                        $industry_description =
                            "Explore products, sourcing opportunities and business requirements within this sector.";
                    }

                    ?>

                    <article class="industries-page-card">


                        <!-- IMAGE -->

                        <div class="industries-page-card-image">

                            <img
                                src="<?= htmlspecialchars($industry_image) ?>"
                                alt="<?= htmlspecialchars($industry_name) ?>"
                                loading="lazy"
                                decoding="async"
                            >

                            <span class="industries-page-card-number">
                                <?= $industry_number ?>
                            </span>

                        </div>


                        <!-- CONTENT -->

                        <div class="industries-page-card-content">

                            <span class="industries-page-card-label">
                                <?= htmlspecialchars(
                                    strtoupper($industry_name)
                                ) ?>
                            </span>


                            <h3>
                                <?= htmlspecialchars($industry_name) ?>
                            </h3>


                            <p>
                                <?= htmlspecialchars(
                                    $industry_description
                                ) ?>
                            </p>


                            <!-- TAGS -->

                            <div class="industries-page-tags">

                                <span>
                                    Egypt
                                </span>

                                <span>
                                    Sourcing
                                </span>

                                <span>
                                    Export
                                </span>

                            </div>


                            <!-- DETAILS LINK -->

                            <?php if ($industry_slug !== ""): ?>

                                <a
                                    href="<?= SITE_URL ?>pages/industry-details.php?slug=<?= urlencode($industry_slug) ?>"
                                    class="industries-page-card-link"
                                >

                                    Explore Industry

                                    <i class="fa-solid fa-arrow-right"></i>

                                </a>

                            <?php endif; ?>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="industries-page-empty">

                <p>
                    No industries are currently available.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================================
     CUSTOM REQUIREMENT
     ========================================================= -->

<section class="industries-page-custom section-padding">

    <div class="container">

        <div class="industries-page-custom-grid">


            <!-- CONTENT -->

            <div class="industries-page-custom-content">

                <span class="industries-page-section-label">
                    LOOKING FOR SOMETHING ELSE?
                </span>

                <h2>
                    Your Requirement
                    <span>Doesn't Have To Fit A Category.</span>
                </h2>

                <p class="industries-page-custom-lead">
                    Our business model is designed to remain flexible.
                    If you are looking for a product, supplier or
                    business opportunity that is not listed above,
                    tell us what you need.
                </p>

                <p>
                    We can explore the Egyptian market, identify
                    potential sources and determine whether we can
                    support your requirement.
                </p>

                <a
                    href="<?= SITE_URL ?>request-quote.php"
                    class="btn btn-primary"
                >
                    Tell Us What You Need
                </a>

            </div>


            <!-- NUMBER BOX -->

            <div class="industries-page-custom-box">

                <span class="industries-page-custom-number">
                    <?= str_pad(
                        count($industries),
                        2,
                        "0",
                        STR_PAD_LEFT
                    ) ?>+
                </span>

                <strong>
                    Industries
                </strong>

                <p>
                    With the flexibility to explore additional
                    products and sectors based on market demand.
                </p>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     CTA
     ========================================================= -->

<section class="industries-page-cta">

    <div class="container">

        <div class="industries-page-cta-content">

            <span>
                FROM EGYPT TO THE WORLD
            </span>

            <h2>
                Looking For Something
                <strong>From Egypt?</strong>
            </h2>

            <p>
                Tell us the product, specification or business
                requirement you are looking for and let us explore
                the possibilities.
            </p>


            <div class="industries-page-cta-actions">

                <a
                    href="<?= SITE_URL ?>request-quote.php"
                    class="btn btn-primary"
                >
                    Request a Quote
                </a>

                <a
                    href="<?= SITE_URL ?>pages/export.php"
                    class="btn btn-outline"
                >
                    Explore Export
                </a>

            </div>

        </div>

    </div>

</section>


</div>

<?php

require_once __DIR__ . "/../includes/footer.php";

?>
