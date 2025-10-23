<?php 
include_once("database/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $Email = trim($_POST['Email']);
    $Password = trim($_POST['Password']);

    // Prepare the statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->bind_param("s", $Email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the entered password with the stored hashed password
        if (password_verify($Password, $user['Password'])) {
            // Set session variables upon successful login
            $_SESSION['UserId'] = $user['UserId'];
            $_SESSION['Email'] = $user['Email'];
            $_SESSION['Permission'] = $user['Permission'];

            // Redirect based on user permission
            if ($user['Permission'] == "admin") {
                echo "<script>alert('Login as admin'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Login successful'); window.location.href='index.php';</script>";
            }
        } else {
            echo "<script>alert('Incorrect password, please try again');</script>";
        }
    } else {
        echo "<script>alert('No account found with that email');</script>";
    }

    $stmt->close();
    $conn->close();
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
                                <form role="form" action="login.php" method="post" class="registration-form">

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
