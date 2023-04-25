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

    //checks that the variables are not longer than the database allows
    if ($var = check_length("title", $_POST["title"], 2, 30)) {
        $errors[] = $var;
    }
    if ($var = check_length("message", $_POST["message"], 25, 500)) {
        $errors[] = $var;
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
<div class="main-content">
    <!-- creates the title -->
    <br><br>

    <h1>Posts</h1>

    <h2>Make a Post</h2>
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
        echo '<h2>Post Successfully Made</h2>';
    }

    if (isset($_SESSION['user_id'])) { ?>

        <!-- creates input boxes for each input-->
        <form name="form" action="posts.php" method="post">
            <div class="form-inner">
                <label for="title">Title</label><br>
                <input name="title" type="text"><br><br>

                <label for="message">Message</label><br>
                <textarea name="message" id="text" cols="50" rows="4" oninput="countText()" style="resize:none;"></textarea><br>

                <!-- creates character count -->
                <label for="characters">Characters: </label>
                <span id="characters">0</span>
                <span>/ 500</span><br><br>
                <input type="submit" class="submit-button"><br><br>
            </div>
        </form>
        <?php
    } else {
        echo '<h2>You must be logged in to make a post</h2>';
    }
    //gets all posts from the database
    $query = "SELECT * FROM tbl_social
        ORDER BY post_id DESC";
    $reviews = mysqli_query($dbc, $query);
    echo '
        <h2>All Posts</h2>';

    //gets the name of the user that made each post and outputs the posts
    while ($reviews_array = mysqli_fetch_array($reviews, MYSQLI_ASSOC)) :
        $query = "SELECT * FROM tbl_users
            WHERE user_id = '" . $reviews_array['user_id'] . "'";
        $user_details = mysqli_query($dbc, $query);

        while ($user_details_array = mysqli_fetch_array($user_details, MYSQLI_ASSOC)) :
        ?>
            <br>
            <p>
                <strong>
                    <?php echo $user_details_array['first_name'] ?> <?php echo $user_details_array['last_name'] ?>
                    <br><?php echo $reviews_array['title'] ?>
                </strong><br>
                <?php echo $reviews_array['message'] ?>
            </p><br>

            <?php
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1) :
            ?>
                <h3>
                    <a class="button" href='delete_post.php?id=<?php echo $reviews_array['post_id'] ?>'>Delete Post</a>
                </h3>
    <?php
            endif;
        endwhile;
    endwhile;
    ?>
</div>

<?php include("../includes/footer.html"); ?>