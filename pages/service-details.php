
<?php

$page_title = "Service Details | Pharaohs Integrated Business";

$page_description = "Explore Pharaohs Integrated Business services and business solutions from Egypt.";

$page_keywords = "Egypt export, sourcing, procurement, general trading, contracting, IT solutions, business development";

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/functions.php";

$current_css = "service-details.css";


/* =========================================================
   GET SERVICE SLUG
   ========================================================= */

$slug = trim($_GET["slug"] ?? "");


/* =========================================================
   404 - EMPTY SLUG
   ========================================================= */

if ($slug === "") {

    http_response_code(404);

    $page_title = "Service Not Found | Pharaohs Integrated Business";

    require_once __DIR__ . "/../includes/header.php";
    ?>

    <main class="service-details-page">

        <section class="service-details-error section-padding">

            <div class="container">

                <div class="service-details-error-content">

                    <span class="section-label">
                        404
                    </span>

                    <h1>
                        Service Not Found
                    </h1>

                    <p>
                        The service you are looking for could not be found.
                    </p>

                    <a
                        href="<?= SITE_URL ?>pages/services.php"
                        class="btn btn-primary"
                    >
                        Back to Services
                    </a>

                </div>

            </div>

        </section>

    </main>

    <?php
    require_once __DIR__ . "/../includes/footer.php";
    exit;
}


/* =========================================================
   GET SERVICE FROM DATABASE
   ========================================================= */

$stmt = $conn->prepare("
    SELECT
        id,
        title,
        slug,
        short_description,
        description,
        details_title,
        details_description,
        feature_1,
        feature_2,
        feature_3,
        feature_4,
        process_1_title,
        process_1_description,
        process_2_title,
        process_2_description,
        process_3_title,
        process_3_description,
        process_4_title,
        process_4_description,
        image,
        icon,
        sort_order
    FROM services
    WHERE slug = ?
    AND status = 'active'
    LIMIT 1
");

if (!$stmt) {
    die("Database query error.");
}

$stmt->bind_param("s", $slug);

$stmt->execute();

$result = $stmt->get_result();

$service = $result->fetch_assoc();

$stmt->close();


/* =========================================================
   SERVICE NOT FOUND
   ========================================================= */

if (!$service) {

    http_response_code(404);

    $page_title = "Service Not Found | Pharaohs Integrated Business";

    require_once __DIR__ . "/../includes/header.php";
    ?>

    <main class="service-details-page">

        <section class="service-details-error section-padding">

            <div class="container">

                <div class="service-details-error-content">

                    <span class="section-label">
                        404
                    </span>

                    <h1>
                        Service Not Found
                    </h1>

                    <p>
                        The service you are looking for is not currently available.
                    </p>

                    <div class="service-details-error-actions">

                        <a
                            href="<?= SITE_URL ?>pages/services.php"
                            class="btn btn-primary"
                        >
                            Back to Services
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

        </section>

    </main>

    <?php
    require_once __DIR__ . "/../includes/footer.php";
    exit;
}


/* =========================================================
   CLEAN SERVICE DATA
   ========================================================= */

$clean_title = trim(
    $service["title"] ?? ""
);

$clean_short_description = trim(
    $service["short_description"] ?? ""
);

$clean_description = trim(
    $service["description"] ?? ""
);

$clean_details_title = trim(
    $service["details_title"] ?? ""
);

$clean_details_description = trim(
    $service["details_description"] ?? ""
);


/* =========================================================
   SEO DATA
   ========================================================= */

$page_title =
    $clean_title . " | Pharaohs Integrated Business";

$page_description =
    $clean_short_description !== ""
        ? $clean_short_description
        : "Explore " . $clean_title . " services from Pharaohs Integrated Business.";

$page_keywords =
    $clean_title . ", Pharaohs Integrated Business, Egypt";


/* =========================================================
   SERVICE DESCRIPTION
   ========================================================= */

$service_description =
    $clean_description !== ""
        ? $clean_description
        : $clean_short_description;


/* =========================================================
   SERVICE DETAILS
   ========================================================= */

$service_details_title =
    $clean_details_title !== ""
        ? $clean_details_title
        : "Built Around Your Requirement";

$service_details_description =
    $clean_details_description !== ""
        ? $clean_details_description
        : "";


/* =========================================================
   SERVICE FEATURES
   ========================================================= */

$service_features = [];

for ($i = 1; $i <= 4; $i++) {

    $feature_key = "feature_" . $i;

    $feature_value = trim(
        $service[$feature_key] ?? ""
    );

    if ($feature_value !== "") {

        $service_features[] = $feature_value;

    }
}


/* =========================================================
   SERVICE PROCESS
   ========================================================= */

$service_process = [];

for ($i = 1; $i <= 4; $i++) {

    $process_title_key =
        "process_" . $i . "_title";

    $process_description_key =
        "process_" . $i . "_description";

    $process_title = trim(
        $service[$process_title_key] ?? ""
    );

    $process_description = trim(
        $service[$process_description_key] ?? ""
    );

    if (
        $process_title !== "" ||
        $process_description !== ""
    ) {

        $service_process[] = [
            "title" => $process_title,
            "description" => $process_description
        ];

    }
}


/* =========================================================
   DEFAULT PROCESS FALLBACK
   ========================================================= */

if (empty($service_process)) {

    $service_process = [

        [
            "title" => "Understand",
            "description" =>
                "We understand your requirement, specifications and business objectives."
        ],

        [
            "title" => "Explore",
            "description" =>
                "We explore suitable products, suppliers, partners or solutions."
        ],

        [
            "title" => "Coordinate",
            "description" =>
                "We coordinate the relevant commercial and operational details."
        ],

        [
            "title" => "Move Forward",
            "description" =>
                "Once the requirements are aligned, we support the next stage of the process."
        ]

    ];

}


/* =========================================================
   SERVICE IMAGE
   ========================================================= */

function get_service_image($image)
{

    if (empty($image)) {

        return SITE_URL .
            "assets/images/hero/hero-main.webp";

    }

    if (preg_match('/^https?:\/\//i', $image)) {

        return $image;

    }

    return SITE_URL .
        "uploads/services/" .
        ltrim($image, "/");
}

$service_image = get_service_image(
    $service["image"] ?? ""
);


/* =========================================================
   SERVICE NUMBER
   ========================================================= */

$service_number =
    (int) ($service["sort_order"] ?? 0);

if ($service_number <= 0) {

    $service_number =
        (int) $service["id"];

}

$service_number = str_pad(
    $service_number,
    2,
    "0",
    STR_PAD_LEFT
);


/* =========================================================
   ESCAPED SERVICE DATA
   ========================================================= */

$service_title = htmlspecialchars(
    $clean_title,
    ENT_QUOTES,
    "UTF-8"
);

$service_short_description =
    $clean_short_description !== ""
        ? htmlspecialchars(
            $clean_short_description,
            ENT_QUOTES,
            "UTF-8"
        )
        : "";


/* =========================================================
   HEADER
   ========================================================= */

require_once __DIR__ . "/../includes/header.php";

?>

<main class="service-details-page">


    <!-- =====================================================
         HERO
         ===================================================== -->

    <section class="service-details-hero">

        <div
            class="service-details-hero-background"
            style="background-image: url('<?= htmlspecialchars($service_image, ENT_QUOTES, "UTF-8") ?>');"
        ></div>

        <div class="service-details-hero-overlay"></div>

        <div class="container service-details-hero-container">

            <div class="service-details-hero-content">

                <div class="service-details-breadcrumb">

                    <a href="<?= SITE_URL ?>pages/services.php">
                        Services
                    </a>

                    <span>/</span>

                    <span>
                        <?= $service_title ?>
                    </span>

                </div>


                <span class="service-details-label">

                    SERVICE <?= $service_number ?>

                </span>


                <h1>
                    <?= $service_title ?>
                </h1>


                <?php if ($service_short_description !== ""): ?>

                    <p>
                        <?= $service_short_description ?>
                    </p>

                <?php endif; ?>

            </div>

        </div>

    </section>



    <!-- =====================================================
         MAIN CONTENT
         ===================================================== -->

    <section class="service-details-content section-padding">

        <div class="container">

            <div class="service-details-layout">


                <!-- MAIN CONTENT -->

                <div class="service-details-main">

                    <div class="service-details-heading">

                        <span class="section-label">
                            SERVICE OVERVIEW
                        </span>

                        <h2>
                            <?= $service_title ?>
                        </h2>

                    </div>


                    <div class="service-details-description">

                        <?php if ($service_description !== ""): ?>

                            <div class="service-details-db-description">

                                <?= nl2br(
                                    htmlspecialchars(
                                        $service_description,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    )
                                ) ?>

                            </div>

                        <?php else: ?>

                            <p>
                                Please contact our team to discuss your
                                requirements and learn more about this service.
                            </p>

                        <?php endif; ?>

                    </div>



                    <!-- =================================================
                         DATABASE SERVICE DETAILS
                         ================================================= -->

                    <?php if (
                        $clean_details_title !== "" ||
                        $clean_details_description !== "" ||
                        !empty($service_features)
                    ): ?>

                        <div class="service-details-features">

                            <div class="service-details-features-heading">

                                <span class="section-label">
                                    SERVICE DETAILS
                                </span>

                                <h3>
                                    <?= htmlspecialchars(
                                        $service_details_title,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </h3>

                            </div>


                            <?php if ($clean_details_description !== ""): ?>

                                <div class="service-details-db-description">

                                    <?= nl2br(
                                        htmlspecialchars(
                                            $clean_details_description,
                                            ENT_QUOTES,
                                            "UTF-8"
                                        )
                                    ) ?>

                                </div>

                            <?php endif; ?>


                            <?php if (!empty($service_features)): ?>

                                <div class="service-details-features-grid">

                                    <?php foreach (
                                        $service_features
                                        as $index => $feature
                                    ): ?>

                                        <div class="service-details-feature">

                                            <span>
                                                <?= str_pad(
                                                    $index + 1,
                                                    2,
                                                    "0",
                                                    STR_PAD_LEFT
                                                ) ?>
                                            </span>

                                            <p>
                                                <?= htmlspecialchars(
                                                    $feature,
                                                    ENT_QUOTES,
                                                    "UTF-8"
                                                ) ?>
                                            </p>

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                </div>



                <!-- SIDEBAR -->

                <aside class="service-details-sidebar">

                    <div class="service-details-sidebar-box">

                        <span class="service-details-sidebar-label">
                            NEED THIS SERVICE?
                        </span>

                        <h3>
                            Tell us what you need.
                        </h3>

                        <p>
                            Share your requirement with our team and
                            we will review it and discuss the next steps
                            with you.
                        </p>

                        <a
                            href="<?= SITE_URL ?>request-quote.php"
                            class="btn btn-primary"
                        >
                            Request a Quote
                        </a>

                        <a
                            href="<?= SITE_URL ?>pages/contact.php"
                            class="service-details-contact-link"
                        >

                            Contact Us

                            <span>
                                →
                            </span>

                        </a>

                    </div>

                </aside>


            </div>

        </div>

    </section>



    <!-- =====================================================
         PROCESS
         ===================================================== -->

    <section class="service-details-process section-padding">

        <div class="container">

            <div class="section-heading">

                <div>

                    <span class="section-label">
                        HOW IT WORKS
                    </span>

                    <h2>
                        From Your Requirement
                        <span>
                            To The Next Step
                        </span>
                    </h2>

                </div>

                <p>
                    We keep the process practical and focused on
                    understanding your requirement, identifying suitable
                    options and coordinating the next steps.
                </p>

            </div>


            <div class="service-details-process-grid">

                <?php foreach (
                    $service_process
                    as $index => $process
                ): ?>

                    <div class="service-details-process-item">

                        <span>
                            <?= str_pad(
                                $index + 1,
                                2,
                                "0",
                                STR_PAD_LEFT
                            ) ?>
                        </span>

                        <div>

                            <h3>

                                <?= htmlspecialchars(
                                    $process["title"] !== ""
                                        ? $process["title"]
                                        : "Step " . ($index + 1),
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>

                            </h3>

                            <?php if ($process["description"] !== ""): ?>

                                <p>

                                    <?= nl2br(
                                        htmlspecialchars(
                                            $process["description"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        )
                                    ) ?>

                                </p>

                            <?php endif; ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </section>



    <!-- =====================================================
         CTA
         ===================================================== -->

    <section class="service-details-cta">

        <div class="container">

            <div class="service-details-cta-content">

                <span>
                    READY TO GET STARTED?
                </span>

                <h2>

                    Let's Discuss

                    <strong>
                        Your Requirement.
                    </strong>

                </h2>

                <p>
                    Tell us what you need and let our team explore
                    the right way to move it forward.
                </p>


                <div class="service-details-cta-actions">

                    <a
                        href="<?= SITE_URL ?>request-quote.php"
                        class="btn btn-primary"
                    >
                        Request a Quote
                    </a>

                    <a
                        href="<?= SITE_URL ?>pages/services.php"
                        class="btn btn-outline"
                    >
                        View All Services
                    </a>

                </div>

            </div>

        </div>

    </section>


</main>

<?php

require_once __DIR__ . "/../includes/footer.php";

?>

