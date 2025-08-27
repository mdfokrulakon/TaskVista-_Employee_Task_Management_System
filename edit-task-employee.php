<?php 
session_start();

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Ensure the user is a logged-in employee
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "employee") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    
    // Check if a Task ID was provided in the URL
    if (!isset($_GET['id'])) {
    	 header("Location: my_task.php?error=Task ID is missing");
    	 exit();
    }
    $id = $_GET['id'];
    $task = get_task_by_id($conn, $id);

    // Ensure the task exists and is assigned to this employee
    if ($task == 0 || $task['assigned_to'] != $_SESSION['id']) {
    	 header("Location: my_task.php?error=Task not found or permission denied");
    	 exit();
    }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Edit Task Progress</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        :root { --primary: #4361ee; --secondary: #3f37c9; --success: #4cc9f0; --info: #4895ef; --warning: #f72585; --light: #f8f9fa; --dark: #212529; --background: #f0f2f5; --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
        body { background-color: var(--background); color: #333; line-height: 1.6; }
        .container { display: flex; min-height: 100vh; }
        
        /* Sidebar Navigation */
        .sidebar { width: 250px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; padding: 20px 0; box-shadow: var(--card-shadow); position: fixed; height: 100vh; z-index: 1000; }
        .logo { padding: 0 20px 20px; text-align: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); margin-bottom: 20px; }
        .logo h1 { font-size: 24px; display: flex; align-items: center; justify-content: center; }
        .logo i { margin-right: 10px; font-size: 28px; }
        .nav-links { list-style: none; }
        .nav-links li { margin-bottom: 5px; }
        .nav-links a { display: flex; align-items: center; color: white; padding: 15px 20px; text-decoration: none; font-weight: 500; }
        .nav-links a.active, .nav-links a:hover { background-color: rgba(255, 255, 255, 0.1); border-left: 4px solid var(--success); }
        .nav-links i { margin-right: 15px; font-size: 18px; width: 20px; text-align: center; }
        
        /* Main Content & Header */
        .main-content { flex: 1; margin-left: 250px; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: white; padding: 15px 25px; border-radius: 10px; box-shadow: var(--card-shadow); margin-bottom: 25px; }
        
        /* Animated Title */
        .animated-title-text {
            font-size: 32px; font-weight: 800; text-transform: uppercase;
            background: linear-gradient(90deg, var(--primary), var(--success), var(--secondary), var(--primary));
            background-size: 200% auto;
            -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradient-animation 4s linear infinite;
        }
        @keyframes gradient-animation { to { background-position: 200% center; } }
        
        .user-info { display: flex; align-items: center; }
        .user-info img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px; border: 2px solid var(--primary); }
        .user-name { font-weight: 600; }
        .user-role { font-size: 12px; color: #6c757d; }
        
        /* Form Container & Centering */
        .page-title { margin-bottom: 20px; }
        .page-title h2 { color: var(--dark); font-size: 28px; }
        .form-container { background: white; border-radius: 10px; padding: 25px; box-shadow: var(--card-shadow); width: 100%; max-width: 800px; margin: 0 auto; }
        .form-title { color: var(--dark); font-size: 22px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15); }
        .btn { display: inline-block; background-color: var(--primary); color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; }
        .btn i { margin-right: 8px; }
        .alert { padding: 1rem; margin-bottom: 1rem; border-radius: .25rem; }
        .alert-success { color: #155724; background-color: #d4edda; }
        .alert-danger { color: #721c24; background-color: #f8d7da; }
        .task-info { background-color: var(--light); padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .task-info p { margin: 0; color: #495057; }
        .task-info p b { color: var(--dark); }
        .task-info p:not(:last-child) { margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container">
    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <h1><i class="fas fa-edit"></i> <span>Edit Task</span></h1>
        </div>
        <ul class="nav-links">
            <!-- Employee-specific navigation -->
            <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="my_task.php" class="active"><i class="fas fa-tasks"></i> <span>My Tasks</span></a></li>
            <li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> <span>Profile</span></a></li>
            <li><a href="leave_request.php"><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
            <li><a href="chuti.php"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
            <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="animated-title-text">TaskVista</div>
            <div class="user-info">
                <img src="img/user-default.png" alt="User">
                <div class="user-details">
                    <span class="user-name"><?php echo $_SESSION['full_name']; ?></span>
                    <span class="user-role"><?php echo ucfirst($_SESSION['role']); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Page Title -->
        <div class="page-title"><h2>Update Task Progress</h2></div>

        <!-- Form Container -->
        <div class="form-container">
            <form method="POST" action="app/update-task-employee.php">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <h3 class="form-title">Task Details</h3>
                <div class="task-info">
                    <p><b>Title: </b><?php echo htmlspecialchars($task['title']); ?></p>
                    <p><b>Description: </b><?php echo htmlspecialchars($task['description']); ?></p>
                </div>

                <div class="form-group">
                    <label for="status">Update Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="pending" <?php if ($task['status'] == "pending") echo "selected"; ?>>Pending</option>
                        <option value="in_progress" <?php if ($task['status'] == "in_progress") echo "selected"; ?>>In Progress</option>
                        <option value="completed" <?php if ($task['status'] == "completed") echo "selected"; ?>>Completed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="percentage">Progress (%)</label>
                    <input type="number" name="percentage" id="percentage" class="form-control" 
                           min="0" max="100" step="1" 
                           value="<?php echo htmlspecialchars($task['percentage']); ?>" required>
                </div>
                
                <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                <button type="submit" class="btn"><i class="fas fa-save"></i> Update Progress</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<?php 
} else { 
   // Redirect if not an employee or not logged in
   header("Location: login.php?error=First login");
   exit();
}
?>