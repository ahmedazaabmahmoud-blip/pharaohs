<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Edit Category";


// Get Category ID

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}


// Get Category

$stmt = $conn->prepare("
    SELECT *
    FROM categories
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

$category = $result->fetch_assoc();

$stmt->close();


// Form Values

$name = $category["name"];
$slug = $category["slug"];
$industry_id = (int) ($category["industry_id"] ?? 0);
$description = $category["description"] ?? "";
$sort_order = (int) $category["sort_order"];
$status = $category["status"];
$current_image = $category["image"] ?? "";


// Errors

$errors = [];


// Generate Slug

function generateSlug($text)
{
    $text = trim($text);

    $text = strtolower($text);

    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);

    $text = trim($text, '-');

    return $text;
}


// Get Industries

$industries = [];

$result = $conn->query("
    SELECT id, name
    FROM industries
    WHERE status = 'active'
    ORDER BY sort_order ASC, name ASC
");

if ($result) {

    while ($row = $result->fetch_assoc()) {

        $industries[] = $row;

    }

}


// Handle Form Submit

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");

    $slug = trim($_POST["slug"] ?? "");

    $industry_id = (int) ($_POST["industry_id"] ?? 0);

    $description = trim(
        $_POST["description"] ?? ""
    );

    $sort_order = (int) (
        $_POST["sort_order"] ?? 0
    );

    $status = $_POST["status"] ?? "active";


    // Validate Name

    if ($name === "") {

        $errors[] =
            "Category name is required.";

    }


    // Generate Slug

    if ($slug === "") {

        $slug = generateSlug($name);

    }


    // Validate Slug

    if ($slug === "") {

        $errors[] =
            "A valid slug is required.";

    }


    // Validate Industry

    if ($industry_id <= 0) {

        $errors[] =
            "Please select an industry.";

    } else {

        $stmt = $conn->prepare("
            SELECT id
            FROM industries
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->bind_param(
            "i",
            $industry_id
        );

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {

            $errors[] =
                "Selected industry does not exist.";

        }

        $stmt->close();

    }


    // Validate Status

    if (
        !in_array(
            $status,
            ["active", "inactive"],
            true
        )
    ) {

        $status = "active";

    }


    // Check Slug Exists

    if (empty($errors)) {

        $stmt = $conn->prepare("
            SELECT id
            FROM categories
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

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $errors[] =
                "This slug already exists. Please use another one.";

        }

        $stmt->close();

    }


    // Image

    $new_image = $current_image;


    // Upload New Image

    if (
        empty($errors) &&
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {

        if (
            $_FILES["image"]["error"] !==
            UPLOAD_ERR_OK
        ) {

            $errors[] =
                "There was an error uploading the image.";

        } else {

            $allowed_extensions = [
                "jpg",
                "jpeg",
                "png",
                "webp"
            ];


            $file_name =
                $_FILES["image"]["name"];


            $file_tmp =
                $_FILES["image"]["tmp_name"];


            $file_size =
                $_FILES["image"]["size"];


            $extension = strtolower(
                pathinfo(
                    $file_name,
                    PATHINFO_EXTENSION
                )
            );


            // Check Extension

            if (
                !in_array(
                    $extension,
                    $allowed_extensions,
                    true
                )
            ) {

                $errors[] =
                    "Only JPG, JPEG, PNG and WEBP images are allowed.";

            }


            // Check Size

            if (
                $file_size >
                5 * 1024 * 1024
            ) {

                $errors[] =
                    "Image size must not exceed 5MB.";

            }


            // Upload

            if (empty($errors)) {

                $upload_dir =
                    __DIR__
                    . "/../../uploads/categories/";


                if (!is_dir($upload_dir)) {

                    mkdir(
                        $upload_dir,
                        0777,
                        true
                    );

                }


                $new_file_name =
                    uniqid(
                        "category_",
                        true
                    )
                    . "."
                    . $extension;


                $upload_path =
                    $upload_dir
                    . $new_file_name;


                if (
                    move_uploaded_file(
                        $file_tmp,
                        $upload_path
                    )
                ) {

                    $new_image =
                        $new_file_name;

                } else {

                    $errors[] =
                        "Failed to upload the image.";

                }

            }

        }

    }


    // Update Category

    if (empty($errors)) {

        $stmt = $conn->prepare("
            UPDATE categories
            SET
                industry_id = ?,
                name = ?,
                slug = ?,
                description = ?,
                image = ?,
                sort_order = ?,
                status = ?
            WHERE id = ?
        ");


        $stmt->bind_param(
            "issssisi",
            $industry_id,
            $name,
            $slug,
            $description,
            $new_image,
            $sort_order,
            $status,
            $id
        );


        if ($stmt->execute()) {

            $stmt->close();


            // Delete Old Image

            if (
                $new_image !== $current_image &&
                !empty($current_image)
            ) {

                $old_image_path =
                    __DIR__
                    . "/../../uploads/categories/"
                    . $current_image;


                if (
                    file_exists($old_image_path)
                ) {

                    unlink($old_image_path);

                }

            }


            header(
                "Location: index.php?success=updated"
            );

            exit;

        } else {

            // Delete New Uploaded Image

            if (
                $new_image !== $current_image &&
                !empty($new_image)
            ) {

                $new_image_path =
                    __DIR__
                    . "/../../uploads/categories/"
                    . $new_image;


                if (
                    file_exists($new_image_path)
                ) {

                    unlink($new_image_path);

                }

            }


            $errors[] =
                "Failed to update the category.";

        }


        $stmt->close();

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
                    Edit Category
                </h2>

                <p>
                    Update the information of this category.
                </p>

            </div>


            <a
                href="<?= ADMIN_URL ?>categories/index.php"
                class="secondary-btn"
            >
                ← Back to Categories
            </a>

        </div>



        <!-- Errors -->

        <?php if (!empty($errors)): ?>

            <div class="alert alert-danger">

                <ul
                    style="
                        margin: 0;
                        padding-left: 20px;
                    "
                >

                    <?php foreach ($errors as $error): ?>

                        <li>
                            <?= htmlspecialchars($error) ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>



        <!-- Form -->

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
                            Update the category information.
                        </p>

                    </div>


                    <div class="form-grid">


                        <!-- Category Name -->

                        <div class="form-group">

                            <label>

                                <span>
                                    Category Name
                                </span>

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="<?= htmlspecialchars($name) ?>"
                                placeholder="e.g. Medical Supplies"
                                required
                            >

                        </div>



                        <!-- Industry -->

                        <div class="form-group">

                            <label>

                                <span>
                                    Industry
                                </span>

                            </label>

                            <select
                                name="industry_id"
                                class="form-control"
                                required
                            >

                                <option value="">
                                    Select Industry
                                </option>


                                <?php foreach (
                                    $industries as $industry
                                ): ?>

                                    <option
                                        value="<?= (int) $industry["id"] ?>"
                                        <?= (
                                            $industry_id ===
                                            (int) $industry["id"]
                                        )
                                            ? "selected"
                                            : ""
                                        ?>
                                    >

                                        <?= htmlspecialchars(
                                            $industry["name"]
                                        ) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>



                        <!-- Slug -->

                        <div class="form-group">

                            <label>

                                <span>
                                    Slug
                                </span>

                            </label>

                            <input
                                type="text"
                                name="slug"
                                class="form-control"
                                value="<?= htmlspecialchars($slug) ?>"
                                placeholder="e.g. medical-supplies"
                            >

                        </div>



                        <!-- Sort Order -->

                        <div class="form-group">

                            <label>

                                <span>
                                    Sort Order
                                </span>

                            </label>

                            <input
                                type="number"
                                name="sort_order"
                                class="form-control"
                                value="<?= (int) $sort_order ?>"
                                min="0"
                            >

                            <div class="form-help">

                                Lower numbers appear first.

                            </div>

                        </div>


                    </div>

                </div>



                <!-- Description -->

                <div class="form-section">

                    <div class="form-section-header">

                        <h3>
                            Description
                        </h3>

                        <p>
                            Update the category description.
                        </p>

                    </div>


                    <div class="form-group">

                        <label>

                            <span>
                                Description
                            </span>

                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="8"
                            placeholder="Write the category description..."
                        ><?= htmlspecialchars($description) ?></textarea>

                    </div>

                </div>



                <!-- Image -->

                <div class="form-section">

                    <div class="form-section-header">

                        <h3>
                            Category Image
                        </h3>

                        <p>
                            Update the category image if needed.
                        </p>

                    </div>


                    <div class="form-group">


                        <?php if (!empty($current_image)): ?>

                            <label>

                                <span>
                                    Current Image
                                </span>

                            </label>


                            <div class="current-image">

                                <img
                                    src="<?= UPLOADS_URL ?>categories/<?= htmlspecialchars($current_image) ?>"
                                    alt="<?= htmlspecialchars($name) ?>"
                                >

                                <span>
                                    Current image
                                </span>

                            </div>

                        <?php endif; ?>


                        <label>

                            <span>
                                Upload New Image
                            </span>

                        </label>


                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                        >


                        <div class="form-help">

                            Leave empty to keep the current image.
                            JPG, JPEG, PNG or WEBP — Maximum 5MB.

                        </div>

                    </div>

                </div>



                <!-- Status -->

                <div class="form-section">

                    <div class="form-section-header">

                        <h3>
                            Status
                        </h3>

                        <p>
                            Control whether this category is active.
                        </p>

                    </div>


                    <div class="form-group">

                        <label>

                            <span>
                                Status
                            </span>

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



                <!-- Actions -->

                <div class="form-actions">

                    <a
                        href="<?= ADMIN_URL ?>categories/index.php"
                        class="secondary-btn"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="page-btn"
                    >
                        Update Category
                    </button>

                </div>


            </form>

        </div>


    </main>

</div>


<?php

require_once __DIR__ . "/../includes/footer.php";

?>