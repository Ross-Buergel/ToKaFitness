<?php
$page_title = "Login";
include("../includes/header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //gets the validation functions
    
    require("../includes/validation_functions.php");

    //creates array containing error messages
    $errors = [];

    //checks that each value has been entered and if not returns a message
    if ($var = check_presence("email", $_POST["email"])) {
        $errors[] = $var;
    }

    if ($var = check_presence("password", $_POST["password"])) {
        $errors[] = $var;
    }

    if (!isset($errors) || empty($errors) || $errors == null) {
        //assigns values to a variable
        $email = $_POST["email"];
        $password = $_POST["password"];

        //runs sql query
        $query = "SELECT user_id, first_name, last_name FROM tbl_users
        WHERE email = '$email'
        AND password = SHA2('$password',256)";
        $result = mysqli_query($dbc, $query);

        //checks if a result was returned
        if (mysqli_num_rows($result) == 1) {
            //starts a session
            session_start();
            
            //assigns values to session
            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['first_name'] = $data['first_name'];
            $_SESSION['last_name'] = $data['last_name'];

            //redirects user to the home page
            header("Location: ../index.php");
        }
        else
        {
            $contains_error = True;
            $errors[] = "No account found with the provided details";
        }
    }

}
?>
<div class="standard-box">
    <div>
        <!-- creates the title -->
        <br><br>
        <div class="box"></div>
        <h1 class="standard-box-title">Login</h1>
        <div class="box"></div>
        <br><br>
        <?php
        //outputs errors
        if (isset($errors) && !empty($errors && $errors != null)) {
            echo '<h2 class = "error-text">The following errors occured</h2>';
            foreach ($errors as $message) {
                if ($message != "No Errors") {
                    echo "<p class = 'error-text'>".$message."</p>";
                }
            }
        }?>

        <!-- creates input boxes for each input-->
        <form action="login.php" method="post">
            <label for="email" class="standard-box-text">Email</label><br>
            <input name="email" type="text"><br><br>

            <label for="password" class="standard-box-text">Password</label><br>
            <input name="password" type="password"><br><br>

            <input type = "submit" class = "submit-button">
        </form>
        <br>
        <div class = "box"></div>
    </div>
</div>
</body>
<?php include("../includes/footer.html"); ?>