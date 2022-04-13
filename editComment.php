<?php
require 'mysql-connect.php';
session_start();

$comment = $_POST['id'];
$new_comment = htmlspecialchars($_POST['new_comment']);    

if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}

$article = $_SESSION['current_article'];

$user_commented = $_SESSION['user'];

//EDIT COMMENT IN THE TABLE BASED ON FORM INPUT

$stmt = $mysqli->prepare("update comments set comment = ? where comment = ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ss', $new_comment, $comment);

$stmt->execute();

$stmt->close();


header("Location: story.php?storyid=". $article); 


?>