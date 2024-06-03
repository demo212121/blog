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
            background: transparent;
            padding: 10px 20px;
            position: relative;
            z-index: 10;
        }

        #menuToggle {
            display: block;
            position: relative;
            z-index: 11;
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
            top: 0;
            left: 0;
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

        #menuToggle span:first-child {
            transform-origin: 0% 0%;
        }

        #menuToggle span:nth-last-child(2) {
            transform-origin: 0% 100%;
        }

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

        #menuToggle input:checked ~ ul {
            transform: none;
        }

        .container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .blog-post {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
            padding: 20px;
            text-align: center;
        }

        .blog-post h2 {
            font-size: 36px;
            margin: 10px 0;
        }

        .blog-post p {
            font-size: 18px;
            color: #555;
            margin: 10px 0;
        }

        .blog-post .time {
            font-size: 14px;
            color: #aaa;
            margin-top: 10px;
        }

        .blog-post img {
            max-width: 100%;
            border-radius: 5px;
            margin: 20px 0;
        }

        .comment-section {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px 0;
        }

        .comment-section h3 {
            margin-top: 0;
        }

        .comment-item {
            background: #fff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            position: relative;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comment-item p {
            margin: 0;
            font-size: 16px;
        }

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

        .comment-form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .comment-form input[type="text"] {
            width: 80%;
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .comment-form button {
            padding: 10px 20px;
            background-color: #89CFF0;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .comment-form button:hover {
            background-color: #6fa3cc;
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
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='blog-post'>";
                echo "<h2>" . htmlspecialchars($row['blog_title']) . "</h2>";
                echo "<p>" . htmlspecialchars($row['blog_description']) . "</p>";
                echo "<div class='time'>" . htmlspecialchars($row['time']) . "</div>";

                $sqlp = "SELECT * FROM blog_pictures WHERE post_id=?";
                $stmtp = $conn->prepare($sqlp);
                $stmtp->bind_param("i", $row['id']);
                $stmtp->execute();
                $resultp = $stmtp->get_result();

                if ($resultp->num_rows > 0) {
                    while ($rowp = $resultp->fetch_assoc()) {
                        echo "<img src='" . htmlspecialchars($rowp['img_url']) . "'>";
                    }
                } else {
                    echo "<p>No images available</p>";
                }
                echo "</div>";
            }
        } else {
            echo "<div class='blog-post'>No blog post found</div>";
        }
        ?>

        <div class="comment-form">
            <form id="commentForm">
                <input name="comment" type="text" required>
                <input type="hidden" name="blog_id" value="<?php echo $id; ?>">
                <button type="submit">Submit</button>
            </form>
        </div>
        <div id="commentResponse"></div>
        <div class="comment-section" id="commentSection">
            <h3>Comments</h3>
            <?php
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
    </div>

    <script>
        document.getElementById('commentForm').addEventListener('submit', function (event) {
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
                        this.reset();

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
