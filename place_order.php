<?php
include 'db_connect.php'; // Ensure this file properly initializes $conn

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
    $customer_name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $products = trim($_POST['products']); // Should be JSON format (string)
    $total_amount = trim($_POST['total']);
    $payment_mode = trim($_POST['payment_mode']);

    // Validate inputs
    if (empty($customer_name) || empty($contact) || empty($products) || empty($total_amount) || empty($payment_mode)) {
        die("Error: All fields are required!");
    }

    if (!preg_match('/^\d{10}$/', $contact)) {
        die("Error: Invalid contact number! Enter a 10-digit number.");
    }

    if (!is_numeric($total_amount) || $total_amount <= 0) {
        die("Error: Invalid total amount!");
    }

    // Ensure products are stored in JSON format
    $products_json = json_encode($products);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, contact, products, total_amount, payment_mode) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("SQL Prepare Error: " . $conn->error);
    }

    $stmt->bind_param("sssis", $customer_name, $contact, $products_json, $total_amount, $payment_mode);

    if ($stmt->execute()) {
        echo "Order placed successfully!";
    } else {
        die("SQL Execution Error: " . $stmt->error);
    }

    $stmt->close();
}

$conn->close(); // Close connection after all queries are done
?>
