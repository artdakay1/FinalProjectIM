<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

if ($_SESSION['role'] !== 'coach') {
    die("Access denied. Only coaches can add teams.");
}

$userId = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teamName = trim($_POST['team_name']);
    $sport = trim($_POST['sport']);

    if (!empty($teamName) && !empty($sport)) {
        $stmt = $conn->prepare("INSERT INTO teams (coach_id, team_name, sport) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $teamName, $sport);
        $stmt->execute();
        $message = "Team added successfully!";
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Team - TeamLink</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <h3>Add New Team</h3>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= $message ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label for="team_name" class="form-label">Team Name</label>
      <input type="text" class="form-control" name="team_name" required>
    </div>
    <div class="mb-3">
      <label for="sport" class="form-label">Sport</label>
      <input type="text" class="form-control" name="sport" required>
    </div>
    <button type="submit" class="btn btn-primary">Create Team</button>
  </form>
</div>
</body>
</html>
