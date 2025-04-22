<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sellerId = $_SESSION['user_id'];


$query = "
SELECT 
    o.id AS order_id, 
    o.name AS buyer_name, 
    o.email AS buyer_email, 
    o.address AS shipping_address, 
    o.total_price, 
    o.created_at,
    p.name AS product_name,
    p.price AS product_price
FROM orders o
JOIN cart_items ci ON ci.order_id = o.id
JOIN products p ON p.id = ci.product_id
WHERE p.seller_id = ?
ORDER BY o.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $sellerId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recent Sales - TeamLink</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
    <h3>Recent Sales</h3>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Buyer Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Price</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['buyer_name']) ?></td>
                        <td><?= htmlspecialchars($row['buyer_email']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['shipping_address'])) ?></td>
                        <td>$<?= number_format($row['product_price'], 2) ?></td>
                        <td><?= date("F j, Y g:i A", strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No sales found yet.</p>
    <?php endif; ?>
</div>
</body>
</html>
