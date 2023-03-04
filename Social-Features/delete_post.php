<?php
$page_title = "Delete Post";
include("../includes/header.php");
//checks if the user is logged into the staff account
if ($_SESSION['user_id'] == "1") {
    //checks if the user submitted a post request
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id = $_POST['id'];

        //deletes post
        $query = "DELETE FROM tbl_social
        WHERE post_id = '" . $id . "'";
        $result = mysqli_query($dbc, $query);

        //redirects user to posts page
        header("Location: posts.php");
    }
    //uses get method to get the id of the post so it can be submitted when the user confirms delete
    else{
        $id = $_GET['id'];
    }
} else {
    //redirects any user that is not staff to the posts page
    header("Location: posts.php");
}
?>
<div class="standard-box">
    <div><br><br>
        <!--creates a title at the top of the page-->
        <div class="box"></div>
        <h1 class="standard-box-title">Delete Post</h1>
        <div class="box"></div>
        <h2 class="standard-box-title">Are you sure you want to delete this post?</h2>
        <?php
        $query = "SELECT * FROM tbl_social
        WHERE post_id = '" . $id . "'";
        $result = mysqli_query($dbc, $query);

        //gets the name of the user that made each post and outputs the posts
        while ($reviews_array = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $query = "SELECT * FROM tbl_users
        WHERE user_id = '" . $reviews_array['user_id'] . "'";
            $user_details = mysqli_query($dbc, $query);
            while ($user_details_array = mysqli_fetch_array($user_details, MYSQLI_ASSOC)) {
                echo '
                <p class = "standard-box-text"><strong>' . $user_details_array['first_name'] . " " . $user_details_array['last_name'] .
                    '<br>' . $reviews_array['title'] . '</strong><br>' .
                    $reviews_array['message'] . '</p><br>';
            }
        }
        ?>
        <!--creates the boxes needed to input a code-->
        <form action="delete_post.php" method="POST">
            <label for="confirm" class="standard-box-text"></label><br>
            <input type="submit" class="submit-button" name="confirm" value= "Confirm"><br><br>

            <input type = "hidden" value = "<?php echo $id?>" name = "id">
        </form>
    </div>
</div>
<?php include("../includes/footer.html");?>