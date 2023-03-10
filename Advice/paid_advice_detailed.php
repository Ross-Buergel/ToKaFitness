<?php
$page_title = "Advice";
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
$query = "SELECT * FROM tbl_advice_paid
WHERE paid_advice_id = '$id'";
$paid_content = mysqli_query($dbc, $query);

//executes the sql query checking if user payed for advice
$query = "SELECT * FROM tbl_payment_confirmation
WHERE user_id = '" . $_SESSION['user_id'] . "' AND paid_content_id = '" . $id . "'";
$payment_confirmation = mysqli_query($dbc, $query);

//outputs advcie if user has payed for it
echo '<div class = "standard-box"><div>';
if (mysqli_num_rows($payment_confirmation) > 0) {
    while ($row = mysqli_fetch_array($paid_content, MYSQLI_ASSOC)) {
        echo '<br><br><div class="box"></div>
        <h1>' . $row['title'] . '</h1>
        <div class="box"></div>

        <p>' . $row['message'] . '</p>
        <div class="box"></div>
        <br>';
    }
    //outputs price if user has not payed for it
} else {
    while ($row = mysqli_fetch_array($paid_content, MYSQLI_ASSOC)) {
        echo '<br><br><div class="box"></div>
    <h1>' . $row['title'] . '</h1>
    <div class="box"></div>

    <h2>This advice costs £' . $row['price'] . '</h2>
    <div class="box"></div>
    <br>
    <button class = "submit-button"><a href = "checkout.php?id=' . $id . '">Continue to Checkout</a></button>';
    }
}
//outputs the return to advice button whether the user has payed for advice or not
echo '
<button class = "submit-button"><a href = "advice_overview.php">Back to All Advice</a></button>
</div></div>';