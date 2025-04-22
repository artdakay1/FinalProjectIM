<?php
include 'includes/auth.php';
include 'includes/db.php';

$currentUser = $_SESSION['user_id'];
$chatUserId = isset($_GET['chat']) ? intval($_GET['chat']) : null;


if ($_SERVER['REQUEST_METHOD'] == 'POST' && $chatUserId) {
    $content = $_POST['message'];
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $currentUser, $chatUserId, $content);
    $stmt->execute();
    echo "Message Sent";
    exit;
}

$users = $conn->query("SELECT id, username FROM users WHERE id != $currentUser");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Messages - TeamLink</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    .chat-box {
      height: 400px;
      overflow-y: auto;
      background: #f0f2f5;
      padding: 15px;
      border-radius: 10px;
      display: flex;
      flex-direction: column;
    }

    .message {
      max-width: 75%;
      margin-bottom: 12px;
      padding: 10px 14px;
      border-radius: 18px;
      font-size: 15px;
      display: inline-block;
      position: relative;
    }

    .message-in {
      align-self: flex-start;
      background-color: #ffffff;
      color: #000;
      border: 1px solid #ddd;
    }

    .message-out {
      align-self: flex-end;
      background-color: #0084ff;
      color: #fff;
    }

    .timestamp {
      font-size: 11px;
      color: #999;
      margin-top: 2px;
    }

    .sidebar a.active {
      background-color: #0084ff;
      color: #fff;
    }
  </style>
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <div class="row">
   
    <div class="col-md-4 sidebar">
      <h5>Chats</h5>
      <ul class="list-group">
        <?php while ($user = $users->fetch_assoc()): ?>
        <a href="messages.php?chat=<?= $user['id'] ?>" class="list-group-item <?= ($chatUserId == $user['id']) ? 'active' : '' ?>">
          <?= htmlspecialchars($user['username']) ?>
        </a>
        <?php endwhile; ?>
      </ul>
    </div>

    
    <div class="col-md-8">
      <?php if ($chatUserId): ?>
        <h5>Chat with <?php
          $u = $conn->query("SELECT username FROM users WHERE id = $chatUserId")->fetch_assoc();
          echo htmlspecialchars($u['username']);
        ?></h5>

        <div class="chat-box mb-3" id="chat-box">
          
        </div>

        <form id="message-form" class="d-flex">
          <input type="text" name="message" class="form-control me-2" placeholder="Type a message..." required>
          <button type="submit" class="btn btn-primary">Send</button>
        </form>
      <?php else: ?>
        <p>Select a user to start chatting.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
  const chatUserId = <?= $chatUserId ?>;

  
  function loadMessages() {
    fetch('getMessages.php?chat=' + chatUserId)
      .then(response => response.text())
      .then(data => {
        document.getElementById('chat-box').innerHTML = data;
        document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
      });
  }

  
  document.getElementById('message-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const message = document.querySelector('[name="message"]').value;

    fetch('messages.php?chat=' + chatUserId, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'message=' + encodeURIComponent(message)
    })
    .then(response => response.text())
    .then(data => {
      loadMessages();  
      document.querySelector('[name="message"]').value = '';  
    });
  });

  
  setInterval(loadMessages, 2000);
  loadMessages(); 
</script>

</body>
</html>
