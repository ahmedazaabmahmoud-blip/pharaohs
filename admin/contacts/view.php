<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "View Message";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Get Contact
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM contacts
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$contact = $result->fetch_assoc();

$stmt->close();

if (!$contact) {
    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Mark New Message As Read
|--------------------------------------------------------------------------
*/

if ($contact["status"] === "new") {

    $update_read = $conn->prepare("
        UPDATE contacts
        SET status = 'read'
        WHERE id = ?
    ");

    $update_read->bind_param("i", $id);
    $update_read->execute();
    $update_read->close();

    $contact["status"] = "read";
}

/*
|--------------------------------------------------------------------------
| Update Contact
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $status = $_POST["status"] ?? "read";
    $admin_notes = trim($_POST["admin_notes"] ?? "");

    $allowed_statuses = [
        "new",
        "read",
        "replied",
        "closed"
    ];

    if (!in_array($status, $allowed_statuses, true)) {
        $status = "read";
    }

    $stmt = $conn->prepare("
        UPDATE contacts
        SET status = ?, admin_notes = ?
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

        header("Location: view.php?id=" . $id . "&success=updated");
        exit;

    } else {

        $stmt->close();

        $error = "Failed to update the message.";
    }
}

/*
|--------------------------------------------------------------------------
| Messages
|--------------------------------------------------------------------------
*/

$success = $_GET["success"] ?? "";

/*
|--------------------------------------------------------------------------
| Status Label
|--------------------------------------------------------------------------
*/

$status_labels = [
    "new" => "New",
    "read" => "Read",
    "replied" => "Replied",
    "closed" => "Closed"
];

$status_label = $status_labels[$contact["status"]] ?? ucfirst($contact["status"]);

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

?>

<div class="main-content">

    <?php require_once __DIR__ . "/../includes/navbar.php"; ?>

    <main class="page-content">

        <?php if ($success === "updated"): ?>

            <div class="alert-success">
                Message updated successfully.
            </div>

        <?php endif; ?>

        <?php if (!empty($error)): ?>

            <div class="alert-success" style="
                background:#fef2f2;
                color:#991b1b;
                border-color:#fecaca;
            ">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>


        <!-- Page Header -->

        <div class="page-header">

            <div>

                <h1>View Message</h1>

                <p>
                    Contact message details and management.
                </p>

            </div>

            <a href="index.php" class="secondary-btn">
                ← Back to Messages
            </a>

        </div>


        <!-- Sender Information -->

        <div class="form-card">

            <div class="form-section">

                <div class="form-section-header">
                    <h3>Sender Information</h3>
                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label>
                            <span>Name</span>
                        </label>

                        <div class="form-control" style="
                            background:#f9fafb;
                            min-height:42px;
                        ">
                            <?= htmlspecialchars($contact["name"]) ?>
                        </div>

                    </div>


                    <div class="form-group">

                        <label>
                            <span>Email</span>
                        </label>

                        <div class="form-control" style="
                            background:#f9fafb;
                            min-height:42px;
                        ">
                            <?= htmlspecialchars($contact["email"]) ?>
                        </div>

                    </div>


                    <div class="form-group">

                        <label>
                            <span>Phone</span>
                        </label>

                        <div class="form-control" style="
                            background:#f9fafb;
                            min-height:42px;
                        ">
                            <?= !empty($contact["phone"])
                                ? htmlspecialchars($contact["phone"])
                                : "-"
                            ?>
                        </div>

                    </div>


                    <div class="form-group">

                        <label>
                            <span>Submitted</span>
                        </label>

                        <div class="form-control" style="
                            background:#f9fafb;
                            min-height:42px;
                        ">
                            <?= htmlspecialchars($contact["created_at"]) ?>
                        </div>

                    </div>

                </div>

            </div>


            <!-- Message -->

            <div class="form-section">

                <div class="form-section-header">
                    <h3>Message</h3>
                </div>


                <div class="form-group">

                    <label>
                        <span>Subject</span>
                    </label>

                    <div class="form-control" style="
                        background:#f9fafb;
                        min-height:42px;
                    ">
                        <?= htmlspecialchars($contact["subject"]) ?>
                    </div>

                </div>


                <div class="form-group">

                    <label>
                        <span>Message Content</span>
                    </label>

                    <div
                        class="form-control"
                        style="
                            background:#f9fafb;
                            min-height:180px;
                            white-space:pre-wrap;
                            line-height:1.7;
                        "
                    >
                        <?= htmlspecialchars($contact["message"]) ?>
                    </div>

                </div>

            </div>


            <!-- Management -->

            <form method="POST">

                <div class="form-section">

                    <div class="form-section-header">
                        <h3>Message Management</h3>
                    </div>


                    <div class="form-grid">

                        <div class="form-group">

                            <label>
                                <span>Status</span>
                            </label>

                            <select
                                name="status"
                                class="form-control"
                            >

                                <option
                                    value="new"
                                    <?= $contact["status"] === "new" ? "selected" : "" ?>
                                >
                                    New
                                </option>

                                <option
                                    value="read"
                                    <?= $contact["status"] === "read" ? "selected" : "" ?>
                                >
                                    Read
                                </option>

                                <option
                                    value="replied"
                                    <?= $contact["status"] === "replied" ? "selected" : "" ?>
                                >
                                    Replied
                                </option>

                                <option
                                    value="closed"
                                    <?= $contact["status"] === "closed" ? "selected" : "" ?>
                                >
                                    Closed
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="form-group">

                        <label>
                            <span>Admin Notes</span>
                        </label>

                        <textarea
                            name="admin_notes"
                            class="form-control"
                            rows="6"
                            placeholder="Add internal notes about this message..."
                        ><?= htmlspecialchars($contact["admin_notes"] ?? "") ?></textarea>

                        <div class="form-help">
                            These notes are for internal administration only.
                        </div>

                    </div>

                </div>


                <div class="form-actions">

                    <a
                        href="index.php"
                        class="secondary-btn"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="page-btn"
                    >
                        Update Message
                    </button>

                </div>

            </form>

        </div>

    </main>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>