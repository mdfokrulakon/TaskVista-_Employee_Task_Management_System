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

        .stat-icon.users {
            background-color: rgba(67, 97, 238, 0.15);
            color: var(--primary);
        }

        .stat-icon.tasks {
            background-color: rgba(76, 201, 240, 0.15);
            color: var(--success);
        }

        .stat-icon.leaves {
            background-color: rgba(247, 37, 133, 0.15);
            color: var(--warning);
        }

        .stat-icon.completed {
            background-color: rgba(58, 12, 163, 0.15);
            color: #3a0ca3;
        }

        .stat-info h3 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #6c757d;
            font-size: 14px;
        }

        /* Recent Activities */
        .recent-activities {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            padding: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .card-header h3 {
            color: var(--dark);
        }

        .card-header a {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
        }

        /* Tasks List */
        .task-list {
            list-style: none;
        }

        .task-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .task-item:last-child {
            border-bottom: none;
        }

        .task-check {
            margin-right: 15px;
        }

        .task-info {
            flex: 1;
        }

        .task-title {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .task-desc {
            color: #6c757d;
            font-size: 13px;
        }

        .task-priority {
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 20px;
            background-color: #eee;
        }

        .priority-high {
            background-color: rgba(247, 37, 133, 0.15);
            color: var(--warning);
        }

        .priority-medium {
            background-color: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }

        .priority-low {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        /* Recent Leaves */
        .leave-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .leave-item:last-child {
            border-bottom: none;
        }

        .leave-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }

        .leave-info {
            flex: 1;
        }

        .leave-name {
            font-weight: 500;
            margin-bottom: 3px;
        }

        .leave-dates {
            color: #6c757d;
            font-size: 13px;
        }

        .leave-status {
            font-size: 12px;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .status-approved {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }

        .status-rejected {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
            }
            
            .sidebar .logo h1 span, 
            .sidebar .nav-links a span {
                display: none;
            }
            
            .sidebar .logo h1 {
                justify-content: center;
            }
            
            .sidebar .nav-links a {
                justify-content: center;
                padding: 15px;
            }
            
            .sidebar .nav-links i {
                margin-right: 0;
                font-size: 20px;
            }
            
            .main-content {
                margin-left: 80px;
            }
            
            .recent-activities {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .stats {
                grid-template-columns: 1fr;
            }
            
            .search-box {
                width: 200px;
            }
        }

        @media (max-width: 576px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }
            
            .search-box {
                width: 100%;
            }
            
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1100;
                background: var(--primary);
                color: white;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
            }
            
            .sidebar.open {
                width: 250px;
            }
            
            .sidebar.open .logo h1 span, 
            .sidebar.open .nav-links a span {
                display: inline;
            }
            
            .sidebar.open .nav-links a {
                justify-content: flex-start;
                padding: 15px 20px;
            }
            
            .sidebar.open .nav-links i {
                margin-right: 15px;
            }
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
                <li><a href="#" class="active"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="admin_manage_user.php"><i class="fas fa-users-cog"></i> <span>Manage Users</span></a></li>
                <li><a href="admin_create_task.php"><i class="fas fa-tasks"></i> <span>Create Task</span></a></li>
                <li><a href="admin_all task.php"><i class="fas fa-list-alt"></i> <span>All Tasks</span></a></li>
                <li><a href="admin_leave.php"><i class="fas fa-calendar-alt"></i> <span>Leave Requests</span></a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Change Password</span></a></li>
                <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <div class="user-info">
                    <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="User">
                    <div class="user-details">
                        <span class="user-name">John Doe</span>
                        <span class="user-role">Administrator</span>
                    </div>
                </div>
            </div>

            <!-- Dashboard Title -->
            <div class="dashboard-title">
                <h2>Welcome back, John!</h2>
                <p>Here's what's happening with your team today.</p>
            </div>

            <!-- Stats Cards -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>245</h3>
                        <p>Total Users</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon tasks">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-info">
                        <h3>128</h3>
                        <p>Pending Tasks</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon leaves">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>42</h3>
                        <p>Leave Requests</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon completed">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>87</h3>
                        <p>Completed Tasks</p>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="recent-activities">
                <!-- Recent Tasks -->
                <div class="card">
                    <div class="card-header">
                        <h3>Recent Tasks</h3>
                        <a href="#">View All</a>
                    </div>
                    <ul class="task-list">
                        <li class="task-item">
                            <div class="task-check">
                                <input type="checkbox">
                            </div>
                            <div class="task-info">
                                <div class="task-title">Design new dashboard layout</div>
                                <div class="task-desc">UI/Design Team</div>
                            </div>
                            <span class="task-priority priority-high">High</span>
                        </li>
                        <li class="task-item">
                            <div class="task-check">
                                <input type="checkbox">
                            </div>
                            <div class="task-info">
                                <div class="task-title">Update documentation</div>
                                <div class="task-desc">Technical Writers</div>
                            </div>
                            <span class="task-priority priority-medium">Medium</span>
                        </li>
                        <li class="task-item">
                            <div class="task-check">
                                <input type="checkbox">
                            </div>
                            <div class="task-info">
                                <div class="task-title">Fix login authentication issue</div>
                                <div class="task-desc">Development Team</div>
                            </div>
                            <span class="task-priority priority-high">High</span>
                        </li>
                        <li class="task-item">
                            <div class="task-check">
                                <input type="checkbox">
                            </div>
                            <div class="task-info">
                                <div class="task-title">Prepare quarterly report</div>
                                <div class="task-desc">Finance Department</div>
                            </div>
                            <span class="task-priority priority-low">Low</span>
                        </li>
                    </ul>
                </div>

                <!-- Recent Leave Requests -->
                <div class="card">
                    <div class="card-header">
                        <h3>Recent Leaves</h3>
                        <a href="#">View All</a>
                    </div>
                    <div class="leave-list">
                        <div class="leave-item">
                            <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="User" class="leave-avatar">
                            <div class="leave-info">
                                <div class="leave-name">Sarah Johnson</div>
                                <div class="leave-dates">Jun 15 - Jun 20, 2023</div>
                            </div>
                            <span class="leave-status status-approved">Approved</span>
                        </div>
                        <div class="leave-item">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="leave-avatar">
                            <div class="leave-info">
                                <div class="leave-name">Michael Brown</div>
                                <div class="leave-dates">Jun 18 - Jun 19, 2023</div>
                            </div>
                            <span class="leave-status status-pending">Pending</span>
                        </div>
                        <div class="leave-item">
                            <img src="https://randomuser.me/api/portraits/women/28.jpg" alt="User" class="leave-avatar">
                            <div class="leave-info">
                                <div class="leave-name">Emily Wilson</div>
                                <div class="leave-dates">Jun 22 - Jun 25, 2023</div>
                            </div>
                            <span class="leave-status status-rejected">Rejected</span>
                        </div>
                        <div class="leave-item">
                            <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="User" class="leave-avatar">
                            <div class="leave-info">
                                <div class="leave-name">Robert Davis</div>
                                <div class="leave-dates">Jun 28 - Jun 30, 2023</div>
                            </div>
                            <span class="leave-status status-pending">Pending</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Toggle -->
    <div class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </div>

    <script>
        // Toggle sidebar on mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });
        
        // Simulate task completion
        const taskCheckboxes = document.querySelectorAll('.task-check input');
        taskCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.parentElement.parentElement.style.opacity = "0.6";
                    this.parentElement.parentElement.style.textDecoration = "line-through";
                } else {
                    this.parentElement.parentElement.style.opacity = "1";
                    this.parentElement.parentElement.style.textDecoration = "none";
                }
            });
        });
    </script>
</body>
</html>