<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TeamLink</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">TeamLink</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">

        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Dashboard</a>
        </li>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'coach'): ?>
        <li class="nav-item">
          <a class="nav-link" href="add_team.php">Add Team</a>
        </li>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link" href="recruitment.php">Recruitment</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="shop.php">Shop</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="messages.php">Messages</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="profile.php">Profile</a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item">
          <a class="nav-link" href="seller_dashboard.php">Seller Dashboard</a>
        </li>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>

      <form class="d-flex ms-auto" action="search.php" method="GET">
        <input class="form-control me-2" type="search" name="q"
               placeholder="Search teams, players, posts…" aria-label="Search">
        <button class="btn btn-outline-light" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
