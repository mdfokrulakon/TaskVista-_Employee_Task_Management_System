<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - My Tasks</title>
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

        /* Task Filters */
        .task-filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 16px;
            background: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: var(--card-shadow);
            font-weight: 500;
            transition: all 0.3s;
        }

        .filter-btn.active {
            background: var(--primary);
            color: white;
        }

        .filter-btn:hover {
            background: var(--primary);
            color: white;
        }

        /* Task Cards */
        .task-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .task-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            position: relative;
            transition: transform 0.3s;
        }

        .task-card:hover {
            transform: translateY(-5px);
        }

        .task-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .task-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
            margin-right: 15px;
        }

        .task-priority {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            white-space: nowrap;
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

        .task-description {
            color: #6c757d;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .task-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .task-deadline {
            display: flex;
            align-items: center;
            color: #6c757d;
        }

        .task-deadline i {
            margin-right: 5px;
            color: var(--primary);
        }

        .task-status {
            padding: 4px 10px;
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

        .task-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-progress {
            flex: 1;
            margin-right: 15px;
        }

        .progress-bar {
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-value {
            height: 100%;
            border-radius: 4px;
            background-color: var(--primary);
        }

        .progress-text {
            font-size: 12px;
            text-align: right;
            color: #6c757d;
            margin-top: 5px;
        }

        .task-buttons {
            display: flex;
            gap: 10px;
        }

        .task-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-start {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .btn-start:hover {
            background-color: var(--primary);
            color: white;
        }

        .btn-complete {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .btn-complete:hover {
            background-color: #28a745;
            color: white;
        }

        /* Task Detail Modal */
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
            width: 90%;
            max-width: 600px;
            padding: 25px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .modal-title {
            font-size: 22px;
            color: var(--dark);
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6c757d;
        }

        .task-detail-item {
            margin-bottom: 15px;
        }

        .task-detail-label {
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .task-detail-value {
            color: var(--dark);
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
            .task-cards {
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
            
            .task-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .task-progress {
                width: 100%;
                margin-right: 0;
            }
            
            .task-buttons {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <h1><i class="fas fa-tachometer-alt"></i> <span>Employee Panel</span></h1>
            </div>
            <ul class="nav-links">
                <li><a href="#"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="#" class="active"><i class="fas fa-tasks"></i> <span>My Tasks</span></a></li>
                <li><a href="#"><i class="fas fa-calendar-alt"></i> <span>My Leaves</span></a></li>
                <li><a href="#"><i class="fas fa-user-circle"></i> <span>Profile</span></a></li>
                <li><a href="#"><i class="fas fa-key"></i> <span>Change Password</span></a></li>
                <li><a href="#"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search tasks...">
                </div>
                <div class="user-info">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User">
                    <div class="user-details">
                        <span class="user-name">Sarah Johnson</span>
                        <span class="user-role">Software Developer</span>
                    </div>
                </div>
            </div>

            <!-- Dashboard Title -->
            <div class="dashboard-title">
                <h2>My Tasks</h2>
                <p>Manage and track your assigned tasks</p>
            </div>

            <!-- Task Filters -->
            <div class="task-filters">
                <button class="filter-btn active">All Tasks</button>
                <button class="filter-btn">Pending</button>
                <button class="filter-btn">In Progress</button>
                <button class="filter-btn">Completed</button>
                <button class="filter-btn">High Priority</button>
            </div>

            <!-- Task Cards -->
            <div class="task-cards">
                <!-- Task 1 -->
                <div class="task-card">
                    <div class="task-card-header">
                        <h3 class="task-title">Design Dashboard UI</h3>
                        <span class="task-priority priority-high">High</span>
                    </div>
                    <p class="task-description">Create a modern and responsive dashboard UI for the new admin panel with all required components.</p>
                    <div class="task-meta">
                        <div class="task-deadline">
                            <i class="far fa-calendar-alt"></i>
                            <span>Due: Jun 30, 2023</span>
                        </div>
                        <span class="task-status status-in-progress">In Progress</span>
                    </div>
                    <div class="task-actions">
                        <div class="task-progress">
                            <div class="progress-bar">
                                <div class="progress-value" style="width: 60%;"></div>
                            </div>
                            <div class="progress-text">60% Complete</div>
                        </div>
                        <div class="task-buttons">
                            <button class="task-btn btn-complete">Complete</button>
                            <button class="task-btn btn-start view-details">Details</button>
                        </div>
                    </div>
                </div>

                <!-- Task 2 -->
                <div class="task-card">
                    <div class="task-card-header">
                        <h3 class="task-title">Update Documentation</h3>
                        <span class="task-priority priority-medium">Medium</span>
                    </div>
                    <p class="task-description">Update API documentation to reflect recent changes to the authentication endpoints.</p>
                    <div class="task-meta">
                        <div class="task-deadline">
                            <i class="far fa-calendar-alt"></i>
                            <span>Due: Jun 25, 2023</span>
                        </div>
                        <span class="task-status status-pending">Pending</span>
                    </div>
                    <div class="task-actions">
                        <div class="task-progress">
                            <div class="progress-bar">
                                <div class="progress-value" style="width: 0%;"></div>
                            </div>
                            <div class="progress-text">0% Complete</div>
                        </div>
                        <div class="task-buttons">
                            <button class="task-btn btn-start">Start Task</button>
                            <button class="task-btn btn-start view-details">Details</button>
                        </div>
                    </div>
                </div>

                <!-- Task 3 -->
                <div class="task-card">
                    <div class="task-card-header">
                        <h3 class="task-title">Fix Login Issue</h3>
                        <span class="task-priority priority-high">High</span>
                    </div>
                    <p class="task-description">Resolve the authentication token expiration issue reported by users.</p>
                    <div class="task-meta">
                        <div class="task-deadline">
                            <i class="far fa-calendar-alt"></i>
                            <span>Due: Jun 20, 2023</span>
                        </div>
                        <span class="task-status status-completed">Completed</span>
                    </div>
                    <div class="task-actions">
                        <div class="task-progress">
                            <div class="progress-bar">
                                <div class="progress-value" style="width: 100%;"></div>
                            </div>
                            <div class="progress-text">100% Complete</div>
                        </div>
                        <div class="task-buttons">
                            <button class="task-btn btn-start" disabled>Completed</button>
                            <button class="task-btn btn-start view-details">Details</button>
                        </div>
                    </div>
                </div>

                <!-- Task 4 -->
                <div class="task-card">
                    <div class="task-card-header">
                        <h3 class="task-title">Write Unit Tests</h3>
                        <span class="task-priority priority-medium">Medium</span>
                    </div>
                    <p class="task-description">Create comprehensive unit tests for the new user management module.</p>
                    <div class="task-meta">
                        <div class="task-deadline">
                            <i class="far fa-calendar-alt"></i>
                            <span>Due: Jul 5, 2023</span>
                        </div>
                        <span class="task-status status-in-progress">In Progress</span>
                    </div>
                    <div class="task-actions">
                        <div class="task-progress">
                            <div class="progress-bar">
                                <div class="progress-value" style="width: 30%;"></div>
                            </div>
                            <div class="progress-text">30% Complete</div>
                        </div>
                        <div class="task-buttons">
                            <button class="task-btn btn-complete">Complete</button>
                            <button class="task-btn btn-start view-details">Details</button>
                        </div>
                    </div>
                </div>

                <!-- Task 5 -->
                <div class="task-card">
                    <div class="task-card-header">
                        <h3 class="task-title">Prepare Meeting Agenda</h3>
                        <span class="task-priority priority-low">Low</span>
                    </div>
                    <p class="task-description">Prepare the agenda for the upcoming sprint planning meeting.</p>
                    <div class="task-meta">
                        <div class="task-deadline">
                            <i class="far fa-calendar-alt"></i>
                            <span>Due: Jun 22, 2023</span>
                        </div>
                        <span class="task-status status-pending">Pending</span>
                    </div>
                    <div class="task-actions">
                        <div class="task-progress">
                            <div class="progress-bar">
                                <div class="progress-value" style="width: 0%;"></div>
                            </div>
                            <div class="progress-text">0% Complete</div>
                        </div>
                        <div class="task-buttons">
                            <button class="task-btn btn-start">Start Task</button>
                            <button class="task-btn btn-start view-details">Details</button>
                        </div>
                    </div>
                </div>

                <!-- Task 6 -->
                <div class="task-card">
                    <div class="task-card-header">
                        <h3 class="task-title">Code Review</h3>
                        <span class="task-priority priority-medium">Medium</span>
                    </div>
                    <p class="task-description">Perform code review for the recent pull requests in the development branch.</p>
                    <div class="task-meta">
                        <div class="task-deadline">
                            <i class="far fa-calendar-alt"></i>
                            <span>Due: Jun 23, 2023</span>
                        </div>
                        <span class="task-status status-completed">Completed</span>
                    </div>
                    <div class="task-actions">
                        <div class="task-progress">
                            <div class="progress-bar">
                                <div class="progress-value" style="width: 100%;"></div>
                            </div>
                            <div class="progress-text">100% Complete</div>
                        </div>
                        <div class="task-buttons">
                            <button class="task-btn btn-start" disabled>Completed</button>
                            <button class="task-btn btn-start view-details">Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Detail Modal -->
    <div class="modal" id="taskModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Task Details</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="task-details">
                <div class="task-detail-item">
                    <div class="task-detail-label">Task Title</div>
                    <div class="task-detail-value" id="detail-title">Design Dashboard UI</div>
                </div>
                <div class="task-detail-item">
                    <div class="task-detail-label">Description</div>
                    <div class="task-detail-value" id="detail-description">Create a modern and responsive dashboard UI for the new admin panel with all required components.</div>
                </div>
                <div class="task-detail-item">
                    <div class="task-detail-label">Priority</div>
                    <div class="task-detail-value" id="detail-priority">High</div>
                </div>
                <div class="task-detail-item">
                    <div class="task-detail-label">Status</div>
                    <div class="task-detail-value" id="detail-status">In Progress</div>
                </div>
                <div class="task-detail-item">
                    <div class="task-detail-label">Due Date</div>
                    <div class="task-detail-value" id="detail-due">Jun 30, 2023</div>
                </div>
                <div class="task-detail-item">
                    <div class="task-detail-label">Assigned By</div>
                    <div class="task-detail-value" id="detail-assigned">John Doe (Project Manager)</div>
                </div>
                <div class="task-detail-item">
                    <div class="task-detail-label">Progress</div>
                    <div class="task-detail-value">
                        <div class="task-progress">
                            <div class="progress-bar">
                                <div class="progress-value" style="width: 60%;"></div>
                            </div>
                            <div class="progress-text">60% Complete</div>
                        </div>
                    </div>
                </div>
                <div class="task-detail-item">
                    <div class="task-detail-label">Notes</div>
                    <div class="task-detail-value">Please follow the design guidelines shared in the Figma file. Focus on making it accessible and responsive.</div>
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
        
        // Task detail modal functionality
        const viewDetailButtons = document.querySelectorAll('.view-details');
        const taskModal = document.getElementById('taskModal');
        const closeModal = document.querySelector('.close-modal');
        
        viewDetailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const taskCard = this.closest('.task-card');
                const title = taskCard.querySelector('.task-title').textContent;
                const description = taskCard.querySelector('.task-description').textContent;
                const priority = taskCard.querySelector('.task-priority').textContent;
                const status = taskCard.querySelector('.task-status').textContent;
                const dueDate = taskCard.querySelector('.task-deadline span').textContent.replace('Due: ', '');
                
                document.getElementById('detail-title').textContent = title;
                document.getElementById('detail-description').textContent = description;
                document.getElementById('detail-priority').textContent = priority;
                document.getElementById('detail-status').textContent = status;
                document.getElementById('detail-due').textContent = dueDate;
                
                taskModal.style.display = 'flex';
            });
        });
        
        closeModal.addEventListener('click', function() {
            taskModal.style.display = 'none';
        });
        
        window.addEventListener('click', function(event) {
            if (event.target === taskModal) {
                taskModal.style.display = 'none';
            }
        });
        
        // Filter buttons functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                // In a real application, this would filter the tasks
            });
        });
        
        // Start task buttons
        const startButtons = document.querySelectorAll('.btn-start');
        
        startButtons.forEach(button => {
            if (button.textContent === 'Start Task') {
                button.addEventListener('click', function() {
                    const taskCard = this.closest('.task-card');
                    const statusElement = taskCard.querySelector('.task-status');
                    const progressValue = taskCard.querySelector('.progress-value');
                    const progressText = taskCard.querySelector('.progress-text');
                    const completeButton = taskCard.querySelector('.btn-complete');
                    
                    statusElement.textContent = 'In Progress';
                    statusElement.className = 'task-status status-in-progress';
                    progressValue.style.width = '10%';
                    progressText.textContent = '10% Complete';
                    this.textContent = 'Details';
                    this.classList.remove('btn-start');
                    
                    if (completeButton) {
                        completeButton.style.display = 'block';
                    }
                });
            }
        });
        
        // Complete task buttons
        const completeButtons = document.querySelectorAll('.btn-complete');
        
        completeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const taskCard = this.closest('.task-card');
                const statusElement = taskCard.querySelector('.task-status');
                const progressValue = taskCard.querySelector('.progress-value');
                const progressText = taskCard.querySelector('.progress-text');
                
                statusElement.textContent = 'Completed';
                statusElement.className = 'task-status status-completed';
                progressValue.style.width = '100%';
                progressText.textContent = '100% Complete';
                this.textContent = 'Completed';
                this.disabled = true;
                this.classList.remove('btn-complete');
            });
        });
    </script>
</body>
</html>