<?php
session_start();
$filename = $_GET['file'];

if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
    echo "Invalid filename";
    exit;
}
$full_path = sprintf("/srv/uploads/%s", $filename);

// Now we need to get the MIME type (e.g., image/jpeg).  PHP provides a neat little interface to do this called finfo.
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($full_path);

// Finally, set the Content-Type header to the MIME type of the file, and display the file.
header("Content-Type: ".$mime);
header('content-disposition: attachment; filename="'.$filename.'";');
readfile($full_path);

header("Location: story.php?storyid=". $_SESSION['current_article']); 

?>