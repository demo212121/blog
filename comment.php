<?php
session_start();
include "db.php";

// Check if 'blog_id' parameter is set in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Error: Missing or invalid blog post ID.');
}

$id = intval($_GET['id']); // Sanitize the input to prevent SQL injection


$sql = "SELECT * FROM blog WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the query was successful
if ($result === false) {
    die('Error: ' . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* CSS styles */
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(270deg, #ff9a9e, #fad0c4, #fad0c4, #ff9a9e);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            font-family: "Avenir Next", "Avenir", sans-serif;
        }
        #menuToggle {
            display: block;
            position: relative;
            top: 50px;
            left: 25px;
            z-index: 1;
            -webkit-user-select: none;
            user-select: none;
        }
        #menuToggle a {
            text-decoration: none;
            color: #89CFF0;
            transition: color 0.3s ease;
        }
        #menuToggle a:hover { color: white; }
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
            transition: transform 0.5s cubic-bezier(0.77, 0.2, 0.05, 1.0),
                        background 0.5s cubic-bezier(0.77, 0.2, 0.05, 1.0),
                        opacity 0.55s ease;
        }
        #menuToggle span:first-child { transform-origin: 0% 0%; }
        #menuToggle span:nth-last-child(2) { transform-origin: 0% 100%; }
        #menuToggle input:checked ~ span {
            opacity: 1;
            transform: rotate(45deg) translate(-2px, -1px);
            background: #89CFF0;
        }
        #menuToggle input:checked ~ span:nth-last-child(3) {
            opacity: 0;
            transform: rotate(0deg) scale(0.2, 0.2);
        }
        #menuToggle input:checked ~ span:nth-last-child(2) {
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
        #menuToggle input:checked ~ ul { transform: none; }
        .single_blog, .blog_text, .time {
            font-size: 30px;
            text-align: center;
        }
        .post_id {
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
        }
        .img {
            width: 400px;
            height: 400px;
            border-style: solid;
        }
        .comment {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .comment-section {
            margin-top: 20px;
        }
        .comment-item {
            background: #fff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            position: relative;
        }
        .comment-item p { margin: 0; }
        .delete-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <nav role="navigation">
        <div id="menuToggle">
            <input type="checkbox" />
            <span></span>
            <span></span>
            <span></span>
            <ul id="menu">
                <a href="main.php"><li>Main page</li></a>
                <a href="input.php"><li>Create post!</li></a>
            </ul>
        </div>
    </nav>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Output blog post content
            echo "<div class='single_blog'>" . htmlspecialchars($row['blog_title']) . "</div>";
            echo "<div class='blog_text'>" . htmlspecialchars($row['blog_description']) . "</div>";
            echo "<div class='time'>" . htmlspecialchars($row['time']) . "</div>";

            // Fetch and display images associated with the post
            $sqlp = "SELECT * FROM blog_pictures WHERE post_id=?";
            $stmtp = $conn->prepare($sqlp);
            $stmtp->bind_param("i", $row['id']);
            $stmtp->execute();
            $resultp = $stmtp->get_result();

            if ($resultp->num_rows > 0) {
                while ($rowp = $resultp->fetch_assoc()) {
                    echo "<div class='post_id'><img class='img' src='" . htmlspecialchars($rowp['img_url']) . "'></div>";
                }
            } else {
                echo "<div class='post_id'>No images available</div>";
            }
        }
    } else {
        echo "<div class='single_blog'>No blog post found</div>";
    }
    ?>

    <!-- Comment Section -->
    <div class="comment">
        <form id="commentForm">
            <input name="comment" type="text" required>
            <input type="hidden" name="blog_id" value="<?php echo $id; ?>">
            <button type="submit">Submit</button>
        </form>
    </div>
    <div id="commentResponse"></div>
    <div class="comment-section" id="commentSection">
        <?php
        // Fetch and display comments
        $sqlc = "SELECT * FROM blog_comment WHERE blog_id=?";
        $stmtc = $conn->prepare($sqlc);
        $stmtc->bind_param("i", $id);
        $stmtc->execute();
        $resultc = $stmtc->get_result();

        if ($resultc === false) {
            echo "<p>Error fetching comments: " . $conn->error . "</p>";
        } elseif ($resultc->num_rows > 0) {
            while ($rowc = $resultc->fetch_assoc()) {
                echo "<div class='comment-item' id='comment-" . $rowc['id'] . "'>";
                echo "<p>" . htmlspecialchars($rowc['comment']) . "</p>";
                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $rowc['user_id']) {
                    echo "<button class='delete-button' onclick='deleteComment(" . $rowc['id'] . ")'>Delete</button>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No comments yet.</p>";
        }
        ?>
    </div>

    <!-- JavaScript code -->
    <script>
        document.getElementById('commentForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch('submit_comment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                var responseDiv = document.getElementById('commentResponse');
                if (data.status === 'success') {
                    responseDiv.innerHTML = `<p>${data.message}</p>`;
                    // Optionally clear the form after successful submission
                    this.reset();

                    // Append the new comment to the comment section
                    var commentSection = document.getElementById('commentSection');
                    var newComment = document.createElement('div');
                    newComment.className = 'comment-item';
                    newComment.id = 'comment-' + data.comment_id;
                    newComment.innerHTML = `<p>${formData.get('comment')}</p>`;
                    if (data.user_id == <?php echo $_SESSION['user_id']; ?>) {
                        newComment.innerHTML += `<button class='delete-button' onclick='deleteComment(${data.comment_id})'>Delete</button>`;
                    }
                    commentSection.appendChild(newComment);
                } else {
                    responseDiv.innerHTML = `<p>Error: ${data.message}</p>`;
                }
            })
            .catch(error => console.error('Error:', error));
        });

        function deleteComment(commentId) {
            if (confirm('Are you sure you want to delete this comment?')) {
                fetch('delete_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ comment_id: commentId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        var commentElement = document.getElementById('comment-' + commentId);
                        commentElement.remove();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>
