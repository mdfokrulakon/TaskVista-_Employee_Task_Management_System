<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Admin Dashboard</title>
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

        /* Change Password Card */
        .change-password-card {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .input-with-icon input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .input-with-icon input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            outline: none;
        }

        .password-requirements {
            background-color: var(--light);
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            margin-bottom: 25px;
        }

        .password-requirements h4 {
            margin-bottom: 10px;
            color: var(--dark);
            font-size: 16px;
        }

        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .requirement i {
            margin-right: 10px;
            font-size: 12px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e9ecef;
            color: #6c757d;
        }

        .requirement.valid i {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        .button-group {
            display: flex;
            gap: 15px;
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
        }

        .submit-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .cancel-btn {
            background: #f8f9fa;
            color: #6c757d;
            border: 1px solid #ddd;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
        }

        .cancel-btn:hover {
            background-color: #e9ecef;
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
            
            .button-group {
                flex-direction: column;
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
                <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="admin_manage_user.php"><i class="fas fa-users-cog"></i> <span>Manage Users</span></a></li>
                <li><a href="admin_create_task.php"><i class="fas fa-tasks"></i> <span>Create Task</span></a></li>
                <li><a href="admin_all task.php"><i class="fas fa-list-alt"></i> <span>All Tasks</span></a></li>
                <li><a href="admin_leave.php"><i class="fas fa-calendar-alt"></i> <span>Leave Requests</span></a></li>
                <li><a href="#" class="active"><i class="fas fa-key"></i> <span>Change Password</span></a></li>
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

            <!-- Page Title -->
            <div class="dashboard-title">
                <h2>Change Password</h2>
                <p>Update your account password</p>
            </div>

            <!-- Change Password Form -->
            <div class="change-password-card">
                <form id="changePasswordForm">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="currentPassword" placeholder="Enter your current password" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <div class="input-with-icon">
                            <i class="fas fa-key"></i>
                            <input type="password" id="newPassword" placeholder="Enter your new password" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <div class="input-with-icon">
                            <i class="fas fa-check-circle"></i>
                            <input type="password" id="confirmPassword" placeholder="Confirm your new password" required>
                        </div>
                    </div>
                    
                    <div class="password-requirements">
                        <h4>Password Requirements:</h4>
                        <div class="requirement" id="reqLength">
                            <i class="fas fa-times"></i>
                            <span>At least 8 characters long</span>
                        </div>
                        <div class="requirement" id="reqUppercase">
                            <i class="fas fa-times"></i>
                            <span>Contains uppercase letters</span>
                        </div>
                        <div class="requirement" id="reqLowercase">
                            <i class="fas fa-times"></i>
                            <span>Contains lowercase letters</span>
                        </div>
                        <div class="requirement" id="reqNumber">
                            <i class="fas fa-times"></i>
                            <span>Contains numbers</span>
                        </div>
                        <div class="requirement" id="reqSpecial">
                            <i class="fas fa-times"></i>
                            <span>Contains special characters</span>
                        </div>
                    </div>
                    
                    <div class="button-group">
                        <button type="submit" class="submit-btn">Update Password</button>
                        <button type="button" class="cancel-btn">Cancel</button>
                    </div>
                </form>
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
        
        // Password validation functionality
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('changePasswordForm');
            const newPasswordInput = document.getElementById('newPassword');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const currentPasswordInput = document.getElementById('currentPassword');
            
            // Password requirement elements
            const reqLength = document.getElementById('reqLength');
            const reqUppercase = document.getElementById('reqUppercase');
            const reqLowercase = document.getElementById('reqLowercase');
            const reqNumber = document.getElementById('reqNumber');
            const reqSpecial = document.getElementById('reqSpecial');
            
            // Validate password as user types
            newPasswordInput.addEventListener('input', function() {
                const password = this.value;
                
                // Check length
                if (password.length >= 8) {
                    reqLength.classList.add('valid');
                    reqLength.innerHTML = '<i class="fas fa-check"></i> At least 8 characters long';
                } else {
                    reqLength.classList.remove('valid');
                    reqLength.innerHTML = '<i class="fas fa-times"></i> At least 8 characters long';
                }
                
                // Check uppercase
                if (/[A-Z]/.test(password)) {
                    reqUppercase.classList.add('valid');
                    reqUppercase.innerHTML = '<i class="fas fa-check"></i> Contains uppercase letters';
                } else {
                    reqUppercase.classList.remove('valid');
                    reqUppercase.innerHTML = '<i class="fas fa-times"></i> Contains uppercase letters';
                }
                
                // Check lowercase
                if (/[a-z]/.test(password)) {
                    reqLowercase.classList.add('valid');
                    reqLowercase.innerHTML = '<i class="fas fa-check"></i> Contains lowercase letters';
                } else {
                    reqLowercase.classList.remove('valid');
                    reqLowercase.innerHTML = '<i class="fas fa-times"></i> Contains lowercase letters';
                }
                
                // Check numbers
                if (/[0-9]/.test(password)) {
                    reqNumber.classList.add('valid');
                    reqNumber.innerHTML = '<i class="fas fa-check"></i> Contains numbers';
                } else {
                    reqNumber.classList.remove('valid');
                    reqNumber.innerHTML = '<i class="fas fa-times"></i> Contains numbers';
                }
                
                // Check special characters
                if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
                    reqSpecial.classList.add('valid');
                    reqSpecial.innerHTML = '<i class="fas fa-check"></i> Contains special characters';
                } else {
                    reqSpecial.classList.remove('valid');
                    reqSpecial.innerHTML = '<i class="fas fa-times"></i> Contains special characters';
                }
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const currentPassword = currentPasswordInput.value;
                const newPassword = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                // Basic validation
                if (!currentPassword) {
                    alert('Please enter your current password');
                    return;
                }
                
                if (newPassword !== confirmPassword) {
                    alert('New password and confirmation do not match');
                    return;
                }
                
                // Check if password meets all requirements
                const requirements = document.querySelectorAll('.requirement.valid');
                if (requirements.length !== 5) {
                    alert('Please ensure your password meets all requirements');
                    return;
                }
                
                // Simulate password change process
                const submitBtn = form.querySelector('.submit-btn');
                const originalText = submitBtn.textContent;
                
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                submitBtn.disabled = true;
                
                // Simulate API call
                setTimeout(() => {
                    // Reset button
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                    
                    // Show success message
                    alert('Password updated successfully!');
                    
                    // Clear form
                    form.reset();
                    
                    // Reset requirement indicators
                    document.querySelectorAll('.requirement').forEach(req => {
                        req.classList.remove('valid');
                        req.innerHTML = req.innerHTML.replace('fa-check', 'fa-times');
                    });
                }, 1500);
            });
            
            // Cancel button functionality
            document.querySelector('.cancel-btn').addEventListener('click', function() {
                form.reset();
                
                // Reset requirement indicators
                document.querySelectorAll('.requirement').forEach(req => {
                    req.classList.remove('valid');
                    req.innerHTML = req.innerHTML.replace('fa-check', 'fa-times');
                });
            });
        });
    </script>
</body>
</html>