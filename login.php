```php
<?php
session_start();
require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $sql = "SELECT id, full_name, role, password
            FROM users
            WHERE email = ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {

        error_log("Login query preparation failed: " . $conn->error);
        $error = "Unable to log in right now. Please try again later.";

    } else {

        $stmt->bind_param("s", $email);

        if (!$stmt->execute()) {

            error_log("Login query execution failed: " . $stmt->error);
            $error = "Unable to log in right now. Please try again later.";

        } else {

            $stmt->bind_result(
                $user_id,
                $full_name,
                $role,
                $hashed_password
            );

            if ($stmt->fetch()) {

                if (password_verify($password, $hashed_password)) {

                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['full_name'] = $full_name;
                    $_SESSION['role'] = $role;

                    header("Location: dashboard.php");
                    exit();

                } else {

                    $error = "Invalid email or password.";

                }

            } else {

                $error = "Invalid email or password.";

            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - School Management System</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-box {
            background: white;
            width: 360px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        }

        h1 {
            color: #0d47a1;
            text-align: center;
            margin-bottom: 8px;
        }

        h2 {
            text-align: center;
            color: #555;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            color: #555;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #0d47a1;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #1565c0;
        }

        .error {
            background: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 18px;
            text-align: center;
        }

    </style>

</head>

<body>

<div class="login-box">

    <h1>SchoolMS</h1>

    <h2>Administrator Login</h2>

    <?php if ($error): ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Email</label>

        <input
            type="email"
            name="email"
            placeholder="admin@school.com"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Enter password"
            required
        >

        <button type="submit">
            Login
        </button>

    </form>

</div>

</body>

</html>
```
