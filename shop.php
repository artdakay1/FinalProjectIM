<?php
include 'includes/db.php';

$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop - TeamLink</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-4">
  <h3 class="mb-4">Shop</h3>
  <a href="add_product.php" class="btn btn-primary mb-4">Add New Product</a>

  <div class="row">
    <?php while ($product = $products->fetch_assoc()): ?>
      <div class="col-md-4">
        <div class="card mb-4 shadow-sm">
          <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>" 
               class="card-img-top" 
               alt="<?= htmlspecialchars($product['product_name']) ?>" 
               style="height: 250px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
            <p class="card-text"><strong>$<?= htmlspecialchars($product['price']) ?></strong></p>
            <a href="cart.php?add=<?= $product['id'] ?>" class="btn btn-success">Add to Cart</a>
            
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

</body>
</html>
