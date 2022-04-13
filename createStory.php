<?php
require 'mysql-connect.php';
session_start();
//get story info from form
$title = $_POST['title'];
$body = $_POST['body'];
$link = $_POST['link'];
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
//update table with the new story
$user_uploaded = $_SESSION['user'];
$stmt = $mysqli->prepare("insert into stories (title, body, link, user_uploaded) values (?, ?, ?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ssss', $title, $body, $link, $user_uploaded);

$stmt->execute();

$stmt->close();

header("Location: loggedInNews.php"); #redirect to page showing all the articles 

?>