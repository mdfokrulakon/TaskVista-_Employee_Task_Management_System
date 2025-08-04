<?php
session_start();
include "DB_connection.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'employee') {
    header("Location: login.php?error=" . urlencode("Please login first"));
    exit();
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['id'];
    $leaveType = trim($_POST['leave_type'] ?? '');
    $startDate = trim($_POST['start_date'] ?? '');
    $endDate = trim($_POST['end_date'] ?? '');
    $reason = trim($_POST['reason'] ?? '');

    // Validation
    if ($leaveType === '' || $startDate === '' || $endDate === '' || $reason === '') {
        $error = "All fields are required.";
    } elseif ($startDate > $endDate) {
        $error = "Start date cannot be after end date.";
    } else {
        $sql = "INSERT INTO leave_requests (user_id, leave_type, start_date, end_date, reason, status) 
                VALUES (?, ?, ?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$userId, $leaveType, $startDate, $endDate, $reason])) {
            $success = "Leave application submitted successfully.";
        } else {
            $error = "Failed to submit leave application.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leave Application</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 20px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        .btn {
            background-color: #007BFF;
            color: white;
            padding: 12px;
            margin-top: 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .alert {
            padding: 12px;
            margin-top: 20px;
            border-radius: 6px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <?php include "inc/header.php"; ?>
    <div class="body">
        <?php include "inc/nav.php"; ?>

        <div class="container">
            <h2>Leave Application</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST">
                <label for="leave_type">Leave Type</label>
                <select name="leave_type" id="leave_type" required>
                    <option value="">Select</option>
                    <option value="Sick Leave">Sick Leave</option>
                    <option value="Casual Leave">Casual Leave</option>
                    <option value="Maternity Leave">Maternity Leave</option>
                    <option value="Paternity Leave">Paternity Leave</option>
                    <option value="Emergency Leave">Emergency Leave</option>
                </select>

                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" required>

                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" required>

                <label for="reason">Reason</label>
                <textarea name="reason" id="reason" rows="4" placeholder="Explain the reason for leave" required></textarea>

                <button type="submit" class="btn">Submit Leave Application</button>
            </form>
        </div>
    </div>
</body>
</html>
