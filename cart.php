<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if (isset($_GET['add'])) {
    $productId = intval($_GET['add']);
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = 1;
    } else {
        $_SESSION['cart'][$productId]++;
    }
    header('Location: cart.php');
    exit;
}


if (isset($_GET['remove'])) {
    $productId = intval($_GET['remove']);
    unset($_SESSION['cart'][$productId]);
    header('Location: cart.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $productId = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity']));
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = $quantity;
    }
    header('Location: cart.php');
    exit;
}


$cartProducts = [];
$totalPrice = 0.00;
foreach ($_SESSION['cart'] as $productId => $quantity) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($product = $result->fetch_assoc()) {
        $product['quantity'] = $quantity;
        $cartProducts[] = $product;
        $totalPrice += $product['price'] * $quantity;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - TeamLink</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
    <h3>Your Cart</h3>

    <?php if (count($cartProducts) > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartProducts as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['product_name']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td>
                            <form method="POST" action="cart.php" class="d-flex align-items-center">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="number" name="quantity" value="<?= $product['quantity'] ?>" min="1" class="form-control w-25 me-2">
                                <button type="submit" class="btn btn-sm btn-secondary">Update</button>
                            </form>
                        </td>
                        <td>
                            <a href="cart.php?remove=<?= $product['id'] ?>" class="btn btn-danger btn-sm">
                                Remove
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center">
            <h4>Total: $<?= number_format($totalPrice, 2) ?></h4>
            <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
        </div>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>
</body>
</html>
