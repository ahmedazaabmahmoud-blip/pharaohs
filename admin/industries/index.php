<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Industries";


// Get Industries
$industries = [];

$result = $conn->query("
    SELECT *
    FROM industries
    ORDER BY sort_order ASC, id DESC
");

if ($result) {

    while ($row = $result->fetch_assoc()) {
        $industries[] = $row;
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
                Industry created successfully.
            </div>

        <?php elseif ($success === "updated"): ?>

            <div class="alert alert-success">
                Industry updated successfully.
            </div>

        <?php elseif ($success === "deleted"): ?>

            <div class="alert alert-success">
                Industry deleted successfully.
            </div>

        <?php elseif ($error === "delete_failed"): ?>

            <div class="alert alert-danger">
                Failed to delete the industry.
            </div>

        <?php endif; ?>



        <!-- Page Header -->

        <div class="page-header">

            <div>

                <h2>
                    Industries
                </h2>

                <p>
                    Manage the industries displayed on your website.
                </p>

            </div>


            <a
                href="<?= ADMIN_URL ?>industries/create.php"
                class="page-btn"
            >
                + Add Industry
            </a>

        </div>



        <!-- Industries Table -->

        <div class="content-card">

            <div class="table-wrapper">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Industry</th>

                            <th>Slug</th>

                            <th>Sort Order</th>

                            <th>Status</th>

                            <th>Created</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (count($industries) > 0): ?>


                        <?php foreach (
                            $industries as $index => $industry
                        ): ?>

                            <tr>


                                <!-- Number -->

                                <td>

                                    <?= $index + 1 ?>

                                </td>



                                <!-- Industry -->

                                <td>

                                    <div class="table-title">


                                        <?php if (
                                            !empty($industry["icon"])
                                        ): ?>

                                            <div class="table-icon">

                                                <?= htmlspecialchars(
                                                    $industry["icon"]
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
                                                    $industry["name"]
                                                ) ?>

                                            </strong>


                                            <small>

                                                <?= htmlspecialchars(

                                                    mb_strimwidth(

                                                        $industry[
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

                                    <?php if (
                                        !empty($industry["slug"])
                                    ): ?>

                                        <span class="slug-text">

                                            <?= htmlspecialchars(
                                                $industry["slug"]
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

                                    <?= (int) $industry["sort_order"] ?>

                                </td>



                                <!-- Status -->

                                <td>

                                    <?php if (
                                        $industry["status"] === "active"
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

                                    if (
                                        !empty(
                                            $industry["created_at"]
                                        )
                                    ) {

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $industry["created_at"]
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
                                            href="<?= ADMIN_URL ?>industries/edit.php?id=<?= (int) $industry["id"] ?>"
                                            class="action-btn edit-btn"
                                        >
                                            Edit
                                        </a>


                                        <a
                                            href="<?= ADMIN_URL ?>industries/delete.php?id=<?= (int) $industry["id"] ?>"
                                            class="action-btn delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this industry?');"
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

                                    No industries found.

                                </div>


                                <a
                                    href="<?= ADMIN_URL ?>industries/create.php"
                                    class="page-btn"
                                >

                                    Add Your First Industry

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