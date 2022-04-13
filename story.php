<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>viewing story</title>
       
    </head>
    
    
    <body>
<?php
require 'mysql-connect.php';
session_start();
$article_name = $_GET['storyid'];

$_SESSION['current_article'] = $article_name;


# retrieves and displays the body of the story
$stmt = $mysqli->prepare("select title, link, body, user_uploaded, image from stories where title like ?" );
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('s', $article_name);

$stmt->execute();

$stmt->bind_result($story_title, $story_link, $story_body, $user_uploaded, $filename);

echo "<ul>\n";
while($stmt->fetch()){
	printf("%s %s %s %s\n",
    
//gives options to click link and displays story's info
		'Story title: "'.htmlspecialchars($story_title).'" <br> ',
        '<a href="' . htmlspecialchars($story_link) . '"> visit link </a> <br>',
        'About story: '. htmlspecialchars($story_body).'<br>',
		'Uploaded by: '. htmlspecialchars($user_uploaded),
        htmlspecialchars($filename)

	);

    
}

echo "</ul>\n";

$stmt->close();

if($filename != null){
echo '<a href="downloadSource.php?file=' . $filename . '" > Download uploaded source! </a>';
}

//gathers all of the article's comments
$stmt2 = $mysqli->prepare("select comment, user_commented from comments where article like ?");
if(!$stmt2){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt2->bind_param('s', $article_name);

$stmt2->execute();

$stmt2->bind_result($comment, $user_commented);

echo "<ul>\n";
while($stmt2->fetch()){
	printf("\t<li>%s %s</li>\n",
		htmlspecialchars($user_commented).' commented: ',
		htmlspecialchars($comment)

	);
    # delete comment link if proper user
    if (($user_commented == $_SESSION['user'] || $_SESSION['user'] == 'admin') && $_SESSION['user'] != 'guest'){
        echo '<a href="' . "deleteComment.php?id=" .$comment. '"> DELETE COMMENT </a>';
        
        echo '<form name="input" action="editComment.php" method="POST"> 
        <input type="text" name="new_comment" placeholder="Edited comment"/>
        <input type="hidden" name="id" value = "' . $comment . '"/>
        <input type="hidden" name="token" value="'.$_SESSION["token"].'"/>
        <input type="submit" value="edit comment"/>
        </form>';

    }
}
echo "</ul>\n";



$stmt2->close();
//delete story if proper user
if (($user_uploaded == $_SESSION['user'] || $_SESSION['user'] == 'admin') && $_SESSION['user'] != 'guest'){

echo "<a href='deleteStory.php?name=" . $article_name . "'> Delete story </a>";
echo '<br>';
echo '<br>';
echo '<form name="input" action="editBody.php" method="POST"> 
        <input type="text" name="new_body" placeholder="Edited about article"/>
        <input type="hidden" name="id" value = "' . $story_title . '"/>
        <input type="hidden" name="token" value="'.$_SESSION["token"].'" />        
        <input type="submit" value="edit body"/>
        </form>';

echo '<form enctype="multipart/form-data" action="picture_uploader.php" method="POST">
<p>
    <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
    <label for="uploadfile_input">Choose a file to upload:</label> <input name="uploadedfile" type="file" id="uploadfile_input" />
</p>
<p>
    <input type="submit" value="Upload File or Image" />
</p>
</form>';   

}
if ($user_uploaded == $_SESSION['user'] || $_SESSION['user'] == 'admin'){
    '<a href="' . "deleteStory.php?name=" .$article_name . '"> DELETE STORY </a>';
    echo '<br>';
}

//create new comment if not the guest user
if($_SESSION['user']!='guest'){
echo '<form name="input" action="createComment.php" method="POST"> 
         <input type="text" name="comment" placeholder="new comment"/>
         <input type="hidden" name="token" value="'.$_SESSION["token"].'" />
        <input type="submit" value="share comment"/>
</form>';

}
echo "<br>";


echo '<a href="loggedInNews.php"> back to all stories </a>';

?>

</body>
</html>