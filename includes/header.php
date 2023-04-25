<?php
session_start();
include("connect_db.php");
?>
<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>
    ToKa Fitness | <?php echo $page_title; ?>
  </title>
  <link rel="stylesheet" href="/ToKaFitness/includes/style.css">
  <script src="/ToKaFitness/includes/scripts.js"></script>
</head>

<body>
  <header>
    <ul>
      <li><a href="home">
          <div class="header-logo"></div>
        </a>
      </li>

      <li class="header-link"><a href="/ToKaFitness/index.php">Home</a></li>

      <li class="dropdown">
        <a href="javascript:void(0)" class="dropbtn">Plans and Posts</a>
        <div class="dropdown-content">
          <a href="/ToKaFitness/Plans/plans.php">Plans</a>
          <a href="/ToKaFitness/Social-Features/posts.php">Posts</a>
        </div>
      </li>

      <li class="dropdown">
        <a href="javascript:void(0)" class="dropbtn">Advice</a>
        <div class="dropdown-content">
          <a href="/ToKaFitness/Emails/email.php">Contact a Professional</a>
          <a href="/ToKaFitness/Advice/advice_overview.php">Search for Advice</a>
        </div>
      </li>

      <li class="dropdown" style="float: right; margin-right: 25px;">
        <a href="javascript:void(0)" class="dropbtn">Account</a>
        <div class="dropdown-content">
          <?php
          //checks if the user is logged in and, if so, adds the account settings and logout button, otherwise
          //adds the create account and login buttons
          if (!isset($_SESSION['user_id'])) {
            echo '
              <a href="/ToKaFitness/User-Accounts/register.php">Create Account</a>
              <a href="/ToKaFitness/User-Accounts/login.php">Login</a>';
          } else {
            echo '
              <a href="/ToKaFitness/User-Accounts/account_settings.php">Account Settings</a>
              <a href="/ToKaFitness/User-Accounts/logout.php">Logout</a>';
          } ?>
        </div>
      </li>
    </ul>
  </header>


  <?php
  //checks if the user is logged in and if so changes the theme of the website to their preffered choice
  if (isset($_SESSION['user_id'])) {
    $query = "SELECT * FROM tbl_colour_scheme
  WHERE user_id = " . $_SESSION['user_id'];
    $result = mysqli_query($dbc, $query);

    while ($row = mysqli_fetch_array($result)) {
      echo '<script>changeColour("' . $row['colour_scheme'] . '")</script>';
    }
  }
  ?>