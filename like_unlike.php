<?php
include "db.php";
session_start();

if (isset($_POST['like_post_id'])) {
    $postId = $_POST['like_post_id'];
    $userId = $_SESSION['id'];

    $sqlCheckLike = "SELECT * FROM blog_likes WHERE post_id='$postId' AND user_id='$userId'";
    $resultCheckLike = mysqli_query($conn, $sqlCheckLike);

    if ($resultCheckLike->num_rows > 0) {
        $sqlUnlike = "DELETE FROM blog_likes WHERE post_id='$postId' AND user_id='$userId'";
        mysqli_query($conn, $sqlUnlike);
    } else {
        $sqlLike = "INSERT INTO blog_likes (post_id, user_id) VALUES ('$postId', '$userId')";
        mysqli_query($conn, $sqlLike);
    }

    $sqlLikeCount = "SELECT COUNT(*) as like_count FROM blog_likes WHERE post_id='$postId'";
    $likeCountResult = mysqli_query($conn, $sqlLikeCount);
    $likeCount = mysqli_fetch_assoc($likeCountResult)['like_count'];

    echo $likeCount;
}
?>
