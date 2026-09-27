<?php

$current_page = basename($_SERVER['PHP_SELF']);

?>

<header class="site-header">

    <div class="container">

        <nav class="navbar">

            <!-- Logo -->

            <a
                href="<?= SITE_URL ?>"
                class="site-logo"
                aria-label="Pharaohs Integrated Business Home"
            >

                <div class="logo-mark">
                    P
                </div>

                <div class="logo-text">

                    <span class="logo-name">
                        PHARAOHS
                    </span>

                    <span class="logo-subtitle">
                        INTEGRATED BUSINESS
                    </span>

                </div>

            </a>


            <!-- Desktop Navigation -->

            <div class="nav-menu">

                <a
                    href="<?= SITE_URL ?>"
                    class="nav-link <?= $current_page === 'index.php' ? 'active' : '' ?>"
                >
                    Home
                </a>

                <a
                    href="<?= SITE_URL ?>pages/about.php"
                    class="nav-link <?= $current_page === 'about.php' ? 'active' : '' ?>"
                >
                    About Us
                </a>

                <a
                    href="<?= SITE_URL ?>pages/services.php"
                    class="nav-link <?= $current_page === 'services.php' ? 'active' : '' ?>"
                >
                    Services
                </a>

                <a
                    href="<?= SITE_URL ?>pages/industries.php"
                    class="nav-link <?= $current_page === 'industries.php' ? 'active' : '' ?>"
                >
                    Industries
                </a>

                <a
                    href="<?= SITE_URL ?>pages/products.php"
                    class="nav-link <?= $current_page === 'products.php' ? 'active' : '' ?>"
                >
                    Products
                </a>

                <a
                    href="<?= SITE_URL ?>pages/export.php"
                    class="nav-link <?= $current_page === 'export.php' ? 'active' : '' ?>"
                >
                    Export From Egypt
                </a>

                <a
                    href="<?= SITE_URL ?>pages/investment.php"
                    class="nav-link <?= $current_page === 'investment.php' ? 'active' : '' ?>"
                >
                    Investment
                </a>

                <a
                    href="<?= SITE_URL ?>pages/contact.php"
                    class="nav-link <?= $current_page === 'contact.php' ? 'active' : '' ?>"
                >
                    Contact
                </a>

                <a
                    href="<?= SITE_URL ?>request-quote.php"
                    class="nav-cta"
                >
                    Request a Quote
                </a>

            </div>


            <!-- Mobile Menu Button -->

            <button
                class="mobile-menu-toggle"
                id="mobileMenuToggle"
                type="button"
                aria-label="Toggle navigation menu"
                aria-expanded="false"
            >

                <span></span>
                <span></span>
                <span></span>

            </button>

        </nav>

    </div>

</header>