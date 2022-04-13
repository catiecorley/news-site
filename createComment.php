<?php
require 'mysql-connect.php';
session_start();



//get comment from form
$comment = htmlspecialchars($_POST['comment']);
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}


$article = $_SESSION['current_article'];

$user_commented = $_SESSION['user'];

//insert comment into table

$stmt = $mysqli->prepare("insert into comments (comment, article, user_commented) values (?, ?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('sss', $comment, $article, $user_commented);

$stmt->execute();

$stmt->close();

header("Location: story.php?storyid=". $article); 


?>