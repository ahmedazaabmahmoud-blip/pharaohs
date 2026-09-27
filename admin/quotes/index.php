<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Quote Requests";

// Get Quote Requests
$quotes = [];

$result = $conn->query("
    SELECT *
    FROM quotes
    ORDER BY id DESC
");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $quotes[] = $row;
    }
}

// Messages
$success = $_GET["success"] ?? "";
$error = $_GET["error"] ?? "";

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="main-content">

    <?php require_once __DIR__ . "/../includes/navbar.php"; ?>

    <main class="page-content">

        <?php if ($success === "updated"): ?>

            <div class="alert alert-success">
                Quote request updated successfully.
            </div>

        <?php elseif ($success === "deleted"): ?>

            <div class="alert alert-success">
                Quote request deleted successfully.
            </div>

        <?php elseif ($error === "delete_failed"): ?>

            <div class="alert alert-danger">
                Failed to delete the quote request.
            </div>

        <?php endif; ?>


        <div class="page-header">

            <div>

                <h2>Quote Requests</h2>

                <p>
                    Manage quotation requests submitted by website visitors.
                </p>

            </div>

        </div>


        <div class="content-card">

            <div class="table-wrapper">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Customer</th>

                            <th>Company</th>

                            <th>Country</th>

                            <th>Product / Service</th>

                            <th>Status</th>

                            <th>Created</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (count($quotes) > 0): ?>

                        <?php foreach ($quotes as $index => $quote): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>


                                <td>

                                    <div class="table-title">

                                        <div class="table-icon">
                                            <?= strtoupper(
                                                substr(
                                                    $quote["full_name"] ?? "C",
                                                    0,
                                                    1
                                                )
                                            ) ?>
                                        </div>

                                        <div>

                                            <strong>
                                                <?= htmlspecialchars(
                                                    $quote["full_name"] ?? "—"
                                                ) ?>
                                            </strong>

                                            <small>
                                                <?= htmlspecialchars(
                                                    $quote["email"] ?? "—"
                                                ) ?>
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <?php if (!empty($quote["company_name"])): ?>

                                        <?= htmlspecialchars(
                                            $quote["company_name"]
                                        ) ?>

                                    <?php else: ?>

                                        <span class="slug-text">—</span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if (!empty($quote["country"])): ?>

                                        <?= htmlspecialchars(
                                            $quote["country"]
                                        ) ?>

                                    <?php else: ?>

                                        <span class="slug-text">—</span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if (!empty($quote["product_service"])): ?>

                                        <?= htmlspecialchars(
                                            mb_strimwidth(
                                                $quote["product_service"],
                                                0,
                                                45,
                                                "..."
                                            )
                                        ) ?>

                                    <?php else: ?>

                                        <span class="slug-text">—</span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php

                                    $status = $quote["status"];

                                    ?>

                                    <?php if ($status === "new"): ?>

                                        <span class="status-badge status-active">
                                            New
                                        </span>

                                    <?php elseif ($status === "in_progress"): ?>

                                        <span class="status-badge status-active">
                                            In Progress
                                        </span>

                                    <?php elseif ($status === "quoted"): ?>

                                        <span class="status-badge status-active">
                                            Quoted
                                        </span>

                                    <?php elseif ($status === "completed"): ?>

                                        <span class="status-badge status-active">
                                            Completed
                                        </span>

                                    <?php elseif ($status === "cancelled"): ?>

                                        <span class="status-badge status-inactive">
                                            Cancelled
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-inactive">
                                            <?= htmlspecialchars($status) ?>
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php

                                    if (!empty($quote["created_at"])) {

                                        echo date(
                                            "d M Y",
                                            strtotime($quote["created_at"])
                                        );

                                    } else {

                                        echo "—";

                                    }

                                    ?>

                                </td>


                                <td>

                                    <div class="table-actions">

                                        <a
                                            href="<?= ADMIN_URL ?>quotes/view.php?id=<?= (int) $quote["id"] ?>"
                                            class="action-btn edit-btn"
                                        >
                                            View
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>


                    <?php else: ?>

                        <tr>

                            <td
                                colspan="8"
                                class="empty-state"
                            >

                                <div>
                                    No quote requests found.
                                </div>

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>