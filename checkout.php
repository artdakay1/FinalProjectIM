<?php
session_start();
include 'includes/db.php';


if (empty($_SESSION['cart'])) {
    header("Location: shop.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $buyer_id = $_SESSION['user_id'] ?? 0; 
    $total_price = 0;

    
    foreach ($_SESSION['cart'] as $productId) {
        $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $total_price += $row['price'];
        }
    }

    
    $stmt = $conn->prepare("INSERT INTO orders (buyer_id, name, email, address, total_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssd", $buyer_id, $name, $email, $address, $total_price);

    if ($stmt->execute()) {
        $_SESSION['cart'] = []; 
        $message = "Thank you for your order, $name! We'll send the details to your email ($email).";
    } else {
        $message = "Failed to place order: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TeamLink</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
    <h3>Checkout</h3>

    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Shipping Address</label>
            <textarea name="address" id="address" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
            <button type="submit" class="btn btn-success">Place Order</button>
        </div>
    </form>
</div>

</body>
</html>
