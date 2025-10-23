<?php 
include_once("database/db.php");

// if (!isset($_SESSION['Permission']) || $_SESSION['Permission'] !== 'admin') {
//     echo "<script>alert('Access denied. Admins only.'); window.location.href='index.php';</script>";
//     exit();
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $query = "SELECT * FROM books";
    $sql = mysqli_query($conn,$query);

if(isset($_POST['submit'])){
	if (!empty($_FILES['image']['name'])) 
	{
		$ext= explode('.', $_FILES['image']['name']);

		$ext= strtolower(array_pop($ext));

		$file= 'images/'.date('YmdHis').'.'.$ext;

		if (($ext == 'jpg'|| $ext == 'png'|| $ext == 'jpeg' || $ext == 'gif')) {
			$target_path = $file;
		}else{
			$error_ext = 1;
		}
		if (file_exists($file)) {
			$file_exists = 1;
		}
	}
if (isset($error_ext)) {
		echo "<Script>alert(Please upload .jpg,.png or .jpeg files only...)</script>";
	}elseif (isset($file_exists)) {
		echo "<script>alert(This file already exists!)</script";
	}elseif (isset($target_path)&& !move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
		echo "<script>alert('Upload Failed....')</script>";
	}else{
		$image = $file;
		$m_name = $_POST['m_name'];
		$m_hours = $_POST['m_hours'];
		$m_about = $_POST['m_about'];
		$m_price = $_POST['m_price'];
		$m_general = $_POST['m_general'];

	$qry = "INSERT INTO movie(a_id,m_name,m_hours,m_about,m_price,m_pic,m_genre) VALUES ('".$_SESSION['id']."','$m_name','$m_hours','$m_about','$m_price','$image','$m_general')";
	if($result = mysqli_query($conn,$qry)){
		echo "<script>window.location.href='admin.php';alert('add sucessed');</script>";
	}else{
		echo "<script>alert('add failed');</script>";
	}

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
    <title>Sign Up Your Account Here!</title>

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
                        <h1>Sign Up Now!</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-box">
                            <div class="form-bottom">
                                <form role="form" action="login.php" method="POST" class="registration-form">

                                    <div class="form-group">
                                        <label class="sr-only" for="Email">Email</label>
                                        <input type="text" name="Email" placeholder="Enter Your Email..." class="form-email form-control" id="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="Password">Password</label>
                                        <input type="text" name="Password" placeholder="Enter Your Password..." class="form-password form-control" id="Password" required>
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
