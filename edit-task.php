<?php 
session_start();
// Ensure the user is a logged-in admin
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    
    // Check for task ID in URL
    if (!isset($_GET['id'])) {
    	 header("Location: tasks.php?error=Task ID is missing");
    	 exit();
    }
    $id = $_GET['id'];
    $task = get_task_by_id($conn, $id);

    // Redirect if task not found
    if ($task == 0) {
    	 header("Location: tasks.php?error=Task not found");
    	 exit();
    }
   $users = get_all_users($conn); // Get users for the dropdown
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* --- Standard Styles from your index.php --- */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
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
        
        /* Main Content and Header */
        .main-content { flex: 1; margin-left: 250px; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: white; padding: 15px 25px; border-radius: 10px; box-shadow: var(--card-shadow); margin-bottom: 25px; }
        .animated-title-text {
            font-size: 32px; font-weight: 800; text-transform: uppercase;
            background: linear-gradient(90deg, var(--primary), var(--success), var(--secondary), var(--primary));
            background-size: 200% auto; -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent; animation: gradient-animation 4s linear infinite;
        }
        @keyframes gradient-animation { to { background-position: 200% center; } }
        
        .user-info { display: flex; align-items: center; }
        .user-info img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px; border: 2px solid var(--primary); }
        .user-details { display: flex; flex-direction: column; }
        .user-name { font-weight: 600; }
        .user-role { font-size: 12px; color: #6c757d; }
        
        /* Page-specific Styles */
        .dashboard-title { margin-bottom: 20px; }
        .dashboard-title h2 { color: var(--dark); font-size: 28px; margin-bottom: 5px; }
        .dashboard-title p { color: #6c757d; }
        .form-container { background: white; border-radius: 10px; padding: 25px; box-shadow: var(--card-shadow); max-width: 800px; }
        .form-title { color: var(--dark); font-size: 22px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark); }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; transition: border-color 0.3s; }
        textarea.form-control { min-height: 120px; resize: vertical; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15); }
        .btn { display: inline-block; background-color: var(--primary); color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; transition: background-color 0.3s; }
        .btn:hover { background-color: var(--secondary); }
        .btn i { margin-right: 8px; }
        .alert { padding: 1rem; margin-bottom: 1rem; border-radius: .25rem; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar" id="sidebar">
            <div class="logo"><h1><i class="fas fa-edit"></i> <span>Edit Task</span></h1></div>
            <ul class="nav-links">
                <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="user.php"><i class="fas fa-users-cog"></i> <span>Manage Users</span></a></li>
                <li><a href="create_task.php"><i class="fas fa-plus-circle"></i> <span>Create Task</span></a></li>
                <li><a href="tasks.php" class="active"><i class="fas fa-list-alt"></i> <span>All Tasks</span></a></li>
                <li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                <li><a href="edit_profile.php"><i class="fas fa-user-edit"></i> <span>Edit Profile</span></a></li>
                <li><a href="extension_request.php"><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
                <li><a href="chuti.php"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header with Title -->
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
            <div class="dashboard-title">
                <h2>Edit Task</h2>
                <p>You are modifying the task: "<?=$task['title']?>"</p>
            </div>

            <!-- Edit Task Form -->
            <div class="form-container">
                <form method="POST" action="app/update-task.php">
                    <?php if (isset($_GET['error'])) {?>
                        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error']); ?></div>
                    <?php } ?>
                    <?php if (isset($_GET['success'])) {?>
                        <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($_GET['success']); ?></div>
                    <?php } ?>

                    <h3 class="form-title">Task Details</h3>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Task Title" value="<?=$task['title']?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" rows="5" class="form-control"><?=$task['description']?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="<?=$task['due_date']?>">
                    </div>
                    <div class="form-group">
                        <label for="assigned_to">Assigned to</label>
                        <select name="assigned_to" class="form-control">
                            <option value="0">Select employee</option>
                            <?php if ($users != 0) { 
                                foreach ($users as $user) {
                                    $selected = ($task['assigned_to'] == $user['id']) ? "selected" : "";
                                    echo "<option value='{$user['id']}' $selected>{$user['full_name']}</option>";
                                } 
                            } ?>
                        </select>
                    </div>
                    
                    <input type="hidden" name="id" value="<?=$task['id']?>">

                    <button type="submit" class="btn"><i class="fas fa-save"></i> Update Task</button>
                </form>
            </div>
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