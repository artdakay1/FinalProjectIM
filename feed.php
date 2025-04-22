<?php
include 'includes/auth.php';
include 'includes/db.php';


if (isset($_POST['post'])) {
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    $image = '';

    
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    $stmt = $conn->prepare("INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $content, $image);
    $stmt->execute();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Feed - TeamLink</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">

  <form method="POST" enctype="multipart/form-data" class="mb-4">
    <textarea name="content" class="form-control mb-2" placeholder="Share something..." required></textarea>
    <input type="file" name="image" class="form-control mb-2">
    <button type="submit" name="post" class="btn btn-primary">Post</button>
  </form>

  <?php
  $result = $conn->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
  while ($post = $result->fetch_assoc()):
  ?>
    <div class="card mb-3">
      <div class="card-header">
        <strong><?= $post['username'] ?></strong> | <?= date('F j, Y', strtotime($post['created_at'])) ?>
      </div>
      <div class="card-body">
        <p><?= htmlspecialchars($post['content']) ?></p>
        <?php if ($post['image']): ?>
          <img src="<?= $post['image'] ?>" class="img-fluid rounded" />
        <?php endif; ?>
      </div>
    </div>
  <?php endwhile; ?>

</div>
</body>
</html>
