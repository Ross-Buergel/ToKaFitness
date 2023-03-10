<?php
$page_title = "Plans";
include("../includes/header.php");

//redirects the user to the login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

//gets the id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

//executes the sql query getting the advice
$query = "SELECT * FROM tbl_plans
WHERE plan_id = '$id'";
$result = mysqli_query($dbc, $query);

//outputs the advice
echo '<div class = "standard-box"><div>';
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<br><br><div class="box"></div>
        <h1>' . $row['title'] . '</h1>
        <div class="box"></div>

        <p>' . $row['message'] . '</p>
        <div class="box"></div>';
    }
}
echo '<br>
<button class = "submit-button"><a href = "plans.php">Back to All Plans</a></button>
</div></div>';