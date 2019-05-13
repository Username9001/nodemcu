<?php
$button = $_GET['pressed'];
$servername = "http://159.65.204.46/";
$username = "root";
$password = "tiger";
$dbname = "nodemcu";
// Create connection
$conn = new mysqli($servername, $username,$password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$val = $_GET['pressed'];
// $val = "pressed";
$sql = "INSERT INTO buttonpress(pressed) VALUES ($val);";
if ($conn->query($sql) === TRUE) {
    echo "Button Press Registered Successfully!";
} else {
    echo "Error:" . $sql . "<br>" . $conn->error;
}
$conn->close();
?>