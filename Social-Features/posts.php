<?php
$page_title = "Posts";
include("../includes/header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //gets the and validation functions
    require("../includes/validation_functions.php");

    //creates array containing error messages
    $errors = [];

    //checks that each value has been entered and if not returns a message 
    if ($var = check_presence("title", $_POST["title"])) {
        $errors[] = $var;
    }
    if ($var = check_presence("message", $_POST["message"])) {
        $errors[] = $var;
    }

    //checks that the message is not more than 500 characters
    if (strlen($_POST["message"]) > 500) {
        $errors[] = 'Your message is too long';
    }



    if (!isset($errors) || empty($errors) || $errors == null) {
        //assigns values to a variable
        $title = $_POST['title'];
        $message = $_POST['message'];

        //runs sql query
        $query = "INSERT INTO tbl_social(user_id,title, message)
        VALUES (" . $_SESSION['user_id'] . ",'$title','$message')";
        $result = mysqli_query($dbc, $query);
    }
}
?>
<div class="standard-box">
    <div>
        <!-- creates the title -->
        <br><br>
        <div class="box"></div>
        <h1 class="standard-box-title">Posts</h1>
        <div class="box"></div>
        <h2 class="standard-box-title">Make a Post</h2>
        <br><br>
        <?php
        //outputs errors
        if (isset($errors) && !empty($errors && $errors != null)) {
            echo '<h2 class = "error-text">The following errors occured</h2>';
            foreach ($errors as $message) {
                if ($message != "No Errors") {
                    echo "<p class = 'error-text'>" . $message . "</p>";
                }
            }
        }
        //confirmation message of post
        else if ($_SERVER['REQUEST_METHOD'] == "POST") {
            echo '<h2 class = "standard-box-title">Post Successfully Made</h2>';
        }

        if (isset($_SESSION['user_id'])) { ?>

            <!-- creates input boxes for each input-->
            <form name="form" action="posts.php" method="post">
                <label for="title" class="standard-box-text">Title</label><br>
                <input name="title" type="text"><br><br>

                <label for="message" class="standard-box-text">Message</label><br>
                <textarea name="message" id="text" cols="50" rows="4" oninput="countText()"
                    style="resize:none;"></textarea><br>

                <!-- creates character count -->
                <label for="characters">Characters: </label>
                <span id="characters">0</span>
                <span>/ 500</span><br><br>
                <input type="submit" class="submit-button"><br><br>
            </form>
            <?php
        } else {
            echo '<h2 class="standard-box-title">You must be logged in to make a post</h2>';
        }
        //gets all posts from the database
        $query = "SELECT * FROM tbl_social
        ORDER BY post_id DESC";
        $reviews = mysqli_query($dbc, $query);
        echo '<div class="box"></div>
        <h2 class = "standard-box-title">All Posts</h2>';

        //gets the name of the user that made each post and outputs the posts
        while ($reviews_array = mysqli_fetch_array($reviews, MYSQLI_ASSOC)) {
            $query = "SELECT * FROM tbl_users
        WHERE user_id = '" . $reviews_array['user_id'] . "'";
            $user_details = mysqli_query($dbc, $query);
            while ($user_details_array = mysqli_fetch_array($user_details, MYSQLI_ASSOC)) {
                echo '<div class="box"></div><br>
                <p class = "standard-box-text"><strong>' . $user_details_array['first_name'] . " " . $user_details_array['last_name'] .
                '<br>' . $reviews_array['title'] . '</strong><br>' .
                $reviews_array['message'] . '</p><br>';

                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1){
                    echo "<h3 class = 'standard-box-title'><a href='delete_post.php?id=" . $reviews_array['post_id'] .
                    "'>Delete Post</a></h3>";
                }
            }
        }

        echo "</div></div>";
        include("../includes/footer.html"); ?>