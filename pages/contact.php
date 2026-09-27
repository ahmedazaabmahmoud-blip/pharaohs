
<?php

$page_title = "Contact Us | Pharaohs Integrated Business";

$page_description = "Contact Pharaohs Integrated Business for export, sourcing, trading, contracting, IT solutions, investment opportunities and business partnerships.";

$page_keywords = "contact Pharaohs Integrated Business, Egypt export company, sourcing Egypt, trading Egypt, investment Egypt, business partnerships Egypt";


require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/functions.php";

$current_css = "contact.css";


/* =========================================================
   HELPER
   Get setting value with fallback for empty values
   ========================================================= */

function contact_setting($key, $fallback = "")
{
    $value = get_setting($key, "");

    return !empty(trim((string) $value))
        ? $value
        : $fallback;
}


/* =========================================================
   CONTACT FORM
   ========================================================= */

$success_message = "";
$error_message   = "";

$name    = "";
$email   = "";
$phone   = "";
$subject = "";
$message = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");


    /* =====================================================
       VALIDATION
       ===================================================== */

    if (
        empty($name) ||
        empty($email) ||
        empty($subject) ||
        empty($message)
    ) {

        $error_message = "Please fill in all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error_message = "Please enter a valid email address.";

    } else {


        /* =================================================
           INSERT CONTACT MESSAGE
           ================================================= */

        $stmt = $conn->prepare("
            INSERT INTO contacts
            (
                name,
                email,
                phone,
                subject,
                message,
                status
            )
            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?,
                'new'
            )
        ");


        if ($stmt) {

            $stmt->bind_param(
                "sssss",
                $name,
                $email,
                $phone,
                $subject,
                $message
            );


            if ($stmt->execute()) {

                $success_message =
                    "Thank you for contacting us. We have received your message and will get back to you soon.";

                $name    = "";
                $email   = "";
                $phone   = "";
                $subject = "";
                $message = "";

            } else {

                $error_message =
                    "Something went wrong while sending your message. Please try again.";

            }


            $stmt->close();

        } else {

            $error_message =
                "Unable to process your request at the moment. Please try again.";

        }
    }
}



/* =========================================================
   COMPANY CONTACT SETTINGS
   ========================================================= */

$company_email = contact_setting(
    "email",
    "info@pharaohs.com"
);

$company_phone = contact_setting(
    "phone",
    "+20 100 000 0000"
);

$company_whatsapp = contact_setting(
    "whatsapp",
    ""
);

$company_address = contact_setting(
    "address",
    "Egypt"
);

$google_maps = contact_setting(
    "google_maps",
    ""
);



/* =========================================================
   HEADER
   ========================================================= */

require_once __DIR__ . "/../includes/header.php";

?>


<!-- =========================================================
     CONTACT HERO
     ========================================================= -->

<section class="contact-page-hero">

    <div class="contact-page-hero-background">

        <div class="contact-page-hero-overlay"></div>

    </div>


    <div class="container contact-page-hero-container">

        <div class="contact-page-hero-content">

            <span class="contact-page-hero-label">
                CONTACT US
            </span>


            <h1>

                Let's Start

                <span>
                    A Conversation.
                </span>

            </h1>


            <p>

                Whether you are looking for a product from Egypt,
                an export partner, a supplier, a business solution
                or a new opportunity, we are ready to hear from you.

            </p>

        </div>

    </div>

</section>



<!-- =========================================================
     CONTACT MAIN
     ========================================================= -->

<section class="contact-main section-padding">

    <div class="container">

        <div class="contact-main-grid">


            <!-- =================================================
                 CONTACT INFORMATION
                 ================================================= -->

            <div class="contact-info">

                <span class="section-label">
                    GET IN TOUCH
                </span>


                <h2>

                    Tell Us

                    <span>
                        What You Need.
                    </span>

                </h2>


                <p class="contact-info-intro">

                    Share your requirement, business idea, product
                    request or partnership opportunity with us.
                    Our team will review your request and get back
                    to you with the appropriate next steps.

                </p>



                <!-- =================================================
                     FORM MESSAGE
                     ================================================= -->

                <?php if (!empty($success_message)): ?>

                    <div class="contact-form-message contact-form-success">

                        <?= htmlspecialchars(
                            $success_message,
                            ENT_QUOTES,
                            "UTF-8"
                        ); ?>

                    </div>

                <?php endif; ?>


                <?php if (!empty($error_message)): ?>

                    <div class="contact-form-message contact-form-error">

                        <?= htmlspecialchars(
                            $error_message,
                            ENT_QUOTES,
                            "UTF-8"
                        ); ?>

                    </div>

                <?php endif; ?>



                <!-- =================================================
                     EMAIL
                     ================================================= -->

                <div class="contact-info-item">

                    <div class="contact-info-icon">
                        ✉
                    </div>


                    <div>

                        <span>
                            EMAIL
                        </span>

                        <a
                            href="mailto:<?= htmlspecialchars(
                                $company_email,
                                ENT_QUOTES,
                                "UTF-8"
                            ); ?>"
                        >

                            <?= htmlspecialchars(
                                $company_email,
                                ENT_QUOTES,
                                "UTF-8"
                            ); ?>

                        </a>

                    </div>

                </div>



                <!-- =================================================
                     PHONE
                     ================================================= -->

                <div class="contact-info-item">

                    <div class="contact-info-icon">
                        ☎
                    </div>


                    <div>

                        <span>
                            PHONE
                        </span>

                        <a
                            href="tel:<?= htmlspecialchars(
                                preg_replace(
                                    "/[^0-9+]/",
                                    "",
                                    $company_phone
                                ),
                                ENT_QUOTES,
                                "UTF-8"
                            ); ?>"
                        >

                            <?= htmlspecialchars(
                                $company_phone,
                                ENT_QUOTES,
                                "UTF-8"
                            ); ?>

                        </a>

                    </div>

                </div>



                <!-- =================================================
                     WHATSAPP
                     ================================================= -->

                <?php if (!empty($company_whatsapp)): ?>

                    <div class="contact-info-item">

                        <div class="contact-info-icon">
                            ◉
                        </div>


                        <div>

                            <span>
                                WHATSAPP
                            </span>

                            <a
                                href="https://wa.me/<?= htmlspecialchars(
                                    preg_replace(
                                        "/[^0-9]/",
                                        "",
                                        $company_whatsapp
                                    ),
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                            >

                                <?= htmlspecialchars(
                                    $company_whatsapp,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>

                            </a>

                        </div>

                    </div>

                <?php endif; ?>



                <!-- =================================================
                     LOCATION
                     ================================================= -->

                <div class="contact-info-item">

                    <div class="contact-info-icon">
                        ⌖
                    </div>


                    <div>

                        <span>
                            LOCATION
                        </span>


                        <p>

                            <?= htmlspecialchars(
                                $company_address,
                                ENT_QUOTES,
                                "UTF-8"
                            ); ?>

                        </p>

                    </div>

                </div>



                <!-- =================================================
                     GOOGLE MAPS
                     ================================================= -->

                <?php if (!empty($google_maps)): ?>

                    <div class="contact-info-item">

                        <div class="contact-info-icon">
                            ◈
                        </div>


                        <div>

                            <span>
                                MAP
                            </span>


                            <a
                                href="<?= htmlspecialchars(
                                    $google_maps,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                View Location
                            </a>

                        </div>

                    </div>

                <?php endif; ?>



                <!-- =================================================
                     BUSINESS
                     ================================================= -->

                <div class="contact-info-item">

                    <div class="contact-info-icon">
                        ◆
                    </div>


                    <div>

                        <span>
                            BUSINESS
                        </span>


                        <p>
                            Export • Trading • Sourcing •
                            Contracting • IT Solutions
                        </p>

                    </div>

                </div>


            </div>



            <!-- =================================================
                 CONTACT FORM
                 ================================================= -->

            <div class="contact-form-wrapper">

                <div class="contact-form-header">

                    <span>
                        SEND A MESSAGE
                    </span>


                    <h3>
                        How Can We Help?
                    </h3>


                    <p>
                        Fill in the form below and tell us about
                        your requirement.
                    </p>

                </div>



                <form
                    action=""
                    method="POST"
                    class="contact-form"
                >


                    <!-- NAME + EMAIL -->

                    <div class="contact-form-row">

                        <div class="contact-form-group">

                            <label for="name">
                                Full Name
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                placeholder="Your full name"
                                value="<?= htmlspecialchars(
                                    $name,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                                required
                            >

                        </div>



                        <div class="contact-form-group">

                            <label for="email">
                                Email Address
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="Your email address"
                                value="<?= htmlspecialchars(
                                    $email,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                                required
                            >

                        </div>

                    </div>



                    <!-- PHONE + SUBJECT -->

                    <div class="contact-form-row">

                        <div class="contact-form-group">

                            <label for="phone">
                                Phone / WhatsApp
                            </label>

                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                placeholder="Your phone number"
                                value="<?= htmlspecialchars(
                                    $phone,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                            >

                        </div>



                        <div class="contact-form-group">

                            <label for="subject">
                                Subject
                            </label>

                            <input
                                type="text"
                                id="subject"
                                name="subject"
                                placeholder="How can we help?"
                                value="<?= htmlspecialchars(
                                    $subject,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                                required
                            >

                        </div>

                    </div>



                    <!-- MESSAGE -->

                    <div class="contact-form-group">

                        <label for="message">
                            Message
                        </label>

                        <textarea
                            id="message"
                            name="message"
                            rows="6"
                            placeholder="Tell us about your requirement..."
                            required
                        ><?= htmlspecialchars(
                            $message,
                            ENT_QUOTES,
                            "UTF-8"
                        ); ?></textarea>

                    </div>



                    <!-- SUBMIT -->

                    <button
                        type="submit"
                        class="contact-form-submit"
                    >

                        Send Message

                        <span>
                            →
                        </span>

                    </button>


                </form>

            </div>


        </div>

    </div>

</section>



<!-- =========================================================
     CONTACT SUPPORT
     ========================================================= -->

<section class="contact-support section-padding">

    <div class="container">


        <div class="section-heading">

            <div>

                <span class="section-label">
                    BUSINESS INQUIRIES
                </span>


                <h2>

                    One Requirement.

                    <span>
                        Multiple Possibilities.
                    </span>

                </h2>

            </div>


            <p>

                You do not need to know exactly which service you need.
                Simply tell us what you are looking for and we will
                explore the most suitable way to support your requirement.

            </p>

        </div>



        <div class="contact-support-grid">


            <!-- ITEM 01 -->

            <div class="contact-support-item">

                <span>
                    01
                </span>

                <h3>
                    Need a Product?
                </h3>

                <p>
                    Tell us what you need and we can explore sourcing
                    options from Egypt.
                </p>

            </div>



            <!-- ITEM 02 -->

            <div class="contact-support-item">

                <span>
                    02
                </span>

                <h3>
                    Want to Export?
                </h3>

                <p>
                    We can help explore products, suppliers and export
                    opportunities from Egypt.
                </p>

            </div>



            <!-- ITEM 03 -->

            <div class="contact-support-item">

                <span>
                    03
                </span>

                <h3>
                    Have a Project?
                </h3>

                <p>
                    Share your project requirements and let us explore
                    the appropriate business solution.
                </p>

            </div>



            <!-- ITEM 04 -->

            <div class="contact-support-item">

                <span>
                    04
                </span>

                <h3>
                    Have an Opportunity?
                </h3>

                <p>
                    We are open to exploring new business ideas,
                    partnerships and investment opportunities.
                </p>

            </div>


        </div>

    </div>

</section>



<!-- =========================================================
     CONTACT CTA
     ========================================================= -->

<section class="contact-cta">

    <div class="container">

        <div class="contact-cta-content">

            <span>
                READY TO TALK?
            </span>


            <h2>

                Your Next Opportunity

                <strong>
                    Could Start Here.
                </strong>

            </h2>


            <p>

                Contact Pharaohs Integrated Business and let's
                explore how we can work together.

            </p>


            <div class="contact-cta-actions">

                <a
                    href="mailto:<?= htmlspecialchars(
                        $company_email,
                        ENT_QUOTES,
                        "UTF-8"
                    ); ?>"
                    class="btn btn-primary"
                >
                    Email Us
                </a>


                <a
                    href="<?= SITE_URL ?>request-quote.php"
                    class="btn btn-outline"
                >
                    Request a Quote
                </a>

            </div>

        </div>

    </div>

</section>



<!-- =========================================================
     FOOTER
     ========================================================= -->

<?php require_once __DIR__ . "/../includes/footer.php"; ?>

