<?php
$page_title='Logout';

session_start();

//checks whether the user is logged in
if(isset($_SESSION['user_id']))
{
    //logs user out if they are logged in
    $_SESSION=array();
    session_destroy();
}
header("Location: ../index.php");
?>