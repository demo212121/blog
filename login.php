<?php
session_start(); // Start session

// Check if the user is already logged in, if yes, redirect them to the profile page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: main.php");
    exit;
}

// Include database connection
include "db.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Check input errors before attempting to login
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1){
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, start a new session
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Debugging statements
                            error_log("Login successful. Redirecting to main.php");
                            error_log("Session variables: " . print_r($_SESSION, true));

                            // Redirect user to profile page
                            header("location: main.php");
                            exit;
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 20;
            padding: 1;
            background: #232323;
            color: black;
            font-family: "Avenir Next", "Avenir", sans-serif;
        }

        #menuToggle {
            display: block;
            position: relative;
            top: 50px;
            left: 50px;
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
            transition: transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0),
                        background 0.5s cubic-bezier(0.77,0.2,0.05,1.0),
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
            transition: transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0);
        }

        #menu li {
            padding: 10px 0;
            font-size: 22px;
        }

        #menuToggle input:checked ~ ul {
            transform: none;
        }

        body {
            background-image: linear-gradient(to bottom left, black, pink, red, green, orange, blue, purple, yellow, white);
        }

        /* Full-width inputs */
        input[type=text], input[type=password] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            background-color: black;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 20%;
        }

        button:hover {
            opacity: 0.8;
        }

        .container {
            padding: 16px;
            margin-right: auto;
            margin-left: auto;
            width: 30%;
            border: 3px solid black;
        }

        span.psw {
            float: right;
            padding-top: 16px;
        }

        @media screen and (max-width: 300px) {
            span.psw {
                display: block;
                float: none;
            }
            .cancelbtn {
                width: 20%;
            }
        }

        .imgcontainer {
            width: 25%;
            background-color: red;
            margin-right: auto;
            margin-left: auto;
        }

        #login-error-msg-holder {
            width: 100%;
            height: 100%;
            display: grid;
            justify-items: center;
            align-items: center;
        }

        #login-error-msg {
            width: 23%;
            text-align: center;
            margin: 0;
            padding: 5px;
            font-size: 12px;
            font-weight: bold;
            color: #8a0000;
            border: 1px solid #8a0000;
            background-color: #e58f8f;
            opacity: 0;
        }

        #error-msg-second-line {
            display: block;
        }
    </style>
</head>
<body>
    <form method="post">
        <div class="imgcontainer"></div>
        
        <div class="container">
            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" required value="<?php echo htmlspecialchars($username); ?>"><br>
            <span><?php echo $username_err; ?></span>

            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" required><br>
            <span><?php echo $password_err; ?></span>

            <button type="submit" name="submit">Login</button>
            <label>
              <input type="checkbox" checked="checked" name="remember"> Remember me
            </label>
        </div>
    </form>
</body>
</html>
