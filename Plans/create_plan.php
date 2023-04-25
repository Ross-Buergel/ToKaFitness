<?php
$page_title = "Create Plan";
include("../includes/header.php");
?>

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

    //checks that the variables are not longer than the database column allows
    if ($var = check_length("title", $_POST["title"], 2, 30)) {
        $errors[] = $var;
    }
    if ($var = check_length("message", $_POST["message"], 25, 1000)) {
        $errors[] = $var;
    }


    //checks if any errors occured
    if (!isset($errors) || empty($errors) || $errors == null) {
        //assigns values to a variable
        $title = $_POST['title'];
        $message = $_POST['message'];

        //runs sql query
        $query = "INSERT INTO tbl_plans(user_id, title, message, date)
        VALUES ('" . $_SESSION['user_id'] . "','$title','$message', NOW())";
        $result = mysqli_query($dbc, $query);
?>

        <h2>Plan Created</h2>
        <br>
<?php
    }
}
?>
<div class="main-content">
    <!-- creates the titles -->
    <br><br>

    <h1>Create a Plan</h1>
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
        <div class="form-inner">
            <label for="title">Title</label><br>
            <input name="title" type="text"><br><br>

            <label for="message">Message</label><br>

            <textarea name="message" id="text" cols="50" rows="20" oninput="countText()" style="resize:none;"></textarea><br>

            <!-- creates character count -->
            <label for="characters">Characters: </label>
            <span id="characters">0</span>
            <span>/ 1000</span><br><br>
            <input type="submit" class="submit-button">
        </div>
    </form>
    <br>
    <br>
    <a href="plans.php" class="button">Back to All Plans</a>
</div>
</div>
<?php include("../includes/footer.html"); ?>