<?php
$hostname = "http://localhost/news-blog";
$HOST="127.0.0.1"; //localhost
$USERNAME="root";
$PASSWORD="";
$DB_NAME="news-blog";


$conn = new mysqli($HOST, $USERNAME, $PASSWORD, $DB_NAME);
if($conn->connect_error){
    die($conn->connect_error);
} else {
    // echo "Database Connected";
}
// echo "HEllo"


?>
