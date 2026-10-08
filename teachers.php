<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "config/database.php";

$message = "";

/* ADD TEACHER */

if (isset($_POST['add_teacher'])) {

    $employee_number = $_POST['employee_number'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];

    $sql = "INSERT INTO teachers
            (employee_number, full_name, phone, email, subject)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssss",
        $employee_number,
        $full_name,
        $phone,
        $email,
        $subject
    );

    if ($stmt->execute()) {
        $message = "Teacher added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

/* GET TEACHERS */

$teachers = $conn->query("
    SELECT *
    FROM teachers
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Teachers - School Management System</title>

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

        .teacher-form {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .teacher-form h2 {
            color: #0d47a1;
            margin-bottom: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
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

        .teachers-table {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow-x: auto;
        }

        .teachers-table h2 {
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

        @media (max-width: 700px) {

            .form-grid {
                grid-template-columns: 1fr;
            }

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

        <li class="active">
            <a href="teachers.php">Teachers</a>
        </li>

        <li>
            <a href="#">Classes</a>
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

            <h1>Teachers</h1>

            <p>Manage school teachers</p>

        </div>

        <div class="profile">

            <strong>Administrator</strong>

        </div>

    </div>


    <div class="page-header">

        <h1>Teacher Management</h1>

        <p>Add and view teachers in the school.</p>

    </div>


    <?php if ($message != ""): ?>

        <div class="message">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <div class="teacher-form">

        <h2>Add New Teacher</h2>

        <form method="POST">

            <div class="form-grid">

                <div class="form-group">

                    <label>Employee Number</label>

                    <input
                        type="text"
                        name="employee_number"
                        placeholder="e.g. T002"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Full Name</label>

                    <input
                        type="text"
                        name="full_name"
                        placeholder="Teacher full name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Phone Number</label>

                    <input
                        type="text"
                        name="phone"
                        placeholder="e.g. 0712345678"
                    >

                </div>


                <div class="form-group">

                    <label>Email Address</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="teacher@example.com"
                    >

                </div>


                <div class="form-group">

                    <label>Subject</label>

                    <input
                        type="text"
                        name="subject"
                        placeholder="e.g. Mathematics"
                    >

                </div>

            </div>


            <button
                type="submit"
                name="add_teacher"
                class="submit-button"
            >

                + Add Teacher

            </button>

        </form>

    </div>


    <div class="teachers-table">

        <h2>Registered Teachers</h2>

        <table>

            <thead>

                <tr>

                    <th>#</th>

                    <th>Employee No.</th>

                    <th>Full Name</th>

                    <th>Phone</th>

                    <th>Email</th>

                    <th>Subject</th>

                </tr>

            </thead>

            <tbody>

                <?php if ($teachers->num_rows > 0): ?>

                    <?php $number = 1; ?>

                    <?php while ($teacher = $teachers->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php echo $number++; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($teacher['employee_number']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($teacher['full_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($teacher['phone']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($teacher['email']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($teacher['subject']); ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="6" style="text-align:center; padding:30px;">

                            No teachers registered yet.

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>