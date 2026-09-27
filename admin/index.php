<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: login.php");
    exit;

}

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/config.php";

$page_title = "Dashboard";


/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

function getCount($conn, $table)
{
    $allowed_tables = [
        "products",
        "services",
        "industries",
        "categories",
        "investment_opportunities",
        "quotes",
        "contacts"
    ];

    if (!in_array($table, $allowed_tables)) {
        return 0;
    }

    $result = $conn->query("SELECT COUNT(*) AS total FROM `$table`");

    if ($result) {

        $row = $result->fetch_assoc();

        return (int) $row["total"];

    }

    return 0;
}


$total_products = getCount($conn, "products");

$total_services = getCount($conn, "services");

$total_industries = getCount($conn, "industries");

$total_categories = getCount($conn, "categories");

$total_investments = getCount($conn, "investment_opportunities");

$total_quotes = getCount($conn, "quotes");

$total_contacts = getCount($conn, "contacts");


/*
|--------------------------------------------------------------------------
| New Requests
|--------------------------------------------------------------------------
*/

$new_quotes = 0;

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM quotes
    WHERE status = 'new'
");

if ($result) {

    $row = $result->fetch_assoc();

    $new_quotes = (int) $row["total"];

}


$new_contacts = 0;

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM contacts
    WHERE status = 'new'
");

if ($result) {

    $row = $result->fetch_assoc();

    $new_contacts = (int) $row["total"];

}


require_once __DIR__ . "/includes/header.php";

require_once __DIR__ . "/includes/sidebar.php";
?>

<div class="main-content">

    <?php require_once __DIR__ . "/includes/navbar.php"; ?>


    <main class="page-content">


        <!-- Welcome -->

        <div class="dashboard-welcome">

            <div>

                <h2>
                    Welcome back,
                    <?= htmlspecialchars($_SESSION["admin_name"]) ?> 👋
                </h2>

                <p>
                    Here's what's happening with your business today.
                </p>

            </div>

        </div>



        <!-- Statistics -->

        <div class="stats-grid">


            <!-- Products -->

            <div class="stat-card">

                <div class="stat-icon">
                    □
                </div>

                <div class="stat-content">

                    <span>
                        Total Products
                    </span>

                    <strong>
                        <?= $total_products ?>
                    </strong>

                </div>

            </div>



            <!-- Services -->

            <div class="stat-card">

                <div class="stat-icon">
                    ▣
                </div>

                <div class="stat-content">

                    <span>
                        Services
                    </span>

                    <strong>
                        <?= $total_services ?>
                    </strong>

                </div>

            </div>



            <!-- Industries -->

            <div class="stat-card">

                <div class="stat-icon">
                    ◈
                </div>

                <div class="stat-content">

                    <span>
                        Industries
                    </span>

                    <strong>
                        <?= $total_industries ?>
                    </strong>

                </div>

            </div>



            <!-- Categories -->

            <div class="stat-card">

                <div class="stat-icon">
                    ▤
                </div>

                <div class="stat-content">

                    <span>
                        Categories
                    </span>

                    <strong>
                        <?= $total_categories ?>
                    </strong>

                </div>

            </div>



            <!-- Investments -->

            <div class="stat-card">

                <div class="stat-icon">
                    ◆
                </div>

                <div class="stat-content">

                    <span>
                        Investments
                    </span>

                    <strong>
                        <?= $total_investments ?>
                    </strong>

                </div>

            </div>



            <!-- Quotes -->

            <div class="stat-card">

                <div class="stat-icon">
                    ✉
                </div>

                <div class="stat-content">

                    <span>
                        Quote Requests
                    </span>

                    <strong>
                        <?= $total_quotes ?>
                    </strong>

                </div>

            </div>



            <!-- Messages -->

            <div class="stat-card">

                <div class="stat-icon">
                    ☏
                </div>

                <div class="stat-content">

                    <span>
                        Messages
                    </span>

                    <strong>
                        <?= $total_contacts ?>
                    </strong>

                </div>

            </div>



            <!-- New Requests -->

            <div class="stat-card stat-highlight">

                <div class="stat-icon">
                    !
                </div>

                <div class="stat-content">

                    <span>
                        New Requests
                    </span>

                    <strong>
                        <?= $new_quotes + $new_contacts ?>
                    </strong>

                </div>

            </div>

        </div>



        <!-- Bottom Grid -->

        <div class="dashboard-grid">


            <!-- Quick Actions -->

            <div class="dashboard-card">

                <div class="card-header">

                    <div>

                        <h3>
                            Quick Actions
                        </h3>

                        <p>
                            Manage your website content
                        </p>

                    </div>

                </div>


                <div class="quick-actions">

                    <a href="<?= ADMIN_URL ?>products/create.php"
                       class="quick-action">

                        <span>+</span>

                        <div>

                            <strong>
                                Add Product
                            </strong>

                            <small>
                                Create a new product
                            </small>

                        </div>

                    </a>


                    <a href="<?= ADMIN_URL ?>services/create.php"
                       class="quick-action">

                        <span>+</span>

                        <div>

                            <strong>
                                Add Service
                            </strong>

                            <small>
                                Create a new service
                            </small>

                        </div>

                    </a>


                    <a href="<?= ADMIN_URL ?>industries/create.php"
                       class="quick-action">

                        <span>+</span>

                        <div>

                            <strong>
                                Add Industry
                            </strong>

                            <small>
                                Create a new industry
                            </small>

                        </div>

                    </a>


                    <a href="<?= ADMIN_URL ?>investments/create.php"
                       class="quick-action">

                        <span>+</span>

                        <div>

                            <strong>
                                Add Investment
                            </strong>

                            <small>
                                Create investment opportunity
                            </small>

                        </div>

                    </a>

                </div>

            </div>



            <!-- Requests -->

            <div class="dashboard-card">

                <div class="card-header">

                    <div>

                        <h3>
                            New Requests
                        </h3>

                        <p>
                            Requests that need your attention
                        </p>

                    </div>

                </div>


                <div class="request-summary">


                    <div class="request-row">

                        <div class="request-icon">
                            ✉
                        </div>

                        <div class="request-info">

                            <strong>
                                Quote Requests
                            </strong>

                            <span>
                                New quotation requests
                            </span>

                        </div>

                        <div class="request-count">
                            <?= $new_quotes ?>
                        </div>

                    </div>



                    <div class="request-row">

                        <div class="request-icon">
                            ☏
                        </div>

                        <div class="request-info">

                            <strong>
                                Contact Messages
                            </strong>

                            <span>
                                New contact messages
                            </span>

                        </div>

                        <div class="request-count">
                            <?= $new_contacts ?>
                        </div>

                    </div>


                </div>

            </div>

        </div>


    </main>

</div>


<?php

require_once __DIR__ . "/includes/footer.php";

?>