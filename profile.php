<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "employee") {
    include "DB_connection.php";
    include "app/Model/User.php";
    $user = get_user_by_id($conn, $_SESSION['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }
        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ccc;
        }
        .profile-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .profile-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .profile-title h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .btn-edit {
            padding: 8px 15px;
            background: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn-edit:hover {
            background: #0056b3;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        .main-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e1e1e1;
            color: #333;
        }
        .main-table td:first-child {
            width: 35%;
            font-weight: bold;
            background: #f9f9f9;
        }
        @media (max-width: 768px) {
            .profile-title {
                flex-direction: column;
                align-items: flex-start;
            }
            .btn-edit {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <div class="profile-container">
                <div class="profile-title">
                    <h2>Employee Profile</h2>
                    <a href="edit_profile.php" class="btn-edit"><i class="fa fa-pencil"></i> Edit Profile</a>
                </div>

                <div style="text-align:center; margin-bottom: 25px;">
                    <?php if (!empty($user['profile_image'])): ?>
                        <img src="uploads/profile/<?=htmlspecialchars($user['profile_image'])?>" class="profile-image" alt="Profile Image">
                    <?php else: ?>
                        <img src="uploads/profile/default.png" class="profile-image" alt="Default Profile">
                    <?php endif; ?>
                </div>

                <table class="main-table">
                    <tr><td>Full Name</td><td><?= htmlspecialchars($user['full_name'] ?? 'N/A') ?></td></tr>
                    <tr><td>Username</td><td><?= htmlspecialchars($user['username'] ?? 'N/A') ?></td></tr>
                    <tr><td>Email</td><td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td></tr>
                    <tr><td>Phone</td><td><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></td></tr>
                    <tr><td>Gender</td><td><?= htmlspecialchars(ucfirst($user['gender'] ?? 'N/A')) ?></td></tr>
                    <tr><td>Date of Birth</td><td><?= htmlspecialchars($user['dob'] ?? 'N/A') ?></td></tr>
                    <tr><td>NID Number</td><td><?= htmlspecialchars($user['nid'] ?? 'N/A') ?></td></tr>
                    <tr><td>Blood Group</td><td><?= htmlspecialchars($user['blood_group'] ?? 'N/A') ?></td></tr>
                    <tr><td>Emergency Contact</td><td><?= htmlspecialchars($user['emergency_contact'] ?? 'N/A') ?></td></tr>
                    <tr><td>Address</td><td><?= htmlspecialchars($user['address'] ?? 'N/A') ?></td></tr>
                    <tr><td>Position</td><td><?= htmlspecialchars($user['position'] ?? 'Employee') ?></td></tr>
                    <tr><td>Department</td><td><?= htmlspecialchars($user['department'] ?? 'General') ?></td></tr>
                    <tr><td>Status</td><td><?= htmlspecialchars($user['status'] ?? 'Active') ?></td></tr>
                    <tr><td>Marital Status</td><td><?= htmlspecialchars($user['marital_status'] ?? 'N/A') ?></td></tr>
                    <tr><td>Nationality</td><td><?= htmlspecialchars($user['nationality'] ?? 'N/A') ?></td></tr>
                    <tr><td>Religion</td><td><?= htmlspecialchars($user['religion'] ?? 'N/A') ?></td></tr>
                    <tr><td>Joined On</td><td><?= htmlspecialchars(date("F j, Y", strtotime($user['created_at']))) ?></td></tr>
                </table>
            </div>
        </section>
    </div>

    <script type="text/javascript">
        var active = document.querySelector("#navList li:nth-child(3)");
        if (active) active.classList.add("active");
    </script>
</body>
</html>
<?php 
} else { 
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
}
?>
