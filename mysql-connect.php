<?php
// Content of database.php

//mysql username: php_user
//password: php_pass
//database: news
//tables: userInfo (username, password)
//stories (id, title, body, link, user_uploaded)
//comments (id, comment, article, user_commented)

$mysqli = new mysqli('localhost', 'php_user', 'php_pass', 'news');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}

?>