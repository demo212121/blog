<?php
include "db.php";
session_start();
var_dump($_SESSION["loggedin"]); // Debug statement to check the value of $_SESSION["loggedin"]
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_POST['save'])) {
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      $sql = "INSERT INTO blog (user_id, blog_title, blog_description, time)
              VALUES ('" . $_SESSION["id"] . "', '" . $_POST["blog_title"] . "', '" . $_POST["blog_description"] . "', '" . $_POST["time"] . "')";
  
      if (mysqli_query($conn, $sql)) {
          $last_id = mysqli_insert_id($conn);
          $sqlImg = "INSERT INTO blog_pictures (img_url, post_id, is_main)
                     VALUES ('" . $_POST["img_url"] . "', $last_id, 1)";
          mysqli_query($conn, $sqlImg);
      }
  } else {
      echo "You must be logged in to create a post.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
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

        input[type="text"], input[type="date"] {
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
        <form method="post">
            <label for="blog_title">Blog Title</label>
            <input type="text" id="blog_title" name="blog_title" required>
            
            <label for="blog_description">Blog Description</label>
            <input type="text" id="blog_description" name="blog_description" required>
            
            <label for="time">Time</label>
            <input type="date" id="time" name="time" required>
            
            <label for="img_url">Picture URL</label>
            <input type="text" id="img_url" name="img_url" placeholder="Image URL" required>
            
            <button type="submit" name="save">Save</button>
        </form>
    </div>
</body>

</html>
