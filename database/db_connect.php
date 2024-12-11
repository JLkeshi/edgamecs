<?php
// Database credentials
$host = "sql202.infinityfree.com";
$dbname = "if0_37892376_seproject";
$username = "if0_37892376";
$password = "A84oEAkNuxwC";
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

