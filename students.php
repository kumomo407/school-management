<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "config/database.php";

$message = "";

/* ADD STUDENT */

if (isset($_POST['add_student'])) {

    $admission_number = $_POST['admission_number'];
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $class_id = $_POST['class_id'];
    $parent_name = $_POST['parent_name'];
    $parent_phone = $_POST['parent_phone'];

    $sql = "INSERT INTO students 
            (admission_number, full_name, gender, date_of_birth, class_id, parent_name, parent_phone)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssiss",
        $admission_number,
        $full_name,
        $gender,
        $date_of_birth,
        $class_id,
        $parent_name,
        $parent_phone
    );

    if ($stmt->execute()) {
        $message = "Student added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

/* GET CLASSES */

$classes = $conn->query("SELECT * FROM classes ORDER BY class_name ASC");

/* GET STUDENTS */

$students = $conn->query("
    SELECT students.*, classes.class_name
    FROM students
    LEFT JOIN classes ON students.class_id = classes.id
    ORDER BY students.id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Students - School Management System</title>

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

        .student-form {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .student-form h2 {
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

        .students-table {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow-x: auto;
        }

        .students-table h2 {
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

        <li class="active">
            <a href="students.php">Students</a>
        </li>

        <li>
            <a href="#">Teachers</a>
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

            <h1>Students</h1>

            <p>Manage school students</p>

        </div>

        <div class="profile">

            <strong>Administrator</strong>

        </div>

    </div>


    <div class="page-header">

        <h1>Student Management</h1>

        <p>Add and view students in the school.</p>

    </div>


    <?php if ($message != ""): ?>

        <div class="message">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <div class="student-form">

        <h2>Add New Student</h2>

        <form method="POST">

            <div class="form-grid">

                <div class="form-group">

                    <label>Admission Number</label>

                    <input
                        type="text"
                        name="admission_number"
                        placeholder="e.g. ADM003"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Full Name</label>

                    <input
                        type="text"
                        name="full_name"
                        placeholder="Student full name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Gender</label>

                    <select name="gender" required>

                        <option value="">Select Gender</option>

                        <option value="Male">Male</option>

                        <option value="Female">Female</option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Date of Birth</label>

                    <input
                        type="date"
                        name="date_of_birth"
                    >

                </div>


                <div class="form-group">

                    <label>Class</label>

                    <select name="class_id" required>

                        <option value="">Select Class</option>

                        <?php while ($class = $classes->fetch_assoc()): ?>

                            <option value="<?php echo $class['id']; ?>">

                                <?php echo htmlspecialchars($class['class_name']); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <div class="form-group">

                    <label>Parent/Guardian Name</label>

                    <input
                        type="text"
                        name="parent_name"
                        placeholder="Parent or guardian"
                    >

                </div>


                <div class="form-group">

                    <label>Parent/Guardian Phone</label>

                    <input
                        type="text"
                        name="parent_phone"
                        placeholder="e.g. 0712345678"
                    >

                </div>

            </div>


            <button
                type="submit"
                name="add_student"
                class="submit-button"
            >

                + Add Student

            </button>

        </form>

    </div>


    <div class="students-table">

        <h2>Registered Students</h2>

        <table>

            <thead>

                <tr>

                    <th>#</th>

                    <th>Admission No.</th>

                    <th>Full Name</th>

                    <th>Gender</th>

                    <th>Date of Birth</th>

                    <th>Class</th>

                    <th>Parent/Guardian</th>

                    <th>Phone</th>

                </tr>

            </thead>

            <tbody>

                <?php if ($students->num_rows > 0): ?>

                    <?php $number = 1; ?>

                    <?php while ($student = $students->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php echo $number++; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($student['admission_number']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($student['full_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($student['gender']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($student['date_of_birth']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($student['class_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($student['parent_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($student['parent_phone']); ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="8" style="text-align:center; padding:30px;">

                            No students registered yet.

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>