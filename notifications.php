<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";
    include "app/Model/Notification.php";

    // Get all notifications for the currently logged-in user
    $notifications = get_all_my_notifications($conn, $_SESSION['id']);

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Notifications</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        :root {
            --primary: #4361ee; --secondary: #3f37c9; --success: #4cc9f0; --info: #4895ef; --warning: #f72585; --light: #f8f9fa; --dark: #212529; --background: #f0f2f5; --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        body { background-color: var(--background); color: #333; line-height: 1.6; }
        .container { display: flex; min-height: 100vh; }
        
        /* Sidebar Navigation */
        .sidebar { width: 250px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; padding: 20px 0; box-shadow: var(--card-shadow); position: fixed; height: 100vh; overflow-y: auto; z-index: 1000; }
        .logo { padding: 0 20px 20px; text-align: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); margin-bottom: 20px; }
        .logo h1 { font-size: 24px; display: flex; align-items: center; justify-content: center; }
        .logo i { margin-right: 10px; font-size: 28px; }
        .nav-links { list-style: none; }
        .nav-links li { margin-bottom: 5px; }
        .nav-links a { display: flex; align-items: center; color: white; padding: 15px 20px; text-decoration: none; transition: all 0.3s; font-weight: 500; }
        .nav-links a:hover, .nav-links a.active { background-color: rgba(255, 255, 255, 0.1); border-left: 4px solid var(--success); }
        .nav-links i { margin-right: 15px; font-size: 18px; width: 20px; text-align: center; }
        
        /* Main Content */
        .main-content { flex: 1; margin-left: 250px; padding: 20px; }
        
        /* Header */
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
        
        /* Page Title */
        .dashboard-title { margin-bottom: 20px; }
        .dashboard-title h2 { color: var(--dark); font-size: 28px; margin-bottom: 5px; }
        .dashboard-title p { color: #6c757d; }

        /* Notifications Table Styles */
        .notifications-table-container { background: white; border-radius: 10px; box-shadow: var(--card-shadow); padding: 20px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: var(--light); font-weight: 600; color: var(--dark); }
        tr:hover { background-color: #f9f9f9; }
        .message-col { max-width: 500px; }
        .type-badge {
            background-color: rgba(67, 97, 238, 0.15);
            color: var(--primary);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar" id="sidebar">
            <div class="logo"><h1><i class="fas fa-bell"></i> <span>Notifications</span></h1></div>
            
            <ul class="nav-links">
                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                    <li><a href="user.php"><i class="fas fa-users-cog"></i> <span>Manage Users</span></a></li>
                    <li><a href="create_task.php"><i class="fas fa-tasks"></i> <span>Create Task</span></a></li>
                    <li><a href="tasks.php"><i class="fas fa-list-alt"></i> <span>All Tasks</span></a></li>
                    <li><a href="notifications.php" class="active"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                    <li><a href="edit_profile.php"><i class="fas fa-user-edit"></i> <span>Edit Profile</span></a></li>
                    <li><a href="leave_request.php" ><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
                    <li><a href="chuti.php"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                    <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
                <?php else: // This is for 'employee' role ?>
                    <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                    <li><a href="my_task.php"><i class="fas fa-tasks"></i> <span>My Tasks</span></a></li>
                    <li><a href="notifications.php" class="active"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                    <li><a href="profile.php"><i class="fas fa-user"></i> <span>Profile</span></a></li>
                    <li><a href="leave_request.php" ><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
                    <li><a href="chuti.php"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                    <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <!-- MODIFIED: Replaced search box with animated title -->
                <div class="animated-title-text">TaskVista - Employee Task Management System</div>
                <div class="user-info">
                    <img src="img/user-default.png" alt="User">
                    <div class="user-details">
                        <span class="user-name"><?php echo $_SESSION['full_name']; ?></span>
                        <span class="user-role"><?php echo ucfirst($_SESSION['role']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Page Title -->
            <div class="dashboard-title">
                <h2>Notifications</h2>
                <p>Here are all your recent notifications.</p>
            </div>

            <!-- Notifications Table -->
            <div class="notifications-table-container">
                <?php if ($notifications != 0) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Message</th>
                            <th>Type</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; foreach ($notifications as $notification) { ?>
                        <tr>
                            <td><?=++$i?></td>
                            <td class="message-col"><?=$notification['message']?></td>
                            <td><span class="type-badge"><?=$notification['type']?></span></td>
                            <td><?=date("F d, Y", strtotime($notification['date']))?></td>
                        </tr>
                       <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                    <div style="text-align: center; padding: 20px;">
                        <h3>You have no notifications.</h3>
                        <p>Check back later for new updates.</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php 
} else { 
   // Redirect if user is not logged in
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
?>