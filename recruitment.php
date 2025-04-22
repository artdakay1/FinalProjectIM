<?php

session_start();
include 'includes/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_recruitment'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $team = $_POST['team'];
    $coach_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO recruitments (title, description, team_name, coach_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $description, $team, $coach_id);
    $stmt->execute();
    header("Location: recruitment.php");
    exit;
}


$search = $_GET['search'] ?? '';
if ($search) {
    $stmt = $conn->prepare("SELECT * FROM recruitments WHERE title LIKE ? OR description LIKE ?");
    $likeSearch = "%$search%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
} else {
    $stmt = $conn->prepare("SELECT * FROM recruitments ORDER BY created_at DESC");
}
$stmt->execute();
$recruitments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Recruitment - TeamLink</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <h3>Team Recruitment</h3>

 
  <form method="GET" class="mb-4">
    <input type="text" name="search" placeholder="Search positions, teams, etc." class="form-control">
  </form>

  
  <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'coach'): ?>
    <div class="card mb-4">
      <div class="card-body">
        <h5>Create Recruitment Post</h5>
        <form method="POST">
          <input type="hidden" name="create_recruitment" value="1">
          <div class="mb-2">
            <input type="text" name="title" class="form-control" placeholder="Position (e.g., Goalkeeper)" required>
          </div>
          <div class="mb-2">
            <textarea name="description" class="form-control" placeholder="Describe requirements, age range, skill level" required></textarea>
          </div>
          <div class="mb-2">
            <input type="text" name="team" class="form-control" placeholder="Team Name" required>
          </div>
          <button class="btn btn-primary">Post Recruitment</button>
        </form>
      </div>
    </div>
  <?php endif; ?>

  
  <?php while ($row = $recruitments->fetch_assoc()): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h5><?= htmlspecialchars($row['title']) ?> - <?= htmlspecialchars($row['team_name']) ?></h5>
        <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
        <small class="text-muted">Posted on <?= $row['created_at'] ?></small>
        <?php if ($_SESSION['role'] === 'player'): ?>
          <a href="apply_recruitment.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm float-end">Apply</a>
        <?php endif; ?>
      </div>
    </div>
  <?php endwhile; ?>
</div>
</body>
</html>
