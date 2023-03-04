<?php
$page_title = "Create Plan";
include("../includes/header.php");
?>
<div class="standard-box">
    <div>
        <!-- creates the titles -->
        <br><br>
        <div class="box"></div>
        <h1 class="standard-box-title">Create a Plan</h1>
        <div class="box"></div>
<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //gets the validation functions

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

    //checks that the message is not more than 1000 characters
    if (strlen($_POST["message"]) > 1000) {
        $errors[] = 'Your message is too long';
    }



    if (!isset($errors) || empty($errors) || $errors == null) {
        //assigns values to a variable
        $title = $_POST['title'];
        $message = $_POST['message'];

        //runs sql query
        $query = "INSERT INTO tbl_plans(user_id, title, message, date)
        VALUES ('" . $_SESSION['user_id'] . "','$title','$message', NOW())";
        $result = mysqli_query($dbc, $query);
        ?>

        <h2 class="standard-box-title">Plan Created</h2>
        <div class="box"></div><br>
<?php
    }
}
    ?>
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
        } ?>

        <!-- creates input boxes for each input-->
        <form name="form" action="create_plan.php" method="post">
            <label for="title" class="standard-box-text">Title</label><br>
            <input name="title" type="text"><br><br>

            <label for="message" class="standard-box-text">Message</label><br>
            <textarea name="message" id="text" cols="50" rows="20" oninput="countText()"
                style="resize:none;"></textarea><br>

            <!-- creates character count -->
            <label for="characters">Characters: </label>
            <span id="characters">0</span>
            <span>/ 1000</span><br><br>
            <input type="submit" class="submit-button">
        </form>
        <br>
        <div class="box"></div><br>
        <button class = "submit-button"><a href = "plans.php">Back to All Plans</a></button>
    </div>
</div>

</body>
<?php include("../includes/footer.html");?>