<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$recruitment_id = $_GET['id'] ?? null;

if (!$recruitment_id) {
    echo "Invalid recruitment post.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);
    $applicant_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO recruitment_applications (recruitment_id, applicant_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $recruitment_id, $applicant_id, $message);
    $stmt->execute();

    header("Location: recruitment.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Apply to Recruitment</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <h3>Apply to Recruitment</h3>

  <form method="POST">
    <div class="mb-3">
      <label for="message" class="form-label">Message (Optional)</label>
      <textarea name="message" id="message" class="form-control" rows="4" placeholder="Why should you be recruited?"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit Application</button>
    <a href="recruitment.php" class="btn btn-secondary">Back</a>
  </form>
</div>
</body>
</html>
