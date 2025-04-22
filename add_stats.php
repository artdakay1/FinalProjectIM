<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $games = $_POST['games_played'];
    $goals = $_POST['goals'];
    $assists = $_POST['assists'];

    $stmt = $conn->prepare("REPLACE INTO player_stats (user_id, games_played, goals, assists) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $userId, $games, $goals, $assists);
    $stmt->execute();

    header("Location: profile.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Stats – TeamLink</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="mb-4">Add Player Stats</h3>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Games Played</label>
        <input type="number" name="games_played" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Goals</label>
        <input type="number" name="goals" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Assists</label>
        <input type="number" name="assists" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success">Save Stats</button>
      <a href="profile.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
</body>
</html>
