<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';


$query = isset($_GET['q']) ? $_GET['q'] : '';


$teams = [];
$players = [];
$posts = [];

if ($query) {
    
    $teamStmt = $conn->prepare("SELECT * FROM teams WHERE team_name LIKE ?");
    $searchTerm = "%" . $query . "%";
    $teamStmt->bind_param("s", $searchTerm);
    $teamStmt->execute();
    $teamResult = $teamStmt->get_result();
    while ($row = $teamResult->fetch_assoc()) {
        $teams[] = $row;
    }

    
    $playerStmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ?");
    $playerStmt->bind_param("s", $searchTerm);
    $playerStmt->execute();
    $playerResult = $playerStmt->get_result();
    while ($row = $playerResult->fetch_assoc()) {
        $players[] = $row;
    }

    
    $postStmt = $conn->prepare("SELECT * FROM posts WHERE content LIKE ?");
    $postStmt->bind_param("s", $searchTerm);
    $postStmt->execute();
    $postResult = $postStmt->get_result();
    while ($row = $postResult->fetch_assoc()) {
        $posts[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Results - TeamLink</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h3>Search Results for: <strong><?= htmlspecialchars($query) ?></strong></h3>

    <?php if (count($teams) > 0): ?>
        <h4>Teams</h4>
        <?php foreach ($teams as $team): ?>
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($team['team_name']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($team['sport']) ?></p>
                    <a href="team.php?id=<?= $team['id'] ?>" class="btn btn-primary">View Team</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No teams found.</p>
    <?php endif; ?>

    <?php if (count($players) > 0): ?>
        <h4>Players/Coaches</h4>
        <?php foreach ($players as $player): ?>
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($player['username']) ?></h5>
                    <a href="profile.php?id=<?= $player['id'] ?>" class="btn btn-primary">View Profile</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No players or coaches found.</p>
    <?php endif; ?>

    <?php if (count($posts) > 0): ?>
        <h4>Posts</h4>
        <?php foreach ($posts as $post): ?>
            <div class="card mb-2">
                <div class="card-body">
                    <p class="card-text"><?= htmlspecialchars($post['content']) ?></p>
                    <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-primary">View Post</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>
</div>

</body>
</html>
