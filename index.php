<?php

$page_title = "Pharaohs Integrated Business | Export From Egypt & Business Solutions";

$page_description = "Pharaohs Integrated Business is an Egyptian multi-activity company providing international export, sourcing, procurement, trading, contracting, IT solutions and business services from Egypt to global markets.";

$page_keywords = "Egypt export company, export from Egypt, Egyptian products, sourcing from Egypt, international trade Egypt, procurement Egypt, trading company Egypt, contracting Egypt, IT solutions Egypt";

require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/includes/functions.php";

$current_css = "home.css";

require_once __DIR__ . "/includes/header.php";

?>

<!-- =====================================================
     HERO SECTION
     ===================================================== -->

<section class="hero">
 

<div class="hero-background">
    <div class="hero-overlay"></div>
</div>

<div class="container hero-container">

    <div class="hero-content">

        <span class="hero-label">
            EGYPTIAN BUSINESS • GLOBAL REACH
        </span>

        <h1>
            From Egypt.
            <span>Beyond Boundaries.</span>
        </h1>

        <p class="hero-description">
            Pharaohs Integrated Business connects global
            opportunities with Egyptian products, suppliers
            and business solutions through sourcing, trading,
            procurement and international export.
        </p>

        <div class="hero-actions">

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
                Explore Export Services
            </a>

        </div>

        <div class="hero-trust">

            <div class="hero-trust-item">
                <strong>Global</strong>
                <span>Export Reach</span>
            </div>

            <div class="hero-trust-item">
                <strong>Multi-Activity</strong>
                <span>Business Solutions</span>
            </div>

            <div class="hero-trust-item">
                <strong>Egypt</strong>
                <span>Sourcing Hub</span>
            </div>

        </div>

    </div>

</div>
```

</section>

<!-- =====================================================
     WHO WE ARE
     ===================================================== -->

<section class="about-preview section-padding">

```
<div class="container">

    <div class="about-grid">

        <div class="about-content">

            <span class="section-label">
                WHO WE ARE
            </span>

            <h2>
                Connecting Egypt's
                <span>Business Potential</span>
                With the World
            </h2>

            <p class="about-lead">
                Pharaohs Integrated Business is an Egyptian multi-activity company
                focused on sourcing, trading, procurement, international export
                and business solutions.
            </p>

            <p>
                We help international clients access products, suppliers and
                opportunities from Egypt by handling sourcing, communication,
                procurement, follow-up and export coordination.
            </p>

            <p>
                Our approach is simple: when there is a genuine business
                opportunity, we work to find the right solution and connect it
                to the right market.
            </p>

            <a
                href="<?= SITE_URL ?>pages/about.php"
                class="text-link"
            >
                Discover More About Us
                <span>→</span>
            </a>

        </div>


        <div class="about-visual">

            <div class="about-image-main">

                <img
                    src="<?= SITE_URL ?>assets/images/about/about-main.webp"
                    alt="Pharaohs Integrated Business - Egypt"
                    loading="lazy"
                    decoding="async"
                >

            </div>

            <div class="about-card">
                <strong>From Egypt</strong>
                <span>To Global Markets</span>
            </div>

        </div>

    </div>

</div>
```

</section>

<!-- =====================================================
     WHAT WE DO
     ===================================================== -->

<section class="services-preview section-padding">

```
<div class="container">

    <div class="section-heading">

        <div>

            <span class="section-label">
                WHAT WE DO
            </span>

            <h2>
                Business Solutions
                <span>Without Borders</span>
            </h2>

        </div>

        <p>
            From sourcing products in Egypt to connecting businesses with
            international markets, we provide flexible solutions across
            multiple business sectors.
        </p>

    </div>


    <div class="services-grid">

        <!-- 01 Export -->
        <a
            href="<?= SITE_URL ?>pages/export.php"
            class="service-card"
        >

            <div class="service-number">01</div>

            <div class="service-icon">
                ↗
            </div>

            <h3>
                Export From Egypt
            </h3>

            <p>
                We help international buyers source and export products
                from Egypt with professional coordination and follow-up.
            </p>

            <span class="service-link">
                Explore Service →
            </span>

        </a>


        <!-- 02 Sourcing -->
        <a
            href="<?= SITE_URL ?>pages/service-details.php?slug=sourcing-procurement"
            class="service-card"
        >

            <div class="service-number">02</div>

            <div class="service-icon">
                ◇
            </div>

            <h3>
                Sourcing & Procurement
            </h3>

            <p>
                Finding the right Egyptian suppliers, products and
                procurement solutions based on your requirements.
            </p>

            <span class="service-link">
                Explore Service →
            </span>

        </a>


        <!-- 03 Trading -->
        <a
            href="<?= SITE_URL ?>pages/service-details.php?slug=general-trading"
            class="service-card"
        >

            <div class="service-number">03</div>

            <div class="service-icon">
                ⇄
            </div>

            <h3>
                General Trading
            </h3>

            <p>
                Trading and supplying products across different sectors
                based on market needs and business opportunities.
            </p>

            <span class="service-link">
                Explore Service →
            </span>

        </a>


        <!-- 04 Contracting -->
        <a
            href="<?= SITE_URL ?>pages/service-details.php?slug=contracting"
            class="service-card"
        >

            <div class="service-number">04</div>

            <div class="service-icon">
                ▦
            </div>

            <h3>
                Contracting
            </h3>

            <p>
                Supporting construction and contracting opportunities
                through reliable coordination and business partnerships.
            </p>

            <span class="service-link">
                Explore Service →
            </span>

        </a>


        <!-- 05 IT -->
        <a
            href="<?= SITE_URL ?>pages/service-details.php?slug=it-solutions"
            class="service-card"
        >

            <div class="service-number">05</div>

            <div class="service-icon">
                &lt;/&gt;
            </div>

            <h3>
                IT Solutions
            </h3>

            <p>
                Digital and technology solutions designed to support
                businesses, operations and modern commercial needs.
            </p>

            <span class="service-link">
                Explore Service →
            </span>

        </a>


        <!-- 06 Business Development -->
        <a
            href="<?= SITE_URL ?>pages/service-details.php?slug=business-development"
            class="service-card"
        >

            <div class="service-number">06</div>

            <div class="service-icon">
                +
            </div>

            <h3>
                Business Development
            </h3>

            <p>
                Exploring business opportunities, partnerships and
                investment possibilities across different markets.
            </p>

            <span class="service-link">
                Explore Service →
            </span>

        </a>

    </div>

</div>
```

</section>

<!-- =====================================================
     EXPORT FROM EGYPT
     ===================================================== -->

<section class="export-preview section-padding">

```
<div class="container">

    <div class="export-grid">

        <div class="export-content">

            <span class="section-label">
                EXPORT FROM EGYPT
            </span>

            <h2>
                Need Something
                <span>From Egypt?</span>
            </h2>

            <p class="export-lead">
                Tell us what you need, and we will work to source it
                from Egypt and coordinate the process from supplier to
                international shipment.
            </p>

            <p>
                From agricultural products and food commodities to
                commercial products, industrial supplies and other
                sourcing requirements, we connect international buyers
                with suitable Egyptian suppliers.
            </p>


            <div class="export-features">

                <div class="export-feature">

                    <div class="export-feature-icon">
                        01
                    </div>

                    <div>

                        <h4>
                            Tell Us What You Need
                        </h4>

                        <p>
                            Share your product or sourcing requirements
                            with our team.
                        </p>

                    </div>

                </div>


                <div class="export-feature">

                    <div class="export-feature-icon">
                        02
                    </div>

                    <div>

                        <h4>
                            We Source & Coordinate
                        </h4>

                        <p>
                            We search for suitable suppliers and coordinate
                            the required details.
                        </p>

                    </div>

                </div>


                <div class="export-feature">

                    <div class="export-feature-icon">
                        03
                    </div>

                    <div>

                        <h4>
                            We Handle the Process
                        </h4>

                        <p>
                            We follow up on procurement, preparation and
                            export coordination.
                        </p>

                    </div>

                </div>

            </div>


            <div class="export-actions">

                <a
                    href="<?= SITE_URL ?>request-quote.php"
                    class="btn btn-primary"
                >
                    Tell Us What You Need
                </a>

                <a
                    href="<?= SITE_URL ?>pages/export.php"
                    class="text-link"
                >
                    Learn More
                    <span>→</span>
                </a>

            </div>

        </div>


        <div class="export-visual">

            <div class="export-image">

                <img
                    src="<?= SITE_URL ?>assets/images/export/export-main.webp"
                    alt="Export From Egypt - Pharaohs Integrated Business"
                    loading="lazy"
                    decoding="async"
                >

            </div>

            <div class="export-badge">
                <strong>FROM EGYPT</strong>
                <span>TO THE WORLD</span>
            </div>

            <div class="export-location">
                <span class="location-dot"></span>
                Egypt
            </div>

        </div>

    </div>

</div>
```

</section>

<!-- =====================================================
     INDUSTRIES & PRODUCTS
     ===================================================== -->

<section class="industries-preview section-padding">

```
<div class="container">

    <div class="section-heading industries-heading">

        <div>

            <span class="section-label">
                INDUSTRIES & PRODUCTS
            </span>

            <h2>
                What Can We
                <span>Source For You?</span>
            </h2>

        </div>

        <p>
            We work across different sectors and product categories,
            connecting international requirements with suitable
            Egyptian sources and suppliers.
        </p>

    </div>


    <div class="industries-grid">

        <!-- 01 Food & Agriculture -->
        <a
            href="<?= SITE_URL ?>pages/industry-details.php?slug=agriculture-food"
            class="industry-card"
        >

            <div class="industry-image">

                <img
                    src="<?= SITE_URL ?>assets/images/industries/food.webp"
                    alt="Food and agricultural products from Egypt"
                    loading="lazy"
                    decoding="async"
                >

            </div>

            <div class="industry-overlay"></div>

            <div class="industry-content">

                <span>01</span>

                <h3>
                    Food & Agriculture
                </h3>

                <p>
                    Agricultural products, dates, fresh and processed
                    food products and more.
                </p>

                <strong>
                    Explore →
                </strong>

            </div>

        </a>


        <!-- 02 Medical Supplies -->
        <a
            href="<?= SITE_URL ?>pages/industry-details.php?slug=medical-supplies"
            class="industry-card"
        >

            <div class="industry-image">

                <img
                    src="<?= SITE_URL ?>assets/images/industries/medical.webp"
                    alt="Medical supplies from Egypt"
                    loading="lazy"
                    decoding="async"
                >

            </div>

            <div class="industry-overlay"></div>

            <div class="industry-content">

                <span>02</span>

                <h3>
                    Medical Supplies
                </h3>

                <p>
                    Medical products and supplies for healthcare
                    businesses and organizations.
                </p>

                <strong>
                    Explore →
                </strong>

            </div>

        </a>


        <!-- 03 Industrial -->
        <a
            href="<?= SITE_URL ?>pages/industry-details.php?slug=industrial-products"
            class="industry-card"
        >

            <div class="industry-image">

                <img
                    src="<?= SITE_URL ?>assets/images/industries/industrial.webp"
                    alt="Industrial products from Egypt"
                    loading="lazy"
                    decoding="async"
                >

            </div>

            <div class="industry-overlay"></div>

            <div class="industry-content">

                <span>03</span>

                <h3>
                    Industrial Products
                </h3>

                <p>
                    Industrial materials, supplies and commercial
                    products based on client requirements.
                </p>

                <strong>
                    Explore →
                </strong>

            </div>

        </a>


        <!-- 04 Construction -->
        <a
            href="<?= SITE_URL ?>pages/industry-details.php?slug=construction-contracting"
            class="industry-card"
        >

            <div class="industry-image">

                <img
                    src="<?= SITE_URL ?>assets/images/industries/construction.webp"
                    alt="Construction and contracting"
                    loading="lazy"
                    decoding="async"
                >

            </div>

            <div class="industry-overlay"></div>

            <div class="industry-content">

                <span>04</span>

                <h3>
                    Construction
                </h3>

                <p>
                    Construction materials, contracting services and
                    project-related requirements.
                </p>

                <strong>
                    Explore →
                </strong>

            </div>

        </a>


        <!-- 05 Technology -->
        <a
            href="<?= SITE_URL ?>pages/industry-details.php?slug=it-technology"
            class="industry-card"
        >

            <div class="industry-image">

                <img
                    src="<?= SITE_URL ?>assets/images/industries/technology.webp"
                    alt="Technology and IT solutions"
                    loading="lazy"
                    decoding="async"
                >

            </div>

            <div class="industry-overlay"></div>

            <div class="industry-content">

                <span>05</span>

                <h3>
                    Technology & IT
                </h3>

                <p>
                    IT products, digital solutions and technology
                    requirements for businesses.
                </p>

                <strong>
                    Explore →
                </strong>

            </div>

        </a>


        <!-- 06 General -->
        <a
            href="<?= SITE_URL ?>pages/industries.php"
            class="industry-card industry-card-general"
        >

            <div class="industry-image">

                <img
                    src="<?= SITE_URL ?>assets/images/industries/general.webp"
                    alt="General products and sourcing from Egypt"
                    loading="lazy"
                    decoding="async"
                >

            </div>

            <div class="industry-overlay"></div>

            <div class="industry-content">

                <span>06</span>

                <h3>
                    General Products
                </h3>

                <p>
                    Have something specific in mind? Tell us what you
                    need and we will explore the possibilities.
                </p>

                <strong>
                    Tell Us What You Need →
                </strong>

            </div>

        </a>

    </div>


    <div class="industries-bottom">

        <p>
            <strong>Looking for something not listed?</strong>
            Our capabilities are not limited to the categories above.
        </p>

        <a
            href="<?= SITE_URL ?>request-quote.php"
            class="btn btn-primary"
        >
            Send Your Requirement
        </a>

    </div>

</div>
```

</section>

<!-- =====================================================
     HOW IT WORKS
     ===================================================== -->

<section class="process-section section-padding">

```
<div class="container">

    <div class="process-heading">

        <div class="process-heading-content">

            <span class="section-label">
                HOW IT WORKS
            </span>

            <h2>
                From Your Request
                <span>To Global Delivery</span>
            </h2>

        </div>

        <p>
            A simple and coordinated process designed to make sourcing
            and exporting from Egypt easier for international buyers.
        </p>

    </div>


    <div class="process-steps">

        <!-- Step 01 -->
        <div class="process-step">

            <div class="process-number">
                01
            </div>

            <div class="process-icon">
                ✦
            </div>

            <h3>
                Tell Us What You Need
            </h3>

            <p>
                Send us your product, quantity, specifications or
                business requirement.
            </p>

        </div>


        <!-- Step 02 -->
        <div class="process-step">

            <div class="process-number">
                02
            </div>

            <div class="process-icon">
                ◇
            </div>

            <h3>
                We Find the Right Source
            </h3>

            <p>
                We search for suitable products and suppliers in Egypt
                based on your requirements.
            </p>

        </div>


        <!-- Step 03 -->
        <div class="process-step">

            <div class="process-number">
                03
            </div>

            <div class="process-icon">
                ✓
            </div>

            <h3>
                We Coordinate the Details
            </h3>

            <p>
                We coordinate pricing, specifications, procurement and
                the required business arrangements.
            </p>

        </div>


        <!-- Step 04 -->
        <div class="process-step">

            <div class="process-number">
                04
            </div>

            <div class="process-icon">
                ↗
            </div>

            <h3>
                Export & Delivery
            </h3>

            <p>
                We follow up on preparation and export coordination
                until your order is ready for international delivery.
            </p>

        </div>

    </div>


    <div class="process-cta">

        <div>

            <strong>
                Have a specific requirement?
            </strong>

            <span>
                Let us explore how we can source it from Egypt.
            </span>

        </div>

        <a
            href="<?= SITE_URL ?>request-quote.php"
            class="btn btn-primary"
        >
            Start Your Request
        </a>

    </div>

</div>
```

</section>

<!-- =====================================================
     WHY PHARAOHS
     ===================================================== -->

<section class="why-pharaohs section-padding">

```
<div class="container">

    <div class="section-heading why-heading">

        <div>

            <span class="section-label">
                WHY PHARAOHS
            </span>

            <h2>
                Built Around
                <span>Your Business Needs</span>
            </h2>

        </div>

        <p>
            We combine local knowledge, flexible sourcing and business
            coordination to help clients access opportunities and
            products from Egypt.
        </p>

    </div>


    <div class="why-grid">

        <!-- Feature 01 -->
        <div class="why-card">

            <div class="why-icon">
                <span>01</span>
            </div>

            <div>

                <h3>
                    Egypt-Based Expertise
                </h3>

                <p>
                    We operate from Egypt and understand the local
                    supplier, product and business environment.
                </p>

            </div>

        </div>


        <!-- Feature 02 -->
        <div class="why-card">

            <div class="why-icon">
                <span>02</span>
            </div>

            <div>

                <h3>
                    Flexible Sourcing
                </h3>

                <p>
                    We are not limited to one product category and can
                    explore different sourcing requirements.
                </p>

            </div>

        </div>


        <!-- Feature 03 -->
        <div class="why-card">

            <div class="why-icon">
                <span>03</span>
            </div>

            <div>

                <h3>
                    Business Coordination
                </h3>

                <p>
                    We coordinate communication, sourcing, procurement
                    and follow-up throughout the process.
                </p>

            </div>

        </div>


        <!-- Feature 04 -->
        <div class="why-card">

            <div class="why-icon">
                <span>04</span>
            </div>

            <div>

                <h3>
                    International Reach
                </h3>

                <p>
                    We connect Egyptian products and business opportunities
                    with clients and markets beyond Egypt.
                </p>

            </div>

        </div>

    </div>


    <div class="why-bottom">

        <div class="why-bottom-content">

            <span class="why-bottom-label">
                OUR APPROACH
            </span>

            <h3>
                One Partner.
                <span>Multiple Business Possibilities.</span>
            </h3>

        </div>

        <p>
            Whether you are looking for a specific product, a reliable
            Egyptian supplier or a new business opportunity, we start
            by understanding your requirement and finding a practical
            way forward.
        </p>

    </div>

</div>
```

</section>

<!-- =====================================================
     FINAL CTA
     ===================================================== -->

<section class="final-cta">

```
<div class="container">

    <div class="final-cta-content">

        <span class="final-cta-label">
            LET'S WORK TOGETHER
        </span>

        <h2>
            Have Something
            <span>In Mind?</span>
        </h2>

        <p>
            Whether you need a product from Egypt, a reliable supplier,
            an export solution or a new business opportunity, tell us
            what you are looking for and let us explore the possibilities.
        </p>

        <div class="final-cta-actions">

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

require_once __DIR__ . "/includes/footer.php";

?>
