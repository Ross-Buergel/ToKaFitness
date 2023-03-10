<?php
$page_title = "Registration";
include("../includes/header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //gets the validation functions
    require("../includes/validation_functions.php");

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

    //checks the length of each variable to ensure it is not cut off when inserted into the database
    if ($var = check_length("first name", $_POST["first_name"], 0, 25)) {
        $errors[] = $var;
    }

    if ($var = check_length("last name", $_POST["last_name"], 0, 25)) {
        $errors[] = $var;
    }

    if ($var = check_length("email", $_POST["email"], 5, 40)) {
        $errors[] = $var;
    }

    if ($var = check_length("password", $_POST["password"], 4, 255)) {
        $errors[] = $var;
    }

    //checks that inputs that shouldnt contain an integer dont
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
        $errors[] = "Email is already registered";
    }

    //checks that both the passwords entered match
    if ($_POST['password'] != $_POST['confirm_password']) {
        $errors[] = 'Please ensure your passwords match';
    }

    if (!isset($errors) || empty($errors) || $errors == null) {
        //assigns each value to a variable
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        //runs sql query
        $query = "INSERT INTO tbl_users(first_name, last_name, email, password, date)
        VALUES ('$first_name','$last_name','$email',SHA2('$password', 256),NOW())";
        $result = mysqli_query($dbc, $query);

        //redirects user to the login page
        header('Location: login.php');
    }

}
?>
<div class="standard-box">
    <div>
        <!-- creates the title -->
        <br><br>
        <div class="box"></div>
        <h1>Register</h1>
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


        <!-- creates input boxes for each input-->
        <form action="register.php" method="post">
            <label for="first_name">First Name</label><br>
            <input name="first_name" type="text"><br><br>

            <label for="last_name">Last Name</label><br>
            <input name="last_name" type="text"><br><br>

            <label for="email">Email</label><br>
            <input name="email" type="text"><br><br>

            <label for="password">Password</label><br>
            <input name="password" type="password"><br><br>

            <label for="confirm_password">Confirm Password</label><br>
            <input name="confirm_password" type="password"><br><br>

            <input type="submit" class="submit-button">
        </form>
        <br>
        <div class="box"></div>
    </div>
</div>
</body>
<?php include("../includes/footer.html"); ?>