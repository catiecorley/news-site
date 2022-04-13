<?php
session_start();
require 'mysql-connect.php';

//uploads the photo from someone's computer


// Get the filename and make sure it is valid
$filename = basename($_FILES['uploadedfile']['name']);
if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
	echo "Invalid filename";
	exit;
}



$full_path = sprintf("/srv/uploads/%s", $filename);



if( move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path) ){

$article = $_SESSION['current_article'];

$user_commented = $_SESSION['user'];


$stmt = $mysqli->prepare("update stories set image = ? where title like ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ss', $filename, $article);

$stmt->execute();

    header("Location: story.php?storyid=". $_SESSION['current_article']); 
	exit;
}else{
	header("Location: failure.txt");
	exit;
}

//sources: https://classes.engineering.wustl.edu/cse330/index.php?title=PHP



$stmt->close();

 



?>