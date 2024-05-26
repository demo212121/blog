<?php
session_start();
include "db.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['comment']) && isset($_POST['blog_id'])) {
        $comment = trim($_POST['comment']);
        $blog_id = intval($_POST['blog_id']);
        $user_id = $_SESSION['user_id']; // Assuming you have user session management

        if ($comment !== '' && $blog_id > 0) {
            $stmt = $conn->prepare("INSERT INTO blog_comment (blog_id, user_id, comment) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $blog_id, $user_id, $comment);

            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Comment added successfully',
                    'comment_id' => $stmt->insert_id,
                    'user_id' => $user_id
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add comment']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid comment or blog ID']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
