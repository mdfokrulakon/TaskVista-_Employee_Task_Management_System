<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Leave Requests</title>
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

        /* Filters */
        .filters {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .filter-select {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: white;
            font-size: 14px;
        }

        /* Leave Requests Table */
        .leave-requests {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            padding: 20px;
            margin-bottom: 30px;
            overflow-x: auto;
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

        .requests-table {
            width: 100%;
            border-collapse: collapse;
        }

        .requests-table th,
        .requests-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .requests-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .requests-table tr:hover {
            background-color: #f8f9fa;
        }

        .user-cell {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        /* Status badges */
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

        .status-approved {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        .status-rejected {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-approve {
            background-color: #28a745;
            color: white;
        }

        .btn-approve:hover {
            background-color: #218838;
        }

        .btn-reject {
            background-color: #dc3545;
            color: white;
        }

        .btn-reject:hover {
            background-color: #c82333;
        }

        .btn-view {
            background-color: #17a2b8;
            color: white;
        }

        .btn-view:hover {
            background-color: #138496;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 10px;
            width: 500px;
            max-width: 90%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            color: var(--dark);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #6c757d;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-details {
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            margin-bottom: 12px;
        }

        .detail-label {
            width: 120px;
            font-weight: 500;
            color: #495057;
        }

        .detail-value {
            flex: 1;
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
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
            
            .filters {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }

        @media (max-width: 768px) {
            .stats {
                grid-template-columns: 1fr;
            }
            
            .search-box {
                width: 200px;
            }
            
            .requests-table {
                font-size: 14px;
            }
            
            .requests-table th,
            .requests-table td {
                padding: 10px;
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
            
            .filter-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 5px;
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
                <li><a href="admin_all task.php"><i class="fas fa-list-alt"></i> <span>All Tasks</span></a></li>
                <li><a href="#" class="active"><i class="fas fa-calendar-alt"></i> <span>Leave Requests</span></a></li>
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
                <h2>Leave Requests Management</h2>
                <p>Review and manage employee leave requests</p>
            </div>

            <!-- Stats Cards -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-icon leaves">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>42</h3>
                        <p>Pending Requests</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon completed">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>128</h3>
                        <p>Approved Requests</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon tasks">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>24</h3>
                        <p>Rejected Requests</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>194</h3>
                        <p>Total Requests</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters">
                <div class="filter-group">
                    <label>Filter by:</label>
                    <select class="filter-select">
                        <option>All Requests</option>
                        <option>Pending</option>
                        <option>Approved</option>
                        <option>Rejected</option>
                    </select>
                    <select class="filter-select">
                        <option>All Types</option>
                        <option>Vacation</option>
                        <option>Sick Leave</option>
                        <option>Personal</option>
                        <option>Maternity</option>
                        <option>Paternity</option>
                    </select>
                </div>
                <div class="filter-group">
                    <input type="date" class="filter-select">
                    <span>to</span>
                    <input type="date" class="filter-select">
                </div>
            </div>

            <!-- Leave Requests Table -->
            <div class="leave-requests">
                <div class="card-header">
                    <h3>Recent Leave Requests</h3>
                </div>
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="User" class="user-avatar">
                                    <span>Sarah Johnson</span>
                                </div>
                            </td>
                            <td>Vacation</td>
                            <td>Jun 15, 2023</td>
                            <td>Jun 20, 2023</td>
                            <td>5 days</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-approve">Approve</button>
                                    <button class="btn btn-reject">Reject</button>
                                    <button class="btn btn-view" onclick="openModal()">View</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="user-avatar">
                                    <span>Michael Brown</span>
                                </div>
                            </td>
                            <td>Sick Leave</td>
                            <td>Jun 18, 2023</td>
                            <td>Jun 19, 2023</td>
                            <td>2 days</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-approve">Approve</button>
                                    <button class="btn btn-reject">Reject</button>
                                    <button class="btn btn-view" onclick="openModal()">View</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <img src="https://randomuser.me/api/portraits/women/28.jpg" alt="User" class="user-avatar">
                                    <span>Emily Wilson</span>
                                </div>
                            </td>
                            <td>Personal</td>
                            <td>Jun 22, 2023</td>
                            <td>Jun 25, 2023</td>
                            <td>3 days</td>
                            <td><span class="status-badge status-approved">Approved</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-view" onclick="openModal()">View</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="User" class="user-avatar">
                                    <span>Robert Davis</span>
                                </div>
                            </td>
                            <td>Vacation</td>
                            <td>Jun 28, 2023</td>
                            <td>Jul 5, 2023</td>
                            <td>7 days</td>
                            <td><span class="status-badge status-rejected">Rejected</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-view" onclick="openModal()">View</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <img src="https://randomuser.me/api/portraits/women/43.jpg" alt="User" class="user-avatar">
                                    <span>Jennifer Lee</span>
                                </div>
                            </td>
                            <td>Maternity</td>
                            <td>Jul 1, 2023</td>
                            <td>Sep 30, 2023</td>
                            <td>92 days</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-approve">Approve</button>
                                    <button class="btn btn-reject">Reject</button>
                                    <button class="btn btn-view" onclick="openModal()">View</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="detailsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Leave Request Details</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-details">
                    <div class="detail-row">
                        <div class="detail-label">Employee:</div>
                        <div class="detail-value">Sarah Johnson (ID: 12345)</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Leave Type:</div>
                        <div class="detail-value">Vacation</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Duration:</div>
                        <div class="detail-value">Jun 15, 2023 to Jun 20, 2023 (5 days)</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Reason:</div>
                        <div class="detail-value">Family vacation to Hawaii. Need time to relax and recharge.</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Contact:</div>
                        <div class="detail-value">sarah.j@example.com / (555) 123-4567</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Submitted on:</div>
                        <div class="detail-value">Jun 10, 2023 at 2:30 PM</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Status:</div>
                        <div class="detail-value"><span class="status-badge status-pending">Pending</span></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-reject">Reject</button>
                <button class="btn btn-approve">Approve</button>
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

        // Modal functions
        function openModal() {
            document.getElementById('detailsModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('detailsModal');
            if (event.target === modal) {
                closeModal();
            }
        });

        // Approve/Reject buttons functionality
        const approveButtons = document.querySelectorAll('.btn-approve');
        const rejectButtons = document.querySelectorAll('.btn-reject');
        
        approveButtons.forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const statusCell = row.querySelector('td:nth-child(6)');
                statusCell.innerHTML = '<span class="status-badge status-approved">Approved</span>';
                
                const actionCell = row.querySelector('td:nth-child(7)');
                actionCell.innerHTML = '<div class="action-buttons"><button class="btn btn-view" onclick="openModal()">View</button></div>';
                
                // Show notification
                alert('Leave request approved successfully!');
            });
        });
        
        rejectButtons.forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const statusCell = row.querySelector('td:nth-child(6)');
                statusCell.innerHTML = '<span class="status-badge status-rejected">Rejected</span>';
                
                const actionCell = row.querySelector('td:nth-child(7)');
                actionCell.innerHTML = '<div class="action-buttons"><button class="btn btn-view" onclick="openModal()">View</button></div>';
                
                // Show notification
                alert('Leave request rejected!');
            });
        });
    </script>
</body>
</html>