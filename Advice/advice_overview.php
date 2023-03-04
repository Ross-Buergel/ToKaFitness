<?php
$page_title = "Advice";
include("../includes/header.php");
?>

<div class="standard-box">
    <div>
        <!-- creates the title -->
        <br><br>
        <div class="box"></div>
        <h1 class="standard-box-title">Advice</h1>
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

        <!-- creates box that allows users to search for an item-->
        <form action="advice_overview.php" method="post">
            <label for="search_item" class="standard-box-text">Search for Advice</label><br>
            <input name="search_item" type="text">
            <input type="submit" class="submit-button" value="Search">
        </form>
        <br><br>
        <?php
        //adds the buttons allowing users logged into the staff account to add advice
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1) {
            echo '<div class="box"></div><h2 class = "standard-box-text"><a href = "add_free_advice.php">Add Free Advice</a></h2>
            <h2 class = "standard-box-text"><a href = "add_paid_advice.php">Add Paid Advice</a></h2>';
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //gets the entered item
            $search_item = $_POST['search_item'];

            //filters database by entered item
            $query = "SELECT * FROM tbl_advice_free
        WHERE title LIKE '%" . $search_item . "%'";
            $result = mysqli_query($dbc, $query);
        } else {
            //if no item was inputted, outputs all advice available
            $query = "SELECT * FROM tbl_advice_free";
            $result = mysqli_query($dbc, $query);
        }

        //checks that values were returned from the database then outputs all free advice
        echo '<div class="box"></div><h2 class = "standard-box-title">Free Advice</h2><div class="box"></div>';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                echo "<h3 class = 'standard-box-title'><a href='free_advice_detailed.php?id=" . $row['free_advice_id'] .
                    "'>" . $row['title'] . "</a></h3>";
            }
        }
        //outputs a message if no advice was found
        else {
            echo '<h3 class = "standard-box-title">No advice found</h3>';
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //gets the entered item
            $search_item = $_POST['search_item'];

            //filters database by entered item
            $query = "SELECT * FROM tbl_advice_paid
        WHERE title LIKE '%" . $search_item . "%'";
            $result = mysqli_query($dbc, $query);
        } else {
            //if no item was inputted, outputs all advice available
            $query = "SELECT * FROM tbl_advice_paid";
            $result = mysqli_query($dbc, $query);
        }

        if (isset($_SESSION['user_id'])) {
            //checks that values were returned from the database then outputs all paid advice
            echo '<div class="box"></div><h2 class = "standard-box-title">Paid Advice</h2><div class="box"></div>';
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $query = "SELECT * FROM tbl_payment_confirmation
                 WHERE user_id = '" . $_SESSION['user_id'] . "' AND paid_content_id = '" . $row['paid_advice_id'] . "'";
                    $payment_confirmation = mysqli_query($dbc, $query);
                    echo "<h3 class = 'standard-box-title'><a href='paid_advice_detailed.php?id=" . $row['paid_advice_id'] .
                        "'>" . $row['title'] . "</a></h3>";
                    //outputs advice if user has payed for it
                    if (mysqli_num_rows($payment_confirmation) <= 0) {
                        echo "<h4 class = 'standard-box-title' style = 'margin-top: 0%'>£" . $row['price'] . "</h4>";
                    }
                }

            }

            //outputs a message if no advice was found
            else {
                echo '<h3 class = "standard-box-title">No advice found</h3>';
            }
        } else {
            echo '<div class="box"></div>
            <h2 class = "standard-box-title">You must be logged in to view payed advice</h2>
            <div class="box"></div>';
        }?>

    </div>
</div>
<?php include("../includes/footer.html"); ?>