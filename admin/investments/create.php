<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Add Investment";

$errors = [];

/*
|--------------------------------------------------------------------------
| Create Investment
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $type = trim($_POST["type"] ?? "investment");
    $short_description = trim($_POST["short_description"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $location = trim($_POST["location"] ?? "");
    $status = trim($_POST["status"] ?? "open");
    $featured = isset($_POST["featured"]) ? 1 : 0;
    $sort_order = (int) ($_POST["sort_order"] ?? 0);

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if ($title === "") {
        $errors[] = "Title is required.";
    }

    if ($slug === "") {
        $errors[] = "Slug is required.";
    }

    if ($type === "") {
        $errors[] = "Type is required.";
    }

    $allowed_types = [
        "investment",
        "project",
        "partnership",
        "commercial",
        "cooperation"
    ];

    if (!in_array($type, $allowed_types, true)) {
        $errors[] = "Invalid investment type.";
    }

    $allowed_statuses = [
        "open",
        "closed",
        "coming_soon",
        "inactive"
    ];

    if (!in_array($status, $allowed_statuses, true)) {
        $errors[] = "Invalid status.";
    }

    /*
    |--------------------------------------------------------------------------
    | Check Slug
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $stmt = $conn->prepare("
            SELECT id
            FROM investment_opportunities
            WHERE slug = ?
            LIMIT 1
        ");

        $stmt->bind_param("s", $slug);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "This slug already exists.";
        }

        $stmt->close();
    }

    /*
    |--------------------------------------------------------------------------
    | Image Upload
    |--------------------------------------------------------------------------
    */

    $image_name = "";

    if (
        empty($errors) &&
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {

            $errors[] = "Failed to upload the image.";

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

            if (!in_array($extension, $allowed_extensions, true)) {

                $errors[] =
                    "Invalid image format. Allowed formats: JPG, JPEG, PNG, WEBP.";

            }

            if ($file_size > 5 * 1024 * 1024) {

                $errors[] =
                    "Image size must not exceed 5MB.";

            }

            if (empty($errors)) {

                $upload_directory =
                    __DIR__ . "/../../uploads/investments/";

                if (!is_dir($upload_directory)) {
                    mkdir($upload_directory, 0777, true);
                }

                $image_name =
                    time()
                    . "_"
                    . uniqid()
                    . "."
                    . $extension;

                $upload_path =
                    $upload_directory
                    . $image_name;

                if (!move_uploaded_file($file_tmp, $upload_path)) {

                    $errors[] =
                        "Failed to save the uploaded image.";

                    $image_name = "";
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Insert Investment
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $stmt = $conn->prepare("
            INSERT INTO investment_opportunities
            (
                title,
                slug,
                type,
                short_description,
                description,
                location,
                image,
                status,
                featured,
                sort_order
            )
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssssssii",
            $title,
            $slug,
            $type,
            $short_description,
            $description,
            $location,
            $image_name,
            $status,
            $featured,
            $sort_order
        );

        if ($stmt->execute()) {

            $stmt->close();

            header("Location: index.php?success=created");
            exit;

        } else {

            if (!empty($image_name)) {

                $image_path =
                    __DIR__
                    . "/../../uploads/investments/"
                    . $image_name;

                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            $errors[] =
                "Failed to create the investment opportunity.";

            $stmt->close();
        }
    }
}

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="main-content">

    <?php require_once __DIR__ . "/../includes/navbar.php"; ?>

    <main class="page-content">

        <div class="page-header">

            <div>

                <h2>Add Investment</h2>

                <p>
                    Create a new investment opportunity, project or partnership.
                </p>

            </div>

            <a
                href="<?= ADMIN_URL ?>investments/index.php"
                class="secondary-btn"
            >
                ← Back to Investments
            </a>

        </div>


        <?php if (!empty($errors)): ?>

            <div class="alert alert-danger">

                <?php foreach ($errors as $error): ?>

                    <div>
                        <?= htmlspecialchars($error) ?>
                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <form
            method="POST"
            enctype="multipart/form-data"
        >

            <div class="form-card">

                <div class="form-section">

                    <div class="form-section-header">

                        <h3>Basic Information</h3>

                    </div>


                    <div class="form-grid">

                        <div class="form-group">

                            <label>
                                Title <span>*</span>
                            </label>

                            <input
                                type="text"
                                name="title"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST["title"] ?? "") ?>"
                                placeholder="Investment opportunity title"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label>
                                Slug <span>*</span>
                            </label>

                            <input
                                type="text"
                                name="slug"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST["slug"] ?? "") ?>"
                                placeholder="investment-opportunity-title"
                                required
                            >

                            <div class="form-help">
                                Example: date-export-investment
                            </div>

                        </div>


                        <div class="form-group">

                            <label>
                                Type <span>*</span>
                            </label>

                            <select
                                name="type"
                                class="form-control"
                                required
                            >

                                <option
                                    value="investment"
                                    <?= (($_POST["type"] ?? "investment") === "investment") ? "selected" : "" ?>
                                >
                                    Investment
                                </option>

                                <option
                                    value="project"
                                    <?= (($_POST["type"] ?? "") === "project") ? "selected" : "" ?>
                                >
                                    Project
                                </option>

                                <option
                                    value="partnership"
                                    <?= (($_POST["type"] ?? "") === "partnership") ? "selected" : "" ?>
                                >
                                    Partnership
                                </option>

                                <option
                                    value="commercial"
                                    <?= (($_POST["type"] ?? "") === "commercial") ? "selected" : "" ?>
                                >
                                    Commercial
                                </option>

                                <option
                                    value="cooperation"
                                    <?= (($_POST["type"] ?? "") === "cooperation") ? "selected" : "" ?>
                                >
                                    Cooperation
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label>
                                Location
                            </label>

                            <input
                                type="text"
                                name="location"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST["location"] ?? "") ?>"
                                placeholder="Egypt / Cairo / Saudi Arabia..."
                            >

                        </div>

                    </div>

                </div>


                <div class="form-section">

                    <div class="form-section-header">

                        <h3>Descriptions</h3>

                    </div>


                    <div class="form-group">

                        <label>
                            Short Description
                        </label>

                        <textarea
                            name="short_description"
                            class="form-control"
                            rows="4"
                            placeholder="Short description of the opportunity..."
                        ><?= htmlspecialchars($_POST["short_description"] ?? "") ?></textarea>

                    </div>


                    <div class="form-group">

                        <label>
                            Full Description
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="8"
                            placeholder="Detailed description of the investment opportunity..."
                        ><?= htmlspecialchars($_POST["description"] ?? "") ?></textarea>

                    </div>

                </div>


                <div class="form-section">

                    <div class="form-section-header">

                        <h3>Image</h3>

                    </div>


                    <div class="form-group">

                        <label>
                            Investment Image
                        </label>

                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <div class="form-help">
                            Maximum size: 5MB. Allowed: JPG, JPEG, PNG, WEBP.
                        </div>

                    </div>

                </div>


                <div class="form-section">

                    <div class="form-section-header">

                        <h3>Settings</h3>

                    </div>


                    <div class="form-grid">

                        <div class="form-group">

                            <label>
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-control"
                            >

                                <option
                                    value="open"
                                    <?= (($_POST["status"] ?? "open") === "open") ? "selected" : "" ?>
                                >
                                    Open
                                </option>

                                <option
                                    value="coming_soon"
                                    <?= (($_POST["status"] ?? "") === "coming_soon") ? "selected" : "" ?>
                                >
                                    Coming Soon
                                </option>

                                <option
                                    value="closed"
                                    <?= (($_POST["status"] ?? "") === "closed") ? "selected" : "" ?>
                                >
                                    Closed
                                </option>

                                <option
                                    value="inactive"
                                    <?= (($_POST["status"] ?? "") === "inactive") ? "selected" : "" ?>
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label>
                                Sort Order
                            </label>

                            <input
                                type="number"
                                name="sort_order"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST["sort_order"] ?? "0") ?>"
                                min="0"
                            >

                            <div class="form-help">
                                Lower numbers appear first.
                            </div>

                        </div>


                        <div class="form-group">

                            <label>
                                Featured
                            </label>

                            <label
                                style="
                                    display:flex;
                                    align-items:center;
                                    gap:8px;
                                    margin-top:10px;
                                    cursor:pointer;
                                "
                            >

                                <input
                                    type="checkbox"
                                    name="featured"
                                    value="1"
                                    <?= isset($_POST["featured"]) ? "checked" : "" ?>
                                >

                                <span>
                                    Show this opportunity as featured
                                </span>

                            </label>

                        </div>

                    </div>

                </div>


                <div class="form-actions">

                    <a
                        href="<?= ADMIN_URL ?>investments/index.php"
                        class="secondary-btn"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="page-btn"
                    >
                        Create Investment
                    </button>

                </div>

            </div>

        </form>

    </main>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>