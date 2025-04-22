<?php
include 'includes/db.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login - TeamLink</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2>Login</h2>
    <form method="POST" action="">
      <input type="text" name="username" class="form-control mb-2" placeholder="Username or Email" required>
      <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
      <button type="submit" name="login" class="btn btn-primary">Login</button>
    </form>

    <?php
    if (isset($_POST['login'])) {
      $username = $_POST['username'];
      $password = $_POST['password'];

      $sql = "SELECT * FROM users WHERE username=? OR email=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ss", $username, $username);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['username'] = $user['username'];
          $_SESSION['role'] = $user['role'];
          header("Location: dashboard.php");
        } else {
          echo "<div class='alert alert-danger mt-2'>Invalid password</div>";
        }
      } else {
        echo "<div class='alert alert-danger mt-2'>User not found</div>";
      }
    }
    ?>
  </div>
</body>
</html>
