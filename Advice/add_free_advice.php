<?php
$page_title = "Add Free Advice";
include("../includes/header.php");

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

    //checks the length of the variables to ensure they are not cut off by the database
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
        $query = "INSERT INTO tbl_advice_free(title, message)
        VALUES ('$title','$message')";
        $result = mysqli_query($dbc, $query);
    }
}
?>
<div class="main-content">
    <!-- creates the title -->
    <br><br>
    <h1>Add Free Advice</h1>
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
    <form name="form" action="add_free_advice.php" method="POST">
        <div class="form-inner">
            <!-- creates input boxes -->
            <label for="title">Title</label><br>
            <input name="title" type="text"><br><br>

            <label for="message">Message</label><br>
            <textarea name="message" id="text" cols="50" rows="4" oninput="countText()" style="resize:none;"></textarea><br>

            <!-- creates character count -->
            <label for="characters">Characters: </label>
            <span id="characters">0</span>
            <span>/ 500</span><br><br>
            <input type="submit" class="submit-button">
        </div>
    </form>
</div>
<?php include("../includes/footer.html"); ?>