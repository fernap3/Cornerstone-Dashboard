<?php

require ("connection.php");


$vendor = $_POST['vendor'];
$material = $_POST['material'];
$size = $_POST['size'];
$height = $_POST['height'];
$weight = $_POST['weight'];
$based_on = $_POST['based_on'];
session_start();
$user_name = $_SESSION['user'];
date_default_timezone_set('America/New_York');
$today = date("Y-m-d G:i:s");
$a_p = date("A");
$job = "added popular Weights and Measures";
$sql6 = "INSERT INTO timestamp (user,time,job, a_p) VALUES ('$user_name', '$today','$job', '$a_p')";
$result7 = $conn->query($sql6) or die('Error querying database 5.');

$sql = "INSERT INTO w_and_m (vendor,material,size,height,weight,based_on) VALUES ('$vendor', '$material', '$size', '$height','$weight','$based_on')";
$result = $conn->query($sql) or die('Error querying database.');
 
$conn->close();

header("location: http://localhost/crst_dashboard/vendors.php ");

exit();

?>
