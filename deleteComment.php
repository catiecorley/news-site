<?php
require 'mysql-connect.php';
session_start();
//ids should match the unique id of the article it is commented on
$comment_delete = $_GET['id'];
$current_user = $_SESSION['user'];
$article = $_SESSION['current_article'];
$user_commented = 'placeholder'; 


//get user who commented to make sure deleting is allowed
$stmt = $mysqli->prepare("select user_commented from comments where comment like ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('s', $comment_delete);

$stmt->execute();
$stmt->bind_result($user_commented);
while($stmt->fetch()){
	htmlspecialchars($user_commented);
}

$stmt->close();


//delete comment if you are logged in as its author

if($current_user == $user_commented || $current_user == 'admin'){

$stmt = $mysqli->prepare("delete from comments where comment like ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('s', $comment_delete);

$stmt->execute();

$stmt->close();

header("Location: story.php?storyid=". $article); 
} else{
	echo "You do not have permission to delete this comment";
	echo "current: " . $current_user;
	echo "commented: " . $user_commented;
	echo "comment: " . $comment_delete;
	echo "article name: " .$articleselected;
}
?>