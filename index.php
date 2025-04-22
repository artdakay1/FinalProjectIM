<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TeamLink – Home</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link href="css/styles.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">🏆 TeamLink</a>
    <div class="ms-auto">
      <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
      <a href="register.php" class="btn btn-primary">Sign Up</a>
    </div>
  </nav>

  
  <div class="homepage">
    <div class="container text-center text-dark">
      <h1 class="display-4">Welcome to TeamLink</h1>
      <p class="lead">Where athletes, fans, and coaches connect.</p>
      <a href="register.php" class="btn btn-lg btn-success">Get Started</a>
    </div>
  </div>
</body>
</html>
