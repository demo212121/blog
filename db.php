<?php

$servername = "fdb34.awardspace.net";
$username = "3931234_aitugovs";
$password = "demodog21";
$db = "3931234_aitugovs";
// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
