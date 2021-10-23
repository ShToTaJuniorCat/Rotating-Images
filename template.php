<?php
function sqlConnect() {
        $servername = "Localhost";
        $username = DATABSE_USERNAME;
        $password = DATABASE_PASS;
        $dbname = DATABASE_NAME;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
$conn = sqlConnect();

$stmt = $conn->prepare("SELECT images FROM user_imgs WHERE url_path=?");
$stmt->bind_param("s", $path);
$path = basename(dirname(__FILE__));
$stmt->execute();
$stmt -> store_result();
$stmt -> bind_result($images);
$stmt -> fetch();

$images = explode(", ", $images);
$rand_key = array_rand($images, 1);
header("Location: " . $images[$rand_key]);
?>