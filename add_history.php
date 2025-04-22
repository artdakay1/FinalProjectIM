<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $title = $_POST['title'];
    $desc = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO user_history (user_id, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $title, $desc);
    $stmt->execute();

    header("Location: profile.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add History – TeamLink</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="mb-4">Add Profile History</h3>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control" required></textarea>
      </div>
      <button type="submit" class="btn btn-info">Add History</button>
      <a href="profile.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
</body>
</html>
