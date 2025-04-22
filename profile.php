<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

$sessionUserId = $_SESSION['user_id'];
$viewUserId = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : $sessionUserId;
$isOwnProfile = ($viewUserId === $sessionUserId);


$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $viewUserId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit;
}


if ($isOwnProfile && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = $_POST['bio'];
    $filename = $user['profile_picture'];

    if (!empty($_FILES['profile_picture']['name'])) {
        $targetDir  = "uploads/";
        $newName    = uniqid() . "_" . basename($_FILES['profile_picture']['name']);
        $targetFile = $targetDir . $newName;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile);
        $filename = $newName;
    }

    $update = $conn->prepare("UPDATE users SET bio = ?, profile_picture = ? WHERE id = ?");
    $update->bind_param("ssi", $bio, $filename, $sessionUserId);
    $update->execute();

    header("Location: profile.php?id={$sessionUserId}");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($user['username']) ?>’s Profile – TeamLink</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .profile-pic {
      width: 150px; height: 150px;
      object-fit: cover; border-radius: 50%;
    }
  </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-4">
  <h3><?= htmlspecialchars($user['username']) ?>’s Profile</h3>
  <div class="row">
    <div class="col-md-4 text-center">
      <img src="uploads/<?= htmlspecialchars($user['profile_picture']) ?>" class="profile-pic mb-3" alt="Profile Picture">
      <?php if ($isOwnProfile): ?>
        <form method="POST" enctype="multipart/form-data">
          <input type="file" name="profile_picture" class="form-control mb-2">
    </div>
    <div class="col-md-8">
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Role:</strong> <?= ucfirst($user['role']) ?></p>
        <div class="mb-3">
          <label for="bio" class="form-label">Bio</label>
          <textarea name="bio" id="bio" rows="4" class="form-control"><?= htmlspecialchars($user['bio']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
      <?php else: ?>
        <p><strong>Bio:</strong> <?= nl2br(htmlspecialchars($user['bio'])) ?></p>
    </div>
      <div class="alert alert-secondary">You’re viewing someone else’s profile.</div>
      <?php endif; ?>
  </div>

  <?php if ($user['role'] === 'player'): ?>
    <hr><h5>Stats</h5>
    <?php
      $s = $conn->prepare("SELECT * FROM player_stats WHERE user_id = ?");
      $s->bind_param("i", $viewUserId);
      $s->execute();
      $stats = $s->get_result()->fetch_assoc();
    ?>
    <?php if ($stats): ?>
      <ul>
        <li>Games Played: <?= $stats['games_played'] ?></li>
        <li>Goals: <?= $stats['goals'] ?></li>
        <li>Assists: <?= $stats['assists'] ?></li>
      </ul>
    <?php else: ?>
      <p>No stats available yet.</p>
    <?php endif; ?>
    <?php if ($isOwnProfile): ?>
      <a href="add_stats.php" class="btn btn-outline-success btn-sm">Add Stats</a>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($user['role'] === 'coach'): ?>
    <hr><h5>Teams Coached</h5>
    <?php
      $t = $conn->prepare("SELECT * FROM teams WHERE coach_id = ?");
      $t->bind_param("i", $viewUserId);
      $t->execute();
      $teams = $t->get_result();
    ?>
    <?php if ($teams->num_rows): ?>
      <ul>
        <?php while ($team = $teams->fetch_assoc()): ?>
          <li><?= htmlspecialchars($team['team_name']) ?> (<?= htmlspecialchars($team['sport']) ?>)</li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p>No teams yet.</p>
    <?php endif; ?>
    <?php if ($isOwnProfile): ?>
      <a href="add_team.php" class="btn btn-outline-primary btn-sm mt-2">Add Team</a>
    <?php endif; ?>
  <?php endif; ?>

  
  <hr><h5>History</h5>
  <?php
    $h = $conn->prepare("SELECT * FROM user_history WHERE user_id = ?");
    $h->bind_param("i", $viewUserId);
    $h->execute();
    $history = $h->get_result();
  ?>
  <?php if ($history->num_rows): ?>
    <ul>
      <?php while ($row = $history->fetch_assoc()): ?>
        <li><?= htmlspecialchars($row['title']) ?> – <?= htmlspecialchars($row['description']) ?></li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p>No history yet.</p>
  <?php endif; ?>
  <?php if ($isOwnProfile): ?>
    <a href="add_history.php" class="btn btn-outline-info btn-sm">Add History</a>
  <?php endif; ?>
</div>
</body>
</html>
