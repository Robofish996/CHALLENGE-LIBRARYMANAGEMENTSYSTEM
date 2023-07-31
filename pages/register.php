<?php
// Handle form submission for registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $connection = mysqli_connect('localhost', 'Mathew', 'mysql@123', 'crestlibrary');

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL query to insert the user data into the appropriate table based on the selected role
    if ($role === 'member') {
        $table = 'members';
    } elseif ($role === 'librarian') {
        $table = 'librarians';
    } else {
        // Invalid role selected, show an error message
        $error_message = "Invalid role selected. Please try again.";
    }

    if (!isset($error_message)) {
        // SQL query to insert user data into the specified table
        $query = "INSERT INTO $table (name, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')";
        $result = mysqli_query($connection, $query);

        if ($result) {
            // Registration successful, redirect the user to login.php
            header("Location: login.php");
            exit();
        } else {
            // Registration failed, show an error message
            $error_message = "Error: Unable to register. Please try again.";
        }
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
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="../styling/css/register.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Register</h1>
            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form action="" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">Register</button>
            </form>
        </div>
    </div>
</body>
</html>

