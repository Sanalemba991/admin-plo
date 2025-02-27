<?php
if(isset($_POST['pid'])){
    $pid=$_POST['pid'];
    $email=$_POST['email'];
      $name=$_POST['name'];
      $txn=$_POST['txnid'];
        $amount=$_POST['amount'];
        $servername = "localhost";
    $username = "u421300540_ludo";
    $password = "isP@ssw0rd2024";
    $dbname = "u421300540_ludo";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert into transactions table
    $sql = "INSERT INTO `addcoins` (`playerid`, `image`, `name`, `email`, `coin`, `status`) VALUES ('$pid', '$txn', '$name', '$email', '$amount', '0')";

    $result = $conn->query($sql);
    if($result){
        echo "We REcived your Deposit Request";
    }else{
         echo "Something Went Wrong";
    }
    
}
?>