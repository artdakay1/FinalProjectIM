<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

$sellerId = $_SESSION['user_id'];
$pid = intval($_GET['id'] ?? 0);


$p = $conn->prepare("SELECT * FROM products WHERE id = ? AND user_id = ?");
$p->bind_param("ii", $pid, $sellerId);
$p->execute();
$product = $p->get_result()->fetch_assoc();
if (!$product) { die("Product not found or access denied."); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];

    
    $img = $product['image'];
    if (!empty($_FILES['image']['name'])) {
       $dir="uploads/products/";
       if(!is_dir($dir)) mkdir($dir,0777,true);
       $img=uniqid()."_".basename($_FILES['image']['name']);
       move_uploaded_file($_FILES['image']['tmp_name'], $dir.$img);
    }

    $u = $conn->prepare("
      UPDATE products
      SET product_name=?, price=?, description=?, image=?
      WHERE id=? AND user_id=?
    ");
    $u->bind_param("sdssii",$name,$price,$desc,$img,$pid,$sellerId);
    $u->execute();
    header("Location: seller_dashboard.php");
    exit;
}
?>
<!DOCTYPE html><html lang="en"><head>
  <meta charset="UTF-8"><title>Edit Product – TeamLink</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <h3>Edit Product #<?= $product['id'] ?></h3>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input name="product_name" class="form-control" value="<?= htmlspecialchars($product['product_name']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Price</label>
      <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Image (leave blank to keep current)</label>
      <input type="file" name="image" class="form-control">
    </div>
    <button class="btn btn-primary">Save Changes</button>
    <a href="seller_dashboard.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body></html>
