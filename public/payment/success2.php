<?php

    $servername = "localhost";
    $username = "battle";
    $password = "iamS@njay0809";
    $dbname = "battle";
    $pid = $_GET['pid'];
    $orid = $_GET['txnid'];
    $txnid = $_GET['txnid'];
    $amount = $_GET['amount'];
    $paymentTime=date();
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert into transactions table
    $sql = "INSERT INTO `transactions` (`userid`, `order_id`, `txn_id`, `amount`, `status`, `trans_date`) 
            VALUES ('$pid', '$orid', '$txnid', '$amount', 'Success', '$paymentTime')";

    // Select user data
    $sql2 = "SELECT * FROM `userdatas` WHERE `playerid` = '$pid'";
    $result = $conn->query($sql2);

    if ($result->num_rows > 0) {
        // Fetch user data
        while ($row = $result->fetch_assoc()) {
            $totalcoin = $row['totalcoin'];
            $playcoin = $row['playcoin'];
        }
        $newcoin = $totalcoin + $amount;

        // Update user coins
        if ($conn->query($sql) === TRUE) {
            $sql3 = "UPDATE `userdatas` SET `totalcoin` = '$newcoin', `playcoin` = '$newcoin' WHERE `playerid` = '$pid'";
            $conn->query($sql3);
        }
    }

    // Close the connection
    $conn->close();

    header("Location: https://battle.innovalogic.in/payment/success");
die();


?>
