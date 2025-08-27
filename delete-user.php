<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/User.php";

    if (!isset($_GET['id'])) {
        header("Location: user.php");
        exit();
    }
    $id = $_GET['id'];
    $user = get_user_by_id($conn, $id);

    if ($user == 0) {
        header("Location: user.php");
        exit();
    }

    // Check if user has assigned tasks
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tasks WHERE assigned_to = ?");
    $stmt->execute([$id]);
    $task_count = $stmt->fetchColumn();

    // If confirmation not set and user has tasks, show confirmation page
    if ($task_count > 0 && !isset($_GET['confirm'])) {
        ?>
        <script>
        if (confirm("This user is assigned to one or more tasks. Are you sure you want to delete?")) {
            window.location.href = "delete-user.php?id=<?= $id ?>&confirm=yes";
        } else {
            window.location.href = "user.php";
        }
        </script>
        <?php
        exit();
    }

    // If confirmed or no tasks, proceed with deletion

    // Unassign all tasks assigned to this user
    $stmt = $conn->prepare("UPDATE tasks SET assigned_to = NULL WHERE assigned_to = ?");
    $stmt->execute([$id]);

    $data = array($id, "employee");
    delete_user($conn, $data);
    $sm = "Deleted Successfully";
    header("Location: user.php?success=$sm");
    exit();

} else { 
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
}
?>