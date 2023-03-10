<?php
$page_title = "Checkout";
include("../includes/header.php");


//redirects the user to the login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User-Accounts/login.php");
}

//gets the id from the previous file
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

//inserts row into database confirmaing payment
$query = "INSERT INTO tbl_payment_confirmation(paid_content_id, user_id, date)
VALUES ('$id','" . $_SESSION['user_id'] . "', NOW())";
$result = mysqli_query($dbc, $query);
?>
<!-- creates message informing user of their purchase -->
<div class="standard-box">
    <div>
        <br><br>
        <div class="box"></div>
        <h1>Checkout</h1>
        <div class="box"></div>

        <h2>Thank you for your purchase</h2>
    </div>
</div>
<?php include("../includes/footer.html"); ?>