<?php

session_start();


if (!isset($_SESSION['user_id'])) {
    
    header("Location: login.php");
    exit();
}


include 'includes/db.php';


$currentUser = $_SESSION['user_id'];


$chatUserId = isset($_GET['chat']) ? intval($_GET['chat']) : null;


if ($chatUserId) {
    
    $stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?) ORDER BY sent_at ASC");
    $stmt->bind_param("iiii", $currentUser, $chatUserId, $chatUserId, $currentUser);
    $stmt->execute();
    $result = $stmt->get_result();

    
    while ($msg = $result->fetch_assoc()) {
        $isOwn = ($msg['sender_id'] == $currentUser);
        $class = $isOwn ? 'message-out' : 'message-in';
        $time = date("g:i A", strtotime($msg['sent_at'])); 
        echo "
            <div class='message $class'>
                ".htmlspecialchars($msg['content'])."
                <div class='timestamp'>$time</div>
            </div>
        ";
    }
} else {
    echo "No chat selected.";
}
?>
