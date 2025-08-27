<?php 
  // Start session to display potential messages
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login | Task Management System</title>
    <!-- Font Awesome for Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        :root {
            --primary: #4361ee; --secondary: #3f37c9; --success: #4cc9f0; --info: #4895ef; --warning: #f72585; --light: #f8f9fa; --dark: #212529; --background: #f0f2f5; --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        /* --- UPDATED CSS FOR BACKGROUND IMAGE --- */
        body {
    /* UPDATED: Only the background image is left */
    background: url('img/background-image.jpg'); /* IMPORTANT: Make sure this path is correct */

    /* These properties remain essential to make the image fit the screen */
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    background-attachment: fixed; 
    
    /* Other styles also remain the same */
    color: #333;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}
        /* --- END OF UPDATE --- */

        .login-container {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 550px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-left {
            flex: 1;
            /* This gradient is now only for the side panel */
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        /* ... the rest of your CSS is perfect and remains unchanged ... */
        .login-left h2 { font-size: 28px; margin-bottom: 20px; }
        .login-left p { margin-bottom: 30px; opacity: 0.9; }
        .login-right { flex: 1; padding: 40px; display: flex; flex-direction: column; justify-content: center; position: relative; }
        .login-logo { text-align: center; margin-bottom: 30px; }
        .login-logo h1 { font-size: 28px; color: var(--primary); display: flex; align-items: center; justify-content: center; }
        .login-logo i { margin-right: 10px; font-size: 32px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark); }
        .input-with-icon { position: relative; }
        .input-with-icon i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #6c757d; }
        .input-with-icon input, .input-with-icon select { width: 100%; padding: 12px 15px 12px 45px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; transition: all 0.3s; -webkit-appearance: none; -moz-appearance: none; appearance: none; background-color: white;}
        .input-with-icon input:focus, .input-with-icon select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15); outline: none; }
        .remember-forgot { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .remember-me { display: flex; align-items: center; }
        .remember-me input { margin-right: 8px; }
        .forgot-password { color: var(--primary); text-decoration: none; font-size: 14px; }
        .forgot-password:hover { text-decoration: underline; }
        .login-btn, .signup-btn { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; border: none; padding: 12px 20px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; width: 100%; transition: all 0.3s; }
        .login-btn:hover, .signup-btn:hover { opacity: 0.9; transform: translateY(-2px); }
        .separator { text-align: center; margin-top: 20px; color: #6c757d; }
        .separator a { color: var(--primary); text-decoration: none; font-weight: 600; cursor: pointer; }
        .separator a:hover { text-decoration: underline; }
        .php-alert { padding: 1rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem; }
        .php-alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .php-alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        #signupFormContainer { display: none; }
    </style>
</head>
<body>
    <!-- The rest of your HTML (forms, etc.) is perfect and remains unchanged -->
    <div class="login-container">
        <div class="login-left">
            <h2 id="welcome-title">TaskVista!</h2>
            <p id="welcome-text">"Clarity in Tasks, Excellence in Results"</p>
        </div>
        <div class="login-right">
            <!-- LOGIN FORM CONTAINER -->
            <div id="loginFormContainer">
                <div class="login-logo"><h1><i class="fas fa-lock"></i> LOGIN</h1></div>
                <form method="POST" action="app/login.php">
                    <?php if (isset($_GET['error'])) {?><div class="php-alert php-alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error']); ?></div><?php } ?>
                    <?php if (isset($_GET['success'])) {?><div class="php-alert php-alert-success" role="alert"><?php echo htmlspecialchars($_GET['success']); ?></div><?php } ?>
                    <div class="form-group"><label>Username</label><div class="input-with-icon"><i class="fas fa-user"></i><input type="text" name="user_name" placeholder="Enter your username" required></div></div>
                    <div class="form-group"><label>Password</label><div class="input-with-icon"><i class="fas fa-lock"></i><input type="password" name="password" placeholder="Enter your password" required></div></div>
                    <div class="remember-forgot"><div class="remember-me"><input type="checkbox" id="remember"><label for="remember">Remember me</label></div><a href="#" class="forgot-password"></a></div>
                    <button type="submit" class="login-btn">Login</button>
                    <p class="separator">Don't have an account? <a onclick="toggleForms()">Sign Up Here</a></p>
                </form>
            </div>
            <!-- SIGNUP FORM CONTAINER -->
            <div id="signupFormContainer">
                <div class="login-logo"><h1><i class="fas fa-user-plus"></i> SIGN UP</h1></div>
                <form method="POST" action="app/signup.php">
                    <div class="form-group"><label>Full Name</label><div class="input-with-icon"><i class="fas fa-id-card"></i><input type="text" name="full_name" placeholder="e.g. John Doe" required></div></div>
                    <div class="form-group"><label>Username</label><div class="input-with-icon"><i class="fas fa-user"></i><input type="text" name="username" placeholder="Choose a unique username" required></div></div>
					<div class="form-group"><label>Role</label><div class="input-with-icon"><i class="fas fa-user-shield"></i><select name="role" required><option value="employee">Employee</option><option value="admin">Admin</option></select></div></div>
                    <div class="form-group"><label>Password</label><div class="input-with-icon"><i class="fas fa-lock"></i><input type="password" name="password" placeholder="Create a strong password" required></div></div>
                    <div class="form-group"><label>Confirm Password</label><div class="input-with-icon"><i class="fas fa-check-circle"></i><input type="password" name="confirm_password" placeholder="Re-enter your password" required></div></div>
                    <button type="submit" class="signup-btn">Create Account</button>
                    <p class="separator">Already have an account? <a onclick="toggleForms()">Back to Login</a></p>
                </form>
            </div>
        </div>
    </div>
    <script>
        function toggleForms() {
            const loginForm = document.getElementById('loginFormContainer');
            const signupForm = document.getElementById('signupFormContainer');
            const welcomeTitle = document.getElementById('welcome-title');
            const welcomeText = document.getElementById('welcome-text');

            if (loginForm.style.display === 'none') {
                signupForm.style.display = 'none';
                loginForm.style.display = 'block';
                welcomeTitle.innerText = 'Welcome Back!';
                welcomeText.innerText = 'Sign in to access your personalized dashboard and manage your tasks.';
            } else {
                loginForm.style.display = 'none';
                signupForm.style.display = 'block';
                welcomeTitle.innerText = 'Create an Account';
                welcomeText.innerText = 'Join our platform to start managing your tasks efficiently and collaborate with your team.';
            }
        }
    </script>
</body>
</html>