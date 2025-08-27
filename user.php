<?php 
session_start();
// Ensure the user is logged in and is an admin
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/User.php";

    // Fetch all users from the database
    $users = get_all_users($conn);
  
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
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
            --danger: #f72585;
            --warning: #f9a826;
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
        .header { display: flex; justify-content: space-between; align-items: center; background-color: white; padding: 15px 25px; border-radius: 10px; box-shadow: var(--card-shadow); margin-bottom: 25px; }
        .user-info { display: flex; align-items: center; }
        .user-info img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px; border: 2px solid var(--primary); }
        .user-details { display: flex; flex-direction: column; }
        .user-name { font-weight: 600; }
        .user-role { font-size: 12px; color: #6c757d; }
        
        /* Page Title */
        .page-title { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .page-title h2 { color: var(--dark); font-size: 28px; }
        .btn { padding: 10px 20px; border-radius: 5px; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; background-color: var(--primary); color: white; transition: background-color 0.3s; }
        .btn i { margin-right: 8px; }
        .btn:hover { background-color: var(--secondary); }
        
        /* User Table */
        .user-table-container { background: white; border-radius: 10px; box-shadow: var(--card-shadow); padding: 20px; margin-bottom: 30px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: var(--dark); font-weight: 600; }
        tr:hover { background-color: #f9f9f9; }

        .role-badge { text-transform: capitalize; }
        
        .action-buttons { display: flex; gap: 10px; }
        .action-btn { text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; color: white; transition: opacity 0.3s; display: inline-flex; align-items: center;}
        .action-btn i { margin-right: 5px; }
        .action-btn.edit { background-color: var(--success); }
        .action-btn.delete { background-color: var(--danger); }
        .action-btn:hover { opacity: 0.8; }

        /* --- NEW: Animated Title CSS from the first file --- */
        .animated-title-text {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            background: linear-gradient(90deg, 
                var(--primary), 
                var(--success), 
                var(--secondary), 
                var(--primary)
            );
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
            animation: gradient-animation 4s linear infinite;
            white-space: pre; 
        }

        /* NEW: Keyframes for the gradient animation */
        @keyframes gradient-animation {
            to {
                background-position: 200% center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <h1><i class="fas fa-users-cog"></i> <span>Manage User</span></h1>
            </div>
            <ul class="nav-links">
                <!-- Navigation links -->
                <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="user.php" class="active"><i class="fas fa-users-cog"></i> <span>Manage Users</span></a></li>
                <li><a href="create_task.php"><i class="fas fa-tasks"></i> <span>Create Task</span></a></li>
                <li><a href="tasks.php"><i class="fas fa-list-alt"></i> <span>All Tasks</span></a></li>
                <li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                <li><a href="edit_profile.php"><i class="fas fa-user-edit"></i> <span>Edit Profile</span></a></li>
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
                <!-- MODIFIED: Replaced search box with the animated title -->
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
            <div class="page-title">
                <h2>User Management</h2>
                <a href="add-user.php" class="btn">
                    <i class="fas fa-plus"></i> Add New User
                </a>
            </div>
            
            <!-- User Table -->
            <div class="user-table-container">
                <!-- Success Message -->
                <?php if (isset($_GET['success'])) {?>
                    <div style="padding: 1rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem; color: #155724; background-color: #d4edda; border-color: #c3e6cb;" role="alert">
                      <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                <?php } ?>

                <!-- Check if users exist -->
                <?php if ($users != 0) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop to display users -->
                        <?php $i=0; foreach ($users as $user) { ?>
                        <tr>
                            <td><?=++$i?></td>
                            <td><?=$user['full_name']?></td>
                            <td><?=$user['username']?></td>
                            <td><span class="role-badge"><?=$user['role']?></span></td>
                            <td class="action-buttons">
                                <!-- Action Links -->
                                <a href="edit-user.php?id=<?=$user['id']?>" class="action-btn edit"><i class="fas fa-edit"></i> Edit</a>
                                <a href="delete-user.php?id=<?=$user['id']?>" class="action-btn delete"><i class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                       <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                     <!-- "Empty" Message -->
                    <div style="text-align: center; padding: 20px;">
                        <h3>No users found.</h3>
                        <p>Click the "Add New User" button to get started.</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php 
} else { 
   // Redirect to login if not logged in or not an admin
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
?>