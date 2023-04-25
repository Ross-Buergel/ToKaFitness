<?php
$page_title = "Advice";
include("../includes/header.php");
?>

<div class="main-content">
    <!-- creates the title -->
    <br><br>
    <h1>Advice</h1>
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
        <div class="form-inner">
            <label for="search_item">Search for Advice</label><br>
            <input name="search_item" type="text">
            <input type="submit" class="submit-button" value="Search">
        </div>
    </form>
    <br><br>
    <?php
    //adds the buttons allowing users logged into the staff account to add advice
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1) :
    ?>

        <h2><a href="add_free_advice.php">Add Free Advice</a></h2>
        <h2><a href="add_paid_advice.php">Add Paid Advice</a></h2>
    <?php
    endif;

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
    echo '
        
        <h2>Free Advice</h2>
        ';
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo "<h3><a href='free_advice_detailed.php?id=" . $row['free_advice_id'] .
                "'>" . $row['title'] . "</a></h3>";
        }
    }
    //outputs a message if no advice was found
    else {
        echo '<h3>No advice found</h3>';
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
        echo '<h2>Paid Advice</h2>';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $query = "SELECT * FROM tbl_payment_confirmation
                 WHERE user_id = '" . $_SESSION['user_id'] . "' AND paid_content_id = '" . $row['paid_advice_id'] . "'";
                $payment_confirmation = mysqli_query($dbc, $query);

                echo "<h3><a href='paid_advice_detailed.php?id=" . $row['paid_advice_id'] .
                    "'>" . $row['title'] . "</a></h3>";
                //outputs advice if user has payed for it
                if (mysqli_num_rows($payment_confirmation) <= 0) {
                    echo "<h4 style = 'margin-top: 0%'>Price - £" . $row['price'] . "</h4>";
                }
            }
        }

        //outputs a message if no advice was found
        else {
            echo '<h3>No advice found</h3>';
        }
    } else {
        echo '
            <h2>You must be logged in to view payed advice</h2>
            ';
    } ?>

</div>
<?php include("../includes/footer.html"); ?>