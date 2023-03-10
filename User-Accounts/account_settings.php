<?php
$page_title = "Account Settings";
include("../includes/header.php");

//redirects the user to the login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

if ($_SERVER['REQUEST_METHOD'] = "POST") {
    //gets the validation functions

    require("../includes/validation_functions.php");

    //creates array containing error messages
    $errors = [];

    //checks that each value has been entered and if not returns a message 
    if (isset($_POST['code'])) {
        if ($var = check_presence("code", $_POST["code"])) {
            $errors[] = $var;
        }

        //assigns the entered code to a variable and checks if it is in the database
        $code = $_POST['code'];
        $query = "SELECT * FROM tbl_codes WHERE code = '" . $code . "'";
        $result = mysqli_query($dbc, $query);

        if (mysqli_num_rows($result) != 1) {
            $errors[] = "Invalid Code";
        }

        if (!isset($errors) || empty($errors) || $errors == null) {
            $query = "SELECT * FROM tbl_codes WHERE code = '" . $code . "'";
            $result1 = mysqli_query($dbc, $query);

            //outputs success message
            $code_success_message = '
                <h2>Code Accepted!</h2>
                <h3>You have received the following benefits:</h3>
                <p>';
            while ($row = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {
                //checks what the code is and gives user access to all paid content in the event of an 'All' or specific paid content
                if ($row['paid_content_id'] = "All") {
                    $query = "SELECT paid_advice_id FROM tbl_advice_paid";
                    $ids = mysqli_query($dbc, $query);
                    while ($paid_content_ids = mysqli_fetch_array($ids, MYSQLI_ASSOC)) {
                        $query = "INSERT INTO tbl_payment_confirmation(paid_content_id, user_id, date)
                            VALUES (" . $paid_content_ids["paid_advice_id"] . "," . $_SESSION['user_id'] . ",NOW())";
                    }
                    $code_success_message .= 'Access to all paid content';
                } else {
                    $query = "INSERT INTO tbl_payment_confirmation(paid_content_id, user_id, date)
                    VALUES (" . $row["paid_content_id"] . "," . $_SESSION['user_id'] . ",NOW())";
                    $result = mysqli_query($dbc, $query);

                    $query = "SELECT * FROM tbl_advice_paid
                        WHERE paid_content_id = '" . $row['paid_content_id'] . "'";
                    $content_names = mysqli_query($dbc, $query);

                    while ($content_names_array = mysqli_fetch_array($content_names, MYSQLI_ASSOC)) {
                        $code_success_message .= 'Access to paid content: ' . $content_names_array['title'];
                    }
                }
                //logs the redemption of the code
                $query = "INSERT INTO tbl_code_redemptions(user_id, code_id, date)
                    VALUES ('" . $_SESSION['user_id'] . "','" . $row['code_id'] . "',NOW())";
                $result = mysqli_query($dbc, $query);
            }
        }
    }

    //checks if the user entered a colour scheme and if so checks if they have done so in the past
    else if (isset($_POST['colour_type'])) {
        $query = "SELECT * FROM tbl_colour_scheme
        WHERE user_id = '" . $_SESSION['user_id'] . "'";
        $result = mysqli_query($dbc, $query);

        //inserts a new row into the database if the user has not entered a colour scheme in the past
        if (mysqli_num_rows($result) == 0) {
            $query = "INSERT INTO tbl_colour_scheme(user_id, colour_scheme)
            VALUES ('" . $_SESSION['user_id'] . "','" . $_POST['colour_type'] . "')";
            $result = mysqli_query($dbc, $query);
        }
        //updates row in table if user has entered colour scheme before
        else {
            $query = "UPDATE tbl_colour_scheme
            SET colour_scheme = '" . $_POST['colour_type'] . "'
            WHERE user_id = '" . $_SESSION['user_id'] . "'";
            $result = mysqli_query($dbc, $query);
        }
        //runs the javascript function that changes the colour of the elements
        echo '<script>changeColour("' . $_POST['colour_type'] . '")</script>';

        header("Location: ../index.php");
    } else if (isset($_POST['first_name'])) {
        //creates array containing error messages
        $errors = [];

        //checks that each value has been entered and if not returns a message
        if ($var = check_presence("first name", $_POST["first_name"])) {
            $errors[] = $var;
        }
    
        if ($var = check_presence("last name", $_POST["last_name"])) {
            $errors[] = $var;
        }
    
        if ($var = check_presence("email", $_POST["email"])) {
            $errors[] = $var;
        }
    
        if ($var = check_presence("password", $_POST["password"])) {
            $errors[] = $var;
        }
    
        if ($var = check_presence("password confirmation", $_POST["confirm_password"])) {
            $errors[] = $var;
        }
    
        //checks that inputs that shouldnt contain an integer don't
        if ($var = check_contains_integer("First name", $_POST["first_name"])) {
            $errors[] = $var;
        }
    
        if ($var = check_contains_integer("Last name", $_POST["last_name"])) {
            $errors[] = $var;
        }

        //checks if the email is already in the database
        $query = "SELECT * FROM tbl_users
        WHERE email = '" . $_POST['email'] . "'";
        $result = mysqli_query($dbc, $query);

        //checks that the email contains an @ and ending .
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email';
        } else if (mysqli_num_rows($result) == 1) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                if ($row['user_id'] != $_SESSION['user_id'])
                    $errors[] = "Email is already registered";
            }
        }


        //checks that both the passwords entered match
        if ($_POST['password'] != $_POST['confirm_password']) {
            $errors[] = 'Please ensure your passwords match';
        }

        //checks there were no errors
        $contains_error = False;
        foreach ($errors as $message) {
            if ($message != "No Errors") {
                $contains_error = True;
            }
        }

        if (!isset($errors) || empty($errors) || $errors == null) {
            //assigns each value to a variable
            $first_name = $_POST["first_name"];
            $last_name = $_POST["last_name"];
            $email = $_POST["email"];
            $password = $_POST["password"];

            //runs sql query
            $query = "UPDATE tbl_users
            SET first_name = '$first_name', 
            last_name = '$last_name', 
            email = '$email', 
            password = SHA2('$password', 256)
             WHERE user_id = '".$_SESSION['user_id']."'";
            $result = mysqli_query($dbc, $query);
        }
    } else if (isset($_POST['new_password'])) {
        //checks the user entered the required information
        if ($var = check_presence("old password", $_POST["old_password"])) {
            $errors[] = $var;
        }
        if ($var = check_presence("new password", $_POST["new_password"])) {
            $errors[] = $var;
        }
        if ($var = check_presence("new password confirmation", $_POST["confirm_new_password"])) {
            $errors[] = $var;
        }
        //checks the inputted password is the users old password
        $query = "SELECT * FROM tbl_users
        WHERE user_id = '" . $_SESSION['user_id'] . "'
        AND password = SHA2('" . $_POST['old_password'] . "',256)";
        $result = mysqli_query($dbc, $query);

        if (mysqli_num_rows($result) != 1) {
            $errors[] = "Old password does not match account password";
        }

        //checks the new password and confirmation match
        if ($_POST['new_password'] != $_POST['confirm_new_password']) {
            $errors[] = "New password and new password confirmation must match";
        }

        //checks there were no errors
        $contains_error = False;
        foreach ($errors as $message) {
            if ($message != "No Errors") {
                $contains_error = True;
            }
        }

        if (isset($contains_error) and $contains_error != True) {
            //updates the users password
            $query = "UPDATE tbl_users
            SET password = SHA2('" . $_POST['new_password'] . "',256)
            WHERE user_id = '" . $_SESSION['user_id'] . "'";
            $result = mysqli_query($dbc, $query);
        }
    } else if (isset($_POST['delete_account'])) {
        //checks the user has confirmed the delete
        if (isset($_POST['confirm_delete'])) {
            $query = "DELETE FROM tbl_users
            WHERE user_id = '" . $_SESSION['user_id'] . "'";
            $result = mysqli_query($dbc, $query);

            //logs user out if they are logged in
            $_SESSION = array();
            session_destroy();
        }
    }
}
?>
<div class="standard-box">
    <div><br><br>
        <!--creates a title at the top of the page-->
        <div class="box"></div>
        <h1>Account Settings</h1>
        <div class="box"></div>

        <h2>Redeem a Code</h2>
        <?php
        //outputs errors that occur if user inputs a code
        if (isset($contains_error) && $contains_error == True && isset($_POST['code'])) {
            echo '<h2 class = "error-text">The following errors occured</h2>';
            foreach ($errors as $message) {
                if ($message != "No Errors") {
                    echo "<p class = 'error-text'>" . $message . "</p>";
                }
            }
        } else if (isset($code_success_message)) {
            echo $code_success_message . "<br><br>";
        } ?>
        <!--creates the boxes needed to input a code-->
        <form action="account_settings.php" method="POST">
            <label for="code">Code</label><br>
            <input name="code" type="text"><br><br>

            <input type="submit" class="submit-button"><br>
        </form>

        <!--creates a heading for the accessibility section of the page-->
        <br><br>
        <div class="box"></div>
        <h2>Accessibility</h2>

        <!--creates the form needed to submit colour scheme requirements-->
        <form action="account_settings.php" method="POST">
            <label for="colour_type">Colour Blindness Type</label><br>
            <select name="colour_type">
                <option value="N/A">N/A</option>
                <option value="Red-Green">Red-Green</option>
                <option value="Blue-Yellow">Blue-Yellow</option>
            </select><br><br>

            <input type="submit" class="submit-button"><br><br>
        </form>

        <!--creates the paragraph explaining how to increase font size-->
        <p>
            For increased font and button size please use the zoom tool built into your browser.
            It can be accessed by holding the ctrl key and using the scroll wheel on your mouse or
            by holding the ctrl key and + or - (next to the backspace key)
        </p><br>
        <div class="box"></div>
        <h2>Account Details</h2>
        <?php
        //outputs errors that occur if user inputs account details
        if (isset($contains_error) && $contains_error == True && isset($_POST['first_name'])) {
            echo '<h2 class = "error-text">The following errors occured</h2>';
            foreach ($errors as $message) {
                if ($message != "No Errors") {
                    echo "<p class = 'error-text'>" . $message . "</p>";
                }
            }
        } ?>
        <form action="account_settings.php" method="POST">
            <!-- autofills the users details -->
            <?php

            $query = "SELECT * FROM tbl_users
                        WHERE user_id = '" . $_SESSION['user_id'] . "'";

            $user_details = mysqli_query($dbc, $query);

            $row = mysqli_fetch_array($user_details, MYSQLI_ASSOC);

            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $email = $row['email'];

            
            
                ?>
                <!-- creates the required input boxes -->
                <label for="first_name">First Name</label><br>
                <input name="first_name" type="text" value="<?php echo $first_name ?>"><br><br>

                <label for="last_name">Last Name</label><br>
                <input name="last_name" type="text" value="<?php echo $last_name ?>"><br><br>

                <label for="email">Email</label><br>
                <input name="email" type="text" value="<?php echo $email ?>"><br><br>

                <label for="password">Password</label><br>
                <input name="password" type="password"><br><br>

                <label for="confirm_password">Confirm Password</label><br>
                <input name="confirm_password" type="password"><br><br>

                <input type="submit" class="submit-button"><br><br>
        </form>
        <div class="box"></div>
        <h2>Change Password</h2>
        <?php
        //outputs errors that occur if user inputs account details
        if (isset($contains_error) && $contains_error == True && isset($_POST['new_password'])) {
            echo '<h2 class = "error-text">The following errors occured</h2>';
            foreach ($errors as $message) {
                if ($message != "No Errors") {
                    echo "<p class = 'error-text'>" . $message . "</p>";
                }
            }
        } ?>
        <form action="account_settings.php" method="POST">
            <!-- creates the requred input boxes -->
            <label for="old_password">Old Password</label><br>
            <input name="old_password" type="password"><br><br>

            <label for="new_password">New Password</label><br>
            <input name="new_password" type="password"><br><br>

            <label for="confirm_new_password">Confirm New Password</label><br>
            <input name="confirm_new_password" type="password"><br><br>

            <input type="submit" class="submit-button"><br><br>
        </form>

        <div class="box"></div>
        <h2>Delete Account</h2>
        <!-- creates the required input boxes-->
        <form action="account_settings.php" method="POST">
            <label for="confirm_delete">Confirm Delete</label><br>
            <input type="checkbox" name="confirm_delete" value="True"><br><br>

            <input type="submit" class="submit-button" value="Delete Account" name="delete_account"><br><br>
        </form>
    </div>
</div>
<?php
include("../includes/footer.html"); ?>