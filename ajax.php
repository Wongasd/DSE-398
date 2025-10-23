<?php
include_once('database/db.php');
if (isset($_GET['transactionID'])) {
    $transactionID = $_GET['transactionID'];
    $status = $_GET['status'];

    $selectTransactionSql = "SELECT t.*,b.Quantity as AvailableQuantity from transactions as t left join books as b on b.BookID = t.BookID where transactionID = '$transactionID'";
    $selectTransactionQry = mysqli_query($conn,$selectTransactionSql);
    $selectTransactionFetch = mysqli_fetch_array($selectTransactionQry);

    $borrowQuantity = $selectTransactionFetch['Quantity'];
    $actualQuantity = $selectTransactionFetch['AvailableQuantity'];
    $borrowBook = $selectTransactionFetch['BookID'];
    $currentStatus = $selectTransactionFetch['Status'];

    if($status == "RETURNED"){
        $ReturnDate = "ReturnDate = '" . date('Y-m-d') . "'";
    }else{
        $ReturnDate = "ReturnDate = NULL";
    }

    if(($status == "PENDING" || $status == "RETURNED") && $currentStatus !=="DENIED" ){
        $afterUpdateQuantity = $actualQuantity + $borrowQuantity;
    }elseif($status == "APPROVE"){
        $afterUpdateQuantity = $actualQuantity - $borrowQuantity;
    }

    $updateTransactionSql = "UPDATE transactions SET Status = '$status',  $ReturnDate  WHERE TransactionID = '$transactionID'";
    $updateTransactionQry = mysqli_query($conn, $updateTransactionSql);

    if($currentStatus !=="DENIED"){
        $updateBookSql = "UPDATE books SET Quantity = $afterUpdateQuantity where BookID = $borrowBook";
        $updateBookQry = mysqli_query($conn, $updateBookSql);
    }else{
        $updateBookQry = true;
    }


    if ($updateTransactionQry && $updateBookQry) {
        $fetchQuery = "select t.*,concat(FirstName, LastName) as FullName, b.Title from transactions as t left join users as u on t.UserID = u.UserID left join books as b on b.BookID = t.BookID WHERE TransactionID = '$transactionID'";
        $fetchResult = mysqli_query($conn, $fetchQuery);

        while($fetchArray =mysqli_fetch_array($fetchResult)) {
            // Return the updated status as a response
            echo "
            <td>{$fetchArray['TransactionID']}</td>
            <td>{$fetchArray['Title']}</td>
            <td>{$fetchArray['FullName']}</td>
            <td>{$fetchArray['BorrowDate']}</td>
            <td>{$fetchArray['ReturnDate']}</td>
            <td>{$fetchArray['DueDate']}</td>
            <td>{$fetchArray['Status']}</td>
            <td>{$fetchArray['Quantity']}</td>
            <td>";

            if($fetchArray['Status']=="PENDING"){
                echo"<div class='row'>";
                echo"<div class='col'>";
                echo "<button class='form-control btn btn-success' onclick='updateStatus(\"{$fetchArray['TransactionID']}\", \"APPROVE\", this)'>Approve</button>";
                echo"</div>";
                echo"<div class='col'>";
                echo "<button class='form-control btn btn-danger' onclick='updateStatus(\"{$fetchArray['TransactionID']}\", \"DENIED\", this)'>Denied</button>";
                echo"</div>";
            }else{
                echo"<div class='row'>";
                
                if($fetchArray['Status']=="APPROVE"){
                    echo"<div class='col'>";
                    echo "<button class='form-control btn btn-primary' onclick='updateStatus(\"{$fetchArray['TransactionID']}\", \"RETURNED\", this)'>Return</button>";
                    echo"</div>";
                }
                
                if($fetchArray['Status']!=="RETURNED"){
                echo"<div class='col'>";
                echo "<button class='form-control btn btn-danger' onclick='updateStatus(\"{$fetchArray['TransactionID']}\", \"PENDING\", this)'>Undo</button>";
                echo"</div>";
                }
            }
            "<td>";

        } 
    }
}
?>