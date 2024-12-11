<?php
// Database credentials
$host = "127.0.0.1:3307";
$dbname = "seproject";
$username = "lloyd";
$password = "";
$conn = "";

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}
else{
}

?>


