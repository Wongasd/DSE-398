<?php 
include_once("database/db.php");

if (!isset($_SESSION['Permission']) || $_SESSION['Permission'] !== 'admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='index.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $FirstName = trim($_POST['FirstName']);
    $LastName = trim($_POST['LastName']);

    $qry = "SELECT * FROM authors where FirstName = '".$FirstName."' AND LastName = '".$LastName."' ";
	$result = mysqli_query($conn,$qry);
	$rows = mysqli_num_rows($result);

	if($rows == 1){
		echo "<script>alert('That person is already in database');</script>";
	}else{
	$query = "INSERT INTO authors(FirstName, LastName) VALUES ('$FirstName','$LastName')";
	if($sql = mysqli_query($conn,$query)){
		echo "<script>window.location.href='login.php';alert('create successful');</script>";
	}else{
		echo "<script>alert('Error, Please Try Again');</script>";
		}
	}
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Authors</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="shortcut icon" href="assets/ico/favicon.png">
</head>

<body>

    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <h1>Authors</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-box">
                            <div class="form-bottom">
                                <form role="form" action="add_author.php" method="POST" class="registration-form">

                                    <div class="form-group">
                                        <label class="sr-only" for="FirstName">First name</label>
                                        <input type="text" name="FirstName" placeholder="First name..." class="form-first-name form-control" id="FirstName" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="LastName">Last name</label>
                                        <input type="text" name="LastName" placeholder="Last name..." class="form-last-name form-control" id="LastName" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                                            </div>
                                            <div class="col-xs-6">
                                                <button type="button" class="btn btn-secondary btn-block" onclick="window.location.href='register.php'">Sign up</button>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-1.11.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.backstretch.min.js"></script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>
