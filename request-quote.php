
<?php

$page_title = "Request a Quote | Pharaohs Integrated Business";

$page_description = "Send your sourcing, export, procurement, trading, contracting or technology requirement to Pharaohs Integrated Business.";

$page_keywords = "request quote Egypt, export request Egypt, sourcing request, procurement request, Egypt supplier request";


/* =========================================================
   LOAD CONFIGURATION
   ========================================================= */

require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/includes/functions.php";


/* =========================================================
   PAGE CSS
   ========================================================= */

$current_css = "request-quote.css";


/* =========================================================
   FORM VARIABLES
   ========================================================= */

$form_success = "";
$form_error   = "";

$full_name        = "";
$company_name     = "";
$country          = "";
$email            = "";
$phone            = "";
$service          = "";
$product          = "";
$quantity         = "";
$destination      = "";
$specifications   = "";
$additional_notes = "";


/* =========================================================
   HANDLE FORM SUBMISSION
   ========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    /* =====================================================
       GET FORM DATA
       ===================================================== */

    $full_name        = trim($_POST["full_name"] ?? "");
    $company_name     = trim($_POST["company_name"] ?? "");
    $country          = trim($_POST["country"] ?? "");
    $email            = trim($_POST["email"] ?? "");
    $phone            = trim($_POST["phone"] ?? "");

    $service          = trim($_POST["service"] ?? "");
    $product          = trim($_POST["product"] ?? "");
    $quantity         = trim($_POST["quantity"] ?? "");
    $destination      = trim($_POST["destination"] ?? "");

    $specifications   = trim($_POST["specifications"] ?? "");
    $additional_notes = trim($_POST["additional_notes"] ?? "");


    /* =====================================================
       VALIDATION
       ===================================================== */

    if (
        $full_name === "" ||
        $country === "" ||
        $email === "" ||
        $service === "" ||
        $product === "" ||
        $specifications === ""
    ) {

        $form_error = "Please complete all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $form_error = "Please enter a valid email address.";

    } else {


        /* =================================================
           DATABASE CONNECTION
           ================================================= */

        $database = null;


        if (isset($conn) && $conn instanceof mysqli) {

            $database = $conn;

        } elseif (isset($mysqli) && $mysqli instanceof mysqli) {

            $database = $mysqli;

        } elseif (isset($db) && $db instanceof mysqli) {

            $database = $db;

        }


        /* =================================================
           CHECK DATABASE CONNECTION
           ================================================= */

        if (!$database) {

            $form_error = "Database connection is not available.";

        } else {


            /* =============================================
               INSERT REQUEST
               ============================================= */

            $sql = "
                INSERT INTO quotes
                (
                    full_name,
                    company_name,
                    country,
                    email,
                    phone,
                    service,
                    product_service,
                    quantity,
                    destination,
                    requirements,
                    additional_notes,
                    status
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    'new'
                )
            ";


            $stmt = $database->prepare($sql);


            /* =============================================
               PREPARE ERROR
               ============================================= */

            if (!$stmt) {

                $form_error =
                    "Unable to prepare your request. Please try again.";

            } else {


                /* =========================================
                   BIND PARAMETERS
                   ========================================= */

                $stmt->bind_param(
                    "sssssssssss",
                    $full_name,
                    $company_name,
                    $country,
                    $email,
                    $phone,
                    $service,
                    $product,
                    $quantity,
                    $destination,
                    $specifications,
                    $additional_notes
                );


                /* =========================================
                   EXECUTE
                   ========================================= */

                if ($stmt->execute()) {

                    $form_success =
                        "Thank you. Your request has been submitted successfully. Our team will review your requirement and get back to you.";


                    /* =====================================
                       CLEAR FORM
                       ===================================== */

                    $full_name        = "";
                    $company_name     = "";
                    $country          = "";
                    $email            = "";
                    $phone            = "";
                    $service          = "";
                    $product          = "";
                    $quantity         = "";
                    $destination      = "";
                    $specifications   = "";
                    $additional_notes = "";

                } else {

                    $form_error =
                        "Something went wrong while submitting your request. Please try again.";

                }


                $stmt->close();

            }

        }

    }

}


/* =========================================================
   HEADER
   ========================================================= */

require_once __DIR__ . "/includes/header.php";

?>


<!-- =========================================================
     REQUEST QUOTE HERO
     ========================================================= -->

<section class="quote-page-hero">

    <div class="quote-page-hero-background">

        <div class="quote-page-hero-overlay"></div>

    </div>


    <div class="container quote-page-hero-container">

        <div class="quote-page-hero-content">

            <span class="quote-page-hero-label">
                REQUEST A QUOTE
            </span>


            <h1>

                Tell Us What

                <span>
                    You Need.
                </span>

            </h1>


            <p>
                Whether you are looking for a product from Egypt,
                a supplier, an export solution, a business service
                or a specific requirement, send us the details and
                we will explore the right options for you.
            </p>

        </div>

    </div>

</section>



<!-- =========================================================
     QUOTE FORM
     ========================================================= -->

<section class="quote-form-section section-padding">

    <div class="container">


        <!-- =================================================
             SUCCESS / ERROR MESSAGE
             ================================================= -->

        <?php if (!empty($form_success)): ?>

            <div class="quote-form-message quote-form-success">

                <?= htmlspecialchars(
                    $form_success,
                    ENT_QUOTES,
                    "UTF-8"
                ); ?>

            </div>

        <?php endif; ?>


        <?php if (!empty($form_error)): ?>

            <div class="quote-form-message quote-form-error">

                <?= htmlspecialchars(
                    $form_error,
                    ENT_QUOTES,
                    "UTF-8"
                ); ?>

            </div>

        <?php endif; ?>


        <div class="quote-layout">


            <!-- =================================================
                 LEFT SIDE
                 ================================================= -->

            <div class="quote-info">

                <span class="section-label">
                    LET'S WORK TOGETHER
                </span>


                <h2>

                    Start Your

                    <span>
                        Requirement.
                    </span>

                </h2>


                <p>
                    Give us as much information as possible about what
                    you are looking for. The more details you provide,
                    the easier it is for us to understand your
                    requirement and explore suitable options.
                </p>



                <div class="quote-info-list">


                    <div class="quote-info-item">

                        <span class="quote-info-number">
                            01
                        </span>


                        <div>

                            <h3>
                                Tell Us What You Need
                            </h3>

                            <p>
                                Describe the product, service or
                                business requirement.
                            </p>

                        </div>

                    </div>



                    <div class="quote-info-item">

                        <span class="quote-info-number">
                            02
                        </span>


                        <div>

                            <h3>
                                We Explore the Options
                            </h3>

                            <p>
                                We review the requirement and explore
                                suitable suppliers or solutions.
                            </p>

                        </div>

                    </div>



                    <div class="quote-info-item">

                        <span class="quote-info-number">
                            03
                        </span>


                        <div>

                            <h3>
                                We Get Back to You
                            </h3>

                            <p>
                                We coordinate the relevant details
                                and continue with the next steps.
                            </p>

                        </div>

                    </div>


                </div>



                <div class="quote-contact-box">

                    <span>
                        NEED TO TALK DIRECTLY?
                    </span>


                    <p>
                        You can also contact our team directly
                        for your requirement.
                    </p>


                    <a href="<?= SITE_URL ?>pages/contact.php">

                        Contact Us

                        <strong>
                            →
                        </strong>

                    </a>

                </div>

            </div>



            <!-- =================================================
                 RIGHT SIDE - FORM
                 ================================================= -->

            <div class="quote-form-card">


                <div class="quote-form-header">

                    <span>
                        REQUIREMENT FORM
                    </span>


                    <h2>
                        Request a Quote
                    </h2>


                    <p>
                        Please fill in the details below.
                    </p>

                </div>



                <form
                    action=""
                    method="POST"
                    class="quote-form"
                >


                    <!-- =================================================
                         PERSONAL INFORMATION
                         ================================================= -->

                    <div class="quote-form-group">

                        <label for="full_name">

                            Full Name

                            <span>
                                *
                            </span>

                        </label>


                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            placeholder="Your full name"
                            value="<?= htmlspecialchars(
                                $full_name,
                                ENT_QUOTES,
                                "UTF-8"
                            ); ?>"
                            required
                        >

                    </div>



                    <div class="quote-form-row">


                        <div class="quote-form-group">

                            <label for="company_name">
                                Company Name
                            </label>


                            <input
                                type="text"
                                id="company_name"
                                name="company_name"
                                placeholder="Company name"
                                value="<?= htmlspecialchars(
                                    $company_name,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                            >

                        </div>



                        <div class="quote-form-group">

                            <label for="country">

                                Country

                                <span>
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                id="country"
                                name="country"
                                placeholder="Your country"
                                value="<?= htmlspecialchars(
                                    $country,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                                required
                            >

                        </div>


                    </div>



                    <div class="quote-form-row">


                        <div class="quote-form-group">

                            <label for="email">

                                Email Address

                                <span>
                                    *
                                </span>

                            </label>


                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="name@company.com"
                                value="<?= htmlspecialchars(
                                    $email,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                                required
                            >

                        </div>



                        <div class="quote-form-group">

                            <label for="phone">
                                Phone / WhatsApp
                            </label>


                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                placeholder="+20..."
                                value="<?= htmlspecialchars(
                                    $phone,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                            >

                        </div>


                    </div>



                    <!-- =================================================
                         REQUIREMENT
                         ================================================= -->

                    <div class="quote-form-group">

                        <label for="service">

                            What Do You Need?

                            <span>
                                *
                            </span>

                        </label>


                        <select
                            id="service"
                            name="service"
                            required
                        >

                            <option value="">
                                Select a service
                            </option>


                            <option
                                value="Export From Egypt"
                                <?= ($service === "Export From Egypt") ? "selected" : "" ?>
                            >
                                Export From Egypt
                            </option>


                            <option
                                value="Sourcing & Procurement"
                                <?= ($service === "Sourcing & Procurement") ? "selected" : "" ?>
                            >
                                Sourcing & Procurement
                            </option>


                            <option
                                value="General Trading"
                                <?= ($service === "General Trading") ? "selected" : "" ?>
                            >
                                General Trading
                            </option>


                            <option
                                value="Contracting"
                                <?= ($service === "Contracting") ? "selected" : "" ?>
                            >
                                Contracting
                            </option>


                            <option
                                value="IT Solutions"
                                <?= ($service === "IT Solutions") ? "selected" : "" ?>
                            >
                                IT Solutions
                            </option>


                            <option
                                value="Business Development"
                                <?= ($service === "Business Development") ? "selected" : "" ?>
                            >
                                Business Development
                            </option>


                            <option
                                value="Other"
                                <?= ($service === "Other") ? "selected" : "" ?>
                            >
                                Other Requirement
                            </option>

                        </select>

                    </div>



                    <div class="quote-form-group">

                        <label for="product">

                            Product / Requirement

                            <span>
                                *
                            </span>

                        </label>


                        <input
                            type="text"
                            id="product"
                            name="product"
                            placeholder="What product or service are you looking for?"
                            value="<?= htmlspecialchars(
                                $product,
                                ENT_QUOTES,
                                "UTF-8"
                            ); ?>"
                            required
                        >

                    </div>



                    <div class="quote-form-row">


                        <div class="quote-form-group">

                            <label for="quantity">
                                Quantity
                            </label>


                            <input
                                type="text"
                                id="quantity"
                                name="quantity"
                                placeholder="Required quantity"
                                value="<?= htmlspecialchars(
                                    $quantity,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                            >

                        </div>



                        <div class="quote-form-group">

                            <label for="destination">
                                Destination Country
                            </label>


                            <input
                                type="text"
                                id="destination"
                                name="destination"
                                placeholder="Where should it be shipped?"
                                value="<?= htmlspecialchars(
                                    $destination,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                            >

                        </div>


                    </div>



                    <div class="quote-form-group">

                        <label for="specifications">

                            Specifications / Details

                            <span>
                                *
                            </span>

                        </label>


                        <textarea
                            id="specifications"
                            name="specifications"
                            rows="6"
                            placeholder="Please describe the product specifications, quality, packaging, required quantity or any other important details..."
                            required
                        ><?= htmlspecialchars(
                            $specifications,
                            ENT_QUOTES,
                            "UTF-8"
                        ); ?></textarea>

                    </div>



                    <div class="quote-form-group">

                        <label for="additional_notes">
                            Additional Notes
                        </label>


                        <textarea
                            id="additional_notes"
                            name="additional_notes"
                            rows="4"
                            placeholder="Anything else we should know?"
                        ><?= htmlspecialchars(
                            $additional_notes,
                            ENT_QUOTES,
                            "UTF-8"
                        ); ?></textarea>

                    </div>



                    <button
                        type="submit"
                        class="btn btn-primary quote-submit-btn"
                    >

                        Submit Request

                        <span>
                            →
                        </span>

                    </button>



                    <p class="quote-form-note">

                        By submitting this form, you are sending your
                        business requirement to Pharaohs Integrated Business.

                    </p>


                </form>

            </div>

        </div>

    </div>

</section>



<!-- =========================================================
     BOTTOM CTA
     ========================================================= -->

<section class="quote-bottom-cta">

    <div class="container">

        <div class="quote-bottom-content">

            <span>
                FROM EGYPT. BEYOND BOUNDARIES.
            </span>


            <h2>

                Your Requirement

                <strong>
                    Starts Here.
                </strong>

            </h2>


            <p>
                Let's explore what Egypt can offer your business.
            </p>

        </div>

    </div>

</section>



<?php require_once __DIR__ . "/includes/footer.php"; ?>
