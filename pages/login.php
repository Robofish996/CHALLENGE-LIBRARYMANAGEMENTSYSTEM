<?php
session_start();

//Login values
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the database
    $connection = mysqli_connect('localhost', 'Mathew', 'mysql@123', 'crestlibrary');

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL query to fetch the user data based on the username
    $query_members = "SELECT * FROM members WHERE name = '$username'";
    $query_librarians = "SELECT * FROM librarians WHERE name = '$username'";

    $result_members = mysqli_query($connection, $query_members);
    $result_librarians = mysqli_query($connection, $query_librarians);

    if (($result_members && mysqli_num_rows($result_members) === 1) || ($result_librarians && mysqli_num_rows($result_librarians) === 1)) {
        // User found in either members or librarians table
        $user = ($result_members && mysqli_num_rows($result_members) === 1) ? mysqli_fetch_assoc($result_members) : mysqli_fetch_assoc($result_librarians);

        if ($user['role'] === 'blocked') {
            // User is blocked, show an error message
            $error_message = "You have been blocked. Please contact the administrator for further assistance.";
        } elseif(password_verify($password, $user['password'])) {
            // Username and password are correct, store the username, role, user ID, and email in the session
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['email'] = $user['email']; 

            // Redirect the user based on their role
            if ($user['role'] === 'member') {
                // If the user is a member (customer), redirect to home.php
                header("Location: home.php");
                exit();
            } elseif ($user['role'] === 'librarian') {
                // If the user is a librarian (admin), redirect to admin.php
                header("Location: admin.php");
                exit();
            }
        } else {
            // Invalid password, show an error message
            $error_message = "Invalid password. Please try again.";
        }
    } else {
        // User not found in any table, show an error message
        $error_message = "Invalid username or password. Please try again.";
    }

    // Close the database connection
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../styling/css/login.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Login</h1>
            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form action="" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
