
</main>


<footer class="site-footer">

    <div class="container">

        <div class="footer-grid">


            <!-- Company -->

            <div class="footer-column footer-company">

                <a
                    href="<?= SITE_URL ?>"
                    class="footer-logo"
                >

                    <div class="footer-logo-mark">
                        P
                    </div>

                    <div>

                        <strong>
                            PHARAOHS
                        </strong>

                        <span>
                            INTEGRATED BUSINESS
                        </span>

                    </div>

                </a>


                <p>

                    An Egyptian multi-activity company providing
                    sourcing, trading, procurement, contracting,
                    IT solutions and international export services.

                </p>


                <p class="footer-tagline">
                    From Egypt. Beyond Boundaries.
                </p>


                <!-- Follow Us -->

                <div class="footer-social">

                    <span class="footer-social-label">
                        FOLLOW US
                    </span>


                    <div class="footer-social-links">

                        <?php
                        $footer_facebook = trim((string) get_setting('facebook', ''));
                        $footer_instagram = trim((string) get_setting('instagram', ''));
                        $footer_linkedin = trim((string) get_setting('linkedin', ''));
                        ?>


                        <?php if (!empty($footer_facebook)): ?>

                            <a
                                href="<?= htmlspecialchars($footer_facebook, ENT_QUOTES, 'UTF-8') ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="Facebook"
                                class="footer-social-link"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M14 8h3V4h-3c-3.31 0-5 1.69-5 5v3H6v4h3v8h4v-8h3l1-4h-4V9c0-.66.34-1 1-1z"
                                    />
                                </svg>

                            </a>

                        <?php endif; ?>


                        <?php if (!empty($footer_instagram)): ?>

                            <a
                                href="<?= htmlspecialchars($footer_instagram, ENT_QUOTES, 'UTF-8') ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="Instagram"
                                class="footer-social-link"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <rect
                                        x="3"
                                        y="3"
                                        width="18"
                                        height="18"
                                        rx="5"
                                        ry="5"
                                    />

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="4"
                                    />

                                    <circle
                                        cx="17.5"
                                        cy="6.5"
                                        r="1"
                                        class="social-fill"
                                    />

                                </svg>

                            </a>

                        <?php endif; ?>


                        <?php if (!empty($footer_linkedin)): ?>

                            <a
                                href="<?= htmlspecialchars($footer_linkedin, ENT_QUOTES, 'UTF-8') ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="LinkedIn"
                                class="footer-social-link"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M6 8H3V21H6V8ZM4.5 3C3.67 3 3 3.67 3 4.5C3 5.33 3.67 6 4.5 6C5.33 6 6 5.33 6 4.5C6 3.67 5.33 3 4.5 3ZM21 13.5C21 9.91 19.09 8 16.25 8C14.57 8 13.46 8.92 13 9.69V8H10V21H13V14.25C13 12.48 13.5 11 15.25 11C16.97 11 17 12.61 17 14.36V21H21V13.5Z"
                                    />
                                </svg>

                            </a>

                        <?php endif; ?>


                    </div>

                </div>

            </div>


            <!-- Quick Links -->

            <div class="footer-column">

                <h3>
                    Quick Links
                </h3>

                <ul>

                    <li>
                        <a href="<?= SITE_URL ?>">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="<?= SITE_URL ?>pages/about.php">
                            About Us
                        </a>
                    </li>

                    <li>
                        <a href="<?= SITE_URL ?>pages/services.php">
                            Services
                        </a>
                    </li>

                    <li>
                        <a href="<?= SITE_URL ?>pages/industries.php">
                            Industries
                        </a>
                    </li>

                    <li>
                        <a href="<?= SITE_URL ?>pages/products.php">
                            Products
                        </a>
                    </li>

                </ul>

            </div>


            <!-- Business -->

            <div class="footer-column">

                <h3>
                    Business
                </h3>

                <ul>

                    <li>
                        <a href="<?= SITE_URL ?>pages/export.php">
                            Export From Egypt
                        </a>
                    </li>

                    <li>
                        <a href="<?= SITE_URL ?>pages/investment.php">
                            Investment Opportunities
                        </a>
                    </li>

                    <li>
                        <a href="<?= SITE_URL ?>request-quote.php">
                            Request a Quote
                        </a>
                    </li>

                    <li>
                        <a href="<?= SITE_URL ?>pages/contact.php">
                            Contact Us
                        </a>
                    </li>

                </ul>

            </div>


            <!-- Contact -->

            <div class="footer-column">

                <h3>
                    Contact Us
                </h3>

                <ul class="footer-contact">

                    <li>
                        <span class="contact-icon">✉</span>

                        <span>
                            <?= htmlspecialchars(
                                get_setting('email', '')
                            ) ?>
                        </span>
                    </li>


                    <li>
                        <span class="contact-icon">☎</span>

                        <span>
                            <?= htmlspecialchars(
                                get_setting('phone', '')
                            ) ?>
                        </span>
                    </li>


                    <li>
                        <span class="contact-icon">◉</span>

                        <span>
                            <?= htmlspecialchars(
                                get_setting('address', '')
                            ) ?>
                        </span>
                    </li>

                </ul>

            </div>

        </div>


        <!-- Footer Bottom -->

        <div class="footer-bottom">

            <p>
                © <?= date("Y") ?>
                Pharaohs Integrated Business.
                All Rights Reserved.
            </p>


            <div class="footer-bottom-links">

                <a href="<?= SITE_URL ?>pages/contact.php">
                    Contact
                </a>

                <span>•</span>

                <a href="<?= SITE_URL ?>pages/privacy.php">
                    Privacy Policy
                </a>

            </div>

        </div>

    </div>

</footer>


<!-- =========================================================
     GLOBAL JAVASCRIPT
========================================================== -->

<script
    src="<?= SITE_URL ?>assets/js/main.js"
></script>

<script
    src="<?= SITE_URL ?>assets/js/animations.js"
></script>


<!-- =========================================================
     PAGE JAVASCRIPT
========================================================== -->

<?php if (!empty($current_js)): ?>

    <script
        src="<?= SITE_URL ?>assets/js/<?= htmlspecialchars($current_js) ?>"
    ></script>

<?php endif; ?>


</body>

</html>

