<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "config/database.php";

/* Dashboard Statistics */

$students_count = $conn->query(
    "SELECT COUNT(*) AS total FROM students"
)->fetch_assoc()['total'];

$teachers_count = $conn->query(
    "SELECT COUNT(*) AS total FROM teachers"
)->fetch_assoc()['total'];

$classes_count = $conn->query(
    "SELECT COUNT(*) AS total FROM classes"
)->fetch_assoc()['total'];

$subjects_count = $conn->query(
    "SELECT COUNT(*) AS total FROM subjects"
)->fetch_assoc()['total'];

$results_count = $conn->query(
    "SELECT COUNT(*) AS total FROM results"
)->fetch_assoc()['total'];

$fees_total = $conn->query(
    "SELECT COALESCE(SUM(amount), 0) AS total FROM fees"
)->fetch_assoc()['total'];

/* Recent Students */

$recent_students = $conn->query(
    "SELECT admission_number, full_name, created_at
     FROM students
     ORDER BY id DESC
     LIMIT 5"
);

/* Recent Payments */

$recent_payments = $conn->query(
    "SELECT 
        students.full_name,
        fees.amount,
        fees.payment_method,
        fees.payment_date
     FROM fees
     INNER JOIN students
        ON fees.student_id = students.id
     ORDER BY fees.id DESC
     LIMIT 5"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - SchoolMS</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>

        .dashboard-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .dashboard-section h2 {
            color: #0d47a1;
            margin-bottom: 20px;
        }

        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dashboard-table th,
        .dashboard-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        .dashboard-table th {
            background: #0d47a1;
            color: white;
        }

        .dashboard-table tr:hover {
            background: #f5f9ff;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .money {
            color: #2e7d32 !important;
        }

        @media (max-width: 800px) {

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

    <div class="logo">

        <h2>SchoolMS</h2>

        <p>School Management System</p>

    </div>

    <ul class="menu">

        <li class="active">
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

        <li>
            <a href="settings.php">Settings</a>
        </li>

        <li>
            <a href="logout.php">Logout</a>
        </li>

    </ul>

</div>


<!-- MAIN CONTENT -->

<div class="main-content">

    <!-- TOP BAR -->

    <div class="topbar">

        <div>

            <h1>Dashboard</h1>

            <p>School management overview</p>

        </div>

        <div class="profile">

            Administrator

        </div>

    </div>


    <!-- STATISTICS -->

    <div class="cards">

        <div class="card">

            <h3>Students</h3>

            <h2>
                <?php echo $students_count; ?>
            </h2>

            <p>Registered students</p>

        </div>


        <div class="card">

            <h3>Teachers</h3>

            <h2>
                <?php echo $teachers_count; ?>
            </h2>

            <p>Teaching staff</p>

        </div>


        <div class="card">

            <h3>Classes</h3>

            <h2>
                <?php echo $classes_count; ?>
            </h2>

            <p>Available classes</p>

        </div>


        <div class="card">

            <h3>Subjects</h3>

            <h2>
                <?php echo $subjects_count; ?>
            </h2>

            <p>School subjects</p>

        </div>

    </div>


    <!-- SECOND STATISTICS -->

    <div class="cards">

        <div class="card">

            <h3>Total Fees</h3>

            <h2 class="money">

                KSh <?php echo number_format($fees_total, 2); ?>

            </h2>

            <p>Total recorded payments</p>

        </div>


        <div class="card">

            <h3>Results</h3>

            <h2>
                <?php echo $results_count; ?>
            </h2>

            <p>Recorded results</p>

        </div>


        <div class="card">

            <h3>System</h3>

            <h2>Active</h2>

            <p>Database connected</p>

        </div>


        <div class="card">

            <h3>Admin</h3>

            <h2>Online</h2>

            <p>Administrator account</p>

        </div>

    </div>


    <!-- WELCOME -->

    <div class="welcome-box">

        <h2>Welcome to SchoolMS</h2>

        <p>

            Use the menu on the left to manage students, teachers,
            classes, subjects, school fees and examination results.

            The information displayed on this dashboard is retrieved
            directly from your MySQL database.

        </p>

    </div>


    <!-- RECENT INFORMATION -->

    <div class="dashboard-grid">


        <!-- RECENT STUDENTS -->

        <div class="dashboard-section">

            <h2>Recent Students</h2>

            <div style="overflow-x:auto;">

                <table class="dashboard-table">

                    <thead>

                        <tr>

                            <th>Admission No.</th>

                            <th>Name</th>

                            <th>Date Added</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if ($recent_students->num_rows > 0): ?>

                            <?php while ($student = $recent_students->fetch_assoc()): ?>

                                <tr>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $student['admission_number']
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $student['full_name']
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo date(
                                            "d M Y",
                                            strtotime($student['created_at'])
                                        );
                                        ?>
                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="3" style="text-align:center;">
                                    No students registered yet.
                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- RECENT PAYMENTS -->

        <div class="dashboard-section">

            <h2>Recent Payments</h2>

            <div style="overflow-x:auto;">

                <table class="dashboard-table">

                    <thead>

                        <tr>

                            <th>Student</th>

                            <th>Amount</th>

                            <th>Method</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if ($recent_payments->num_rows > 0): ?>

                            <?php while ($payment = $recent_payments->fetch_assoc()): ?>

                                <tr>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $payment['full_name']
                                        );
                                        ?>
                                    </td>

                                    <td>

                                        KSh
                                        <?php
                                        echo number_format(
                                            $payment['amount'],
                                            2
                                        );
                                        ?>

                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $payment['payment_method']
                                        );
                                        ?>
                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="3" style="text-align:center;">
                                    No payments recorded yet.
                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- QUICK ACTIONS -->

    <div class="quick-actions">

        <h2>Quick Actions</h2>

        <div class="action-buttons">

            <a href="students.php">
                Add Student
            </a>

            <a href="teachers.php">
                Add Teacher
            </a>

            <a href="classes.php">
                Add Class
            </a>

            <a href="subjects.php">
                Add Subject
            </a>

            <a href="fees.php">
                Record Fee
            </a>

            <a href="results.php">
                Record Result
            </a>

        </div>

    </div>

</div>

</body>

</html>