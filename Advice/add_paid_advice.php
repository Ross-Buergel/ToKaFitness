<?php
$page_title = "Add Paid Advice";
include("../includes/header.php");

//redirects the user to the login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

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
    if ($var = check_presence("price", $_POST["price"])) {
        $errors[] = $var;
    }

    //checks the length of the variables to ensure they are not cut off by the database
    if ($var = check_length("title", $_POST["title"], 2, 30)) {
        $errors[] = $var;
    }
    if ($var = check_length("message", $_POST["message"], 25, 500)) {
        $errors[] = $var;
    }

    $price = $_POST['price'];

    //checks the price is greater than 0
    if ($_POST['price'] == "0.00" || $_POST['price'] = "0") {
        $errors[] = 'Price cannot be 0';
    }
    //checks if the value is a float and if not returns an error message
    elseif (!filter_var($price, FILTER_VALIDATE_FLOAT)) {
        $errors[] = "Price must be in the format '0.00'";
    }




    if (!isset($errors) || empty($errors) || $errors == null) {
        //assigns values to a variable
        $title = $_POST['title'];
        $message = $_POST['message'];
        $price = $_POST['price'];

        //runs sql query
        $query = "INSERT INTO tbl_advice_paid(title, message, price)
        VALUES ('$title','$message','$price')";
        $result = mysqli_query($dbc, $query);
    }
}
?>
<div class="standard-box">
    <div>
        <!-- creates the title -->
        <br><br>
        <div class="box"></div>
        <h1>Add Paid Advice</h1>
        <div class="box"></div>
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
        <form name="form" action="add_paid_advice.php" method="POST">
            <!-- creates input boxes-->
            <label for="title">Title</label><br>
            <input name="title" type="text"><br><br>

            <label for="message">Message</label><br>
            <textarea name="message" id="text" cols="50" rows="4" oninput="countText()"
                style="resize:none;"></textarea><br>

            <!-- creates character count -->
            <label for="characters">Characters: </label>
            <span id="characters">0</span>
            <span>/ 500</span><br><br>

            <!-- creates input box for price -->
            <label for="price">Price</label><br>
            <input name="price" type="text"><br><br>

            <input type="submit" class="submit-button">
        </form>
    </div>
</div>
<?php include("../includes/footer.html"); ?>