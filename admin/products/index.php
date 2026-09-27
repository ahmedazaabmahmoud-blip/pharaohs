<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Products";


// Get Products

$products = [];

$result = $conn->query("
    SELECT
        products.*,
        categories.name AS category_name
    FROM products
    LEFT JOIN categories
        ON products.category_id = categories.id
    ORDER BY products.sort_order ASC, products.id DESC
");

if ($result) {

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
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
                Product created successfully.
            </div>

        <?php elseif ($success === "updated"): ?>

            <div class="alert alert-success">
                Product updated successfully.
            </div>

        <?php elseif ($success === "deleted"): ?>

            <div class="alert alert-success">
                Product deleted successfully.
            </div>

        <?php elseif ($error === "delete_failed"): ?>

            <div class="alert alert-danger">
                Failed to delete the product.
            </div>

        <?php endif; ?>


        <!-- Page Header -->

        <div class="page-header">

            <div>

                <h2>
                    Products
                </h2>

                <p>
                    Manage the products displayed on your website.
                </p>

            </div>


            <a
                href="<?= ADMIN_URL ?>products/create.php"
                class="page-btn"
            >
                + Add Product
            </a>

        </div>



        <!-- Products Table -->

        <div class="content-card">

            <div class="table-wrapper">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Product</th>

                            <th>Category</th>

                            <th>Origin</th>

                            <th>Export</th>

                            <th>Featured</th>

                            <th>Status</th>

                            <th>Created</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (count($products) > 0): ?>


                        <?php foreach ($products as $index => $product): ?>

                            <tr>


                                <!-- Number -->

                                <td>

                                    <?= $index + 1 ?>

                                </td>



                                <!-- Product -->

                                <td>

                                    <div class="table-title">


                                        <?php if (!empty($product["image"])): ?>

                                            <img
                                                src="<?= UPLOADS_URL ?>products/<?= htmlspecialchars($product["image"]) ?>"
                                                alt="<?= htmlspecialchars($product["name"]) ?>"
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

                                                <?= htmlspecialchars(
                                                    $product["name"]
                                                ) ?>

                                            </strong>


                                            <small>

                                                <?= htmlspecialchars(

                                                    mb_strimwidth(

                                                        $product[
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



                                <!-- Category -->

                                <td>

                                    <?php if (!empty($product["category_name"])): ?>

                                        <?= htmlspecialchars(
                                            $product["category_name"]
                                        ) ?>

                                    <?php else: ?>

                                        <span class="slug-text">
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>



                                <!-- Origin -->

                                <td>

                                    <?= !empty($product["origin_country"])
                                        ? htmlspecialchars($product["origin_country"])
                                        : "—"
                                    ?>

                                </td>



                                <!-- Export Available -->

                                <td>

                                    <?php if (
                                        (int) $product["export_available"] === 1
                                    ): ?>

                                        <span class="status-badge status-active">

                                            Yes

                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-inactive">

                                            No

                                        </span>

                                    <?php endif; ?>

                                </td>



                                <!-- Featured -->

                                <td>

                                    <?php if (
                                        (int) $product["featured"] === 1
                                    ): ?>

                                        <span class="status-badge status-active">

                                            Yes

                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-inactive">

                                            No

                                        </span>

                                    <?php endif; ?>

                                </td>



                                <!-- Status -->

                                <td>

                                    <?php if (
                                        $product["status"] === "active"
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

                                    if (!empty($product["created_at"])) {

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $product["created_at"]
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
                                            href="<?= ADMIN_URL ?>products/edit.php?id=<?= (int) $product["id"] ?>"
                                            class="action-btn edit-btn"
                                        >
                                            Edit
                                        </a>


                                        <a
                                            href="<?= ADMIN_URL ?>products/delete.php?id=<?= (int) $product["id"] ?>"
                                            class="action-btn delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this product?');"
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
                                colspan="9"
                                class="empty-state"
                            >

                                <div>

                                    No products found.

                                </div>


                                <a
                                    href="<?= ADMIN_URL ?>products/create.php"
                                    class="page-btn"
                                >

                                    Add Your First Product

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