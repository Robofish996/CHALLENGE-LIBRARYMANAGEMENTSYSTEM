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

// Get the user ID from the session
$userID = $_SESSION['user_id'];

// Fetch all order information for the logged-in user from the rentals table
$query_orders = "SELECT * FROM rentals WHERE user_id = $userID ORDER BY order_date DESC";
$result_orders = mysqli_query($connection, $query_orders);
$rentals = array();
while ($order_row = mysqli_fetch_assoc($result_orders)) {
    $rentals[] = $order_row;
}
// Store rental data in the session for easy access
$_SESSION['rentals'] = $rentals;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" type="text/css" href="../styling/css/history.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <a href="#">Crest Library</a>
        </div>
        <!--navigation links and user dropdown-->
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
        </ul>
        <div class="user-dropdown">
            <i class="fas fa-user"></i>
            <div class="dropdown-content">
                <a href="./settings.php">Settings</a>
                <a href="../index.php?logout=true">Logout</a>

            </div>
        </div>
    </nav>

    <!-- Order History Page Content -->
    <div class="container">
        <p>Welcome, <?php echo $_SESSION['username']; ?></p>
        <p>Here is a list of all your orders</p>
        <h1>Order History</h1>
        <?php
        if (!empty($_SESSION['rentals'])) {
            $currentDate = null;
            $totalPrice = 0;
            foreach ($_SESSION['rentals'] as $order_row) {
                $orderDate = date('j F Y', strtotime($order_row['order_date']));
                if ($currentDate !== $orderDate) {
                    //display a new section with the date
                    if ($currentDate !== null) {
                        // Display the total price
                        echo "<p>Total Price: R" . number_format($totalPrice, 2) . "</p>";
                    }
                    $currentDate = $orderDate;
                    $totalPrice = 0;
                    echo "<h2>Date of Order: $orderDate</h2>";
                }

                $bookID = $order_row['book_id'];
                $bookInfo = $_SESSION['all_books'][$bookID];
                $bookTitle = $bookInfo['book_title'];
                $bookPrice = $bookInfo['price'];

                // Calculate the total price for this order
                $totalPrice += $bookPrice;

                echo "<p>Order Number: " . $order_row['order_number'] . "</p>";
                echo "<p>Book Title: $bookTitle</p>";
                echo "<p>Price: R" . number_format($bookPrice, 2) . "</p>";
                echo "<p>Return Date: " . $order_row['end_date'] . "</p>";
                echo "<hr>";
            }

            // Display the total price for the last date
            echo "<p>Total Price: R" . number_format($totalPrice, 2) . "</p>";
        } else {
            echo "<p>No order history available.</p>";
        }
        ?>
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