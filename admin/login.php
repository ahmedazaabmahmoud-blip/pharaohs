<?php

session_start();

require_once __DIR__ . "/../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $login = trim($_POST["login"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($login === "" || $password === "") {

        $error = "Please enter your login ID and password.";

    } else {

        $stmt = $conn->prepare("
            SELECT id, name, email, password, role, status
            FROM users
            WHERE (name = ? OR email = ?)
            LIMIT 1
        ");

        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if ($user["status"] !== "active") {

                $error = "Your account is inactive.";

            } elseif (password_verify($password, $user["password"])) {

                session_regenerate_id(true);

                $_SESSION["admin_id"] = $user["id"];
                $_SESSION["admin_name"] = $user["name"];
                $_SESSION["admin_role"] = $user["role"];

                $update = $conn->prepare("
                    UPDATE users
                    SET last_login = NOW()
                    WHERE id = ?
                ");

                $update->bind_param("i", $user["id"]);
                $update->execute();
                $update->close();

                header("Location: index.php");
                exit;

            } else {

                $error = "Invalid login ID or password.";

            }

        } else {

            $error = "Invalid login ID or password.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login | Pharaohs Integrated Business</title>

    <link rel="stylesheet" href="assets/css/admin.css">

</head>

<body>

<div class="login-page">

    <div class="login-container">

        <div class="login-box">

            <div class="login-logo">

                <h1>PHARAOHS</h1>

                <p>Integrated Business</p>

            </div>

            <?php if ($error !== ""): ?>

                <div class="alert alert-danger">

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="form-group">

                    <label for="login">
                        Login ID
                    </label>

                    <input
                        type="text"
                        id="login"
                        name="login"
                        class="form-control"
                        placeholder="Enter your login ID"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter your password"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Login
                </button>

            </form>

        </div>

    </div>

</div>

</body>

</html>