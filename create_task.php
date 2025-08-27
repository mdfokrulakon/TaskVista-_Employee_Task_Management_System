<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/User.php";

    // Get all users to populate the "Assign To" dropdown
    $users = get_all_users($conn);

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Create Task</title>
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
        }

        .logo {
            padding: 0 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .logo h1 { font-size: 24px; display: flex; align-items: center; justify-content: center; }
        .logo i { margin-right: 10px; font-size: 28px; }
        .nav-links { list-style: none; }
        .nav-links li { margin-bottom: 5px; }
        .nav-links a { display: flex; align-items: center; color: white; padding: 15px 20px; text-decoration: none; transition: all 0.3s; font-weight: 500; }
        .nav-links a:hover, .nav-links a.active { background-color: rgba(255, 255, 255, 0.1); border-left: 4px solid var(--success); }
        .nav-links i { margin-right: 15px; font-size: 18px; width: 20px; text-align: center; }

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

        /* Dashboard Content */
        .dashboard-title { margin-bottom: 20px; }
        .dashboard-title h2 { color: var(--dark); font-size: 28px; margin-bottom: 5px; }
        .dashboard-title p { color: #6c757d; }

        /* Form Styles */
        .form-container { background: white; border-radius: 10px; padding: 25px; box-shadow: var(--card-shadow); margin-bottom: 30px; }
        .form-title { color: var(--dark); font-size: 22px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark); }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; transition: border-color 0.3s; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15); }
        textarea.form-control { min-height: 120px; resize: vertical; }
        .form-row { display: flex; gap: 20px; margin-bottom: 20px; }
        .form-col { flex: 1; }

        .btn { display: inline-block; background-color: var(--primary); color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; transition: background-color 0.3s; }
        .btn:hover { background-color: var(--secondary); }
        .btn i { margin-right: 8px; }
        
        /* Custom styles for PHP alerts */
        .alert { padding: 1rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar" id="sidebar">
             <div class="logo">
                <h1><i class="fas fa-tasks"></i> <span>Create Task</span></h1>
            </div>
            <ul class="nav-links">
                <!-- Navigation links -->
                <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="user.php"><i class="fas fa-users-cog"></i> <span>Manage Users</span></a></li>
                <!-- MODIFIED: Set this link as active -->
                <li><a href="create_task.php" class="active"><i class="fas fa-tasks"></i> <span>Create Task</span></a></li>
                <li><a href="tasks.php"><i class="fas fa-list-alt"></i> <span>All Tasks</span></a></li>
                <li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                <li><a href="edit_profile.php"><i class="fas fa-user-edit"></i> <span>Edit Profile</span></a></li>
                <li><a href="leave_request.php" ><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
                <li><a href="chuti.php"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <!-- MODIFIED: Added animated title -->
                <div class="animated-title-text">TaskVista - Employee Task Management System</div>
                <div class="user-info">
                    <img src="img/user-default.png" alt="User">
                    <div class="user-details">
                        <span class="user-name"><?php echo $_SESSION['full_name']; ?></span>
                        <span class="user-role"><?php echo ucfirst($_SESSION['role']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Dashboard Title -->
            <div class="dashboard-title">
                <h2>Create New Task</h2>
                <p>Add a new task and assign it to an employee</p>
            </div>

            <!-- Create Task Form -->
            <div class="form-container">
                <h3 class="form-title">Task Details</h3>
                
                <form method="POST" action="app/add-task.php">

                    <?php if (isset($_GET['error'])) {?>
                        <div class="alert alert-danger" role="alert">
                          <?php echo htmlspecialchars($_GET['error']); ?>
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET['success'])) {?>
                        <div class="alert alert-success" role="alert">
                          <?php echo htmlspecialchars($_GET['success']); ?>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <label for="taskTitle">Task Title</label>
                        <input type="text" name="title" id="taskTitle" class="form-control" placeholder="Enter task title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="taskDescription">Description</label>
                        <textarea name="description" id="taskDescription" class="form-control" placeholder="Describe the task in detail" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="assignTo">Assign To</label>
                                <select name="assigned_to" id="assignTo" class="form-control" required>
                                    <option value="">Select an employee</option>
                                    <?php if ($users != 0) { 
                                        foreach ($users as $user) {
                                    ?>
                                      <option value="<?=$user['id']?>"><?=$user['full_name']?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="dueDate">Due Date</label>
                                <input type="date" name="due_date" id="dueDate" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn"><i class="fas fa-plus-circle"></i> Create Task</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php } else { 
   // Redirect if not logged in or not an admin
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
?>