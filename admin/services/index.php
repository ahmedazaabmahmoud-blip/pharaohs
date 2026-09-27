<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Services";


// Get Services
$services = [];

$result = $conn->query("
    SELECT *
    FROM services
    ORDER BY sort_order ASC, id DESC
");

if ($result) {

    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }

}


// Success / Error Messages
$success = $_GET["success"] ?? "";
$error = $_GET["error"] ?? "";


require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

?>

<div class="main-content">

    <?php require_once __DIR__ . "/../includes/navbar.php"; ?>

    <main class="page-content">


        <!-- Success / Error Messages -->

        <?php if ($success === "created"): ?>

            <div class="alert alert-success">
                Service created successfully.
            </div>

        <?php elseif ($success === "updated"): ?>

            <div class="alert alert-success">
                Service updated successfully.
            </div>

        <?php elseif ($success === "deleted"): ?>

            <div class="alert alert-success">
                Service deleted successfully.
            </div>

        <?php elseif ($error === "delete_failed"): ?>

            <div class="alert alert-danger">
                Failed to delete the service.
            </div>

        <?php endif; ?>


        <!-- Page Header -->

        <div class="page-header">

            <div>

                <h2>
                    Services
                </h2>

                <p>
                    Manage the services displayed on your website.
                </p>

            </div>


            <a
                href="<?= ADMIN_URL ?>services/create.php"
                class="page-btn"
            >
                + Add Service
            </a>

        </div>



        <!-- Services Table -->

        <div class="content-card">

            <div class="table-wrapper">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Service</th>

                            <th>Slug</th>

                            <th>Sort Order</th>

                            <th>Status</th>

                            <th>Created</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (count($services) > 0): ?>


                        <?php foreach ($services as $index => $service): ?>

                            <tr>


                                <!-- Number -->

                                <td>

                                    <?= $index + 1 ?>

                                </td>



                                <!-- Service -->

                                <td>

                                    <div class="table-title">


                                        <?php if (!empty($service["icon"])): ?>

                                            <div class="table-icon">

                                                <?= htmlspecialchars(
                                                    $service["icon"]
                                                ) ?>

                                            </div>

                                        <?php else: ?>

                                            <div class="table-icon">

                                                ◈

                                            </div>

                                        <?php endif; ?>


                                        <div>

                                            <strong>

                                                <?= htmlspecialchars(
                                                    $service["title"]
                                                ) ?>

                                            </strong>


                                            <small>

                                                <?= htmlspecialchars(

                                                    mb_strimwidth(

                                                        $service[
                                                            "short_description"
                                                        ] ?? "",

                                                        0,

                                                        70,

                                                        "..."

                                                    )

                                                ) ?>

                                            </small>

                                        </div>


                                    </div>

                                </td>



                                <!-- Slug -->

                                <td>

                                    <?php if (!empty($service["slug"])): ?>

                                        <span class="slug-text">

                                            <?= htmlspecialchars(
                                                $service["slug"]
                                            ) ?>

                                        </span>

                                    <?php else: ?>

                                        <span class="slug-text">

                                            —

                                        </span>

                                    <?php endif; ?>

                                </td>



                                <!-- Sort Order -->

                                <td>

                                    <?= (int) $service["sort_order"] ?>

                                </td>



                                <!-- Status -->

                                <td>

                                    <?php if (
                                        $service["status"] === "active"
                                    ): ?>

                                        <span class="status-badge status-active">

                                            Active

                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-inactive">

                                            Inactive

                                        </span>

                                    <?php endif; ?>

                                </td>



                                <!-- Created -->

                                <td>

                                    <?php

                                    if (!empty($service["created_at"])) {

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $service["created_at"]
                                            )
                                        );

                                    } else {

                                        echo "—";

                                    }

                                    ?>

                                </td>



                                <!-- Actions -->

                                <td>

                                    <div class="table-actions">


                                        <a
                                            href="<?= ADMIN_URL ?>services/edit.php?id=<?= (int) $service["id"] ?>"
                                            class="action-btn edit-btn"
                                        >
                                            Edit
                                        </a>


                                        <a
                                            href="<?= ADMIN_URL ?>services/delete.php?id=<?= (int) $service["id"] ?>"
                                            class="action-btn delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this service?');"
                                        >
                                            Delete
                                        </a>


                                    </div>

                                </td>


                            </tr>

                        <?php endforeach; ?>


                    <?php else: ?>


                        <!-- Empty State -->

                        <tr>

                            <td
                                colspan="7"
                                class="empty-state"
                            >

                                <div>

                                    No services found.

                                </div>


                                <a
                                    href="<?= ADMIN_URL ?>services/create.php"
                                    class="page-btn"
                                >

                                    Add Your First Service

                                </a>

                            </td>

                        </tr>


                    <?php endif; ?>


                    </tbody>

                </table>

            </div>

        </div>


    </main>

</div>


<?php

require_once __DIR__ . "/../includes/footer.php";

?>