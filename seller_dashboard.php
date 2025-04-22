<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

$sellerId = $_SESSION['user_id'];


if (isset($_GET['delete'])) {
    $pid = intval($_GET['delete']);
    $d = $conn->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
    $d->bind_param("ii", $pid, $sellerId);
    $d->execute();
    header("Location: seller_dashboard.php");
    exit;
}


$prodStmt = $conn->prepare("SELECT * FROM products WHERE user_id = ?");
$prodStmt->bind_param("i", $sellerId);
$prodStmt->execute();
$yourProducts = $prodStmt->get_result();


$saleStmt = $conn->prepare("
  SELECT oi.*, o.buyer_id, o.created_at, u.username AS buyer_name, p.product_name
  FROM order_items oi
  JOIN orders o ON oi.order_id = o.id
  JOIN users u ON o.buyer_id = u.id
  JOIN products p ON oi.product_id = p.id
  WHERE p.user_id = ?
  ORDER BY o.created_at DESC
");
$saleStmt->bind_param("i", $sellerId);
$saleStmt->execute();
$sales = $saleStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><title>Seller Dashboard – TeamLink</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <h3>Your Products</h3>
  <a href="add_product.php" class="btn btn-primary mb-3">Add New Product</a>
  <table class="table table-bordered">
    <tr><th>ID</th><th>Name</th><th>Price</th><th>Actions</th></tr>
    <?php while ($p = $yourProducts->fetch_assoc()): ?>
    <tr>
      <td><?= $p['id'] ?></td>
      <td><?= htmlspecialchars($p['product_name']) ?></td>
      <td>$<?= number_format($p['price'],2) ?></td>
      <td>
        <a href="edit_product.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="seller_dashboard.php?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger"
           onclick="return confirm('Remove this product?')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

  <h3 class="mt-5">Recent Sales</h3>
  <?php if ($sales->num_rows): ?>
  <table class="table table-striped">
    <tr><th>Order #</th><th>Product</th><th>Qty</th><th>Price</th><th>Buyer</th><th>Date</th></tr>
    <?php while ($s = $sales->fetch_assoc()): ?>
    <tr>
      <td><?= $s['order_id'] ?></td>
      <td><?= htmlspecialchars($s['product_name']) ?></td>
      <td><?= $s['quantity'] ?></td>
      <td>$<?= number_format($s['price'],2) ?></td>
      <td><?= htmlspecialchars($s['buyer_name']) ?></td>
      <td><?= $s['created_at'] ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
  <?php else: ?>
    <p>No sales yet.</p>
  <?php endif; ?>
</div>
</body></html>
