<?php
if(!isset($_SESSION)){
		if($_SERVER['REQUEST_METHOD']=="POST")
{
	session_Start();
}
$servername="localhost";
$username="root";
$password="";
$database="library";
 
$conn = new mysqli($servername,$username,$password,$database);
if($conn->connect_error){
	die ("Connection Failed" . $conn->connect_error);
}
}

?>

