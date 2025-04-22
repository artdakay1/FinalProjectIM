<?php
include 'includes/auth.php';
include 'includes/db.php';
include 'includes/header.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard - TeamLink</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">🏆 TeamLink</a>
    <div class="ms-auto text-white me-3">
      Welcome, <?php echo $_SESSION['username']; ?> |
      <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
    </div>
  </nav>

  <div class="container mt-5">
    <h2>Dashboard</h2>
    <p>You are logged in as a <strong><?php echo $_SESSION['role']; ?></strong>.</p>

    <div class="row mt-4">
      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-body">
            <h5 class="card-title">📅 Schedule</h5>
            <p class="card-text">View and manage practices and games.</p>
            <a href="schedule.php" class="btn btn-primary">Go</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-body">
            <h5 class="card-title">📰 Newsfeed</h5>
            <p class="card-text">See posts from teams, coaches, and fans.</p>
            <a href="feed.php" class="btn btn-primary">Go</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-body">
            <h5 class="card-title">💬 Messages</h5>
            <p class="card-text">Chat with teammates or coaches.</p>
            <a href="messages.php" class="btn btn-primary">Go</a>
          </div>
        </div>
      </div>
    </div>

  </div>
</body>
</html>
