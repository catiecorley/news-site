<?php
require 'mysql-connect.php';
session_start();

// identify by id
$title = $_POST['id'];
$new_body = htmlspecialchars($_POST['new_body']);   

if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}

$article = $_SESSION['current_article'];

$user_commented = $_SESSION['user'];


// will update with form input
$stmt = $mysqli->prepare("update stories set body = ? where title = ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ss', $new_body, $title);

$stmt->execute();

$stmt->close();


header("Location: story.php?storyid=". $article); 


?>