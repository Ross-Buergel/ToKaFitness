<?php
$page_title = "Advice";
include("../includes/header.php");

//redirects the user to the login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}
?>

<div class="main-content">
    <!-- creates the title -->
    <br><br>

    <h1>Plans</h1>

    <br><br>

    <!-- creates box that allows users to search for an item-->
    <form action="advice_overview.php" method="post">
        <div class="form-inner">
            <label for="search_item">Search for Plan</label><br>
            <input name="search_item" type="text">
            <input type="submit" class="submit-button" value="Search">
        </div>
    </form>
    <br><br>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //gets the entered item
        $search_item = $_POST['search_item'];

        //filters database by entered item
        $query = "SELECT * FROM tbl_plans
        WHERE title LIKE '%" . $search_item . "%'
        AND user_id = '" . $_SESSION['user_id'] . "'";
    } else {
        //if no item was inputted, outputs all plans made by the user
        $query = "SELECT * FROM tbl_plans
            WHERE user_id = '" . $_SESSION['user_id'] . "'";
    }
    $result = mysqli_query($dbc, $query);

    echo '<br>
        <h2>
        <a href = "create_plan.php" class = "button">
        Create Plan
        </a>
        </h2><br>';


    //checks that values were returned from the database then outputs all plans
    echo '<h2>Your Plans</h2>';
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo "<h3><a href='plans_detailed.php?id=" . $row['plan_id'] .
                "'>" . $row['title'] . "</a></h3>";
        }
    }
    //outputs a message if no plans were found
    else {
        echo '<h3>No plans found or you have not made any plans yet</h3>';
    }
    ?>
</div>
<?php include("../includes/footer.html"); ?>