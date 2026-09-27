<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Categories";

$categories = [];

$result = $conn->query("
    SELECT
        categories.*,
        industries.name AS industry_name
    FROM categories
    LEFT JOIN industries
        ON categories.industry_id = industries.id
    ORDER BY categories.sort_order ASC, categories.id DESC
");

if ($result) {

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

}

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
                Category created successfully.
            </div>

        <?php elseif ($success === "updated"): ?>

            <div class="alert alert-success">
                Category updated successfully.
            </div>

        <?php elseif ($success === "deleted"): ?>

            <div class="alert alert-success">
                Category deleted successfully.
            </div>

        <?php elseif ($error === "delete_failed"): ?>

            <div class="alert alert-danger">
                Failed to delete the category.
            </div>

        <?php endif; ?>


        <div class="page-header">

            <div>

                <h2>
                    Categories
                </h2>

                <p>
                    Manage product categories and their industries.
                </p>

            </div>

            <a
                href="<?= ADMIN_URL ?>categories/create.php"
                class="page-btn"
            >
                + Add Category
            </a>

        </div>


        <div class="content-card">

            <div class="table-wrapper">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Category</th>
                            <th>Industry</th>
                            <th>Slug</th>
                            <th>Sort Order</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (count($categories) > 0): ?>

                        <?php foreach ($categories as $index => $category): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>


                                <td>

                                    <div class="table-title">

                                        <?php if (!empty($category["image"])): ?>

                                            <div class="table-icon">

                                                <img
                                                    src="<?= UPLOADS_URL ?>categories/<?= htmlspecialchars($category["image"]) ?>"
                                                    alt="<?= htmlspecialchars($category["name"]) ?>"
                                                    style="
                                                        width:40px;
                                                        height:40px;
                                                        object-fit:cover;
                                                        border-radius:6px;
                                                    "
                                                >

                                            </div>

                                        <?php else: ?>

                                            <div class="table-icon">
                                                ◈
                                            </div>

                                        <?php endif; ?>


                                        <div>

                                            <strong>
                                                <?= htmlspecialchars($category["name"]) ?>
                                            </strong>

                                            <small>

                                                <?= htmlspecialchars(
                                                    mb_strimwidth(
                                                        $category["description"] ?? "",
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

                                    <?php if (!empty($category["industry_name"])): ?>

                                        <?= htmlspecialchars(
                                            $category["industry_name"]
                                        ) ?>

                                    <?php else: ?>

                                        <span class="slug-text">
                                            Not Assigned
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if (!empty($category["slug"])): ?>

                                        <span class="slug-text">

                                            <?= htmlspecialchars(
                                                $category["slug"]
                                            ) ?>

                                        </span>

                                    <?php else: ?>

                                        <span class="slug-text">
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>
                                    <?= (int) $category["sort_order"] ?>
                                </td>


                                <td>

                                    <?php if (
                                        $category["status"] === "active"
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


                                <td>

                                    <?php

                                    if (!empty($category["created_at"])) {

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $category["created_at"]
                                            )
                                        );

                                    } else {

                                        echo "—";

                                    }

                                    ?>

                                </td>


                                <td>

                                    <div class="table-actions">

                                        <a
                                            href="<?= ADMIN_URL ?>categories/edit.php?id=<?= (int) $category["id"] ?>"
                                            class="action-btn edit-btn"
                                        >
                                            Edit
                                        </a>

                                        <a
                                            href="<?= ADMIN_URL ?>categories/delete.php?id=<?= (int) $category["id"] ?>"
                                            class="action-btn delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this category?');"
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
                                    No categories found.
                                </div>

                                <a
                                    href="<?= ADMIN_URL ?>categories/create.php"
                                    class="page-btn"
                                >
                                    Add Your First Category
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