<?php
session_start();
include "db.php";

$data = json_decode(file_get_contents('php://input'), true);
$comment_id = intval($data['comment_id']);
$user_id = intval($_SESSION['user_id']);

// Verify if the comment belongs to the logged-in user
$sql = "SELECT * FROM blog_comment WHERE id=$comment_id AND user_id=$user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $sql = "DELETE FROM blog_comment WHERE id=$comment_id AND user_id=$user_id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Comment deleted!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized action.']);
}
?>
