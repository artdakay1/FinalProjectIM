<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Register - TeamLink</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2>Sign Up</h2>
    <form method="POST" action="">
      <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
      <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
      <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
      <select name="role" class="form-control mb-2">
        <option value="player">Player</option>
        <option value="coach">Coach</option>
        <option value="fan">Fan</option>
      </select>
      <button type="submit" name="register" class="btn btn-success">Register</button>
    </form>

    <?php
    if (isset($_POST['register'])) {
      $username = $_POST['username'];
      $email = $_POST['email'];
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $role = $_POST['role'];

      $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $username, $email, $password, $role);

      if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-2'>Account created! <a href='login.php'>Login here</a>.</div>";
      } else {
        echo "<div class='alert alert-danger mt-2'>Error: " . $conn->error . "</div>";
      }
    }
    ?>
  </div>
</body>
</html>
