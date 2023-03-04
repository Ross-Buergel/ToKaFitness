<?php
$page_title='Logout';

include("../includes/header.php");

//informs user they are not logged in if they are not
if(!isset($_SESSION['user_id']))
{
    echo'
    <div class = "standard-box">
    <div>
    <h1 class = "standard-box-title">You are not logged in</h1>
    </div>
    </div>';
}
else
{
    //logs user out if they are logged in
    $_SESSION=array();
    session_destroy();

    echo'
    <div class = "standard-box">
    <div>
    <h1 class = "standard-box-title">You are now logged out</h1>
    </div>
    </div>';

}

include("../includes/footer.html");
?>