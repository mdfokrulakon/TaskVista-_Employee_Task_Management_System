<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) ) {

	 include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";

	if ($_SESSION['role'] == "admin") {
		  $todaydue_task = count_tasks_due_today($conn);
	     $overdue_task = count_tasks_overdue($conn);
	     $nodeadline_task = count_tasks_NoDeadline($conn);
	     $num_task = count_tasks($conn);
	     $num_users = count_users($conn);
	     $pending = count_pending_tasks($conn);
	     $in_progress = count_in_progress_tasks($conn);
	     $completed = count_completed_tasks($conn);
	}else {
        // NOTE: The original code defined $num_my_task but the UI did not use it. 
        // I have added a card for it in the user's view.
        $num_my_task = count_my_tasks($conn, $_SESSION['id']);
        $overdue_task = count_my_tasks_overdue($conn, $_SESSION['id']);
        $nodeadline_task = count_my_tasks_NoDeadline($conn, $_SESSION['id']);
        $pending = count_my_pending_tasks($conn, $_SESSION['id']);
	     $in_progress = count_my_in_progress_tasks($conn, $_SESSION['id']);
	     $completed = count_my_completed_tasks($conn, $_SESSION['id']);

	}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --background: #f0f2f5;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        body {
            background-color: var(--background);
            color: #333;
            line-height: 1.6;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 20px 0;
            box-shadow: var(--card-shadow);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .logo {
            padding: 0 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .logo h1 {
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo i {
            margin-right: 10px;
            font-size: 28px;
        }

        .nav-links {
            list-style: none;
        }

        .nav-links li {
            margin-bottom: 5px;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            color: white;
            padding: 15px 20px;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav-links a:hover, .nav-links a.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--success);
        }

        .nav-links i {
            margin-right: 15px;
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
        }

        .search-box {
            display: flex;
            align-items: center;
            background-color: var(--light);
            border-radius: 50px;
            padding: 5px 15px;
            width: 300px;
        }

        .search-box input {
            border: none;
            background: transparent;
            padding: 8px;
            width: 100%;
            outline: none;
        }

        .search-box i {
            color: #6c757d;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            border: 2px solid var(--primary);
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
        }

        .user-role {
            font-size: 12px;
            color: #6c757d;
        }

        /* Dashboard Content */
        .dashboard-title {
            margin-bottom: 20px;
        }

        .dashboard-title h2 {
            color: var(--dark);
            font-size: 28px;
            margin-bottom: 5px;
        }

        .dashboard-title p {
            color: #6c757d;
        }

        /* Stats Cards */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 15px;
        }

        .stat-icon.users { background-color: rgba(67, 97, 238, 0.15); color: var(--primary); }
        .stat-icon.tasks { background-color: rgba(76, 201, 240, 0.15); color: var(--success); }
        .stat-icon.overdue { background-color: rgba(247, 37, 133, 0.15); color: var(--warning); }
        .stat-icon.no-deadline { background-color: rgba(255, 193, 7, 0.15); color: #ffc107; }
        .stat-icon.due-today { background-color: rgba(220, 53, 69, 0.15); color: #dc3545; }
        .stat-icon.pending { background-color: rgba(108, 117, 125, 0.15); color: #6c757d; }
        .stat-icon.in-progress { background-color: rgba(23, 162, 184, 0.15); color: #17a2b8; }
        .stat-icon.completed { background-color: rgba(40, 167, 69, 0.15); color: #28a745; }

        .stat-info h3 { font-size: 24px; margin-bottom: 5px; }
        .stat-info p { color: #6c757d; font-size: 14px; }

        /* --- REPLACE any old .website-title-container CSS with this --- */

/* Main container for the animated title */
.animated-title-text {
    /* --- THIS IS WHERE YOU CHANGE THE FONT SIZE --- */
    font-size: 32px; /* Adjust this value */
    
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    
    /* The Gradient Background */
    background: linear-gradient(90deg, 
        var(--primary), 
        var(--success), 
        var(--secondary), 
        var(--primary)
    );
    background-size: 200% auto;
    
    /* Clip the background to the text shape */
    -webkit-background-clip: text;
    background-clip: text;
    
    /* Make the text color transparent to show the gradient */
    -webkit-text-fill-color: transparent;
    color: transparent;

    /* The moving color animation */
    animation: gradient-animation 4s linear infinite;

    /* Preserve all spaces */
    white-space: pre; 
}
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <h1><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></h1>
            </div>
            <ul class="nav-links">
                <!-- Conditional navigation based on user role -->
                <?php if ($_SESSION['role'] == "admin") { ?>
                    <li><a href="index.php" class="active"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                    <li><a href="user.php"><i class="fas fa-users-cog"></i> <span>Manage Users</span></a></li>
                    <li><a href="create_task.php"><i class="fas fa-tasks"></i> <span>Create Task</span></a></li>
                    <li><a href="tasks.php"><i class="fas fa-list-alt"></i> <span>All Tasks</span></a></li>
					<li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                    <li><a href="edit_profile.php"><i class="fas fa-user-edit"></i> <span>Edit Profile</span></a></li>
                    <li><a href="leave_request.php" ><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
                    <li><a href="chuti.php"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                    <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
                <?php } else { ?>
                    <li><a href="index.php" class="active"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                    <li><a href="my_task.php"><i class="fas fa-tasks"></i> <span>My Tasks</span></a></li>
                    <li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                    <li><a href="profile.php"><i class="fas fa-user"></i> <span>Profile</span></a></li>
                    <li><a href="leave_request.php" ><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
                    <li><a href="chuti.php"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                    <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
                <?php } ?>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="animated-title-text">TaskVista - Employee Task Management System</div>
                <div class="user-info">
                    <img src="img/user-default.png" alt="User">
                    <div class="user-details">
                        <!-- Dynamic user info from PHP Session -->
                        <span class="user-name"><?php echo $_SESSION['full_name']; ?></span>
                        <span class="user-role"><?php echo ucfirst($_SESSION['role']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Dashboard Title -->
            <div class="dashboard-title">
                 <!-- Dynamic welcome message from PHP Session -->
                <h2>Welcome back, <?php echo strtok($_SESSION['full_name'], " "); ?>!</h2>
                <p>Here's what's happening today.</p>
            </div>

            <!-- Stats Cards with dynamic data from PHP -->
            <?php if ($_SESSION['role'] == "admin") { ?>
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-icon users"><i class="fas fa-users"></i></div>
                    <div class="stat-info"><h3><?=$num_users?></h3><p>Employees</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon tasks"><i class="fas fa-tasks"></i></div>
                    <div class="stat-info"><h3><?=$num_task?></h3><p>All Tasks</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon overdue"><i class="fas fa-exclamation-circle"></i></div>
                    <div class="stat-info"><h3><?=$overdue_task?></h3><p>Overdue</p></div>
                </div>
                 <div class="stat-card">
                    <div class="stat-icon due-today"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-info"><h3><?=$todaydue_task?></h3><p>Due Today</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon no-deadline"><i class="fas fa-clock"></i></div>
                    <div class="stat-info"><h3><?=$nodeadline_task?></h3><p>No Deadline</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon pending"><i class="far fa-square"></i></div>
                    <div class="stat-info"><h3><?=$pending?></h3><p>Pending</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon in-progress"><i class="fas fa-spinner"></i></div>
                    <div class="stat-info"><h3><?=$in_progress?></h3><p>In progress</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon completed"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info"><h3><?=$completed?></h3><p>Completed</p></div>
                </div>
            </div>
            <?php } else { ?>
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-icon tasks"><i class="fas fa-tasks"></i></div>
                    <div class="stat-info"><h3><?=$num_my_task?></h3><p>My Tasks</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon overdue"><i class="fas fa-exclamation-circle"></i></div>
                    <div class="stat-info"><h3><?=$overdue_task?></h3><p>Overdue</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon no-deadline"><i class="fas fa-clock"></i></div>
                    <div class="stat-info"><h3><?=$nodeadline_task?></h3><p>No Deadline</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon pending"><i class="far fa-square"></i></div>
                    <div class="stat-info"><h3><?=$pending?></h3><p>Pending</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon in-progress"><i class="fas fa-spinner"></i></div>
                    <div class="stat-info"><h3><h3><?=$in_progress?></h3><p>In progress</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon completed"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info"><h3><?=$completed?></h3><p>Completed</p></div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
<?php 
} else { 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
?>