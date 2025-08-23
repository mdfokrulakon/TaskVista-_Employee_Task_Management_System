<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Tasks - Admin Dashboard</title>
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

        /* Tasks Table */
        .tasks-table-container {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            padding: 20px;
            overflow-x: auto;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-header h3 {
            color: var(--dark);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: var(--light);
            font-weight: 600;
            color: var(--dark);
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }

        .status-in-progress {
            background-color: rgba(0, 123, 255, 0.15);
            color: #007bff;
        }

        .status-completed {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }
        
        .priority-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .priority-high {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }
        
        .priority-medium {
            background-color: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }
        
        .priority-low {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .edit-btn {
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            display: flex;
            align-items: center;
            background-color: rgba(58, 12, 163, 0.1);
            color: #3a0ca3;
        }

        .edit-btn:hover {
            background-color: rgba(58, 12, 163, 0.2);
        }

        .edit-btn i {
            margin-right: 5px;
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
        }

        @media (max-width: 768px) {
            .search-box {
                width: 200px;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
            
            th, td {
                padding: 10px 8px;
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
            
            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
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
                <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="admin_manage_user.php"><i class="fas fa-users-cog"></i> <span>Manage Users</span></a></li>
                <li><a href="admin_create_task.php"><i class="fas fa-tasks"></i> <span>Create Task</span></a></li>
                <li><a href="#" class="active"><i class="fas fa-list-alt"></i> <span>All Tasks</span></a></li>
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
                    <input type="text" placeholder="Search tasks..." id="searchInput">
                </div>
                <div class="user-info">
                    <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="User">
                    <div class="user-details">
                        <span class="user-name">John Doe</span>
                        <span class="user-role">Administrator</span>
                    </div>
                </div>
            </div>

            <!-- Page Title -->
            <div class="dashboard-title">
                <h2>All Tasks</h2>
                <p>View and manage all tasks in the system</p>
            </div>

            <!-- Tasks Table -->
            <div class="tasks-table-container">
                <div class="table-header">
                    <h3>Task List</h3>
                    <div class="filter-controls">
                        <select id="statusFilter">
                            <option value="all">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                        <select id="priorityFilter">
                            <option value="all">All Priorities</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                </div>

                <table id="tasksTable">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Assigned To</th>
                            <th>Due Date</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Design new dashboard layout</td>
                            <td>Create a modern and responsive dashboard UI</td>
                            <td>Sarah Johnson</td>
                            <td>2023-06-30</td>
                            <td><span class="priority-badge priority-high">High</span></td>
                            <td><span class="status-badge status-in-progress">In Progress</span></td>
                            <td class="action-buttons">
                                <button class="edit-btn"><i class="fas fa-edit"></i> Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Update documentation</td>
                            <td>Update API documentation for version 2.0</td>
                            <td>Michael Brown</td>
                            <td>2023-07-05</td>
                            <td><span class="priority-badge priority-medium">Medium</span></td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td class="action-buttons">
                                <button class="edit-btn"><i class="fas fa-edit"></i> Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Fix login authentication issue</td>
                            <td>Resolve the OAuth2 authentication bug</td>
                            <td>Robert Davis</td>
                            <td>2023-06-25</td>
                            <td><span class="priority-badge priority-high">High</span></td>
                            <td><span class="status-badge status-completed">Completed</span></td>
                            <td class="action-buttons">
                                <button class="edit-btn"><i class="fas fa-edit"></i> Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Prepare quarterly report</td>
                            <td>Compile financial reports for Q2 2023</td>
                            <td>Emily Wilson</td>
                            <td>2023-07-10</td>
                            <td><span class="priority-badge priority-low">Low</span></td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td class="action-buttons">
                                <button class="edit-btn"><i class="fas fa-edit"></i> Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Implement push notifications</td>
                            <td>Add push notification system for mobile app</td>
                            <td>David Miller</td>
                            <td>2023-07-15</td>
                            <td><span class="priority-badge priority-medium">Medium</span></td>
                            <td><span class="status-badge status-in-progress">In Progress</span></td>
                            <td class="action-buttons">
                                <button class="edit-btn"><i class="fas fa-edit"></i> Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Client meeting preparation</td>
                            <td>Prepare slides and demo for client meeting</td>
                            <td>Jessica Taylor</td>
                            <td>2023-06-28</td>
                            <td><span class="priority-badge priority-high">High</span></td>
                            <td><span class="status-badge status-completed">Completed</span></td>
                            <td class="action-buttons">
                                <button class="edit-btn"><i class="fas fa-edit"></i> Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Server maintenance</td>
                            <td>Perform routine server maintenance and updates</td>
                            <td>Daniel Wilson</td>
                            <td>2023-07-03</td>
                            <td><span class="priority-badge priority-medium">Medium</span></td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td class="action-buttons">
                                <button class="edit-btn"><i class="fas fa-edit"></i> Edit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
        
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const priorityFilter = document.getElementById('priorityFilter');
        const tableRows = document.querySelectorAll('#tasksTable tbody tr');
        
        function filterTasks() {
            const searchText = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            const priorityValue = priorityFilter.value;
            
            tableRows.forEach(row => {
                const title = row.cells[0].textContent.toLowerCase();
                const description = row.cells[1].textContent.toLowerCase();
                const assignedTo = row.cells[2].textContent.toLowerCase();
                const priority = row.cells[4].textContent.toLowerCase();
                const status = row.cells[5].textContent.toLowerCase();
                
                const matchesSearch = title.includes(searchText) || 
                                     description.includes(searchText) || 
                                     assignedTo.includes(searchText);
                
                const matchesStatus = statusValue === 'all' || status.includes(statusValue);
                const matchesPriority = priorityValue === 'all' || priority.includes(priorityValue);
                
                if (matchesSearch && matchesStatus && matchesPriority) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('input', filterTasks);
        statusFilter.addEventListener('change', filterTasks);
        priorityFilter.addEventListener('change', filterTasks);
        
        // Add event listeners to edit buttons
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const taskTitle = this.closest('tr').cells[0].textContent;
                alert(`Edit task: ${taskTitle}`);
                // In a real application, this would open a modal or navigate to an edit page
            });
        });
    </script>
</body>
</html>