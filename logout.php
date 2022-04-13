<?php
session_start();
//destroy current user's session to log out of the current account
session_destroy();

header("Location: news-index.html");
exit;

?>