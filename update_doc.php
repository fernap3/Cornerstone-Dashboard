<?php
require ("connection.php");
$title = $_POST['title'];
$text = $_POST['text'];
$description = $_POST['description'];

if(isset($_POST['submit_form'])){
	session_start();
	$user_name = $_SESSION['user'];
	date_default_timezone_set('America/New_York');
	$today = date("Y-m-d G:i:s");
	$a_p = date("A");
	$job = "updated documentation";
	$sql6 = "INSERT INTO timestamp (user,time,job, a_p) VALUES ('$user_name', '$today','$job', '$a_p')";
	$result7 = $conn->query($sql6) or die('Error querying database 5.');

	$sql = 'UPDATE documentation SET title="' . $title . '",text="' . $text . '", user="' . $user_name . '", timestamp="' . $today . '", description="' .$description . '" WHERE title ="' . $title . '"';
	$result = $conn->query($sql) or die(mysqli_error($conn));

	$conn->close();
	header("location: documentation.php?p=1");
	exit();
}
else{
	session_start();
	$user_name = $_SESSION['user'];
	date_default_timezone_set('America/New_York');
	$today = date("Y-m-d G:i:s");
	$a_p = date("A");
	$job = "deleted documentation";
	$sql6 = "INSERT INTO timestamp (user,time,job, a_p) VALUES ('$user_name', '$today','$job', '$a_p')";
	$result7 = $conn->query($sql6) or die('Error querying database 5.');

	$sql = "DELETE FROM documentation WHERE title = '$title'";
	mysqli_query($conn, $sql);

	$conn->close();
	header("location: documentation.php");
	exit();
}
?>
