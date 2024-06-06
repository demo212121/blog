<?php

$servername = "localhost";
$username = "kantanud_toms";
$password = "Tomats123123";
$db = "kantanud_toms";
// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
