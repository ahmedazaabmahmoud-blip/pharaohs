<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Quote Request";

$errors = [];

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Quote Request
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM quotes
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

$quote = $result->fetch_assoc();

$stmt->close();


/*
|--------------------------------------------------------------------------
| Update Quote Request
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $status = trim($_POST["status"] ?? "");
    $admin_notes = trim($_POST["admin_notes"] ?? "");


    /*
    |--------------------------------------------------------------------------
    | Validate Status
    |--------------------------------------------------------------------------
    */

    $allowed_statuses = [
        "new",
        "in_progress",
        "quoted",
        "completed",
        "cancelled"
    ];

    if (!in_array($status, $allowed_statuses, true)) {

        $errors[] = "Invalid status.";

    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $stmt = $conn->prepare("
            UPDATE quotes
            SET
                status = ?,
                admin_notes = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ssi",
            $status,
            $admin_notes,
            $id
        );


        if ($stmt->execute()) {

            $stmt->close();

            header(
                "Location: view.php?id=" .
                $id .
                "&success=updated"
            );

            exit;

        } else {

            $errors[] =
                "Failed to update the quote request.";

            $stmt->close();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Keep Submitted Data
    |--------------------------------------------------------------------------
    */

    $quote["status"] = $status;
    $quote["admin_notes"] = $admin_notes;
}


$success = $_GET["success"] ?? "";


/*
|--------------------------------------------------------------------------
| Mark As Read
|--------------------------------------------------------------------------
|
| When opening a new request, automatically change it to in_progress.
|
*/

if (
    $quote["status"] === "new" &&
    $_SERVER["REQUEST_METHOD"] !== "POST"
) {

    $new_status = "in_progress";

    $stmt = $conn->prepare("
        UPDATE quotes
        SET status = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "si",
        $new_status,
        $id
    );

    $stmt->execute();

    $stmt->close();

    $quote["status"] = $new_status;
}


require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="main-content">

    <?php require_once __DIR__ . "/../includes/navbar.php"; ?>

    <main class="page-content">


        <!-- Success Message -->

        <?php if ($success === "updated"): ?>

            <div class="alert alert-success">
                Quote request updated successfully.
            </div>

        <?php endif; ?>


        <!-- Error Messages -->

        <?php if (!empty($errors)): ?>

            <div class="alert alert-danger">

                <?php foreach ($errors as $error): ?>

                    <div>
                        <?= htmlspecialchars($error) ?>
                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <!-- Page Header -->

        <div class="page-header">

            <div>

                <h2>
                    Quote Request #<?= (int) $quote["id"] ?>
                </h2>

                <p>
                    View and manage quotation request details.
                </p>

            </div>


            <a
                href="<?= ADMIN_URL ?>quotes/index.php"
                class="secondary-btn"
            >
                ← Back to Quote Requests
            </a>

        </div>


        <div class="form-card">


            <!-- ========================================================= -->
            <!-- Customer Information -->
            <!-- ========================================================= -->

            <div class="form-section">

                <div class="form-section-header">

                    <h3>
                        Customer Information
                    </h3>

                </div>


                <div class="form-grid">


                    <!-- Full Name -->

                    <div class="form-group">

                        <label>
                            Full Name
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $quote["full_name"] ?? ""
                            ) ?>"
                            readonly
                        >

                    </div>


                    <!-- Company Name -->

                    <div class="form-group">

                        <label>
                            Company Name
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $quote["company_name"] ?? ""
                            ) ?>"
                            readonly
                        >

                    </div>


                    <!-- Country -->

                    <div class="form-group">

                        <label>
                            Country
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $quote["country"] ?? ""
                            ) ?>"
                            readonly
                        >

                    </div>


                    <!-- Email -->

                    <div class="form-group">

                        <label>
                            Email
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $quote["email"] ?? ""
                            ) ?>"
                            readonly
                        >

                    </div>


                    <!-- Phone -->

                    <div class="form-group">

                        <label>
                            Phone
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $quote["phone"] ?? ""
                            ) ?>"
                            readonly
                        >

                    </div>


                </div>

            </div>


            <!-- ========================================================= -->
            <!-- Request Information -->
            <!-- ========================================================= -->

            <div class="form-section">

                <div class="form-section-header">

                    <h3>
                        Request Information
                    </h3>

                </div>


                <div class="form-grid">


                    <!-- Service -->

                    <div class="form-group">

                        <label>
                            Service
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $quote["service"] ?? "—"
                            ) ?>"
                            readonly
                        >

                    </div>


                    <!-- Product / Service -->

                    <div class="form-group">

                        <label>
                            Product / Service
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $quote["product_service"] ?? ""
                            ) ?>"
                            readonly
                        >

                    </div>


                    <!-- Quantity -->

                    <div class="form-group">

                        <label>
                            Quantity
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $quote["quantity"] ?? ""
                            ) ?>"
                            readonly
                        >

                    </div>


                    <!-- Destination -->

                    <div class="form-group">

                        <label>
                            Destination
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $quote["destination"] ?? ""
                            ) ?>"
                            readonly
                        >

                    </div>


                </div>


                <!-- Requirements -->

                <div class="form-group">

                    <label>
                        Requirements
                    </label>

                    <textarea
                        class="form-control"
                        rows="7"
                        readonly
                    ><?= htmlspecialchars(
                        $quote["requirements"] ?? ""
                    ) ?></textarea>

                </div>


                <!-- Additional Notes -->

                <div class="form-group">

                    <label>
                        Additional Notes
                    </label>

                    <textarea
                        class="form-control"
                        rows="5"
                        readonly
                    ><?= htmlspecialchars(
                        $quote["additional_notes"] ?? ""
                    ) ?></textarea>

                </div>


                <!-- Attachment -->

                <?php if (!empty($quote["attachment"])): ?>

                    <div class="form-group">

                        <label>
                            Attachment
                        </label>

                        <div>

                            <a
                                href="<?= UPLOADS_URL ?>quotes/<?= htmlspecialchars(
                                    $quote["attachment"]
                                ) ?>"
                                target="_blank"
                                class="page-btn"
                            >
                                View Attachment
                            </a>

                        </div>

                    </div>

                <?php endif; ?>


            </div>


            <!-- ========================================================= -->
            <!-- Request Details -->
            <!-- ========================================================= -->

            <div class="form-section">

                <div class="form-section-header">

                    <h3>
                        Request Details
                    </h3>

                </div>


                <div class="form-grid">


                    <!-- Submitted At -->

                    <div class="form-group">

                        <label>
                            Submitted At
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?=
                                !empty($quote["created_at"])
                                ? date(
                                    "d M Y - h:i A",
                                    strtotime($quote["created_at"])
                                )
                                : "—"
                            ?>"
                            readonly
                        >

                    </div>


                    <!-- Current Status -->

                    <div class="form-group">

                        <label>
                            Current Status
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?php

                                $status_labels = [

                                    "new" =>
                                        "New",

                                    "in_progress" =>
                                        "In Progress",

                                    "quoted" =>
                                        "Quoted",

                                    "completed" =>
                                        "Completed",

                                    "cancelled" =>
                                        "Cancelled"

                                ];

                                echo htmlspecialchars(
                                    $status_labels[
                                        $quote["status"]
                                    ]
                                    ?? $quote["status"]
                                );

                            ?>"
                            readonly
                        >

                    </div>


                </div>

            </div>


            <!-- ========================================================= -->
            <!-- Admin Management -->
            <!-- ========================================================= -->

            <form method="POST">

                <div class="form-section">

                    <div class="form-section-header">

                        <h3>
                            Admin Management
                        </h3>

                    </div>


                    <div class="form-grid">


                        <!-- Status -->

                        <div class="form-group">

                            <label>
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-control"
                                required
                            >

                                <option
                                    value="new"
                                    <?= $quote["status"] === "new"
                                        ? "selected"
                                        : "" ?>
                                >
                                    New
                                </option>


                                <option
                                    value="in_progress"
                                    <?= $quote["status"] === "in_progress"
                                        ? "selected"
                                        : "" ?>
                                >
                                    In Progress
                                </option>


                                <option
                                    value="quoted"
                                    <?= $quote["status"] === "quoted"
                                        ? "selected"
                                        : "" ?>
                                >
                                    Quoted
                                </option>


                                <option
                                    value="completed"
                                    <?= $quote["status"] === "completed"
                                        ? "selected"
                                        : "" ?>
                                >
                                    Completed
                                </option>


                                <option
                                    value="cancelled"
                                    <?= $quote["status"] === "cancelled"
                                        ? "selected"
                                        : "" ?>
                                >
                                    Cancelled
                                </option>

                            </select>

                        </div>


                    </div>


                    <!-- Admin Notes -->

                    <div class="form-group">

                        <label>
                            Admin Notes
                        </label>

                        <textarea
                            name="admin_notes"
                            class="form-control"
                            rows="7"
                            placeholder="Add internal notes about this quotation request..."
                        ><?= htmlspecialchars(
                            $quote["admin_notes"] ?? ""
                        ) ?></textarea>

                    </div>


                    <!-- Form Actions -->

                    <div class="form-actions">

                        <a
                            href="<?= ADMIN_URL ?>quotes/index.php"
                            class="secondary-btn"
                        >
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="page-btn"
                        >
                            Save Changes
                        </button>

                    </div>

                </div>

            </form>


        </div>

    </main>

</div>


<?php require_once __DIR__ . "/../includes/footer.php"; ?>

