<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Investments";

// Get Investment Opportunities
$investments = [];

$result = $conn->query("
    SELECT *
    FROM investment_opportunities
    ORDER BY sort_order ASC, id DESC
");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $investments[] = $row;
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

        <?php if ($success === "created"): ?>

            <div class="alert alert-success">
                Investment opportunity created successfully.
            </div>

        <?php elseif ($success === "updated"): ?>

            <div class="alert alert-success">
                Investment opportunity updated successfully.
            </div>

        <?php elseif ($success === "deleted"): ?>

            <div class="alert alert-success">
                Investment opportunity deleted successfully.
            </div>

        <?php elseif ($error === "delete_failed"): ?>

            <div class="alert alert-danger">
                Failed to delete the investment opportunity.
            </div>

        <?php endif; ?>


        <div class="page-header">

            <div>
                <h2>Investments</h2>

                <p>
                    Manage investment opportunities, projects and partnerships.
                </p>
            </div>

            <a
                href="<?= ADMIN_URL ?>investments/create.php"
                class="page-btn"
            >
                + Add Investment
            </a>

        </div>


        <div class="content-card">

            <div class="table-wrapper">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Opportunity</th>

                            <th>Type</th>

                            <th>Location</th>

                            <th>Featured</th>

                            <th>Status</th>

                            <th>Created</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (count($investments) > 0): ?>

                        <?php foreach ($investments as $index => $investment): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>


                                <td>

                                    <div class="table-title">

                                        <?php if (!empty($investment["image"])): ?>

                                            <img
                                                src="<?= UPLOADS_URL ?>investments/<?= htmlspecialchars($investment["image"]) ?>"
                                                alt="<?= htmlspecialchars($investment["title"]) ?>"
                                                style="
                                                    width:48px;
                                                    height:48px;
                                                    object-fit:cover;
                                                    border-radius:8px;
                                                    border:1px solid var(--border);
                                                "
                                            >

                                        <?php else: ?>

                                            <div class="table-icon">
                                                ◈
                                            </div>

                                        <?php endif; ?>


                                        <div>

                                            <strong>
                                                <?= htmlspecialchars($investment["title"]) ?>
                                            </strong>

                                            <small>

                                                <?= htmlspecialchars(
                                                    mb_strimwidth(
                                                        $investment["short_description"] ?? "",
                                                        0,
                                                        70,
                                                        "..."
                                                    )
                                                ) ?>

                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <?php

                                    $type_labels = [
                                        "investment" => "Investment",
                                        "project" => "Project",
                                        "partnership" => "Partnership",
                                        "commercial" => "Commercial",
                                        "cooperation" => "Cooperation"
                                    ];

                                    echo htmlspecialchars(
                                        $type_labels[$investment["type"]]
                                        ?? ucfirst($investment["type"])
                                    );

                                    ?>

                                </td>


                                <td>

                                    <?php if (!empty($investment["location"])): ?>

                                        <?= htmlspecialchars($investment["location"]) ?>

                                    <?php else: ?>

                                        <span class="slug-text">—</span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if ((int) $investment["featured"] === 1): ?>

                                        <span class="status-badge status-active">
                                            Yes
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-inactive">
                                            No
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php

                                    $status_labels = [
                                        "open" => "Open",
                                        "closed" => "Closed",
                                        "coming_soon" => "Coming Soon",
                                        "inactive" => "Inactive"
                                    ];

                                    $status = $investment["status"];

                                    ?>

                                    <?php if ($status === "open"): ?>

                                        <span class="status-badge status-active">
                                            Open
                                        </span>

                                    <?php elseif ($status === "closed"): ?>

                                        <span class="status-badge status-inactive">
                                            Closed
                                        </span>

                                    <?php elseif ($status === "coming_soon"): ?>

                                        <span class="status-badge status-active">
                                            Coming Soon
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-inactive">
                                            Inactive
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php

                                    if (!empty($investment["created_at"])) {

                                        echo date(
                                            "d M Y",
                                            strtotime($investment["created_at"])
                                        );

                                    } else {

                                        echo "—";

                                    }

                                    ?>

                                </td>


                                <td>

                                    <div class="table-actions">

                                        <a
                                            href="<?= ADMIN_URL ?>investments/edit.php?id=<?= (int) $investment["id"] ?>"
                                            class="action-btn edit-btn"
                                        >
                                            Edit
                                        </a>


                                        <a
                                            href="<?= ADMIN_URL ?>investments/delete.php?id=<?= (int) $investment["id"] ?>"
                                            class="action-btn delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this investment opportunity?');"
                                        >
                                            Delete
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
                                    No investment opportunities found.
                                </div>

                                <a
                                    href="<?= ADMIN_URL ?>investments/create.php"
                                    class="page-btn"
                                >
                                    Add Your First Investment
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

<?php require_once __DIR__ . "/../includes/footer.php"; ?>