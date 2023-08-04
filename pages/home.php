<?php
session_start();

//Cart Array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

//Dates Array
if (!isset($_SESSION['dates'])) {
    $_SESSION['dates'] = array();
}

// Database connection
$connection = mysqli_connect('localhost', 'Mathew', 'mysql@123', 'crestlibrary');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all book information from the books table and store it in the session
if (!isset($_SESSION['all_books'])) {
    $_SESSION['all_books'] = array();

    // Fetch all books from the books table
    $query_all_books = "SELECT * FROM books";
    $result_all_books = mysqli_query($connection, $query_all_books);

    // Store book information in the session
    while ($book_row = mysqli_fetch_assoc($result_all_books)) {
        $_SESSION['all_books'][$book_row['book_id']] = $book_row;
    }
}

//Adding books to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart']) && isset($_POST['book_id'])) {
    $bookID = $_POST['book_id'];
    if (!in_array($bookID, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $bookID;
        // Initialize the end date for this book if not set
        if (!isset($_SESSION['dates'][$bookID])) {
            $_SESSION['dates'][$bookID] = '';
        }
    }
}

//Removing books from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_from_cart']) && isset($_POST['book_id'])) {
    $bookID = $_POST['book_id'];
    $index = array_search($bookID, $_SESSION['cart']);
    if ($index !== false) {
        unset($_SESSION['cart'][$index]);
        // Remove the end date for this book
        unset($_SESSION['dates'][$bookID]);
    }
}

//When Checkout is pressed
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    // Get the current user's ID from the session
    $userID = $_SESSION['user_id'];

    // Get the cart items and dates from the session
    $cartItems = $_SESSION['cart'];

    // Get the order date and time
    $orderDate = date('Y-m-d H:i:s');

    // Clear the cart and dates arrays as the books are now rented
    $_SESSION['cart'] = array();
    $_SESSION['dates'] = array();

    foreach ($cartItems as $bookID) {
        // Calculate the total price for this book
        $bookInfo = $_SESSION['all_books'][$bookID];
        $bookPrice = $bookInfo['price'];

        // Add the book price to the total price
        $totalPrice = $bookPrice;

        // Get the end date
        $endDate = $_POST['end_date'][$bookID];
        $_SESSION['dates'][$bookID] = $endDate;

        // Pushing this information into the rentals table
        $query_insert_rental = "INSERT INTO rentals (order_number, order_date, user_id, book_id, price, end_date) VALUES (NULL, '$orderDate', $userID, $bookID, $bookPrice, '$endDate')";
        mysqli_query($connection, $query_insert_rental);
    }

    // Show a success message
    echo "<script>alert('Checkout successful!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books</title>
    <link rel="stylesheet" type="text/css" href="../styling/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Nav Bar -->
    <nav class="navbar">
        <div class="logo">
            <a href="#">Crest Library</a>
        </div>
        <ul class="nav-links">
            <li><a href="../pages/history.php">Previous Orders</a></li>
        </ul>
        <div class="user-dropdown">
            <i class="fas fa-user"></i>
            <div class="dropdown-content">
                <a href="./settings.php">Settings</a>
                <a href="../index.php?logout=true">Logout</a>
            </div>
        </div>
    </nav>
    <div class="dashboard">
        <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    </div>
    <div class="container">
        <h1>Browse Books</h1>
        <div class="books-container">

            <?php
            // Database connection
            $connection = mysqli_connect('localhost', 'Mathew', 'mysql@123', 'crestlibrary');
            if (!$connection) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch all books from the books table
            $query_books = "SELECT * FROM books";
            $result_books = mysqli_query($connection, $query_books);

            // Display books in cards
            while ($row = mysqli_fetch_assoc($result_books)) {
                echo '<div class="book-card">';

                if (!empty($row['image_path'])) {
                    echo '<img src="' . $row['image_path'] . '" alt="' . $row['book_title'] . '">';
                }
                echo '<h2>' . $row['book_title'] . '</h2>';
                echo '<p>Author: ' . $row['author'] . '</p>';
                echo '<p>Price: R' . $row['price'] . '</p>';

                if (in_array($row['book_id'], $_SESSION['cart'])) {
                    echo '<form method="post">';
                    echo '<input type="hidden" name="book_id" value="' . $row['book_id'] . '">';
                    echo '<button type="submit" name="remove_from_cart" value="' . $row['book_id'] . '">Remove from Cart</button>';
                    echo '</form>';
                } else {
                    if ($row['status'] === 'Available') {
                        echo '<form method="post">';
                        echo '<input type="hidden" name="book_id" value="' . $row['book_id'] . '">';
                        echo '<button type="submit" name="add_to_cart">Add to Cart</button>';
                        echo '</form>';
                    } else {
                        echo '<p class="' . strtolower($row['status']) . '">Status: ' . $row['status'] . '</p>';
                    }
                }
                

                echo '</div>';
            }

            // Close the database connection
            mysqli_close($connection);
            ?>

        </div>

        <!-- Cart Items Section -->
        <div class="cart-items-container">
            <h2>Cart Items</h2>
            <form method="post">
                <ul>
                    <?php
                    if (!empty($_SESSION['cart'])) {
                        // Database connection
                        $connection = mysqli_connect('localhost', 'Mathew', 'mysql@123', 'crestlibrary');
                        if (!$connection) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        foreach ($_SESSION['cart'] as $bookID) {
                            // Fetch book information from the session based on bookID
                            $bookInfo = $_SESSION['all_books'][$bookID];

                            // Display the cart item with end date input and remove button
                            echo '<li class="cart-item">';
                            echo '<div class="cart-item-info">';
                            echo $bookInfo['book_title'] . ' - ' . $bookInfo['author'] . ' (R' . $bookInfo['price'] . ')';
                            echo '</div>';
                            echo '<div class="cart-item-date">';
                            echo '<label for="end_date">Return Date:</label>';
                            echo '<input type="date" name="end_date[' . $bookID . ']" id="end_date" class="return-date" value="' . ($_SESSION['dates'][$bookID] ?? '') . '" required>';
                            echo '</div>';
                            echo '<button type="submit" name="remove_from_cart" value="' . $bookID . '">Remove from Cart</button>';
                            echo '</li>';
                        }

                        // Close the database connection
                        mysqli_close($connection);
                    } else {
                        echo '<li>Your cart is empty.</li>';
                    }
                    ?>
                </ul>
                <!-- Checkout Button -->
                <div class="checkout-button-container">
                    <button class="checkout-button" type="submit" name="checkout">Checkout</button>
                </div>
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
