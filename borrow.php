<?php
include_once("database/db.php");

$sqlBookCount = "select * from books where BookID = '$_GET[BookID]'";
$qryBookCount = mysqli_query($conn, $sqlBookCount);
$aryBookCount = mysqli_fetch_array($qryBookCount);

$maxQuantity = $aryBookCount['Quantity'];


if(isset($_POST['submit'])){

    $bookID= $_GET['BookID'];
    $UserId= $_SESSION['UserID'];
    $formDate= $_POST['fromDate'];
    $dueDate= $_POST['dueDate'];
    $Quantity= $_POST['Quantity'];
    

    $sql = "insert into transactions (BookID, Quantity,UserID, BorrowDate, DueDate, Status) Values ('$bookID',$Quantity,'$UserId','$formDate','$dueDate','PENDING')";
    if($qry = mysqli_query($conn, $sql)){
        echo "<script>alert('request borrow successful');</script>";
        
    }else{
        echo "<script>alert('request borrow failed. Please try again.');</script>";
    }  
 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Author</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <style>
        label {
            color: white;
        }
    </style>
</head>

<body>

    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <h1>Borrow</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-box">
                            <div class="form-bottom">
                                <form role="form" action="borrow.php?BookID=<?=$_GET['BookID']?>" method="POST" enctype="multipart/form-data" class="registration-form">

                                    <div class="form-group">
                                        <label for="fromDate">from date:</label>
                                        <input type="date" id="fromDate" name="fromDate" class="form-control">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="dueDate">due date</label>
                                        <input type="date" id="dueDate" name="dueDate" class="form-control">
                                    </div>
                                    

                                    <div class="form-group">
                                        <label for="dueDate">Quantity</label>
                                        <input type="number" id="Quantity" name="Quantity" class="form-control" value="1" min="1" max="<?=$maxQuantity?>">
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <?php if($_SESSION['Status'] == 'Banned' || $_SESSION['Status'] == 'Banned2'){ ?>
                                            <div class="col-xs-6">
                                                <button type="button" class="btn btn-danger btn-block"
                                                    onclick="alert('You have been banned from borrowing books.');">
                                                        Borrow
                                                </button>
                                            </div>
                                            <?php }else{ ?>
                                            <div class="col-xs-6">
                                                <button type="submit" name= "submit"class="btn btn-primary btn-block">Borrow</button>
                                            </div>
                                            <?php } ?>
                                            <div class="col-xs-6">
                                                <button type="button" class="btn btn-secondary btn-block" onclick="window.location.href='index.php'">Go Back</button>
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
 
<script>
    
    function formatDate(date) {
        const yyyy = date.getFullYear();
        const mm = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
        const dd = String(date.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    }

    // Set today's date
    const today = new Date();
    document.getElementById('fromDate').value = formatDate(today);

    // Set one week from today
    const nextWeek = new Date();
    nextWeek.setDate(today.getDate() + 7); // Add 7 days
    document.getElementById('dueDate').value = formatDate(nextWeek);
</script>
