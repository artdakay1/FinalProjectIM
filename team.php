<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['team_id']) && $role === 'fan') {
    $teamId = $_POST['team_id'];
    
    $checkStmt = $conn->prepare("SELECT * FROM follows WHERE user_id = ? AND team_id = ?");
    $checkStmt->bind_param("ii", $userId, $teamId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        $followStmt = $conn->prepare("INSERT INTO follows (user_id, team_id) VALUES (?, ?)");
        $followStmt->bind_param("ii", $userId, $teamId);
        $followStmt->execute();
    }
}


$teamStmt = $conn->prepare("SELECT t.*, u.username AS coach_name FROM teams t JOIN users u ON t.coach_id = u.id");
$teamStmt->execute();
$teams = $teamStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teams - TeamLink</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <h3>All Teams</h3>

  <?php while ($team = $teams->fetch_assoc()): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($team['team_name']) ?> (<?= htmlspecialchars($team['sport']) ?>)</h5>
        <p class="card-text"><strong>Coach:</strong> <?= htmlspecialchars($team['coach_name']) ?></p>

        <?php if ($role === 'fan'): ?>
          <form method="POST" style="display:inline;">
            <input type="hidden" name="team_id" value="<?= $team['id'] ?>">
            <button type="submit" class="btn btn-outline-success btn-sm">Follow</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  <?php endwhile; ?>
</div>
</body>
</html>
