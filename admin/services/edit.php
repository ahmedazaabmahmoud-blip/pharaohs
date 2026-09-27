<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Edit Service";

$error = "";
$success = "";


/*
|--------------------------------------------------------------------------
| Get Service ID
|--------------------------------------------------------------------------
*/

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {

    header("Location: index.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| Load Service
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        id,
        title,
        slug,
        short_description,
        description,
        details_title,
        details_description,
        feature_1,
        feature_2,
        feature_3,
        feature_4,
        process_1_title,
        process_1_description,
        process_2_title,
        process_2_description,
        process_3_title,
        process_3_description,
        process_4_title,
        process_4_description,
        image,
        icon,
        sort_order,
        status
    FROM services
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

$service = $result->fetch_assoc();

$stmt->close();


if (!$service) {

    header("Location: index.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| Generate Slug
|--------------------------------------------------------------------------
*/

function generateSlug($text)
{
    $text = strtolower(trim($text));

    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);

    $text = trim($text, '-');

    return $text;
}


/*
|--------------------------------------------------------------------------
| Handle Form Submission
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");

    $slug = trim($_POST["slug"] ?? "");

    $short_description = trim(
        $_POST["short_description"] ?? ""
    );

    $description = trim(
        $_POST["description"] ?? ""
    );


    /*
    |--------------------------------------------------------------------------
    | Service Details Content
    |--------------------------------------------------------------------------
    */

    $details_title = trim(
        $_POST["details_title"] ?? ""
    );

    $details_description = trim(
        $_POST["details_description"] ?? ""
    );

    $feature_1 = trim(
        $_POST["feature_1"] ?? ""
    );

    $feature_2 = trim(
        $_POST["feature_2"] ?? ""
    );

    $feature_3 = trim(
        $_POST["feature_3"] ?? ""
    );

    $feature_4 = trim(
        $_POST["feature_4"] ?? ""
    );


    /*
    |--------------------------------------------------------------------------
    | Service Process Content
    |--------------------------------------------------------------------------
    */

    $process_1_title = trim(
        $_POST["process_1_title"] ?? ""
    );

    $process_1_description = trim(
        $_POST["process_1_description"] ?? ""
    );

    $process_2_title = trim(
        $_POST["process_2_title"] ?? ""
    );

    $process_2_description = trim(
        $_POST["process_2_description"] ?? ""
    );

    $process_3_title = trim(
        $_POST["process_3_title"] ?? ""
    );

    $process_3_description = trim(
        $_POST["process_3_description"] ?? ""
    );

    $process_4_title = trim(
        $_POST["process_4_title"] ?? ""
    );

    $process_4_description = trim(
        $_POST["process_4_description"] ?? ""
    );


    $icon = trim($_POST["icon"] ?? "");

    $sort_order = (int) ($_POST["sort_order"] ?? 0);

    $status = $_POST["status"] ?? "active";


    /*
    |--------------------------------------------------------------------------
    | Keep Existing Image
    |--------------------------------------------------------------------------
    */

    $image_name = $service["image"];


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if ($title === "") {

        $error = "Service title is required.";

    } else {


        /*
        |--------------------------------------------------------------------------
        | Generate Slug Automatically
        |--------------------------------------------------------------------------
        */

        if ($slug === "") {

            $slug = generateSlug($title);

        }


        if ($slug === "") {

            $error = "Please enter a valid service title.";

        }


        /*
        |--------------------------------------------------------------------------
        | Validate Status
        |--------------------------------------------------------------------------
        */

        if (
            $status !== "active" &&
            $status !== "inactive"
        ) {

            $status = "active";

        }


        /*
        |--------------------------------------------------------------------------
        | Check Duplicate Slug
        |--------------------------------------------------------------------------
        */

        if ($error === "") {

            $check = $conn->prepare("
                SELECT id
                FROM services
                WHERE slug = ?
                AND id != ?
                LIMIT 1
            ");

            $check->bind_param(
                "si",
                $slug,
                $id
            );

            $check->execute();

            $check_result = $check->get_result();

            if ($check_result->num_rows > 0) {

                $error =
                    "This slug already exists. Please use another slug.";

            }

            $check->close();

        }


        /*
        |--------------------------------------------------------------------------
        | Image Upload
        |--------------------------------------------------------------------------
        */

        $new_image_uploaded = false;


        if (
            $error === "" &&
            isset($_FILES["image"]) &&
            $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
        ) {

            $file = $_FILES["image"];


            if ($file["error"] !== UPLOAD_ERR_OK) {

                $error =
                    "There was an error uploading the image.";

            } else {

                $allowed_types = [
                    "image/jpeg",
                    "image/png",
                    "image/webp"
                ];


                $finfo = finfo_open(FILEINFO_MIME_TYPE);

                $mime_type = finfo_file(
                    $finfo,
                    $file["tmp_name"]
                );

                finfo_close($finfo);


                if (!in_array($mime_type, $allowed_types)) {

                    $error =
                        "Only JPG, PNG and WEBP images are allowed.";

                }


                if (
                    $error === "" &&
                    $file["size"] > 5 * 1024 * 1024
                ) {

                    $error =
                        "Image size must not exceed 5 MB.";

                }


                if ($error === "") {

                    $upload_directory =
                        __DIR__ . "/../../uploads/services/";


                    if (!is_dir($upload_directory)) {

                        mkdir(
                            $upload_directory,
                            0755,
                            true
                        );

                    }


                    $extension = strtolower(
                        pathinfo(
                            $file["name"],
                            PATHINFO_EXTENSION
                        )
                    );


                    $new_filename =
                        uniqid("service_", true)
                        . "."
                        . $extension;


                    $destination =
                        $upload_directory
                        . $new_filename;


                    if (
                        move_uploaded_file(
                            $file["tmp_name"],
                            $destination
                        )
                    ) {

                        $image_name = $new_filename;

                        $new_image_uploaded = true;

                    } else {

                        $error =
                            "Failed to save the uploaded image.";

                    }

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Update Service
        |--------------------------------------------------------------------------
        */

        if ($error === "") {

            $stmt = $conn->prepare("
                UPDATE services
                SET
                    title = ?,
                    slug = ?,
                    short_description = ?,
                    description = ?,
                    details_title = ?,
                    details_description = ?,
                    feature_1 = ?,
                    feature_2 = ?,
                    feature_3 = ?,
                    feature_4 = ?,
                    process_1_title = ?,
                    process_1_description = ?,
                    process_2_title = ?,
                    process_2_description = ?,
                    process_3_title = ?,
                    process_3_description = ?,
                    process_4_title = ?,
                    process_4_description = ?,
                    image = ?,
                    icon = ?,
                    sort_order = ?,
                    status = ?
                WHERE id = ?
            ");


            $stmt->bind_param(
                "ssssssssssssssssssssisi",
                $title,
                $slug,
                $short_description,
                $description,
                $details_title,
                $details_description,
                $feature_1,
                $feature_2,
                $feature_3,
                $feature_4,
                $process_1_title,
                $process_1_description,
                $process_2_title,
                $process_2_description,
                $process_3_title,
                $process_3_description,
                $process_4_title,
                $process_4_description,
                $image_name,
                $icon,
                $sort_order,
                $status,
                $id
            );


            if ($stmt->execute()) {


                /*
                |--------------------------------------------------------------------------
                | Delete Old Image
                |--------------------------------------------------------------------------
                */

                if (
                    $new_image_uploaded &&
                    !empty($service["image"]) &&
                    $service["image"] !== $image_name
                ) {

                    $old_image =
                        __DIR__
                        . "/../../uploads/services/"
                        . $service["image"];


                    if (file_exists($old_image)) {

                        unlink($old_image);

                    }

                }


                $stmt->close();


                header(
                    "Location: index.php?success=updated"
                );

                exit;


            } else {


                /*
                |--------------------------------------------------------------------------
                | Remove New Image If Database Update Failed
                |--------------------------------------------------------------------------
                */

                if ($new_image_uploaded) {

                    $new_uploaded_file =
                        __DIR__
                        . "/../../uploads/services/"
                        . $image_name;


                    if (file_exists($new_uploaded_file)) {

                        unlink($new_uploaded_file);

                    }

                }


                $error =
                    "Failed to update service: "
                    . $stmt->error;

                $stmt->close();

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Keep Submitted Values After Error
    |--------------------------------------------------------------------------
    */

    if ($error !== "") {

        $service["title"] = $title;
        $service["slug"] = $slug;
        $service["short_description"] = $short_description;
        $service["description"] = $description;

        $service["details_title"] = $details_title;
        $service["details_description"] = $details_description;

        $service["feature_1"] = $feature_1;
        $service["feature_2"] = $feature_2;
        $service["feature_3"] = $feature_3;
        $service["feature_4"] = $feature_4;

        $service["process_1_title"] = $process_1_title;
        $service["process_1_description"] = $process_1_description;

        $service["process_2_title"] = $process_2_title;
        $service["process_2_description"] = $process_2_description;

        $service["process_3_title"] = $process_3_title;
        $service["process_3_description"] = $process_3_description;

        $service["process_4_title"] = $process_4_title;
        $service["process_4_description"] = $process_4_description;

        $service["icon"] = $icon;
        $service["sort_order"] = $sort_order;
        $service["status"] = $status;

    }

}


require_once __DIR__ . "/../includes/header.php";

require_once __DIR__ . "/../includes/sidebar.php";

?>

<div class="main-content">

```
<?php require_once __DIR__ . "/../includes/navbar.php"; ?>


<main class="page-content">


    <!-- Page Header -->

    <div class="page-header">

        <div>

            <h2>
                Edit Service
            </h2>

            <p>
                Update the service information and its details content.
            </p>

        </div>


        <a
            href="<?= ADMIN_URL ?>services/index.php"
            class="secondary-btn"
        >
            ← Back to Services
        </a>

    </div>



    <!-- Error -->

    <?php if ($error !== ""): ?>

        <div class="alert alert-danger">

            <?= htmlspecialchars($error) ?>

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
                        Update the main information about the service.
                    </p>

                </div>


                <div class="form-grid">


                    <!-- Title -->

                    <div class="form-group">

                        <label for="title">
                            Service Title
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $service["title"]
                            ) ?>"
                            placeholder="Example: Export & International Trade"
                            required
                        >

                    </div>



                    <!-- Slug -->

                    <div class="form-group">

                        <label for="slug">
                            Slug
                        </label>

                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $service["slug"]
                            ) ?>"
                            placeholder="export-international-trade"
                        >

                        <small class="form-help">
                            Used in the website URL.
                        </small>

                    </div>


                </div>


                <!-- Short Description -->

                <div class="form-group">

                    <label for="short_description">
                        Short Description
                    </label>

                    <textarea
                        id="short_description"
                        name="short_description"
                        class="form-control"
                        rows="4"
                        placeholder="Write a short description..."
                    ><?= htmlspecialchars(
                        $service["short_description"] ?? ""
                    ) ?></textarea>

                </div>



                <!-- Full Description -->

                <div class="form-group">

                    <label for="description">
                        Full Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        class="form-control"
                        rows="8"
                        placeholder="Write the full service description..."
                    ><?= htmlspecialchars(
                        $service["description"] ?? ""
                    ) ?></textarea>

                </div>


            </div>



            <!-- Service Details -->

            <div class="form-section">

                <div class="form-section-header">

                    <h3>
                        Service Details Content
                    </h3>

                    <p>
                        Update the unique content that will appear on this service's details page.
                    </p>

                </div>


                <!-- Details Title -->

                <div class="form-group">

                    <label for="details_title">
                        Details Title
                    </label>

                    <input
                        type="text"
                        id="details_title"
                        name="details_title"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $service["details_title"] ?? ""
                        ) ?>"
                        placeholder="Example: What We Provide"
                    >

                </div>



                <!-- Details Description -->

                <div class="form-group">

                    <label for="details_description">
                        Details Description
                    </label>

                    <textarea
                        id="details_description"
                        name="details_description"
                        class="form-control"
                        rows="7"
                        placeholder="Write the detailed content specific to this service..."
                    ><?= htmlspecialchars(
                        $service["details_description"] ?? ""
                    ) ?></textarea>

                </div>



                <!-- Features -->

                <div class="form-grid">


                    <!-- Feature 1 -->

                    <div class="form-group">

                        <label for="feature_1">
                            Feature 1
                        </label>

                        <input
                            type="text"
                            id="feature_1"
                            name="feature_1"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $service["feature_1"] ?? ""
                            ) ?>"
                            placeholder="Example: Export Coordination"
                        >

                    </div>



                    <!-- Feature 2 -->

                    <div class="form-group">

                        <label for="feature_2">
                            Feature 2
                        </label>

                        <input
                            type="text"
                            id="feature_2"
                            name="feature_2"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $service["feature_2"] ?? ""
                            ) ?>"
                            placeholder="Example: Supplier Coordination"
                        >

                    </div>



                    <!-- Feature 3 -->

                    <div class="form-group">

                        <label for="feature_3">
                            Feature 3
                        </label>

                        <input
                            type="text"
                            id="feature_3"
                            name="feature_3"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $service["feature_3"] ?? ""
                            ) ?>"
                            placeholder="Example: Documentation Support"
                        >

                    </div>



                    <!-- Feature 4 -->

                    <div class="form-group">

                        <label for="feature_4">
                            Feature 4
                        </label>

                        <input
                            type="text"
                            id="feature_4"
                            name="feature_4"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $service["feature_4"] ?? ""
                            ) ?>"
                            placeholder="Example: International Follow-up"
                        >

                    </div>


                </div>

            </div>



            <!-- Service Process -->

            <div class="form-section">

                <div class="form-section-header">

                    <h3>
                        Service Process
                    </h3>

                    <p>
                        Define the process steps that will appear on this service's details page.
                    </p>

                </div>


                <!-- Process 1 -->

                <div class="form-grid">

                    <div class="form-group">

                        <label for="process_1_title">
                            Process 1 Title
                        </label>

                        <input
                            type="text"
                            id="process_1_title"
                            name="process_1_title"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $service["process_1_title"] ?? ""
                            ) ?>"
                            placeholder="Example: Understand"
                        >

                    </div>


                    <div class="form-group">

                        <label for="process_1_description">
                            Process 1 Description
                        </label>

                        <textarea
                            id="process_1_description"
                            name="process_1_description"
                            class="form-control"
                            rows="4"
                            placeholder="Describe the first step..."
                        ><?= htmlspecialchars(
                            $service["process_1_description"] ?? ""
                        ) ?></textarea>

                    </div>

                </div>



                <!-- Process 2 -->

                <div class="form-grid">

                    <div class="form-group">

                        <label for="process_2_title">
                            Process 2 Title
                        </label>

                        <input
                            type="text"
                            id="process_2_title"
                            name="process_2_title"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $service["process_2_title"] ?? ""
                            ) ?>"
                            placeholder="Example: Source"
                        >

                    </div>


                    <div class="form-group">

                        <label for="process_2_description">
                            Process 2 Description
                        </label>

                        <textarea
                            id="process_2_description"
                            name="process_2_description"
                            class="form-control"
                            rows="4"
                            placeholder="Describe the second step..."
                        ><?= htmlspecialchars(
                            $service["process_2_description"] ?? ""
                        ) ?></textarea>

                    </div>

                </div>



                <!-- Process 3 -->

                <div class="form-grid">

                    <div class="form-group">

                        <label for="process_3_title">
                            Process 3 Title
                        </label>

                        <input
                            type="text"
                            id="process_3_title"
                            name="process_3_title"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $service["process_3_title"] ?? ""
                            ) ?>"
                            placeholder="Example: Prepare"
                        >

                    </div>


                    <div class="form-group">

                        <label for="process_3_description">
                            Process 3 Description
                        </label>

                        <textarea
                            id="process_3_description"
                            name="process_3_description"
                            class="form-control"
                            rows="4"
                            placeholder="Describe the third step..."
                        ><?= htmlspecialchars(
                            $service["process_3_description"] ?? ""
                        ) ?></textarea>

                    </div>

                </div>



                <!-- Process 4 -->

                <div class="form-grid">

                    <div class="form-group">

                        <label for="process_4_title">
                            Process 4 Title
                        </label>

                        <input
                            type="text"
                            id="process_4_title"
                            name="process_4_title"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $service["process_4_title"] ?? ""
                            ) ?>"
                            placeholder="Example: Deliver"
                        >

                    </div>


                    <div class="form-group">

                        <label for="process_4_description">
                            Process 4 Description
                        </label>

                        <textarea
                            id="process_4_description"
                            name="process_4_description"
                            class="form-control"
                            rows="4"
                            placeholder="Describe the fourth step..."
                        ><?= htmlspecialchars(
                            $service["process_4_description"] ?? ""
                        ) ?></textarea>

                    </div>

                </div>

            </div>



            <!-- Media -->

            <div class="form-section">

                <div class="form-section-header">

                    <h3>
                        Media & Display
                    </h3>

                    <p>
                        Add an icon and image for this service.
                    </p>

                </div>


                <div class="form-grid">


                    <!-- Icon -->

                    <div class="form-group">

                        <label for="icon">
                            Icon
                        </label>

                        <input
                            type="text"
                            id="icon"
                            name="icon"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $service["icon"] ?? ""
                            ) ?>"
                            placeholder="Example: 🌍"
                        >

                        <small class="form-help">
                            You can use an emoji or icon character.
                        </small>

                    </div>



                    <!-- Image -->

                    <div class="form-group">

                        <label for="image">
                            Service Image
                        </label>

                        <input
                            type="file"
                            id="image"
                            name="image"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <small class="form-help">
                            JPG, PNG or WEBP — Maximum 5 MB.
                        </small>


                        <?php if (!empty($service["image"])): ?>

                            <div style="margin-top: 12px;">

                                <img
                                    src="<?= UPLOADS_URL ?>services/<?= htmlspecialchars($service["image"]) ?>"
                                    alt="<?= htmlspecialchars($service["title"]) ?>"
                                    style="width: 140px; height: 90px; object-fit: cover; border-radius: 8px;"
                                >

                            </div>

                        <?php endif; ?>

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
                        Control the order and visibility of the service.
                    </p>

                </div>


                <div class="form-grid">


                    <!-- Sort Order -->

                    <div class="form-group">

                        <label for="sort_order">
                            Sort Order
                        </label>

                        <input
                            type="number"
                            id="sort_order"
                            name="sort_order"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $service["sort_order"] ?? "0"
                            ) ?>"
                            min="0"
                        >

                    </div>



                    <!-- Status -->

                    <div class="form-group">

                        <label for="status">
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-control"
                        >

                            <option
                                value="active"
                                <?= (
                                    ($service["status"] ?? "active")
                                    === "active"
                                )
                                    ? "selected"
                                    : ""
                                ?>
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                <?= (
                                    ($service["status"] ?? "")
                                    === "inactive"
                                )
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



            <!-- Buttons -->

            <div class="form-actions">

                <a
                    href="<?= ADMIN_URL ?>services/index.php"
                    class="secondary-btn"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="page-btn"
                >
                    Update Service
                </button>

            </div>


        </form>

    </div>


</main>
```

</div>

<script>

const titleInput = document.getElementById("title");
const slugInput = document.getElementById("slug");

let slugManuallyChanged = true;

slugInput.addEventListener("input", function () {

    slugManuallyChanged = true;

});


titleInput.addEventListener("input", function () {

    if (slugManuallyChanged) {
        return;
    }

    let slug = this.value
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, "-")
        .replace(/^-+|-+$/g, "");

    slugInput.value = slug;

});

</script>

<?php

require_once __DIR__ . "/../includes/footer.php";

?>
