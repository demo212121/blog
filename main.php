<?php
include "db.php";
session_start();

// Handle blog post deletion
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // Display user information
    echo "Logged in as: " . $_SESSION["username"];
} else {
    // If the user is not logged in, display a message or redirect to the login page
    echo "You are not logged in. Please log in to view blog posts.";
    // Alternatively, you can redirect to the login page using header("Location: login.php");
}

if (isset($_GET['delete_post_id'])) {
    $deletePostId = $_GET['delete_post_id'];
    $sqlDeletePictures = "DELETE FROM blog_pictures WHERE post_id='$deletePostId'";
    $sqlDeletePost = "DELETE FROM blog WHERE id='$deletePostId'";

    mysqli_query($conn, $sqlDeletePictures);
    mysqli_query($conn, $sqlDeletePost);
}

// Handle picture deletion
if (isset($_GET['delete_picture_id'])) {
    $deletePictureId = $_GET['delete_picture_id'];
    $sqlDeletePicture = "DELETE FROM blog_pictures WHERE id='$deletePictureId'";

    mysqli_query($conn, $sqlDeletePicture);
}

// Handle like/unlike
if (isset($_POST['like_post_id'])) {
    $postId = $_POST['like_post_id'];
    $userId = $_SESSION['id'];

    // Check if the user has already liked the post
    $sqlCheckLike = "SELECT * FROM blog_likes WHERE post_id='$postId' AND user_id='$userId'";
    $resultCheckLike = mysqli_query($conn, $sqlCheckLike);

    if ($resultCheckLike->num_rows > 0) {
        // User has already liked the post, so unlike it
        $sqlUnlike = "DELETE FROM blog_likes WHERE post_id='$postId' AND user_id='$userId'";
        mysqli_query($conn, $sqlUnlike);
    } else {
        // User has not liked the post yet, so like it
        $sqlLike = "INSERT INTO blog_likes (post_id, user_id) VALUES ('$postId', '$userId')";
        mysqli_query($conn, $sqlLike);
    }
}

// Insert new blog post
if (isset($_POST['save'])) {
    $sql = "INSERT INTO blog (blog_title, blog_description, time)
            VALUES ('" . $_POST["blog_title"] . "', '" . $_POST["blog_description"] . "', '" . $_POST["time"] . "')";

    if (mysqli_query($conn, $sql)) {
        $last_id = mysqli_insert_id($conn);
        $sqlImg = "INSERT INTO blog_pictures (img_url, post_id, is_main)
                   VALUES ('" . $_POST["img_url"] . "', $last_id, 1)";
        mysqli_query($conn, $sqlImg);
    }
}

$sql = "SELECT * FROM blog";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(270deg, #ff9a9e, #fad0c4, #fad0c4, #ff9a9e);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            font-family: "Avenir Next", "Avenir", sans-serif;
        }

        nav {
            background: rgba(0, 0, 0, 0.7);
            padding: 10px 20px;
        }

        #menuToggle {
            display: block;
            position: relative;
            top: 10px;
            left: 10px;
            z-index: 1;
            -webkit-user-select: none;
            user-select: none;
        }

        #menuToggle a {
            text-decoration: none;
            color: #89CFF0;
            transition: color 0.3s ease;
        }

        #menuToggle a:hover {
            color: white;
        }

        #menuToggle input {
            display: block;
            width: 40px;
            height: 32px;
            position: absolute;
            top: 0px;
            left: 0px;
            cursor: pointer;
            opacity: 0;
            z-index: 2;
            -webkit-touch-callout: none;
        }

        #menuToggle span {
            display: block;
            width: 33px;
            height: 4px;
            margin-bottom: 5px;
            position: relative;
            background: black;
            border-radius: 3px;
            z-index: 1;
            transform-origin: 4px 0px;
            transition: transform 0.5s cubic-bezier(0.77, 0.2, 0.05, 1.0), background 0.5s cubic-bezier(0.77, 0.2, 0.05, 1.0), opacity 0.55s ease;
        }

        #menuToggle span:first-child {
            transform-origin: 0% 0%;
        }

        #menuToggle span:nth-last-child(2) {
            transform-origin: 0% 100%;
        }

        #menuToggle input:checked~span {
            opacity: 1;
            transform: rotate(45deg) translate(-2px, -1px);
            background: #89CFF0;
        }

        #menuToggle input:checked~span:nth-last-child(3) {
            opacity: 0;
            transform: rotate(0deg) scale(0.2, 0.2);
        }

        #menuToggle input:checked~span:nth-last-child(2) {
            transform: rotate(-45deg) translate(0, -1px);
        }

        #menu {
            position: absolute;
            width: 300px;
            margin: -100px 0 0 -50px;
            padding: 50px;
            padding-top: 125px;
            background: black;
            list-style-type: none;
            -webkit-font-smoothing: antialiased;
            transform-origin: 0% 0%;
            transform: translate(-100%, 0);
            transition: transform 0.5s cubic-bezier(0.77, 0.2, 0.05, 1.0);
        }

        #menu li {
            padding: 10px 0;
            font-size: 22px;
        }

        #menuToggle input:checked~ul {
            transform: none;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }

        .blog-post {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            width: 300px;
            text-align: center;
            position: relative;
        }

        .blog-post img {
            max-width: 100%;
            border-radius: 5px;
        }

        .blog-post h2 {
            font-size: 24px;
            margin: 10px 0;
        }

        .blog-post p {
            font-size: 16px;
            color: #555;
        }

        .blog-post .actions {
            margin-top: 10px;
        }

        .blog-post .actions a {
            text-decoration: none;
            color: red;
            font-size: 24px;
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="date"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #89CFF0;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #6fa3cc;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.like-button').click(function (e) {
                e.preventDefault();
                var postId = $(this).data('post-id');
                var heartIcon = $(this).find('i');

                $.ajax({
                    type: 'POST',
                    url: 'like_unlike.php',
                    data: { like_post_id: postId },
                    success: function (response) {
                        if (heartIcon.hasClass('fa-heart-o')) {
                            heartIcon.removeClass('fa-heart-o').addClass('fa fa-heart');
                        } else {
                            heartIcon.removeClass('fa-heart').addClass('fa fa-heart-o');
                        }
                        $('#like-count-' + postId).text(response);
                    }
                });
            });
        });
    </script>
</head>

<body>
    <nav role="navigation">
        <div id="menuToggle">
            <input type="checkbox" />
            <span></span>
            <span></span>
            <span></span>
            <ul id="menu">
                <a href="main.php">
                    <li>Main page</li>
                </a>
                <a href="input.php">
                    <li>Create post!</li>
                </a>
                <a href="logout.php">
                    <li>Logout</li>
                </a>
            </ul>
        </div>
    </nav>

    <div class="container">
        <?php
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $postId = $row['id'];
                    $userId = $row['user_id'];
                    $sqlPictures = "SELECT * FROM blog_pictures WHERE post_id='$postId'";
                    $picturesResult = $conn->query($sqlPictures);
                    $sqlLikeCount = "SELECT COUNT(*) as like_count FROM blog_likes WHERE post_id='$postId'";
                    $likeCountResult = mysqli_query($conn, $sqlLikeCount);
                    $likeCount = mysqli_fetch_assoc($likeCountResult)['like_count'];
                    $sqlUserLiked = "SELECT * FROM blog_likes WHERE post_id='$postId' AND user_id='{$_SESSION['id']}'";
                    $userLikedResult = mysqli_query($conn, $sqlUserLiked);
                    $userLiked = $userLikedResult->num_rows > 0;
                    ?>
                    <div class="blog-post">
                        <a href="comment.php?id=<?php echo $postId; ?>" style="text-decoration: none; color: inherit;">
                            <h2><?php echo $row['blog_title']; ?></h2>
                            <p><?php echo $row['blog_description']; ?></p>
                            <p><?php echo $row['time']; ?></p>
                        </a>
                        <div class="actions">
                            <a href="#" class="like-button" data-post-id="<?php echo $postId; ?>">
                                <i class="fa <?php echo $userLiked ? 'fa-heart' : 'fa-heart-o'; ?>" style="color: red;"></i>
                            </a>
                            <span id="like-count-<?php echo $postId; ?>"><?php echo $likeCount; ?></span>
                            <?php if ($_SESSION["id"] == $userId) { ?>
                                <a href="delete_post.php?delete_post_id=<?php echo $postId; ?>" onclick="return confirm('Are you sure you want to delete this post?')">Delete Post</a>
                            <?php } ?>
                        </div>
                        <?php
                        if ($picturesResult->num_rows > 0) {
                            while ($picRow = $picturesResult->fetch_assoc()) {
                                ?>
                                <div>
                                    <img src="<?php echo $picRow['img_url']; ?>" alt="Image">
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No blog posts found.</p>";
            }
        } else {
            echo "<p>Please log in to view blog posts.</p>";
        }
        ?>
    </div>
</body>
</html>
