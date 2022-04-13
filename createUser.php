<?php
require 'mysql-connect.php';

// gets the new username and password from log in
$username = $_POST['newUsername'];
$password = $_POST['newPassword'];
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}

//hash password for security
$hashed_pw = password_hash($password, PASSWORD_DEFAULT);

// create user with this username and pass
$stmt = $mysqli->prepare("insert into userInfo (username, password) values (?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ss', $username, $hashed_pw);

$stmt->execute();

$stmt->close();

header("Location: news-index.html");

?>