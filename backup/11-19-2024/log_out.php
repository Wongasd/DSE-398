<?php
session_start();

if(($_GET['action'] && $_GET['action'] == 'logout')){
	session_destroy();
	echo "<script>window.location.href='index.php';alert('log out');</script>";
}
?>