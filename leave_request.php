<?php
session_start();

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";

    // --- LOGIC USES 'leave_requests' TABLE BUT IS FOR EXTENSIONS ---

    if ($_SESSION['role'] == 'admin') {
        // Admin: Fetch pending and approved extension requests from the 'leave_requests' table
        $sql_pending = "SELECT lr.*, u.full_name, t.title FROM leave_requests lr 
                        JOIN users u ON lr.user_id = u.id 
                        JOIN tasks t ON lr.task_id = t.id 
                        WHERE lr.status = 'pending' ORDER BY lr.requested_at DESC";
        $pending_requests = $conn->query($sql_pending)->fetchAll();

        // Fetch approved requests AND the task's current due date for comparison
        $sql_approved = "SELECT lr.*, u.full_name, t.title, t.due_date FROM leave_requests lr 
                         JOIN users u ON lr.user_id = u.id 
                         JOIN tasks t ON lr.task_id = t.id 
                         WHERE lr.status = 'approved' ORDER BY lr.requested_at DESC";
        $approved_requests = $conn->query($sql_approved)->fetchAll();

    } else {
        // Employee: Fetch their assigned tasks to request an extension for
        $sql_tasks = "SELECT * FROM tasks WHERE assigned_to = ? ORDER BY created_at DESC";
        $stmt_tasks = $conn->prepare($sql_tasks);
        $stmt_tasks->execute([$_SESSION['id']]);
        $my_tasks = $stmt_tasks->fetchAll();
        
        // Employee: Fetch their own approved extension requests and the task's current due date
        $sql_my_approved = "SELECT lr.*, t.title, t.due_date FROM leave_requests lr
                            JOIN tasks t ON lr.task_id = t.id
                            WHERE lr.user_id = ? AND lr.status = 'approved'
                            ORDER BY lr.requested_at DESC";
        $stmt_approved = $conn->prepare($sql_my_approved);
        $stmt_approved->execute([$_SESSION['id']]);
        $my_approved_requests = $stmt_approved->fetchAll();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deadline Extension Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* --- EXACT CSS FROM INDEX.PHP --- */
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
        
        /* Page specific styles */
        .page-title { margin-bottom: 20px; }
        .page-title h2 { color: var(--dark); font-size: 28px; }
        .table-container { background: white; border-radius: 10px; box-shadow: var(--card-shadow); padding: 20px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: var(--light); font-weight: 600; }
        .action-buttons { display: flex; gap: 10px; }
        .btn { border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; color: white; font-weight: 500; }
        .btn-approve { background-color: #28a745; }
        .btn-reject { background-color: #dc3545; }
        .btn-apply { background-color: var(--primary); }
        .alert { padding: 1rem; margin-bottom: 1rem; border-radius: .25rem; }
        .alert-success { color: #155724; background-color: #d4edda; }
        .alert-danger { color: #721c24; background-color: #f8d7da; }
    </style>
</head>
<body>
<div class="container">
    <!-- Sidebar from index.php -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <h1><i class="fas fa-hourglass-half"></i> <span>Extensions</span></h1>
        </div>
        <ul class="nav-links">
            <?php if ($_SESSION['role'] == "admin") { ?>
                <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="user.php"><i class="fas fa-users-cog"></i> <span>Manage Users</span></a></li>
                <li><a href="create_task.php"><i class="fas fa-tasks"></i> <span>Create Task</span></a></li>
                <li><a href="tasks.php"><i class="fas fa-list-alt"></i> <span>All Tasks</span></a></li>
                <li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                <li><a href="edit_profile.php"><i class="fas fa-user-edit"></i> <span>Edit Profile</span></a></li>
                <li><a href="leave_request.php" class="active"><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
                <li><a href="chuti.php"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            <?php } else { ?>
                <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="my_task.php"><i class="fas fa-tasks"></i> <span>My Tasks</span></a></li>
                <li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> <span>Profile</span></a></li>
                <li><a href="leave_request.php" class="active"><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
                <li><a href="chuti.php"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            <?php } ?>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header from index.php -->
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

        <?php if (isset($_GET['success'])): ?><div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div><?php endif; ?>
        <?php if (isset($_GET['error'])): ?><div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div><?php endif; ?>

        <!-- ADMIN VIEW -->
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <div class="page-title"><h2>Pending Extension Requests</h2></div>
            <div class="table-container">
                <?php if (count($pending_requests) > 0): ?>
                <table>
                    <thead><tr><th>Task Title</th><th>Employee</th><th>Days Requested</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($pending_requests as $req): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($req['title']); ?></td>
                            <td><?php echo htmlspecialchars($req['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($req['days']); ?></td>
                            <td class="action-buttons">
                                <form action="app/handle_extension_request.php" method="POST" style="display:inline;"><input type="hidden" name="request_id" value="<?php echo $req['id']; ?>"><button type="submit" name="action" value="approve" class="btn btn-approve">Approve</button></form>
                                <form action="app/handle_extension_request.php" method="POST" style="display:inline;"><input type="hidden" name="request_id" value="<?php echo $req['id']; ?>"><button type="submit" name="action" value="reject" class="btn btn-reject">Reject</button></form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?><p>No pending extension requests.</p><?php endif; ?>
            </div>

            <div class="page-title" style="margin-top:40px;"><h2>Approved Extension History</h2></div>
            <div class="table-container">
                 <?php if (count($approved_requests) > 0): ?>
                <table>
                     <thead><tr><th>Task</th><th>Employee</th><th>Previous Deadline</th><th>New Deadline</th></tr></thead>
                    <tbody>
                        <?php foreach ($approved_requests as $req): 
                            $new_deadline = $req['due_date'];
                            $previous_deadline = date('Y-m-d', strtotime($new_deadline . ' -' . $req['days'] . ' days'));
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($req['title']); ?></td>
                            <td><?php echo htmlspecialchars($req['full_name']); ?></td>
                            <td><?php echo date("M d, Y", strtotime($previous_deadline)); ?></td>
                            <td><?php echo date("M d, Y", strtotime($new_deadline)); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                 <?php else: ?><p>No extensions have been approved yet.</p><?php endif; ?>
            </div>

        <!-- EMPLOYEE VIEW -->
        <?php else: ?>
            <div class="page-title"><h2>Request Deadline Extension</h2></div>
            <div class="table-container">
                <?php if (count($my_tasks) > 0): ?>
                <table>
                    <thead><tr><th>Task Title</th><th>Current Due Date</th><th>Request Additional Days</th></tr></thead>
                    <tbody>
                        <?php foreach ($my_tasks as $task): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($task['title']); ?></td>
                            <td><?php echo empty($task['due_date']) || $task['due_date'] == '0000-00-00' ? 'N/A' : date("M d, Y", strtotime($task['due_date'])); ?></td>
                            <td>
                                <form action="app/apply_extension.php" method="POST">
                                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                    <input type="number" name="days" placeholder="e.g., 3" min="1" required style="padding: 8px; border-radius: 5px; border: 1px solid #ccc; width: 100px;">
                                    <button type="submit" class="btn btn-apply">Request</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?><p>You have no assigned tasks to request an extension for.</p><?php endif; ?>
            </div>
            
            <div class="page-title" style="margin-top:40px;"><h2>My Approved Extension History</h2></div>
            <div class="table-container">
                <?php if (count($my_approved_requests) > 0): ?>
                <table>
                     <thead><tr><th>Task Title</th><th>Previous Deadline</th><th>New Deadline</th></tr></thead>
                    <tbody>
                         <?php foreach ($my_approved_requests as $req): 
                            $new_deadline = $req['due_date'];
                            $previous_deadline = date('Y-m-d', strtotime($new_deadline . ' -' . $req['days'] . ' days'));
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($req['title']); ?></td>
                            <td><?php echo date("M d, Y", strtotime($previous_deadline)); ?></td>
                            <td><?php echo date("M d, Y", strtotime($new_deadline)); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?><p>You have no approved extension requests.</p><?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php
} else {
    header("Location: login.php?error=First login");
    exit();
}
?>