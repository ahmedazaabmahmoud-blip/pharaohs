<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Edit Product";


// Get Product ID

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}


// Get Product

$stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {

    $stmt->close();

    header("Location: index.php");
    exit;
}

$product = $result->fetch_assoc();

$stmt->close();


// Get Categories

$categories = [];

$result = $conn->query("
    SELECT
        categories.id,
        categories.name,
        industries.name AS industry_name
    FROM categories
    LEFT JOIN industries
        ON categories.industry_id = industries.id
    WHERE categories.status = 'active'
       OR categories.id = " . (int) $product["category_id"] . "
    ORDER BY categories.sort_order ASC, categories.name ASC
");

if ($result) {

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

}


// Form Variables

$name = $product["name"];
$category_id = (int) $product["category_id"];
$slug = $product["slug"];
$short_description = $product["short_description"] ?? "";
$description = $product["description"] ?? "";
$current_image = $product["image"] ?? "";
$origin_country = $product["origin_country"] ?? "Egypt";
$export_available = (int) $product["export_available"];
$additional_info = $product["additional_info"] ?? "";
$featured = (int) $product["featured"];
$sort_order = (int) $product["sort_order"];
$status = $product["status"];

$error = "";


// Generate Slug

function generateSlug($text)
{
    $text = strtolower(trim($text));

    $text = preg_replace("/[^a-z0-9]+/", "-", $text);

    $text = trim($text, "-");

    return $text;
}


// Handle Form

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $category_id = (int) ($_POST["category_id"] ?? 0);
    $slug = trim($_POST["slug"] ?? "");
    $short_description = trim($_POST["short_description"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $origin_country = trim($_POST["origin_country"] ?? "Egypt");
    $export_available = (int) ($_POST["export_available"] ?? 0);
    $additional_info = trim($_POST["additional_info"] ?? "");
    $featured = (int) ($_POST["featured"] ?? 0);
    $sort_order = (int) ($_POST["sort_order"] ?? 0);
    $status = $_POST["status"] ?? "active";


    // Required Fields

    if ($name === "") {

        $error = "Product name is required.";

    } elseif ($category_id <= 0) {

        $error = "Please select a category.";

    } elseif ($slug === "") {

        $slug = generateSlug($name);

    }


    // Check Category

    if ($error === "") {

        $stmt = $conn->prepare("
            SELECT id
            FROM categories
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->bind_param("i", $category_id);

        $stmt->execute();

        $category_result = $stmt->get_result();

        if ($category_result->num_rows === 0) {

            $error = "Selected category does not exist.";

        }

        $stmt->close();

    }


    // Check Slug

    if ($error === "" && $slug !== "") {

        $stmt = $conn->prepare("
            SELECT id
            FROM products
            WHERE slug = ?
              AND id != ?
            LIMIT 1
        ");

        $stmt->bind_param(
            "si",
            $slug,
            $id
        );

        $stmt->execute();

        $slug_result = $stmt->get_result();

        if ($slug_result->num_rows > 0) {

            $error = "This slug already exists.";

        }

        $stmt->close();

    }


    // Image

    $image_name = $current_image;


    // New Image Upload

    if ($error === "" && !empty($_FILES["image"]["name"])) {

        $upload_dir = __DIR__ . "/../../uploads/products/";

        if (!is_dir($upload_dir)) {

            mkdir($upload_dir, 0777, true);

        }


        $original_name = $_FILES["image"]["name"];
        $tmp_name = $_FILES["image"]["tmp_name"];
        $file_size = $_FILES["image"]["size"];
        $file_error = $_FILES["image"]["error"];


        if ($file_error !== UPLOAD_ERR_OK) {

            $error = "Failed to upload the image.";

        } else {

            $allowed_extensions = [
                "jpg",
                "jpeg",
                "png",
                "webp"
            ];

            $extension = strtolower(
                pathinfo(
                    $original_name,
                    PATHINFO_EXTENSION
                )
            );


            if (!in_array(
                $extension,
                $allowed_extensions,
                true
            )) {

                $error = "Invalid image type. Allowed: JPG, JPEG, PNG, WEBP.";

            } elseif ($file_size > 5 * 1024 * 1024) {

                $error = "Image size must not exceed 5 MB.";

            } else {

                $new_image_name =
                    uniqid("product_", true)
                    . "."
                    . $extension;


                $destination =
                    $upload_dir
                    . $new_image_name;


                if (move_uploaded_file(
                    $tmp_name,
                    $destination
                )) {

                    $image_name = $new_image_name;

                } else {

                    $error = "Failed to save the image.";

                }

            }

        }

    }


    // Update Product

    if ($error === "") {

        $stmt = $conn->prepare("
            UPDATE products

            SET
                category_id = ?,
                name = ?,
                slug = ?,
                short_description = ?,
                description = ?,
                image = ?,
                origin_country = ?,
                export_available = ?,
                additional_info = ?,
                featured = ?,
                sort_order = ?,
                status = ?

            WHERE id = ?
        ");


        $stmt->bind_param(
            "isssssisiiisi",
            $category_id,
            $name,
            $slug,
            $short_description,
            $description,
            $image_name,
            $origin_country,
            $export_available,
            $additional_info,
            $featured,
            $sort_order,
            $status,
            $id
        );


        if ($stmt->execute()) {

            $stmt->close();


            // Delete old image after successful update

            if (
                $image_name !== $current_image
                && !empty($current_image)
            ) {

                $old_image_path =
                    __DIR__
                    . "/../../uploads/products/"
                    . $current_image;

                if (file_exists($old_image_path)) {

                    unlink($old_image_path);

                }

            }


            header("Location: index.php?success=updated");

            exit;

        } else {

            $error = "Failed to update the product.";

            $stmt->close();


            // Delete newly uploaded image if update failed

            if (
                $image_name !== $current_image
                && !empty($image_name)
            ) {

                $new_image_path =
                    __DIR__
                    . "/../../uploads/products/"
                    . $image_name;

                if (file_exists($new_image_path)) {

                    unlink($new_image_path);

                }

            }

        }

    }

}


require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

?>

<div class="main-content">

    <?php require_once __DIR__ . "/../includes/navbar.php"; ?>

    <main class="page-content">


        <!-- Page Header -->

        <div class="page-header">

            <div>

                <h2>
                    Edit Product
                </h2>

                <p>
                    Update product information.
                </p>

            </div>


            <a
                href="<?= ADMIN_URL ?>products/index.php"
                class="secondary-btn"
            >
                Back to Products
            </a>

        </div>



        <!-- Error -->

        <?php if ($error !== ""): ?>

            <div class="alert alert-danger">

                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>



        <!-- Product Form -->

        <div class="form-card">

            <form
                method="POST"
                enctype="multipart/form-data"
            >


                <!-- Basic Information -->

                <div class="form-section">

                    <div class="form-section-header">

                        <h3>
                            Basic Information
                        </h3>

                        <p>
                            Update the main product information.
                        </p>

                    </div>


                    <div class="form-grid">


                        <!-- Product Name -->

                        <div class="form-group">

                            <label>
                                Product Name
                                <span>*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="<?= htmlspecialchars($name) ?>"
                                placeholder="Enter product name"
                                required
                            >

                        </div>



                        <!-- Category -->

                        <div class="form-group">

                            <label>
                                Category
                                <span>*</span>
                            </label>

                            <select
                                name="category_id"
                                class="form-control"
                                required
                            >

                                <option value="">
                                    Select Category
                                </option>


                                <?php foreach ($categories as $category): ?>

                                    <option
                                        value="<?= (int) $category["id"] ?>"
                                        <?= (
                                            $category_id ===
                                            (int) $category["id"]
                                        )
                                            ? "selected"
                                            : ""
                                        ?>
                                    >

                                        <?= htmlspecialchars(
                                            $category["name"]
                                        ) ?>

                                        <?php if (!empty($category["industry_name"])): ?>

                                            -
                                            <?= htmlspecialchars(
                                                $category["industry_name"]
                                            ) ?>

                                        <?php endif; ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>



                        <!-- Slug -->

                        <div class="form-group">

                            <label>
                                Slug
                            </label>

                            <input
                                type="text"
                                name="slug"
                                class="form-control"
                                value="<?= htmlspecialchars($slug) ?>"
                                placeholder="product-slug"
                            >

                            <div class="form-help">
                                Use a unique English URL-friendly slug.
                            </div>

                        </div>



                        <!-- Origin Country -->

                        <div class="form-group">

                            <label>
                                Origin Country
                            </label>

                            <input
                                type="text"
                                name="origin_country"
                                class="form-control"
                                value="<?= htmlspecialchars($origin_country) ?>"
                                placeholder="Egypt"
                            >

                        </div>


                    </div>

                </div>



                <!-- Descriptions -->

                <div class="form-section">

                    <div class="form-section-header">

                        <h3>
                            Product Description
                        </h3>

                        <p>
                            Update the product description and information.
                        </p>

                    </div>


                    <div class="form-grid">


                        <!-- Short Description -->

                        <div class="form-group">

                            <label>
                                Short Description
                            </label>

                            <textarea
                                name="short_description"
                                class="form-control"
                                rows="4"
                                placeholder="Short description of the product..."
                            ><?= htmlspecialchars($short_description) ?></textarea>

                        </div>



                        <!-- Full Description -->

                        <div class="form-group">

                            <label>
                                Full Description
                            </label>

                            <textarea
                                name="description"
                                class="form-control"
                                rows="6"
                                placeholder="Full product description..."
                            ><?= htmlspecialchars($description) ?></textarea>

                        </div>



                        <!-- Additional Information -->

                        <div class="form-group">

                            <label>
                                Additional Information
                            </label>

                            <textarea
                                name="additional_info"
                                class="form-control"
                                rows="5"
                                placeholder="Additional product information..."
                            ><?= htmlspecialchars($additional_info) ?></textarea>

                        </div>


                    </div>

                </div>



                <!-- Product Image -->

                <div class="form-section">

                    <div class="form-section-header">

                        <h3>
                            Product Image
                        </h3>

                        <p>
                            Update the product image if needed.
                        </p>

                    </div>


                    <div class="form-group">

                        <label>
                            Current Image
                        </label>


                        <?php if (!empty($current_image)): ?>

                            <div class="current-image">

                                <img
                                    src="<?= UPLOADS_URL ?>products/<?= htmlspecialchars($current_image) ?>"
                                    alt="<?= htmlspecialchars($name) ?>"
                                >

                                <span>
                                    Current product image
                                </span>

                            </div>

                        <?php else: ?>

                            <div class="form-help">
                                No image uploaded.
                            </div>

                        <?php endif; ?>


                        <label>
                            Replace Image
                        </label>

                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <div class="form-help">
                            Leave empty to keep the current image.
                            Allowed: JPG, JPEG, PNG, WEBP. Maximum size: 5 MB.
                        </div>

                    </div>

                </div>



                <!-- Settings -->

                <div class="form-section">

                    <div class="form-section-header">

                        <h3>
                            Product Settings
                        </h3>

                        <p>
                            Manage export availability and product visibility.
                        </p>

                    </div>


                    <div class="form-grid">


                        <!-- Export Available -->

                        <div class="form-group">

                            <label>
                                Export Available
                            </label>

                            <select
                                name="export_available"
                                class="form-control"
                            >

                                <option
                                    value="1"
                                    <?= $export_available === 1
                                        ? "selected"
                                        : ""
                                    ?>
                                >
                                    Yes
                                </option>

                                <option
                                    value="0"
                                    <?= $export_available === 0
                                        ? "selected"
                                        : ""
                                    ?>
                                >
                                    No
                                </option>

                            </select>

                        </div>



                        <!-- Featured -->

                        <div class="form-group">

                            <label>
                                Featured Product
                            </label>

                            <select
                                name="featured"
                                class="form-control"
                            >

                                <option
                                    value="0"
                                    <?= $featured === 0
                                        ? "selected"
                                        : ""
                                    ?>
                                >
                                    No
                                </option>

                                <option
                                    value="1"
                                    <?= $featured === 1
                                        ? "selected"
                                        : ""
                                    ?>
                                >
                                    Yes
                                </option>

                            </select>

                        </div>



                        <!-- Sort Order -->

                        <div class="form-group">

                            <label>
                                Sort Order
                            </label>

                            <input
                                type="number"
                                name="sort_order"
                                class="form-control"
                                value="<?= (int) $sort_order ?>"
                                min="0"
                            >

                        </div>



                        <!-- Status -->

                        <div class="form-group">

                            <label>
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-control"
                            >

                                <option
                                    value="active"
                                    <?= $status === "active"
                                        ? "selected"
                                        : ""
                                    ?>
                                >
                                    Active
                                </option>

                                <option
                                    value="inactive"
                                    <?= $status === "inactive"
                                        ? "selected"
                                        : ""
                                    ?>
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>


                    </div>

                </div>



                <!-- Actions -->

                <div class="form-actions">

                    <a
                        href="<?= ADMIN_URL ?>products/index.php"
                        class="secondary-btn"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="page-btn"
                    >
                        Update Product
                    </button>

                </div>


            </form>

        </div>


    </main>

</div>


<?php

require_once __DIR__ . "/../includes/footer.php";

?>