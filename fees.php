<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "config/database.php";

$message = "";

/* ADD FEE PAYMENT */

if (isset($_POST['add_fee'])) {

    $student_id = $_POST['student_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $payment_method = $_POST['payment_method'];
    $reference_number = $_POST['reference_number'];

    $sql = "INSERT INTO fees
            (student_id, amount, payment_date, payment_method, reference_number)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "idsss",
        $student_id,
        $amount,
        $payment_date,
        $payment_method,
        $reference_number
    );

    if ($stmt->execute()) {
        $message = "Fee payment recorded successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

/* GET STUDENTS */

$students = $conn->query("
    SELECT id, admission_number, full_name
    FROM students
    ORDER BY full_name ASC
");

/* GET PAYMENTS */

$payments = $conn->query("
    SELECT 
        fees.*,
        students.admission_number,
        students.full_name
    FROM fees
    INNER JOIN students
        ON fees.student_id = students.id
    ORDER BY fees.id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Fees - School Management System</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>

        .page-header,
        .fee-form,
        .fees-table {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .page-header h1,
        .fee-form h2,
        .fees-table h2 {
            color: #0d47a1;
        }

        .page-header h1 {
            margin-bottom: 5px;
        }

        .fee-form h2,
        .fees-table h2 {
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

        .form-group input,
        .form-group select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus {
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

        .fees-table {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #0d47a1;
            color: white;
            padding: 13px;
            text-align: left;
        }

        td {
            padding: 13px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
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

        <li><a href="dashboard.php">Dashboard</a></li>

        <li><a href="students.php">Students</a></li>

        <li><a href="teachers.php">Teachers</a></li>

        <li><a href="classes.php">Classes</a></li>

        <li><a href="subjects.php">Subjects</a></li>

        <li class="active"><a href="fees.php">Fees</a></li>

        <li><a href="results.php">Results</a></li>

        <li><a href="#">Settings</a></li>

        <li><a href="logout.php">Logout</a></li>

    </ul>

</div>


<div class="main-content">

    <div class="topbar">

        <div>

            <h1>Fees</h1>

            <p>Manage student fee payments</p>

        </div>

        <div class="profile">
            <strong>Administrator</strong>
        </div>

    </div>


    <div class="page-header">

        <h1>Fee Management</h1>

        <p>Record and view student fee payments.</p>

    </div>


    <?php if ($message != ""): ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>


    <div class="fee-form">

        <h2>Record Fee Payment</h2>

        <form method="POST">

            <div class="form-grid">

                <div class="form-group">

                    <label>Student</label>

                    <select name="student_id" required>

                        <option value="">Select Student</option>

                        <?php while ($student = $students->fetch_assoc()): ?>

                            <option value="<?php echo $student['id']; ?>">

                                <?php
                                echo htmlspecialchars(
                                    $student['admission_number']
                                    . " - "
                                    . $student['full_name']
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <div class="form-group">

                    <label>Amount (KSh)</label>

                    <input
                        type="number"
                        name="amount"
                        step="0.01"
                        min="0"
                        placeholder="e.g. 5000"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Payment Date</label>

                    <input
                        type="date"
                        name="payment_date"
                        value="<?php echo date('Y-m-d'); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Payment Method</label>

                    <select name="payment_method" required>

                        <option value="">Select Method</option>

                        <option value="M-Pesa">M-Pesa</option>

                        <option value="Cash">Cash</option>

                        <option value="Bank">Bank</option>

                        <option value="Cheque">Cheque</option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Reference Number</label>

                    <input
                        type="text"
                        name="reference_number"
                        placeholder="e.g. QWE123456"
                    >

                </div>

            </div>


            <button
                type="submit"
                name="add_fee"
                class="submit-button"
            >

                + Record Payment

            </button>

        </form>

    </div>


    <div class="fees-table">

        <h2>Payment Records</h2>

        <table>

            <thead>

                <tr>

                    <th>#</th>

                    <th>Admission No.</th>

                    <th>Student</th>

                    <th>Amount</th>

                    <th>Date</th>

                    <th>Method</th>

                    <th>Reference</th>

                </tr>

            </thead>

            <tbody>

                <?php if ($payments->num_rows > 0): ?>

                    <?php $number = 1; ?>

                    <?php while ($payment = $payments->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php echo $number++; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($payment['admission_number']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($payment['full_name']); ?>
                            </td>

                            <td>
                                KSh <?php echo number_format($payment['amount'], 2); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($payment['payment_date']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($payment['payment_method']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($payment['reference_number']); ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="7" style="text-align:center; padding:30px;">

                            No fee payments recorded yet.

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>