<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) ) {

	 include "DB_connection.php";
    include "app/Model/User.php";
    
    // Fetch the current user's data to pre-fill the form
    $current_user = get_user_by_id($conn, $_SESSION['id']);

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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

        /* Main Content Wrapper */
        .main-content-wrapper {
            flex: 1;
            margin-left: 250px;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        
        /* Header - Now sticky */
        .header {
            display: flex;
            /* MODIFIED: Changed to space-between for title alignment */
            justify-content: space-between; 
            align-items: center;
            background-color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin: 20px;
            position: sticky;
            top: 20px;
            z-index: 999;
        }
        
        /* --- NEW: CSS for Animated Title --- */
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


        /* Main Content Scrolling Area */
        .main-content {
            flex: 1;
            padding: 0 20px 20px 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center horizontally */
            justify-content: flex-start; /* Align to the top */
        }
        
        /* User Info in Header */
        .user-info { display: flex; align-items: center; }
        .user-info img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px; border: 2px solid var(--primary); }
        .user-details { display: flex; flex-direction: column; }
        .user-name { font-weight: 600; }
        .user-role { font-size: 12px; color: #6c757d; }
        
        /* Page Title & Form Styles */
        .dashboard-title { margin-bottom: 20px; width: 100%; max-width: 800px; }
        .dashboard-title h2 { color: var(--dark); font-size: 28px; margin-bottom: 5px; }
        .dashboard-title p { color: #6c757d; }

        .form-container { background: white; border-radius: 10px; padding: 25px; box-shadow: var(--card-shadow); width: 100%; max-width: 800px; }
        .form-title { color: var(--dark); font-size: 22px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark); }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; transition: border-color 0.3s; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15); }
        
        .btn { display: inline-block; background-color: var(--primary); color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; transition: background-color 0.3s; }
        .btn:hover { background-color: var(--secondary); }
        .btn i { margin-right: 8px; }
        
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
                <h1><i class="fas fa-user-edit"></i> <span>Profile</span></h1>
            </div>
            <ul class="nav-links">
                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                    <li><a href="user.php"><i class="fas fa-users-cog"></i> <span>Manage Users</span></a></li>
                    <li><a href="create_task.php"><i class="fas fa-tasks"></i> <span>Create Task</span></a></li>
                    <li><a href="tasks.php"><i class="fas fa-list-alt"></i> <span>All Tasks</span></a></li>
					<li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                    <!-- MODIFIED: Added 'active' class -->
                    <li><a href="edit_profile.php" class="active"><i class="fas fa-user-edit"></i> <span>Edit Profile</span></a></li>
                    <li><a href="leave_request.php"><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
                    <li><a href="chuti.php"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                    <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
                <?php else: ?>
                    <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                    <li><a href="my_task.php"><i class="fas fa-tasks"></i> <span>My Tasks</span></a></li>
                    <li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                    <li><a href="profile.php"><i class="fas fa-user"></i> <span>Profile</span></a></li>
                    <li><a href="leave_request.php"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                    <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
                <?php endif; ?>
            </ul>
        </div>
        
        <div class="main-content-wrapper">
            <!-- Header (Now Sticky with Title) -->
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

            <!-- Main Content (Scrolls) -->
            <div class="main-content">
                <!-- Page Title -->
                <div class="dashboard-title">
                    <h2>Edit Profile</h2>
                    <p>Update your personal information.</p>
                </div>

                <!-- Edit Profile Form (Centered) -->
                <div class="form-container">
                    <form method="POST" action="app/update-profile.php">
                        
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

                        <h3 class="form-title">Basic Information</h3>
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" name="full_name" id="full_name" class="form-control" value="<?=$current_user['full_name']?>" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" value="<?=$current_user['username']?>" required>
                        </div>
                        
                        <h3 class="form-title" style="margin-top: 30px;">Confirm Changes</h3>
                         <div class="form-group">
                            <label for="current_pass">Current Password</label>
                            <input type="password" name="current_pass" id="current_pass" class="form-control" placeholder="Enter password to save changes" required>
                        </div>
                        
                        <button type="submit" class="btn"><i class="fas fa-save"></i> Save Changes</button>
                    </form>
                </div>
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