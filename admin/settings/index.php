<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";

$page_title = "Settings";

$success = $_GET["success"] ?? "";
$error = "";

/*
|--------------------------------------------------------------------------
| Update Settings
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $settings = $_POST["settings"] ?? [];

    if (!empty($settings)) {

        $stmt = $conn->prepare("
            UPDATE settings
            SET setting_value = ?
            WHERE setting_key = ?
        ");

        if ($stmt) {

            foreach ($settings as $key => $value) {

                $key = trim($key);
                $value = trim($value);

                $stmt->bind_param(
                    "ss",
                    $value,
                    $key
                );

                $stmt->execute();
            }

            $stmt->close();

            header("Location: index.php?success=updated");
            exit;

        } else {

            $error = "Failed to prepare settings update.";
        }
    }
}


/*
|--------------------------------------------------------------------------
| Get Settings
|--------------------------------------------------------------------------
*/

$settings_data = [];

$result = $conn->query("
    SELECT *
    FROM settings
    ORDER BY
        FIELD(setting_group, 'company', 'contact', 'social'),
        id ASC
");

if ($result) {

    while ($row = $result->fetch_assoc()) {
        $settings_data[] = $row;
    }
}


/*
|--------------------------------------------------------------------------
| Group Settings
|--------------------------------------------------------------------------
*/

$grouped_settings = [];

foreach ($settings_data as $setting) {

    $group = $setting["setting_group"];

    if (empty($group)) {
        $group = "general";
    }

    $grouped_settings[$group][] = $setting;
}


/*
|--------------------------------------------------------------------------
| Labels
|--------------------------------------------------------------------------
*/

$group_titles = [

    "company" => [
        "title" => "Company Information",
        "description" => "Basic information about Pharaohs Integrated Business."
    ],

    "contact" => [
        "title" => "Contact Information",
        "description" => "Contact details used across the website."
    ],

    "social" => [
        "title" => "Social Media",
        "description" => "Social media links for the company."
    ],

    "general" => [
        "title" => "General Settings",
        "description" => "General website settings."
    ]

];

?>


<?php

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

?>

<div class="main-content">

    <?php require_once __DIR__ . "/../includes/navbar.php"; ?>

    <main class="page-content">


        <!-- Success -->

        <?php if ($success === "updated"): ?>

            <div class="alert-success">
                Settings updated successfully.
            </div>

        <?php endif; ?>


        <!-- Error -->

        <?php if (!empty($error)): ?>

            <div
                class="alert-success"
                style="
                    background:#fef2f2;
                    color:#991b1b;
                    border-color:#fecaca;
                "
            >
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>


        <!-- Page Header -->

        <div class="page-header">

            <div>

                <h1>Settings</h1>

                <p>
                    Manage your company information, contact details and social media.
                </p>

            </div>

        </div>


        <?php if (empty($grouped_settings)): ?>

            <div class="content-card">

                <div class="empty-state">

                    <h3>No settings found</h3>

                    <p>
                        There are currently no settings available.
                    </p>

                </div>

            </div>

        <?php else: ?>


            <form method="POST">


                <?php foreach ($grouped_settings as $group => $settings): ?>


                    <?php

                    $group_info = $group_titles[$group] ?? [
                        "title" => ucwords(str_replace("_", " ", $group)),
                        "description" => "Website settings."
                    ];

                    ?>


                    <div
                        class="form-card"
                        style="margin-bottom:20px;"
                    >

                        <div class="form-section">


                            <!-- Section Header -->

                            <div class="form-section-header">

                                <div>

                                    <h3>
                                        <?= htmlspecialchars($group_info["title"]) ?>
                                    </h3>

                                    <p
                                        style="
                                            margin-top:5px;
                                            color:#6b7280;
                                            font-size:13px;
                                            font-weight:400;
                                        "
                                    >
                                        <?= htmlspecialchars($group_info["description"]) ?>
                                    </p>

                                </div>

                            </div>


                            <!-- Fields -->

                            <div class="form-grid">


                                <?php foreach ($settings as $setting): ?>


                                    <?php

                                    $key = $setting["setting_key"];

                                    $value = $setting["setting_value"] ?? "";

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Field Labels
                                    |--------------------------------------------------------------------------
                                    */

                                    $labels = [

                                        "company_name" => "Company Name",

                                        "company_name_ar" => "Company Name (Arabic)",

                                        "tagline" => "Tagline",

                                        "email" => "Email Address",

                                        "phone" => "Phone Number",

                                        "whatsapp" => "WhatsApp Number",

                                        "address" => "Address",

                                        "google_maps" => "Google Maps Link",

                                        "facebook" => "Facebook",

                                        "instagram" => "Instagram",

                                        "linkedin" => "LinkedIn",

                                        "about_company" => "About Company"

                                    ];

                                    $label = $labels[$key]
                                        ?? ucwords(str_replace("_", " ", $key));


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Long Text Fields
                                    |--------------------------------------------------------------------------
                                    */

                                    $long_text_fields = [

                                        "about_company",
                                        "description",
                                        "company_description"

                                    ];

                                    $is_long_text =
                                        in_array(
                                            $key,
                                            $long_text_fields,
                                            true
                                        );


                                    ?>


                                    <div
                                        class="form-group"
                                        style="<?= $is_long_text ? "grid-column:1/-1;" : "" ?>"
                                    >


                                        <label>

                                            <span>
                                                <?= htmlspecialchars($label) ?>
                                            </span>

                                        </label>


                                        <?php if ($is_long_text): ?>


                                            <textarea
                                                name="settings[<?= htmlspecialchars($key) ?>]"
                                                class="form-control"
                                                rows="6"
                                            ><?= htmlspecialchars($value) ?></textarea>


                                        <?php else: ?>


                                            <input
                                                type="text"
                                                name="settings[<?= htmlspecialchars($key) ?>]"
                                                class="form-control"
                                                value="<?= htmlspecialchars($value) ?>"
                                            >


                                        <?php endif; ?>


                                    </div>


                                <?php endforeach; ?>


                            </div>


                        </div>


                    </div>


                <?php endforeach; ?>


                <!-- Save -->

                <div class="form-actions">

                    <button
                        type="submit"
                        class="page-btn"
                    >
                        Save Settings
                    </button>

                </div>


            </form>


        <?php endif; ?>


    </main>

</div>


<?php require_once __DIR__ . "/../includes/footer.php"; ?>