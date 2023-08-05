<?php
session_start();

// Check if the user is logged in and has admin role, if not, redirect to login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian') {
    header("Location: login.php");
    exit;
}

//database connection
$connection = mysqli_connect('localhost', 'Mathew', 'mysql@123', 'crestlibrary');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

//blocking or unblocking a user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action === 'block') {
        // Update user's role to blocked in the members table
        $query_update_role = "UPDATE members SET role = 'blocked' WHERE id = $userID";
        mysqli_query($connection, $query_update_role);
    } elseif ($action === 'unblock') {
        // Update user's role to member in the members table
        $query_update_role = "UPDATE members SET role = 'member' WHERE id = $userID";
        mysqli_query($connection, $query_update_role);
    }

    // Refresh the page to reflect changes
    header("Location: blockedUsers.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocked Users</title>
    <link rel="stylesheet" type="text/css" href="../styling/css/blockedUsers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Nav Bar -->
    <nav class="navbar">
        <div class="logo">
            <a href="#">Crest Library</a>
        </div>
        <ul class="nav-links">
            <li><a href="../pages/admin.php">Home</a></li>
        </ul>
        <div class="user-dropdown">
            <i class="fas fa-user"></i>
            <div class="dropdown-content">
                <a href="../index.php?logout=true">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Blocked Users Page Content -->
    <div class="container">
        <h1 class="">User Administration</h1>
        <div class="users-list">
            <h2 class="subHeading">Customers</h2>
            <?php
           
            $query_fetch_active_users = "SELECT * FROM members WHERE role = 'member'";
            $result_fetch_active_users = mysqli_query($connection, $query_fetch_active_users);

            while ($active_user = mysqli_fetch_assoc($result_fetch_active_users)) {
            ?>
            <div class="user-item">
                <p>ID: <?php echo $active_user['id']; ?></p>
                <p>Name: <?php echo $active_user['name']; ?></p>
                <p>Email: <?php echo $active_user['email']; ?></p>
                <p>Role: <?php echo $active_user['role']; ?></p>
                <form method="post">
                    <input type="hidden" name="user_id" value="<?php echo $active_user['id']; ?>">
                    <input type="hidden" name="action" value="block">
                    <button type="submit">Block User</button>
                </form>
            </div>
            <?php } ?>

            <h2 class="subHeading">Blocked Users</h2>
            <?php
          
            $query_fetch_blocked_users = "SELECT * FROM members WHERE role = 'blocked'";
            $result_fetch_blocked_users = mysqli_query($connection, $query_fetch_blocked_users);

            while ($blocked_user = mysqli_fetch_assoc($result_fetch_blocked_users)) {
            ?>
            <div class="user-item">
                <p>ID: <?php echo $blocked_user['id']; ?></p>
                <p>Name: <?php echo $blocked_user['name']; ?></p>
                <p>Email: <?php echo $blocked_user['email']; ?></p>
                <form method="post">
                    <input type="hidden" name="user_id" value="<?php echo $blocked_user['id']; ?>">
                    <input type="hidden" name="action" value="unblock">
                    <button type="submit">Unblock User</button>
                </form>
            </div>
            <?php } ?>
        </div>
    </div>

    <script>
        // JavaScript code for dropdown
        const dropdown = document.querySelector('.user-dropdown');
        dropdown.addEventListener('click', () => {
            dropdown.classList.toggle('active');
        });
    </script>
</body>
</html>
