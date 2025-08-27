<?php 
session_start();

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";
    include "app/Model/Task.php";

    // Get all tasks assigned to the current user
    $tasks = get_all_tasks_by_id($conn, $_SESSION['id']);

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        :root { --primary: #4361ee; --secondary: #3f37c9; --success: #4cc9f0; --info: #4895ef; --warning: #f72585; --light: #f8f9fa; --dark: #212529; --background: #f0f2f5; --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
        body { background-color: var(--background); color: #333; line-height: 1.6; }
        .container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; padding: 20px 0; box-shadow: var(--card-shadow); position: fixed; height: 100vh; overflow-y: auto; z-index: 1000; }
        .logo { padding: 0 20px 20px; text-align: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); margin-bottom: 20px; }
        .logo h1 { font-size: 24px; display: flex; align-items: center; justify-content: center; }
        .logo i { margin-right: 10px; font-size: 28px; }
        .nav-links { list-style: none; }
        .nav-links li { margin-bottom: 5px; }
        .nav-links a { display: flex; align-items: center; color: white; padding: 15px 20px; text-decoration: none; transition: all 0.3s; font-weight: 500; }
        .nav-links a:hover, .nav-links a.active { background-color: rgba(255, 255, 255, 0.1); border-left: 4px solid var(--success); }
        .nav-links i { margin-right: 15px; font-size: 18px; width: 20px; text-align: center; }
        .main-content { flex: 1; margin-left: 250px; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: white; padding: 15px 25px; border-radius: 10px; box-shadow: var(--card-shadow); margin-bottom: 25px; }
        
        /* --- NEW: Animated Title CSS --- */
        .animated-title-text {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            background: linear-gradient(90deg, 
                var(--primary), var(--success), var(--secondary), var(--primary)
            );
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
            animation: gradient-animation 4s linear infinite;
            white-space: pre; 
        }

        @keyframes gradient-animation { to { background-position: 200% center; } }

        .user-info { display: flex; align-items: center; }
        .user-info img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px; border: 2px solid var(--primary); }
        .user-details { display: flex; flex-direction: column; }
        .user-name { font-weight: 600; }
        .user-role { font-size: 12px; color: #6c757d; }
        .dashboard-title { margin-bottom: 20px; }
        .dashboard-title h2 { color: var(--dark); font-size: 28px; margin-bottom: 5px; }
        .dashboard-title p { color: #6c757d; }
        .tasks-table-container { background: white; border-radius: 10px; box-shadow: var(--card-shadow); padding: 20px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: var(--light); font-weight: 600; color: var(--dark); }
        tr:hover { background-color: #f9f9f9; }
        .action-btn { text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; color: white; background-color: var(--info); transition: opacity 0.3s; display: inline-flex; align-items: center;}
        .action-btn:hover { opacity: 0.8; }
        .action-btn i { margin-right: 5px; }
        
        /* Progress Bar CSS */
        .progress-bar { background-color: #e9ecef; border-radius: 50px; height: 20px; width: 120px; overflow: hidden; position: relative; }
        .progress-bar-fill { background: linear-gradient(135deg, var(--info) 0%, var(--success) 100%); height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold; transition: width 0.4s ease-in-out; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar" id="sidebar">
            <div class="logo"><h1><i class="fas fa-user-check"></i> <span>My Tasks</span></h1></div>
            <ul class="nav-links">
                <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="my_task.php" ><i class="fas fa-tasks"></i> <span>My Tasks</span></a></li>
                <li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> <span>Profile</span></a></li>
                <!-- CORRECTED: Changed leave_requests.php to leave_request.php -->
                <li><a href="leave_request.php" ><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
                <li><a href="chuti.php"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>
        <!-- Main Content -->
        <div class="main-content">
            <!-- MODIFIED: Header updated with title and user info -->
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
            <div class="dashboard-title">
                <h2>My Tasks</h2>
                <p>Here are all the tasks that have been assigned to you.</p>
            </div>
            <div class="tasks-table-container">
                <?php if (isset($_GET['success'])) {?><div style="padding: 1rem; margin-bottom: 1rem; border-radius: .25rem; color: #155724; background-color: #d4edda; border-color: #c3e6cb;" role="alert"><?php echo htmlspecialchars($_GET['success']); ?></div><?php } ?>
                <?php if ($tasks != 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Title</th>
                            <th>Progress</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; foreach ($tasks as $task): ?>
                        <tr>
                            <td><?=++$i?></td>
                            <td><?=$task['title']?></td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-bar-fill" style="width: <?=$task['percentage']?>%;">
                                        <?=$task['percentage']?>%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php 
                                    if(empty($task['due_date']) || $task['due_date'] == '0000-00-00') echo "No Deadline";
                                    else echo date("M d, Y", strtotime($task['due_date']));
                                ?>
                            </td>
                            <td><a href="edit-task-employee.php?id=<?=$task['id']?>" class="action-btn"><i class="fas fa-edit"></i> Edit</a></td>
                        </tr>
                       <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 20px;"><h3>You have no tasks assigned.</h3><p>Check back later for new assignments.</p></div>
                <?php endif; ?>
            </div>
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