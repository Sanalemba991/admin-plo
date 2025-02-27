<?php

// Define API URL and credentials
$order_id = $_GET['order_id'];  // Replace this with dynamic order_id from the URL or session
$api_url = "https://api.cashfree.com/pg/orders/" . $order_id . "/payments";
$client_id = "248509910d09c490239e8fe233905842";
$client_secret = "cfsk_ma_prod_9022ae843660fdd536325544136d2119_eba31006";

// Initialize cURL
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-client-id: ' . $client_id,
    'x-client-secret: ' . $client_secret,
    'Accept: application/json',
    'x-api-version: 2023-08-01'
]);

// Execute cURL and get the response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
    exit;
}

// Close cURL session
curl_close($ch);

// Decode the response
$paymentDetails = json_decode($response, true);

// Check if the response is not empty
if (!empty($paymentDetails)) {
    // Extract important payment information
    $paymentStatus = $paymentDetails[0]['payment_status'] ?? 'UNKNOWN';
    $paymentAmount = $paymentDetails[0]['payment_amount'] ?? '0';
    $paymentMethod = $paymentDetails[0]['payment_method']['upi']['upi_id'] ?? 'N/A';
    $paymentTime = $paymentDetails[0]['payment_time'] ?? 'N/A';
    $bankReference = $paymentDetails[0]['bank_reference'] ?? 'N/A';
    $gatewayStatus = $paymentDetails[0]['payment_message'] ?? 'N/A';
} else {
    $paymentStatus = 'No payment information found';
}

// Proceed if the payment status is successful
if ($paymentStatus == "SUCCESS") {
    $servername = "localhost";
    $username = "battle";
    $password = "iamS@njay0809";
    $dbname = "battle";
    $pid = $_GET['pid'];
    $orid = $order_id;
    $txnid = $bankReference;
    $amount = $paymentAmount;

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
}else{
    header("Location: https://battle.innovalogic.in/payment/failed");
die();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .status-box {
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .status-box h2 {
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="status-box">
        <h2>Payment Status: <?php echo htmlspecialchars($paymentStatus); ?></h2>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>
        <p><strong>Player ID:</strong> <?php echo htmlspecialchars($_GET['pid']); ?></p>
        <p><strong>Payment Amount:</strong> <?php echo htmlspecialchars($paymentAmount); ?> INR</p>
        <p><strong>Payment Method (UPI ID):</strong> <?php echo htmlspecialchars($paymentMethod); ?></p>
        <p><strong>Payment Time:</strong> <?php echo htmlspecialchars($paymentTime); ?></p>
        <p><strong>Bank Reference:</strong> <?php echo htmlspecialchars($bankReference); ?></p>
        <p><strong>Gateway Status Message:</strong> <?php echo htmlspecialchars($gatewayStatus); ?></p>
    </div>
    <center>Click on the above back button and check your app Wallet</center>
</body>
</html>
