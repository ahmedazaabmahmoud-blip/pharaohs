
document.addEventListener("DOMContentLoaded", function () {

    const filterButtons = document.querySelectorAll(
        ".products-page-filter"
    );

    const categoryLinks = document.querySelectorAll(
        ".products-category-filter"
    );

    const productCards = document.querySelectorAll(
        ".products-page-product-card"
    );

    const noResults = document.getElementById(
        "products-no-results"
    );


    // =====================================================
    // FILTER PRODUCTS
    // =====================================================

    function filterProducts(category) {

        let visibleProducts = 0;


        productCards.forEach(function (card) {

            const cardCategory =
                card.getAttribute("data-category");


            const shouldShow =
                category === "all" ||
                cardCategory === category;


            if (shouldShow) {

                card.style.display = "";

                visibleProducts++;

            } else {

                card.style.display = "none";

            }

        });


        // Show / hide no-results message

        if (noResults) {

            noResults.hidden =
                visibleProducts !== 0;

        }


        // Update filter buttons

        filterButtons.forEach(function (button) {

            const buttonCategory =
                button.getAttribute("data-filter");

            const isActive =
                buttonCategory === category;


            button.classList.toggle(
                "active",
                isActive
            );


            button.setAttribute(
                "aria-pressed",
                isActive ? "true" : "false"
            );

        });

    }


    // =====================================================
    // FILTER BUTTONS
    // =====================================================

    filterButtons.forEach(function (button) {

        button.addEventListener(
            "click",
            function () {

                const category =
                    this.getAttribute("data-filter");


                filterProducts(category);


                // Keep the products section visible

                const productsSection =
                    document.getElementById(
                        "products-list"
                    );


                if (productsSection) {

                    const headerOffset = 100;

                    const sectionPosition =
                        productsSection.getBoundingClientRect().top +
                        window.pageYOffset -
                        headerOffset;


                    window.scrollTo({
                        top: sectionPosition,
                        behavior: "smooth"
                    });

                }

            }
        );

    });


    // =====================================================
    // CATEGORY CARDS
    // =====================================================

    categoryLinks.forEach(function (link) {

        link.addEventListener(
            "click",
            function (event) {

                event.preventDefault();


                const category =
                    this.getAttribute("data-category");


                filterProducts(category);


                const productsSection =
                    document.getElementById(
                        "products-list"
                    );


                if (productsSection) {

                    const headerOffset = 100;

                    const sectionPosition =
                        productsSection.getBoundingClientRect().top +
                        window.pageYOffset -
                        headerOffset;


                    window.scrollTo({
                        top: sectionPosition,
                        behavior: "smooth"
                    });

                }

            }
        );

    });


    // =====================================================
    // INITIAL STATE
    // =====================================================

    filterProducts("all");

});

