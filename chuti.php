<?php
session_start();
if (!isset($_SESSION['role']) || !isset($_SESSION['id'])) {
    header("Location: login.php?error=First login");
    exit();
}

include "DB_connection.php";

$role = $_SESSION['role'];
$user_id = $_SESSION['id'];

// --- Data Fetching for Leave Request Page ---
if ($role == 'admin') {
    // Admin fetches all pending and historical requests
    $stmt_pending = $conn->prepare(
        "SELECT c.*, u.full_name 
         FROM chuti c 
         JOIN users u ON c.user_id = u.id 
         WHERE c.status = 'pending' 
         ORDER BY c.requested_at DESC"
    );
    $stmt_pending->execute();
    $pending_requests = $stmt_pending->fetchAll();

    $stmt_history = $conn->prepare(
        "SELECT c.*, u.full_name 
         FROM chuti c 
         JOIN users u ON c.user_id = u.id 
         WHERE c.status != 'pending' 
         ORDER BY c.requested_at DESC"
    );
    $stmt_history->execute();
    $history_requests = $stmt_history->fetchAll();

} else {
    // Employee fetches their own requests and remaining leave days
    $stmt_my_requests = $conn->prepare("SELECT * FROM chuti WHERE user_id = ? ORDER BY requested_at DESC");
    $stmt_my_requests->execute([$user_id]);
    $my_requests = $stmt_my_requests->fetchAll();

    $stmt_leaves = $conn->prepare("SELECT remaining_leaves FROM users WHERE id = ?");
    $stmt_leaves->execute([$user_id]);
    $remaining_leaves = $stmt_leaves->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* --- CSS from index.php for a consistent look --- */
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        :root {
            --primary: #4361ee; --secondary: #3f37c9; --success: #4cc9f0; --info: #4895ef;
            --warning: #f72585; --light: #f8f9fa; --dark: #212529; --background: #f0f2f5;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        body { background-color: var(--background); color: #333; line-height: 1.6; }
        .container { display: flex; min-height: 100vh; }
        .sidebar {
            width: 250px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white; padding: 20px 0; box-shadow: var(--card-shadow);
            position: fixed; height: 100vh; overflow-y: auto; z-index: 1000;
        }
        .logo { padding: 0 20px 20px; text-align: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); margin-bottom: 20px; }
        .logo h1 { font-size: 24px; display: flex; align-items: center; justify-content: center; }
        .logo i { margin-right: 10px; font-size: 28px; }
        .nav-links { list-style: none; }
        .nav-links li { margin-bottom: 5px; }
        .nav-links a { display: flex; align-items: center; color: white; padding: 15px 20px; text-decoration: none; font-weight: 500; }
        .nav-links a:hover, .nav-links a.active { background-color: rgba(255, 255, 255, 0.1); border-left: 4px solid var(--success); }
        .nav-links i { margin-right: 15px; font-size: 18px; width: 20px; text-align: center; }
        .main-content { flex: 1; margin-left: 250px; padding: 20px; }
        .header {
            display: flex; justify-content: space-between; align-items: center;
            background-color: white; padding: 15px 25px; border-radius: 10px;
            box-shadow: var(--card-shadow); margin-bottom: 25px;
        }
        .user-info { display: flex; align-items: center; }
        .user-info img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px; border: 2px solid var(--primary); }
        .user-details { display: flex; flex-direction: column; }
        .user-name { font-weight: 600; }
        .user-role { font-size: 12px; color: #6c757d; }
        .animated-title-text {
            font-size: 32px; font-weight: 800; text-transform: uppercase;
            background: linear-gradient(90deg, var(--primary), var(--success), var(--secondary), var(--primary));
            background-size: 200% auto; -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent; animation: gradient-animation 4s linear infinite;
        }
        @keyframes gradient-animation { to { background-position: 200% center; } }

        /* --- Page-specific styles for forms and tables --- */
        .page-header { margin-bottom: 25px; }
        .page-header h1 { font-size: 32px; color: var(--dark); }
        .page-header p { color: #6c757d; }
        .form-container, .table-container { background: white; border-radius: 10px; padding: 30px; box-shadow: var(--card-shadow); margin-bottom: 30px; }
        .form-container h2, .table-container h2 { font-size: 24px; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-group label { margin-bottom: 8px; font-weight: 600; }
        .form-control { padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; }
        .form-control[readonly] { background-color: #e9ecef; }
        .form-actions { grid-column: 1 / -1; text-align: right; margin-top: 20px; }
        .btn { padding: 12px 25px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; }
        .btn-primary { background-color: var(--primary); color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: var(--light); }
        .status-badge { padding: 5px 10px; border-radius: 20px; color: white; font-size: 12px; }
        .status-pending { background-color: var(--warning); }
        .status-approved { background-color: #28a745; }
        .status-rejected { background-color: #dc3545; }
        .btn-approve { background-color: #28a745; color: white; padding: 8px 12px; }
        .btn-reject { background-color: #dc3545; color: white; padding: 8px 12px; }
        .action-buttons { display: flex; gap: 10px; }
        .alert { padding: 1rem; margin-bottom: 1rem; border-radius: .25rem; }
        .alert-success { color: #155724; background-color: #d4edda; }
        .alert-danger { color: #721c24; background-color: #f8d7da; }
    </style>
</head>
<body>
<div class="container">
    <!-- Sidebar taken from index.php for consistency -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <h1><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></h1>
        </div>
        <ul class="nav-links">
            <?php if ($_SESSION['role'] == "admin") { ?>
                <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="user.php"><i class="fas fa-users-cog"></i> <span>Manage Users</span></a></li>
                <li><a href="create_task.php"><i class="fas fa-tasks"></i> <span>Create Task</span></a></li>
                <li><a href="tasks.php"><i class="fas fa-list-alt"></i> <span>All Tasks</span></a></li>
                <li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                <li><a href="edit_profile.php"><i class="fas fa-user-edit"></i> <span>Edit Profile</span></a></li>
                <li><a href="leave_request.php"><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
                <li><a href="chuti.php" class="active"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            <?php } else { ?>
                <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="my_task.php"><i class="fas fa-tasks"></i> <span>My Tasks</span></a></li>
                <li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> <span>Profile</span></a></li>
                <li><a href="leave_request.php"><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
                <li><a href="chuti.php" class="active"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            <?php } ?>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header taken from index.php for consistency -->
        <div class="header">
            <div class="animated-title-text">TaskVista - Employee Task Management System</div>
            <div class="user-info">
                <img src="img/user-default.png" alt="User">
                <div class="user-details">
                    <span class="user-name"><?php echo $_SESSION['full_name']; ?></span>
                    <span class="user-role"><?php echo ucfirst($_SESSION['role']); ?></span>
                </div>
            </div>
        </div>

        <div class="page-header">
            <h1>Leave Request</h1>
            <!-- <p>Submit a new leave request or view your leave history.</p> -->
        </div>
        
        <?php if (isset($_GET['success'])): ?><div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div><?php endif; ?>
        <?php if (isset($_GET['error'])): ?><div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div><?php endif; ?>
        
        <!-- ======================= EMPLOYEE VIEW ======================= -->
        <?php if ($role == 'employee'): ?>
        <div class="form-container">
            <h2>New Leave Request</h2>
            <form action="app/submit_leave.php" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="leave_type">Leave Type</label>
                        <select id="leave_type" name="leave_type" class="form-control" required>
                            <option value="">Select Leave Type</option>
                            <option value="Vacation">Vacation</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Personal Leave">Personal Leave</option>
                            <option value="Unpaid Leave">Unpaid Leave</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Remaining Leaves</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($remaining_leaves) ?> days" readonly>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="duration">Duration</label>
                        <input type="text" id="duration" name="duration" class="form-control" placeholder="0 days" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="contact_details">Contact During Leave</label>
                        <input type="text" id="contact_details" name="contact_details" class="form-control" placeholder="Phone number or email">
                    </div>
                    <div class="form-group full-width">
                        <label for="reason">Reason for Leave</label>
                        <textarea id="reason" name="reason" class="form-control" rows="4" placeholder="Please provide details for your leave request" required></textarea>
                    </div>
                    <!-- <div class="form-group full-width">
                        <label for="attachment">Attachment (if any)</label>
                        <input type="file" id="attachment" name="attachment" class="form-control">
                        <small>You can upload supporting documents if required (max 5MB)</small>
                    </div> -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-container">
            <h2>Requested Leave Status</h2>
            <table>
                <thead>
                    <tr><th>Leave Type</th><th>Start Date</th><th>End Date</th><th>Duration</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php if (count($my_requests) > 0): ?>
                        <?php foreach ($my_requests as $req): ?>
                        <tr>
                            <td><?= htmlspecialchars($req['leave_type']) ?></td>
                            <td><?= date("M d, Y", strtotime($req['start_date'])) ?></td>
                            <td><?= date("M d, Y", strtotime($req['end_date'])) ?></td>
                            <td><?= htmlspecialchars($req['duration']) ?> days</td>
                            <td><span class="status-badge status-<?= strtolower($req['status']) ?>"><?= ucfirst($req['status']) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">You have not submitted any leave requests yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- ======================= ADMIN VIEW ======================= -->
        <?php if ($role == 'admin'): ?>
        <div class="table-container">
            <h2>Pending Leave Requests</h2>
            <table>
                <thead>
                    <tr><th>Employee</th><th>Leave Type</th><th>Dates</th><th>Duration</th><th>Reason</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if (count($pending_requests) > 0): ?>
                        <?php foreach ($pending_requests as $req): ?>
                        <tr>
                            <td><?= htmlspecialchars($req['full_name']) ?></td>
                            <td><?= htmlspecialchars($req['leave_type']) ?></td>
                            <td><?= date("M d", strtotime($req['start_date'])) ?> - <?= date("M d, Y", strtotime($req['end_date'])) ?></td>
                            <td><?= htmlspecialchars($req['duration']) ?> days</td>
                            <td><?= htmlspecialchars($req['reason']) ?></td>
                            <td class="action-buttons">
                                <form action="app/handle_leave.php" method="POST" style="display:inline;"><input type="hidden" name="request_id" value="<?= $req['id'] ?>"><button type="submit" name="action" value="approve" class="btn btn-approve">Approve</button></form>
                                <form action="app/handle_leave.php" method="POST" style="display:inline;"><input type="hidden" name="request_id" value="<?= $req['id'] ?>"><button type="submit" name="action" value="reject" class="btn btn-reject">Reject</button></form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">There are no pending leave requests.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="table-container">
            <h2>Leave Request History</h2>
             <table>
                <thead>
                    <tr><th>Employee</th><th>Leave Type</th><th>Dates</th><th>Duration</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php if (count($history_requests) > 0): ?>
                        <?php foreach ($history_requests as $req): ?>
                        <tr>
                            <td><?= htmlspecialchars($req['full_name']) ?></td>
                            <td><?= htmlspecialchars($req['leave_type']) ?></td>
                            <td><?= date("M d", strtotime($req['start_date'])) ?> - <?= date("M d, Y", strtotime($req['end_date'])) ?></td>
                            <td><?= htmlspecialchars($req['duration']) ?> days</td>
                            <td><span class="status-badge status-<?= strtolower($req['status']) ?>"><?= ucfirst($req['status']) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No historical leave requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const durationInput = document.getElementById('duration');

    function calculateDuration() {
        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            if (endDate >= startDate) {
                const timeDiff = endDate.getTime() - startDate.getTime();
                const dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                durationInput.value = dayDiff + (dayDiff === 1 ? ' day' : ' days');
            } else {
                durationInput.value = '0 days';
            }
        }
    }
    if(startDateInput && endDateInput) {
        startDateInput.addEventListener('change', calculateDuration);
        endDateInput.addEventListener('change', calculateDuration);
    }
</script>

</body>
</html>