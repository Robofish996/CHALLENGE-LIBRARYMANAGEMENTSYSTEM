<?php
session_start();

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$connection = mysqli_connect('localhost', 'Mathew', 'mysql@123', 'crestlibrary');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the user's ID from the session
$userID = $_SESSION['user_id'];

// Query to fetch user information from the members table
$query_user_info = "SELECT * FROM members WHERE id = $userID";
$result_user_info = mysqli_query($connection, $query_user_info);
$user_data = mysqli_fetch_assoc($result_user_info);


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = $_POST['new_name'];
    $newEmail = $_POST['new_email'];
    $newPassword = $_POST['new_password'];

    // Update the user's name in the appropriate table
    $updateNameQuery = "UPDATE members SET name = '$newName' WHERE id = $userID";
    mysqli_query($connection, $updateNameQuery);

    // Update the user's email in the appropriate table
    $updateEmailQuery = "UPDATE members SET email = '$newEmail' WHERE id = $userID";
    mysqli_query($connection, $updateEmailQuery);

    // Update the user's password in the appropriate table
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updatePasswordQuery = "UPDATE members SET password = '$hashedPassword' WHERE id = $userID";
    mysqli_query($connection, $updatePasswordQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link rel="stylesheet" type="text/css" href="../styling/css/settings.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Nav Bar -->
    <nav class="navbar">
        <div class="logo">
            <a href="#">Crest Library</a>
        </div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
        </ul>
        <div class="user-dropdown">
            <i class="fas fa-user"></i>
            <div class="dropdown-content">
                <a href="settings.php">Settings</a>
                <a href="../index.php?logout=true">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>User Settings</h1>
        <div class="user-info">
            <h2>User Information</h2>
            <p>Name: <?php echo $user_data['name']; ?></p>
            <p>Email: <?php echo $user_data['email']; ?></p>
        </div>

        <div class="update-form">
            <h2>Update Information</h2>
            <p>Please fillout all fields</p>
            <form method="post">
                <label for="new_name">New Name:</label>
                <input type="text" id="new_name" name="new_name" required>
                <br>
                <label for="new_email">New Email:</label>
                <input type="email" id="new_email" name="new_email" required>
                <br>
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
                <br>
                <button type="submit">Update</button>
            </form>
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
