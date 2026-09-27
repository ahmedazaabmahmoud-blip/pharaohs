/* =========================================================
   PHARAOHS INTEGRATED BUSINESS
   Main JavaScript
   ========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       MOBILE MENU
       ===================================================== */

    const mobileMenuToggle = document.getElementById("mobileMenuToggle");
    const navMenu = document.querySelector(".nav-menu");

    if (mobileMenuToggle && navMenu) {

        mobileMenuToggle.addEventListener("click", function () {

            const isOpen = navMenu.classList.toggle("active");

            mobileMenuToggle.setAttribute(
                "aria-expanded",
                isOpen ? "true" : "false"
            );

        });


        /* Close menu when clicking a navigation link */

        const navLinks = navMenu.querySelectorAll("a");

        navLinks.forEach(function (link) {

            link.addEventListener("click", function () {

                navMenu.classList.remove("active");

                mobileMenuToggle.setAttribute(
                    "aria-expanded",
                    "false"
                );

            });

        });

    }


    /* =====================================================
       CLOSE MOBILE MENU WHEN RESIZING TO DESKTOP
       ===================================================== */

    window.addEventListener("resize", function () {

        if (window.innerWidth > 900 && navMenu) {

            navMenu.classList.remove("active");

            if (mobileMenuToggle) {

                mobileMenuToggle.setAttribute(
                    "aria-expanded",
                    "false"
                );

            }

        }

    });

});