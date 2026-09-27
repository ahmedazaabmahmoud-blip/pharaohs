<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Add Product";


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
    ORDER BY categories.sort_order ASC, categories.name ASC
");

if ($result) {

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

}


// Form Variables

$name = "";
$category_id = "";
$slug = "";
$short_description = "";
$description = "";
$origin_country = "Egypt";
$export_available = 1;
$additional_info = "";
$featured = 0;
$sort_order = 0;
$status = "active";

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
            LIMIT 1
        ");

        $stmt->bind_param("s", $slug);

        $stmt->execute();

        $slug_result = $stmt->get_result();

        if ($slug_result->num_rows > 0) {

            $error = "This slug already exists.";

        }

        $stmt->close();

    }


    // Image Upload

    $image_name = "";

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
                pathinfo($original_name, PATHINFO_EXTENSION)
            );


            if (!in_array($extension, $allowed_extensions, true)) {

                $error = "Invalid image type. Allowed: JPG, JPEG, PNG, WEBP.";

            } elseif ($file_size > 5 * 1024 * 1024) {

                $error = "Image size must not exceed 5 MB.";

            } else {

                $image_name =
                    uniqid("product_", true)
                    . "."
                    . $extension;


                $destination =
                    $upload_dir
                    . $image_name;


                if (!move_uploaded_file(
                    $tmp_name,
                    $destination
                )) {

                    $error = "Failed to save the image.";

                }

            }

        }

    }


    // Insert Product

    if ($error === "") {

        $stmt = $conn->prepare("
            INSERT INTO products
            (
                category_id,
                name,
                slug,
                short_description,
                description,
                image,
                origin_country,
                export_available,
                additional_info,
                featured,
                sort_order,
                status
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");


        $stmt->bind_param(
            "isssssisiiis",
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
            $status
        );


        if ($stmt->execute()) {

            $stmt->close();

            header("Location: index.php?success=created");

            exit;

        } else {

            $error = "Failed to create the product.";

            $stmt->close();


            // Delete uploaded image if database insert failed

            if (!empty($image_name)) {

                $image_path =
                    __DIR__
                    . "/../../uploads/products/"
                    . $image_name;

                if (file_exists($image_path)) {

                    unlink($image_path);

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
                    Add Product
                </h2>

                <p>
                    Add a new product to your website.
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
                            Enter the main product information.
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
                                            (int) $category_id ===
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
                                Leave empty to generate automatically from the product name.
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
                            Add short and detailed information about the product.
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



                        <!-- Description -->

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



                        <!-- Additional Info -->

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
                            Upload an image for this product.
                        </p>

                    </div>


                    <div class="form-group">

                        <label>
                            Product Image
                        </label>

                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <div class="form-help">
                            Allowed formats: JPG, JPEG, PNG, WEBP. Maximum size: 5 MB.
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
                            Control export availability, featured status and display order.
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
                        Save Product
                    </button>

                </div>


            </form>

        </div>


    </main>

</div>


<?php

require_once __DIR__ . "/../includes/footer.php";

?>