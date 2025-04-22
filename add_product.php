<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    
    $imageName = '';
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/products/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $imageName = uniqid() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            die("Error uploading image.");
        }
    }

    
    $stmt = $conn->prepare("INSERT INTO products (user_id, product_name, price, description, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $userId, $productName, $price, $description, $imageName);
    $stmt->execute();

    header("Location: shop.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product - TeamLink</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-4">
  <h3>Add a Product to Sell</h3>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="product_name" class="form-label">Product Name</label>
      <input type="text" name="product_name" id="product_name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="price" class="form-label">Price</label>
      <input type="text" name="price" id="price" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
    </div>
    <div class="mb-3">
      <label for="image" class="form-label">Product Image</label>
      <input type="file" name="image" id="image" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Add Product</button>
  </form>
</div>

</body>
</html>
