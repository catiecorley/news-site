<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Logged In</title>
        <style>
            input{
                width:250px;
            }
            h1{
                font-size: 25px;
                font-family:  "Lucida Console", "Courier New", monospace;
            }
</style>
    </head>
    
    
    <body>
    <?php
    require 'mysql-connect.php';
    session_start();
    $username = $_SESSION['user'];

    //only allow uploading to regisered users
    if($username != 'guest'){
      echo ' <form name="input" action="createStory.php" method="POST">
            <input type="text" name="title" placeholder="Title">
            <input type="text" name="body" placeholder="About article">
			<input type="text" name="link" placeholder="link (include https://www.)">
            <input type="hidden" name="token" value="'. $_SESSION['token'].'" />
            <input type="submit" value="Upload">
        </form>';
    }
			



echo "Currently logged in as: ".$username;
echo "<br>";
echo '<h1> Uploaded News Articles </h1>';

//output of all the currently uploaded articles
$stmt = $mysqli->prepare("select title, body, link from stories");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->execute();

$stmt->bind_result($story_title, $story_body, $story_link);

echo "<ul>\n";
//give options to view story or to go to link
while($stmt->fetch()){
	printf("\t<li>%s %s %s</li>\n",
		'<a href="story.php?storyid=' . htmlspecialchars($story_title) . '"> '. htmlspecialchars($story_title). '</a>',
		htmlspecialchars($story_body),
		'<a href="' . htmlspecialchars($story_link) . '"> visit link </a>'
	);
}
echo "</ul>\n";


$stmt->close();

?>

<a href='logout.php'> Log Out </a>

</body>
</html>

