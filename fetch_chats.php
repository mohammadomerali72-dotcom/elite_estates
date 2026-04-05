<?php
session_start(); include '../includes/db.php'; $me = $_SESSION['user_id'];
$sql = "SELECT users.id, users.full_name FROM users 
        JOIN messages ON (users.id = messages.sender_id OR users.id = messages.receiver_id)
        WHERE (messages.sender_id = '$me' OR messages.receiver_id = '$me') AND users.id != '$me'
        GROUP BY users.id ORDER BY messages.sent_at DESC";
$res = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($res)) {
    echo "<div class='chat-user-item' onclick='openChat({$row['id']}, \"{$row['full_name']}\")'>
            <div class='user-avatar'><img src='https://ui-avatars.com/api/?name={$row['full_name']}'></div>
            <div class='chat-info'><h4>{$row['full_name']}</h4><p>Click to chat...</p></div>
          </div>";
}
?>