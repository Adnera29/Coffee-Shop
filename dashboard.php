<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "coffee_shop");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM orders";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Records | Coffee Haven</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header>
        <h2>Order Records</h2>
        <a href="logout.php">Logout</a>
    </header>

    <table>
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Contact</th>
            <th>Products</th>
            <th>Total Amount</th>
            <th>Payment Mode</th>
            <th>Order Date</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['customer_name']}</td>
                        <td>{$row['contact']}</td>
                        <td>{$row['products']}</td>
                        <td>₹{$row['total_amount']}</td>
                        <td>{$row['payment_mode']}</td>
                        <td>{$row['order_date']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No orders found</td></tr>";
        }
        $conn->close();
        ?>
    </table>

</body>
</html>
