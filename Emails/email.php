<?php
$page_title = "Email";
include("../includes/header.php");
?>
<div class="standard-box">
    <div>
        <!-- creates the title -->
        <br><br>
        <div class="box"></div>
        <h1 class="standard-box-title">Contact a Fitness Professional</h1>
        <div class="box"></div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //gets the validation functions
        
            require("../includes/validation_functions.php");

            //creates array containing error messages
            $errors = [];

            //checks that each value has been entered and if not returns a message 
            if ($var = check_presence("email", $_POST["email"])) {
                $errors[] = $var;
            }
            if ($var = check_presence("title", $_POST["title"])) {
                $errors[] = $var;
            }
            if ($var = check_presence("message", $_POST["message"])) {
                $errors[] = $var;
            }

            //checks that the email contains an @ and ending .
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Enter a valid email';
            }

            //checks that the message is not more than 500 characters
            if (strlen($_POST["message"]) > 500) {
                $errors[] = 'Your message is too long';
            }

            //checks there are no errors in the array
            $contains_error = False;
            foreach ($errors as $message) {
                if ($message != "No Errors") {
                    $contains_error = True;
                }
            }

            if (!isset($errors) || empty($errors) || $errors == null) {
                //assigns values to a variable
                $email = $_POST['email'];
                $title = $_POST['title'];
                $message = $_POST['message'];

                //runs sql query
                $query = "INSERT INTO tbl_emails(email, title, message)
        VALUES ('$email','$title','$message')";
                $result = mysqli_query($dbc, $query);
                ?>
                <h2 class="standard-box-title">Message Sent</h2>
                <?php
            }
        }
        ?>
        <!-- creates the title -->
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
        <form name="form" action="email.php" method="post">
            <label for="email" class="standard-box-text">Email</label><br>
            <input name="email" type="text"><br><br>

            <label for="title" class="standard-box-text">Title</label><br>
            <input name="title" type="text"><br><br>

            <label for="message" class="standard-box-text">Message</label><br>
            <textarea name="message" id="text" cols="50" rows="4" oninput="countText()"
                style="resize:none;"></textarea><br>

            <!-- creates character count -->
            <label for="characters">Characters: </label>
            <span id="characters">0</span>
            <span>/ 500</span><br><br>
            <input type="submit" class="submit-button">
        </form>
        <br>
        <div class="box"></div>
    </div>
</div>

</body>
<?php include("../includes/footer.html"); ?>