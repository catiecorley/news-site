<?php
require 'mysql-connect.php';
session_start();
$current_user = $_SESSION['user'];

$article_name= $_GET['name']; // gets name from href

//check creator of story to make sure deleting is allowed
$stmt = $mysqli->prepare("select user_uploaded, image from stories where title like ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('s', $article_name);

$stmt->execute();
$stmt->bind_result($true_user_upload, $filename);

while($stmt->fetch()){
	htmlspecialchars($true_user_upload);
}

$stmt->close();

//if user created the story, delete it from the table
if($current_user == $true_user_upload || $current_user == 'admin'){

$stmt = $mysqli->prepare("delete from stories where title like ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('s', $article_name);

$stmt->execute();

$stmt->close();

$stmt = $mysqli->prepare("delete from comments where article like ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('s', $article_name);

$stmt->execute();

$stmt->close();


//delete the file from aws server
$full_path = '/srv/uploads/' . $filename;
unlink($full_path);

header("Location: loggedInNews.php"); 
echo "current user: " . $current_user;
echo " uploaded by: " . $true_user_upload;



} else{
	echo "You do not have permission to delete " . $true_user_upload . "'s story.";
}



?>