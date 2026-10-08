<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "config/database.php";

$message = "";

/* ADD CLASS */

if (isset($_POST['add_class'])) {

    $class_name = trim($_POST['class_name']);

    if ($class_name == "") {

        $message = "Please enter a class name.";

    } else {

        $sql = "INSERT INTO classes (class_name) VALUES (?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $class_name);

        if ($stmt->execute()) {
            $message = "Class added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}

/* GET CLASSES */

$classes = $conn->query("
    SELECT *
    FROM classes
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Classes - School Management System</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>

        .page-header {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .page-header h1 {
            color: #0d47a1;
            margin-bottom: 5px;
        }

        .class-form {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .class-form h2 {
            color: #0d47a1;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            max-width: 500px;
        }

        .form-group label {
            margin-bottom: 7px;
            font-weight: bold;
            color: #444;
        }

        .form-group input {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 14px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0d47a1;
        }

        .submit-button {
            margin-top: 20px;
            background: #0d47a1;
            color: white;
            border: none;
            padding: 13px 25px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 15px;
        }

        .submit-button:hover {
            background: #1565c0;
        }

        .message {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .classes-table {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .classes-table h2 {
            color: #0d47a1;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #0d47a1;
            color: white;
            padding: 13px;
            text-align: left;
        }

        table td {
            padding: 13px;
            border-bottom: 1px solid #eee;
        }

        table tr:hover {
            background: #f7f9fc;
        }

    </style>

</head>

<body>

<div class="sidebar">

    <div class="logo">

        <h2>SchoolMS</h2>

        <p>Management System</p>

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

        <li class="active">
            <a href="classes.php">Classes</a>
        </li>

        <li>
            <a href="#">Subjects</a>
        </li>

        <li>
            <a href="#">Fees</a>
        </li>

        <li>
            <a href="#">Results</a>
        </li>

        <li>
            <a href="#">Settings</a>
        </li>

        <li>
            <a href="logout.php">Logout</a>
        </li>

    </ul>

</div>


<div class="main-content">

    <div class="topbar">

        <div>

            <h1>Classes</h1>

            <p>Manage school classes</p>

        </div>

        <div class="profile">

            <strong>Administrator</strong>

        </div>

    </div>


    <div class="page-header">

        <h1>Class Management</h1>

        <p>Add and view classes in the school.</p>

    </div>


    <?php if ($message != ""): ?>

        <div class="message">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <div class="class-form">

        <h2>Add New Class</h2>

        <form method="POST">

            <div class="form-group">

                <label>Class Name</label>

                <input
                    type="text"
                    name="class_name"
                    placeholder="e.g. Grade 10"
                    required
                >

            </div>


            <button
                type="submit"
                name="add_class"
                class="submit-button"
            >

                + Add Class

            </button>

        </form>

    </div>


    <div class="classes-table">

        <h2>Registered Classes</h2>

        <table>

            <thead>

                <tr>

                    <th>#</th>

                    <th>Class Name</th>

                    <th>Date Created</th>

                </tr>

            </thead>

            <tbody>

                <?php if ($classes->num_rows > 0): ?>

                    <?php $number = 1; ?>

                    <?php while ($class = $classes->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php echo $number++; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($class['class_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($class['created_at']); ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="3" style="text-align:center; padding:30px;">

                            No classes registered yet.

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>