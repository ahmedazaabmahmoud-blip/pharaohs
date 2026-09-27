<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Messages";

// Get Contacts
$contacts = [];

$result = $conn->query("
    SELECT *
    FROM contacts
    ORDER BY id DESC
");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }
}

// Messages
$success = $_GET["success"] ?? "";
$error = $_GET["error"] ?? "";

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="main-content">

    <?php require_once __DIR__ . "/../includes/navbar.php"; ?>

    <main class="page-content">

        <?php if ($success === "updated"): ?>

            <div class="alert alert-success">
                Message updated successfully.
            </div>

        <?php elseif ($success === "deleted"): ?>

            <div class="alert alert-success">
                Message deleted successfully.
            </div>

        <?php elseif ($error === "delete_failed"): ?>

            <div class="alert alert-danger">
                Failed to delete the message.
            </div>

        <?php endif; ?>


        <div class="page-header">

            <div>

                <h2>Messages</h2>

                <p>
                    Manage messages submitted through the website.
                </p>

            </div>

        </div>


        <div class="content-card">

            <div class="table-wrapper">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Sender</th>

                            <th>Phone</th>

                            <th>Subject</th>

                            <th>Status</th>

                            <th>Created</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (count($contacts) > 0): ?>

                        <?php foreach ($contacts as $index => $contact): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>


                                <td>

                                    <div class="table-title">

                                        <div class="table-icon">
                                            <?= strtoupper(
                                                substr(
                                                    $contact["name"] ?? "C",
                                                    0,
                                                    1
                                                )
                                            ) ?>
                                        </div>


                                        <div>

                                            <strong>
                                                <?= htmlspecialchars(
                                                    $contact["name"] ?? "—"
                                                ) ?>
                                            </strong>

                                            <small>
                                                <?= htmlspecialchars(
                                                    $contact["email"] ?? "—"
                                                ) ?>
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <?php if (!empty($contact["phone"])): ?>

                                        <?= htmlspecialchars(
                                            $contact["phone"]
                                        ) ?>

                                    <?php else: ?>

                                        <span class="slug-text">
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if (!empty($contact["subject"])): ?>

                                        <?= htmlspecialchars(
                                            mb_strimwidth(
                                                $contact["subject"],
                                                0,
                                                50,
                                                "..."
                                            )
                                        ) ?>

                                    <?php else: ?>

                                        <span class="slug-text">
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php

                                    $status = $contact["status"];

                                    ?>

                                    <?php if ($status === "new"): ?>

                                        <span class="status-badge status-active">
                                            New
                                        </span>

                                    <?php elseif ($status === "read"): ?>

                                        <span class="status-badge status-active">
                                            Read
                                        </span>

                                    <?php elseif ($status === "replied"): ?>

                                        <span class="status-badge status-active">
                                            Replied
                                        </span>

                                    <?php elseif ($status === "closed"): ?>

                                        <span class="status-badge status-inactive">
                                            Closed
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-inactive">
                                            <?= htmlspecialchars($status) ?>
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php

                                    if (!empty($contact["created_at"])) {

                                        echo date(
                                            "d M Y",
                                            strtotime($contact["created_at"])
                                        );

                                    } else {

                                        echo "—";

                                    }

                                    ?>

                                </td>


                                <td>

                                    <div class="table-actions">

                                        <a
                                            href="<?= ADMIN_URL ?>contacts/view.php?id=<?= (int) $contact["id"] ?>"
                                            class="action-btn edit-btn"
                                        >
                                            View
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>


                    <?php else: ?>

                        <tr>

                            <td
                                colspan="7"
                                class="empty-state"
                            >

                                <div>
                                    No messages found.
                                </div>

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>