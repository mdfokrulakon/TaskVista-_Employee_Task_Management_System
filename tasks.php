<?php 
session_start();

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    
    $users = get_all_users($conn);
    
    // PHP block to build the query (this is correct and remains)
    $text = "All Tasks";
    if (isset($_GET['due_date'])) { if ($_GET['due_date'] == "Due Today") $text = "Tasks Due Today"; if ($_GET['due_date'] == "Overdue") $text = "Overdue Tasks"; }
    if (isset($_GET['status'])) { if ($_GET['status'] == "pending") $text = "Pending Tasks"; if ($_GET['status'] == "in_progress") $text = "In Progress Tasks"; if ($_GET['status'] == "completed") $text = "Completed Tasks"; }
    $query = "SELECT * FROM tasks";
    $params = [];
    $where = [];
    $order = "";
    if (!empty($_GET['search'])) { $where[] = "(title LIKE ? OR description LIKE ?)"; $params[] = "%{$_GET['search']}%"; $params[] = "%{$_GET['search']}%"; }
    if (isset($_GET['due_date'])) { if ($_GET['due_date'] == "Due Today") $where[] = "DATE(due_date) = CURDATE()"; elseif ($_GET['due_date'] == "Overdue") $where[] = "due_date < CURDATE() AND due_date IS NOT NULL AND due_date != '0000-00-00'"; }
    if (!empty($_GET['status'])) { $allowed_statuses = ['pending', 'in_progress', 'completed']; if (in_array($_GET['status'], $allowed_statuses)) { $where[] = "status = ?"; $params[] = $_GET['status']; } }
    if (!empty($_GET['sort'])) { switch ($_GET['sort']) { case 'due_asc': $order = "ORDER BY due_date ASC"; break; case 'due_desc': $order = "ORDER BY due_date DESC"; break; case 'name_asc': $order = "ORDER BY title ASC"; break; case 'name_desc': $order = "ORDER BY title DESC"; break; } }
    if ($where) { $query .= " WHERE " . implode(" AND ", $where); }
    if ($order) { $query .= " $order"; }
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $tasks = $stmt->fetchAll();
    $num_task = count($tasks);
    
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Tasks - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        :root { --primary: #4361ee; --secondary: #3f37c9; --success: #4cc9f0; --info: #4895ef; --warning: #f72585; --light: #f8f9fa; --dark: #212529; --background: #f0f2f5; --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
        body { background-color: var(--background); color: #333; line-height: 1.6; }
        .container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; padding: 20px 0; box-shadow: var(--card-shadow); position: fixed; height: 100vh; overflow-y: auto; z-index: 1000; }
        .logo { padding: 0 20px 20px; text-align: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); margin-bottom: 20px; }
        .logo h1 { font-size: 24px; display: flex; align-items: center; justify-content: center; }
        .logo i { margin-right: 10px; font-size: 28px; }
        .nav-links { list-style: none; }
        .nav-links li { margin-bottom: 5px; }
        .nav-links a { display: flex; align-items: center; color: white; padding: 15px 20px; text-decoration: none; transition: all 0.3s; font-weight: 500; }
        .nav-links a:hover, .nav-links a.active { background-color: rgba(255, 255, 255, 0.1); border-left: 4px solid var(--success); }
        .nav-links i { margin-right: 15px; font-size: 18px; width: 20px; text-align: center; }
        .main-content { flex: 1; margin-left: 250px; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: white; padding: 15px 25px; border-radius: 10px; box-shadow: var(--card-shadow); margin-bottom: 25px; }
        .header-controls { display: flex; align-items: center; gap: 15px; }
        .search-container { position: relative; }
        .search-box { display: flex; align-items: center; background-color: var(--light); border-radius: 50px; padding: 5px 15px; width: 300px; }
        .search-box input { border: none; background: transparent; padding: 8px; width: 100%; outline: none; }
        .search-box i { color: #6c757d; }
        
        /* --- NEW: CSS for Search Suggestions Box --- */
        .suggestions {
            position: absolute; top: 105%; left: 0; width: 100%;
            background: #fff; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 1001; max-height: 250px; overflow-y: auto;
            border: 1px solid #eee; display: none; /* Hidden by default */
        }
        .suggestion-item { padding: 10px 15px; cursor: pointer; font-size: 14px; }
        .suggestion-item:hover { background: #f0f2f5; }

        .sort-container { position: relative; display: flex; align-items: center; background-color: var(--light); border-radius: 50px; padding: 0 5px 0 15px; }
        .sort-container i { color: #6c757d; font-size: 14px; }
        .sort-container select { -webkit-appearance: none; -moz-appearance: none; appearance: none; background: transparent url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%236c757d%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E') no-repeat right 10px center; background-size: 10px; border: none; padding: 12px 30px 12px 10px; font-size: 14px; font-weight: 500; color: var(--dark); cursor: pointer; outline: none; width: 100%; }
        .user-info { display: flex; align-items: center; }
        .user-info img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px; border: 2px solid var(--primary); }
        .user-details { display: flex; flex-direction: column; }
        .user-name { font-weight: 600; }
        .user-role { font-size: 12px; color: #6c757d; }
        .dashboard-title { margin-bottom: 20px; }
        .dashboard-title h2 { color: var(--dark); font-size: 28px; margin-bottom: 5px; }
        .dashboard-title p { color: #6c757d; }
        .tasks-table-container { background: white; border-radius: 10px; box-shadow: var(--card-shadow); padding: 20px; overflow-x: auto; width: 100%; }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;}
        .table-header h3 { color: var(--dark); }
        .filter-controls { display: flex; flex-wrap: wrap; gap: 10px; }
        .filter-controls a { text-decoration: none; padding: 8px 12px; border-radius: 6px; background: var(--light); color: var(--dark); font-size: 14px; transition: all 0.3s; }
        .filter-controls a:hover { background: var(--primary); color: white; }
        .filter-controls a.btn { background: var(--success); color: white; }
        .filter-controls a.btn:hover { background: var(--info); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: var(--light); font-weight: 600; color: var(--dark); }
        tr:hover { background-color: #f9f9f9; }
        .progress-bar { background-color: #e9ecef; border-radius: 50px; height: 20px; width: 120px; overflow: hidden; position: relative; }
        .progress-bar-fill { background: linear-gradient(135deg, var(--warning) 0%, var(--primary) 100%); height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold; transition: width 0.4s ease-in-out; }
        .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; text-transform: capitalize; }
        .status-badge.pending { background-color: rgba(255, 193, 7, 0.15); color: #ffc107; }
        .status-badge.in-progress { background-color: rgba(255, 140, 0, 0.15); color: #ff8c00; }
        .status-badge.completed { background-color: rgba(40, 167, 69, 0.15); color: #28a745; }
        .action-buttons { display: flex; gap: 10px; }
        .action-btn { text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; color: white; transition: opacity 0.3s; }
        .action-btn.edit { background-color: var(--info); }
        .action-btn.delete { background-color: var(--warning); }
        .action-btn:hover { opacity: 0.8; }
        .animated-title-text { font-size: 32px; font-weight: 800; text-transform: uppercase; background: linear-gradient(90deg, var(--primary), var(--success), var(--secondary), var(--primary)); background-size: 200% auto; -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; animation: gradient-animation 4s linear infinite; }
        @keyframes gradient-animation { to { background-position: 200% center; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar" id="sidebar">
            <div class="logo"><h1><i class="fas fa-tasks"></i> <span>All Tasks</span></h1></div>
            <ul class="nav-links">
                <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="user.php"><i class="fas fa-users-cog"></i> <span>Manage Users</span></a></li>
                <li><a href="create_task.php"><i class="fas fa-tasks"></i> <span>Create Task</span></a></li>
                <li><a href="tasks.php" class="active"><i class="fas fa-list-alt"></i> <span>All Tasks</span></a></li>
                <li><a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
                <li><a href="edit_profile.php"><i class="fas fa-user-edit"></i> <span>Edit Profile</span></a></li>
                <li><a href="leave_request.php" ><i class="fas fa-hourglass-half"></i> <span>Extension Requests</span></a></li>
                <li><a href="chuti.php"><i class="fas fa-calendar-alt"></i> <span>Leave Request</span></a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Password Change</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <div class="animated-title-text">TaskVista</div>
                <div class="header-controls">
                    <div class="search-container">
                        <form id="searchForm" class="search-box" method="get" action="tasks.php">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" autocomplete="off" name="search" placeholder="Search tasks..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                        </form>
                        <!-- NEW: Div for suggestions -->
                        <div id="suggestions" class="suggestions"></div>
                    </div>
                    <div class="sort-container">
                        <i class="fas fa-sort-amount-down"></i>
                        <select id="sortSelect">
                            <option value="">Sort By</option>
                            <option value="due_asc" <?= (isset($_GET['sort']) && $_GET['sort']=='due_asc')?'selected':''; ?>>Due Date (Oldest)</option>
                            <option value="due_desc" <?= (isset($_GET['sort']) && $_GET['sort']=='due_desc')?'selected':''; ?>>Due Date (Newest)</option>
                            <option value="name_asc" <?= (isset($_GET['sort']) && $_GET['sort']=='name_asc')?'selected':''; ?>>Name (A-Z)</option>
                            <option value="name_desc" <?= (isset($_GET['sort']) && $_GET['sort']=='name_desc')?'selected':''; ?>>Name (Z-A)</option>
                        </select>
                    </div>
                </div>
                <div class="user-info">
                    <img src="img/user-default.png" alt="User">
                    <div class="user-details">
                        <span class="user-name"><?php echo $_SESSION['full_name']; ?></span>
                        <span class="user-role"><?php echo ucfirst($_SESSION['role']); ?></span>
                    </div>
                </div>
            </div>

            <div class="dashboard-title">
                <h2>Task Management</h2>
                <p>View and manage all tasks in the system.</p>
            </div>
            <div class="tasks-table-container">
                <div class="table-header">
                    <h3><?=$text?> (<?=$num_task?>)</h3>
                    <div class="filter-controls">
                        <a href="create_task.php" class="btn"><i class="fas fa-plus"></i> Create Task</a>
                        <a href="tasks.php">All Tasks</a>
                        <a href="tasks.php?status=pending">Pending</a>
                        <a href="tasks.php?status=in_progress">In Progress</a>
                        <a href="tasks.php?status=completed">Completed</a>
                        <a href="tasks.php?due_date=Due Today">Due Today</a>
                        <a href="tasks.php?due_date=Overdue">Overdue</a>
                    </div>
                </div>
                <?php if (isset($_GET['success'])): ?><div style="padding: 1rem; margin-bottom: 1rem; border-radius: .25rem; color: #155724; background-color: #d4edda; border-color: #c3e6cb;" role="alert"><?php echo htmlspecialchars($_GET['success']); ?></div><?php endif; ?>
                <?php if ($tasks != 0): ?>
                <table>
                   <!-- Table content remains the same -->
                   <thead>
                        <tr>
                            <th>Number</th>
                            <th>Title</th>
                            <th>Assigned To</th>
                            <th>Progress</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; foreach ($tasks as $task): ?>
                        <tr>
                            <td><?=++$i?></td>
                            <td><?=$task['title']?></td>
                            <td>
                                <?php foreach ($users as $user) { if($user['id'] == $task['assigned_to']){ echo $user['full_name']; } } ?>
                            </td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-bar-fill" style="width: <?=$task['percentage']?>%;">
                                        <?=$task['percentage']?>%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php 
                                    if(empty($task['due_date']) || $task['due_date'] == '0000-00-00') echo "No Deadline";
                                    else echo date("M d, Y", strtotime($task['due_date']));
                                ?>
                            </td>
                            <td>
                                <?php $status_class = strtolower(str_replace(' ', '-', $task['status'])); ?>
                                <span class="status-badge <?=$status_class?>"><?=$task['status']?></span>
                            </td>
                            <td class="action-buttons">
                                <a href="edit-task.php?id=<?=$task['id']?>" class="action-btn edit"><i class="fas fa-edit"></i> Edit</a>
                                <a href="delete-task.php?id=<?=$task['id']?>" class="action-btn delete"><i class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                       <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 20px;"><h3>No tasks found for this criteria.</h3><p>Try a different filter or search term.</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- JAVASCRIPT FOR SORTING (already fixed) ---
            const sortSelect = document.getElementById('sortSelect');
            if(sortSelect) {
                sortSelect.addEventListener('change', function() {
                    const sortValue = this.value;
                    if (!sortValue) return;
                    const url = new URL(window.location.href);
                    url.searchParams.set('sort', sortValue);
                    window.location.href = url.toString();
                });
            }

            // --- NEW: JAVASCRIPT FOR SEARCH SUGGESTIONS ---
            const searchInput = document.getElementById('searchInput');
            const suggestionsBox = document.getElementById('suggestions');
            const searchForm = document.getElementById('searchForm');

            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const query = this.value;

                    if (query.length < 2) {
                        suggestionsBox.style.display = 'none';
                        return;
                    }

                    // Fetch suggestions from the backend
                    fetch(`task_suggest.php?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsBox.innerHTML = ''; // Clear old suggestions
                            if (data.length > 0) {
                                data.forEach(item => {
                                    const div = document.createElement('div');
                                    div.className = 'suggestion-item';
                                    div.textContent = item;
                                    div.addEventListener('click', function() {
                                        searchInput.value = this.textContent;
                                        suggestionsBox.style.display = 'none';
                                        searchForm.submit(); // Submit form on click
                                    });
                                    suggestionsBox.appendChild(div);
                                });
                                suggestionsBox.style.display = 'block';
                            } else {
                                suggestionsBox.style.display = 'none';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching suggestions:', error);
                            suggestionsBox.style.display = 'none';
                        });
                });
            }

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target)) {
                    suggestionsBox.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
<?php 
} else { 
   header("Location: login.php?error=First login");
   exit();
}
?>