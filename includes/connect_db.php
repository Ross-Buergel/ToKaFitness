<?php
//sets the variables needed for the database connection
$host = "localhost";
$dbname = "db_tokafitness";
$username = "root";
$password = "";

//creates the database connection
$dbc = new mysqli($host, $username, $password, $dbname);

//outputs an error if one occurs
if ($dbc->connect_errno) {
    die("Connection error: " . $dbc->connect_error);
}

return $dbc;
?>