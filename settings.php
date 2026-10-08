<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "config/database.php";

$message = "";

/* Get current admin */
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT full_name, email FROM users WHERE id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

$stmt->close();


/* Update Profile */
if (isset($_POST['update_profile'])) {

    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare(
        "UPDATE users
         SET full_name = ?, email = ?
         WHERE id = ?"
    );

    $stmt->bind_param(
        "ssi",
        $full_name,
        $email,
        $user_id
    );

    if ($stmt->execute()) {

        $_SESSION['full_name'] = $full_name;

        $message = "Profile updated successfully!";

        $user['full_name'] = $full_name;
        $user['email'] = $email;

    } else {

        $message = "Unable to update profile.";

    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Settings - SchoolMS</title>

    <link rel="stylesheet"
          href="assets/css/style.css">

    <style>

        .settings-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            max-width: 800px;
        }

        .settings-box h2 {
            color: #0d47a1;
            margin-bottom: 20px;
        }

        .message {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 13px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
        }

        .btn {
            background: #0d47a1;
            color: white;
            border: none;
            padding: 13px 22px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        .btn:hover {
            background: #1565c0;
        }

        .info-box {
            background: #f4f7fb;
            padding: 20px;
            border-radius: 8px;
            line-height: 1.7;
        }

    </style>

</head>

<body>

<div class="sidebar">

    <div class="logo">

        <h2>SchoolMS</h2>

        <p>School Management System</p>

    </div>

    <ul class="menu">

        <li>
            <a href="dashboard.php">Dashboard</a>
        </li>

        <li>
            <a href="students.php">Students</a>
        </li>

        <li>
            <a href="teachers.php">Teachers</a>
        </li>

        <li>
            <a href="classes.php">Classes</a>
        </li>

        <li>
            <a href="subjects.php">Subjects</a>
        </li>

        <li>
            <a href="fees.php">Fees</a>
        </li>

        <li>
            <a href="results.php">Results</a>
        </li>

        <li class="active">
            <a href="settings.php">Settings</a>
        </li>

        <li>
            <a href="logout.php">Logout</a>
        </li>

    </ul>

</div>


<div class="main-content">

    <div class="topbar">

        <div>

            <h1>Settings</h1>

            <p>Manage your administrator account</p>

        </div>

        <div class="profile">
            Administrator
        </div>

    </div>


    <?php if ($message != ""): ?>

        <div class="message">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <div class="settings-box">

        <h2>Administrator Profile</h2>

        <form method="POST">

            <div class="form-group">

                <label>Full Name</label>

                <input
                    type="text"
                    name="full_name"
                    value="<?php echo htmlspecialchars($user['full_name']); ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    value="<?php echo htmlspecialchars($user['email']); ?>"
                    required
                >

            </div>


            <button
                type="submit"
                name="update_profile"
                class="btn"
            >
                Save Changes
            </button>

        </form>

    </div>


    <div class="settings-box">

        <h2>System Information</h2>

        <div class="info-box">

            <strong>System:</strong> School Management System<br>

            <strong>Version:</strong> 1.0<br>

            <strong>Backend:</strong> PHP<br>

            <strong>Database:</strong> MySQL<br>

            <strong>Server:</strong> XAMPP / Apache<br>

            <strong>Access:</strong> Administrator

        </div>

    </div>


    <div class="settings-box">

        <h2>Account</h2>

        <p style="margin-bottom:20px; color:#666;">

            You are currently logged in as an administrator.

        </p>

        <a
            href="logout.php"
            class="btn"
            style="text-decoration:none; display:inline-block;"
        >
            Logout
        </a>

    </div>

</div>

</body>

</html>