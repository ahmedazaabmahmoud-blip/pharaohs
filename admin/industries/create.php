<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Add Industry";


// Form Values

$name = "";
$slug = "";
$short_description = "";
$description = "";
$icon = "";
$sort_order = 0;
$status = "active";


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


// Handle Form Submit

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");

    $slug = trim($_POST["slug"] ?? "");

    $short_description = trim(
        $_POST["short_description"] ?? ""
    );

    $description = trim(
        $_POST["description"] ?? ""
    );

    $icon = trim($_POST["icon"] ?? "");

    $sort_order = (int) ($_POST["sort_order"] ?? 0);

    $status = $_POST["status"] ?? "active";


    // Validate Name

    if ($name === "") {

        $errors[] = "Industry name is required.";

    }


    // Generate Slug Automatically

    if ($slug === "") {

        $slug = generateSlug($name);

    }


    // Validate Slug

    if ($slug === "") {

        $errors[] = "A valid slug is required.";

    }


    // Validate Status

    if (!in_array($status, ["active", "inactive"], true)) {

        $status = "active";

    }


    // Check Slug Exists

    if (empty($errors)) {

        $stmt = $conn->prepare(
            "SELECT id FROM industries WHERE slug = ? LIMIT 1"
        );

        $stmt->bind_param("s", $slug);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $errors[] = "This slug already exists. Please use another one.";

        }

        $stmt->close();

    }


    // Upload Image

    $image = null;


    if (
        empty($errors) &&
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {

            $errors[] = "There was an error uploading the image.";

        } else {

            $allowed_extensions = [
                "jpg",
                "jpeg",
                "png",
                "webp"
            ];

            $file_name = $_FILES["image"]["name"];

            $file_tmp = $_FILES["image"]["tmp_name"];

            $file_size = $_FILES["image"]["size"];

            $extension = strtolower(
                pathinfo($file_name, PATHINFO_EXTENSION)
            );


            // Check Extension

            if (!in_array($extension, $allowed_extensions, true)) {

                $errors[] = "Only JPG, JPEG, PNG and WEBP images are allowed.";

            }


            // Check Size - 5MB

            if ($file_size > 5 * 1024 * 1024) {

                $errors[] = "Image size must not exceed 5MB.";

            }


            // Upload

            if (empty($errors)) {

                $upload_dir = __DIR__ . "/../../uploads/industries/";


                if (!is_dir($upload_dir)) {

                    mkdir(
                        $upload_dir,
                        0777,
                        true
                    );

                }


                $new_file_name =
                    uniqid("industry_", true)
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

                    $image = $new_file_name;

                } else {

                    $errors[] =
                        "Failed to upload the image.";

                }

            }

        }

    }


    // Insert Industry

    if (empty($errors)) {

        $stmt = $conn->prepare("
            INSERT INTO industries
            (
                name,
                slug,
                short_description,
                description,
                image,
                icon,
                sort_order,
                status
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");


        $stmt->bind_param(
            "ssssssis",
            $name,
            $slug,
            $short_description,
            $description,
            $image,
            $icon,
            $sort_order,
            $status
        );


        if ($stmt->execute()) {

            $stmt->close();

            header(
                "Location: index.php?success=created"
            );

            exit;

        } else {

            if ($image) {

                $uploaded_file =
                    __DIR__
                    . "/../../uploads/industries/"
                    . $image;

                if (file_exists($uploaded_file)) {

                    unlink($uploaded_file);

                }

            }


            $errors[] =
                "Failed to create the industry.";

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
                    Add Industry
                </h2>

                <p>
                    Create a new industry for your website.
                </p>

            </div>


            <a
                href="<?= ADMIN_URL ?>industries/index.php"
                class="secondary-btn"
            >
                ← Back to Industries
            </a>

        </div>



        <!-- Errors -->

        <?php if (!empty($errors)): ?>

            <div class="alert alert-danger">

                <ul style="margin: 0; padding-left: 20px;">

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
                            Enter the main information about the industry.
                        </p>

                    </div>


                    <div class="form-grid">


                        <!-- Name -->

                        <div class="form-group">

                            <label>

                                <span>
                                    Industry Name
                                </span>

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="<?= htmlspecialchars($name) ?>"
                                placeholder="e.g. Agriculture & Food"
                                required
                            >

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
                                placeholder="e.g. agriculture-food"
                            >

                            <div class="form-help">

                                Leave empty to generate automatically.

                            </div>

                        </div>



                        <!-- Short Description -->

                        <div class="form-group">

                            <label>

                                <span>
                                    Short Description
                                </span>

                            </label>

                            <textarea
                                name="short_description"
                                class="form-control"
                                rows="4"
                                placeholder="Short description of this industry..."
                            ><?= htmlspecialchars($short_description) ?></textarea>

                        </div>



                        <!-- Icon -->

                        <div class="form-group">

                            <label>

                                <span>
                                    Icon
                                </span>

                            </label>

                            <input
                                type="text"
                                name="icon"
                                class="form-control"
                                value="<?= htmlspecialchars($icon) ?>"
                                placeholder="e.g. 🌾"
                            >

                            <div class="form-help">

                                You can enter an emoji or icon class.

                            </div>

                        </div>


                    </div>

                </div>



                <!-- Description -->

                <div class="form-section">

                    <div class="form-section-header">

                        <h3>
                            Full Description
                        </h3>

                        <p>
                            Add detailed information about this industry.
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
                            placeholder="Write the full industry description..."
                        ><?= htmlspecialchars($description) ?></textarea>

                    </div>

                </div>



                <!-- Image -->

                <div class="form-section">

                    <div class="form-section-header">

                        <h3>
                            Industry Image
                        </h3>

                        <p>
                            Upload an image for this industry.
                        </p>

                    </div>


                    <div class="form-group">

                        <label>

                            <span>
                                Image
                            </span>

                        </label>

                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <div class="form-help">

                            JPG, JPEG, PNG or WEBP — Maximum 5MB.

                        </div>

                    </div>

                </div>



                <!-- Settings -->

                <div class="form-section">

                    <div class="form-section-header">

                        <h3>
                            Settings
                        </h3>

                        <p>
                            Control the display order and status.
                        </p>

                    </div>


                    <div class="form-grid">


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



                        <!-- Status -->

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
                                    <?= $status === "active" ? "selected" : "" ?>
                                >
                                    Active
                                </option>

                                <option
                                    value="inactive"
                                    <?= $status === "inactive" ? "selected" : "" ?>
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
                        href="<?= ADMIN_URL ?>industries/index.php"
                        class="secondary-btn"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="page-btn"
                    >
                        Create Industry
                    </button>

                </div>


            </form>

        </div>


    </main>

</div>


<?php

require_once __DIR__ . "/../includes/footer.php";

?>