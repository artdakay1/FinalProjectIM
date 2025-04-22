<?php
include 'includes/auth.php';
include 'includes/db.php';


if (isset($_POST['add'])) {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $date = $_POST['date'];
  $time = $_POST['time'];
  $location = $_POST['location'];
  $created_by = $_SESSION['user_id'];

  $stmt = $conn->prepare("INSERT INTO schedule (title, description, event_date, event_time, location, created_by) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssi", $title, $description, $date, $time, $location, $created_by);
  $stmt->execute();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Schedule - TeamLink</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <h2>Upcoming Games & Practices</h2>

  <?php if ($_SESSION['role'] == 'coach' || $_SESSION['role'] == 'admin'): ?>
  <form method="POST" class="mb-4 border p-3 rounded bg-light">
    <h5>Add New Event</h5>
    <input type="text" name="title" class="form-control mb-2" placeholder="Event Title" required>
    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
    <input type="date" name="date" class="form-control mb-2" required>
    <input type="time" name="time" class="form-control mb-2" required>
    <input type="text" name="location" class="form-control mb-2" placeholder="Location">
    <button type="submit" name="add" class="btn btn-primary">Add Event</button>
  </form>
  <?php endif; ?>

  <?php
  $result = $conn->query("SELECT schedule.*, users.username FROM schedule LEFT JOIN users ON schedule.created_by = users.id ORDER BY event_date ASC, event_time ASC");
  while ($event = $result->fetch_assoc()):
  ?>
    <div class="card mb-3">
      <div class="card-body">
        <h5><?= htmlspecialchars($event['title']) ?> <small class="text-muted">(by <?= $event['username'] ?>)</small></h5>
        <p><?= htmlspecialchars($event['description']) ?></p>
        <p><strong>Date:</strong> <?= $event['event_date'] ?> | <strong>Time:</strong> <?= date("g:i A", strtotime($event['event_time'])) ?> | <strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
      </div>
    </div>
  <?php endwhile; ?>
</div>
</body>
</html>
