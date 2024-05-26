<?php
session_start();
include "db.php";

// Check if the user is logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

    // Check if the delete_post_id is set and is a valid integer
    if(isset($_GET['delete_post_id']) && is_numeric($_GET['delete_post_id'])) {
        $deletePostId = $_GET['delete_post_id'];

        // Retrieve the user ID of the post owner
        $sql = "SELECT user_id FROM blog WHERE id = $deletePostId";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $postOwnerId = $row['user_id'];

            // Check if the post owner matches the currently logged-in user
            if($postOwnerId == $_SESSION['id']) {
                // Proceed with deleting the post and associated pictures
                $sqlDeletePictures = "DELETE FROM blog_pictures WHERE post_id='$deletePostId'";
                $sqlDeletePost = "DELETE FROM blog WHERE id='$deletePostId'";

                mysqli_query($conn, $sqlDeletePictures);
                mysqli_query($conn, $sqlDeletePost);

                // Redirect to a suitable page after successful deletion
                header("location: main.php");
                exit();
            } else {
                // If the user is not the owner of the post, show an error message
                echo "You are not authorized to delete this post.";
            }
        } else {
            // If no post is found with the given ID, show an error message
            echo "No post found with the provided ID.";
        }
    } else {
        // If delete_post_id is not set or not a valid integer, show an error message
        echo "Invalid post ID.";
    }
} else {
    // If the user is not logged in, redirect to the login page
    header("location: login.php");
    exit();
}
?>
