
<?php

$page_title = "Our Services | Pharaohs Integrated Business";

$page_description = "Explore Pharaohs Integrated Business services including export from Egypt, sourcing and procurement, general trading, contracting, IT solutions and business development.";

$page_keywords = "Egypt export services, sourcing Egypt, procurement Egypt, general trading Egypt, contracting Egypt, IT solutions Egypt, business development Egypt";

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/functions.php";

$current_css = "services.css";


/* =========================================================
   GET ACTIVE SERVICES
   ========================================================= */

$services = [];

$stmt = $conn->prepare("
    SELECT
        id,
        title,
        slug,
        short_description,
        description,
        image,
        icon,
        sort_order
    FROM services
    WHERE status = 'active'
    ORDER BY sort_order ASC, id ASC
");

if ($stmt) {

    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {

        $services[] = $row;

    }

    $stmt->close();
}


/* =========================================================
   SERVICE LABELS
   ========================================================= */

function get_service_label($title)
{
    $title_lower = strtolower(trim($title));

    if (
        strpos($title_lower, 'export') !== false
    ) {
        return 'EXPORT';
    }

    if (
        strpos($title_lower, 'sourcing') !== false ||
        strpos($title_lower, 'procurement') !== false
    ) {
        return 'SOURCING';
    }

    if (
        strpos($title_lower, 'trading') !== false
    ) {
        return 'TRADING';
    }

    if (
        strpos($title_lower, 'contract') !== false
    ) {
        return 'CONTRACTING';
    }

    if (
        strpos($title_lower, 'it') !== false ||
        strpos($title_lower, 'technology') !== false
    ) {
        return 'TECHNOLOGY';
    }

    if (
        strpos($title_lower, 'business') !== false ||
        strpos($title_lower, 'development') !== false
    ) {
        return 'BUSINESS';
    }

    return 'SERVICE';
}


/* =========================================================
   SERVICE CTA TEXT
   ========================================================= */

function get_service_cta($title)
{
    $title_lower = strtolower(trim($title));

    if (strpos($title_lower, 'export') !== false) {
        return 'Explore Export Services';
    }

    if (
        strpos($title_lower, 'sourcing') !== false ||
        strpos($title_lower, 'procurement') !== false
    ) {
        return 'Request Sourcing';
    }

    if (strpos($title_lower, 'trading') !== false) {
        return 'Discuss Your Requirement';
    }

    if (strpos($title_lower, 'contract') !== false) {
        return 'Discuss a Project';
    }

    if (
        strpos($title_lower, 'it') !== false ||
        strpos($title_lower, 'technology') !== false
    ) {
        return 'Discuss IT Solutions';
    }

    if (
        strpos($title_lower, 'business') !== false ||
        strpos($title_lower, 'development') !== false
    ) {
        return 'Discuss an Opportunity';
    }

    return 'Explore This Service';
}


/* =========================================================
   HEADER
   ========================================================= */

require_once __DIR__ . "/../includes/header.php";

?>


<!-- =========================================================
     SERVICES HERO
     ========================================================= -->

<section class="services-page-hero">

    <div class="services-page-hero-background">

        <div class="services-page-hero-overlay"></div>

    </div>


    <div class="container services-page-hero-container">

        <div class="services-page-hero-content">

            <span class="services-page-hero-label">
                OUR SERVICES
            </span>


            <h1>

                Business Solutions

                <span>
                    Built Around Your Needs
                </span>

            </h1>


            <p>

                From sourcing and export to technology, contracting
                and business development, Pharaohs Integrated Business
                connects requirements with practical solutions from Egypt
                and beyond.

            </p>

        </div>

    </div>

</section>



<!-- =========================================================
     SERVICES INTRO
     ========================================================= -->

<section class="services-intro section-padding">

    <div class="container">


        <div class="section-heading">

            <div>

                <span class="section-label">
                    WHAT WE DO
                </span>


                <h2>

                    One Company.

                    <span>
                        Multiple Solutions.
                    </span>

                </h2>

            </div>


            <p>

                Our multi-activity approach allows us to work across
                different sectors and business requirements while keeping
                one focus: understanding what you need and finding a
                practical way to move it forward.

            </p>

        </div>



        <!-- =====================================================
             DYNAMIC SERVICES GRID
             ===================================================== -->

        <?php if (!empty($services)): ?>

            <div class="services-grid">


                <?php foreach ($services as $index => $service): ?>


                    <?php

                    $service_number = str_pad(
                        $index + 1,
                        2,
                        '0',
                        STR_PAD_LEFT
                    );

                    $service_label = get_service_label(
                        $service['title']
                    );

                    $service_cta = get_service_cta(
                        $service['title']
                    );

                    $service_title = htmlspecialchars(
                        $service['title']
                    );

                    $service_description = htmlspecialchars(
                        !empty($service['short_description'])
                            ? $service['short_description']
                            : $service['description']
                    );

                    $service_slug = urlencode(
                        $service['slug']
                    );

                    ?>


                    <!-- =================================================
                         SERVICE CARD
                         ================================================= -->

                    <article class="service-card">


                        <div class="service-card-top">

                            <span class="service-number">
                                <?= $service_number ?>
                            </span>


                            <span class="service-label">
                                <?= htmlspecialchars($service_label) ?>
                            </span>

                        </div>



                        <h3>
                            <?= $service_title ?>
                        </h3>



                        <p>

                            <?= $service_description ?>

                        </p>



                        <ul>

                            <li>
                                Practical business solutions
                            </li>

                            <li>
                                Supplier & partner coordination
                            </li>

                            <li>
                                Commercial follow-up
                            </li>

                            <li>
                                Requirement-based support
                            </li>

                        </ul>



                        <a
                            href="<?= SITE_URL ?>pages/service-details.php?slug=<?= $service_slug ?>"
                            class="service-link"
                        >

                            <?= htmlspecialchars($service_cta) ?>

                            <span>
                                →
                            </span>

                        </a>


                    </article>


                <?php endforeach; ?>


            </div>


        <?php else: ?>


            <!-- =================================================
                 EMPTY STATE
                 ================================================= -->

            <div class="services-empty">

                <h3>
                    Services Coming Soon
                </h3>

                <p>
                    Our services are currently being updated.
                    Please contact us for more information.
                </p>

                <a
                    href="<?= SITE_URL ?>pages/contact.php"
                    class="btn btn-primary"
                >
                    Contact Us
                </a>

            </div>


        <?php endif; ?>


    </div>

</section>



<!-- =========================================================
     HOW WE SUPPORT
     ========================================================= -->

<section class="services-support section-padding">

    <div class="container">


        <div class="section-heading">

            <div>

                <span class="section-label">
                    OUR APPROACH
                </span>


                <h2>

                    From Requirement

                    <span>
                        To Execution
                    </span>

                </h2>

            </div>


            <p>

                Whatever the service, we follow a practical process
                focused on understanding the requirement, identifying
                suitable options and coordinating the next steps.

            </p>

        </div>



        <div class="support-grid">


            <div class="support-item">

                <span>
                    01
                </span>

                <div>

                    <h3>
                        Understand
                    </h3>

                    <p>

                        We first understand your product, project,
                        service or business requirement.

                    </p>

                </div>

            </div>



            <div class="support-item">

                <span>
                    02
                </span>

                <div>

                    <h3>
                        Identify
                    </h3>

                    <p>

                        We explore suitable suppliers, products,
                        partners and available solutions.

                    </p>

                </div>

            </div>



            <div class="support-item">

                <span>
                    03
                </span>

                <div>

                    <h3>
                        Coordinate
                    </h3>

                    <p>

                        We coordinate communication, specifications,
                        pricing and other required details.

                    </p>

                </div>

            </div>



            <div class="support-item">

                <span>
                    04
                </span>

                <div>

                    <h3>
                        Move Forward
                    </h3>

                    <p>

                        Once the requirements are aligned, we support
                        the next stage of the business process.

                    </p>

                </div>

            </div>


        </div>

    </div>

</section>



<!-- =========================================================
     SERVICES CTA
     ========================================================= -->

<section class="services-cta">

    <div class="container">

        <div class="services-cta-content">

            <span>
                HAVE A BUSINESS REQUIREMENT?
            </span>


            <h2>

                Tell Us What

                <strong>
                    You Need.
                </strong>

            </h2>


            <p>

                Whether you need a product from Egypt, a supplier,
                an export solution, a technology service or a new
                business opportunity, we are ready to explore it with you.

            </p>


            <div class="services-cta-actions">

                <a
                    href="<?= SITE_URL ?>request-quote.php"
                    class="btn btn-primary"
                >
                    Request a Quote
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



<?php

require_once __DIR__ . "/../includes/footer.php";

?>

