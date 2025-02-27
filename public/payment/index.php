<?php
$amount=$_GET['amount'];
$phone=$_GET['phone'];
$email=$_GET['email'];
$pid=$_GET['Player_ID'];
$name=$_GET['name'];
// Define API URL and credentials
$api_url = "https://api.cashfree.com/pg/orders";
$client_id = "248509910d09c490239e8fe233905842";
$client_secret = "cfsk_ma_prod_9022ae843660fdd536325544136d2119_eba31006";

// Define the order details
$orderData = [
    "order_amount" => $amount,
    "order_currency" => "INR",
    "order_id" => "Kart".time(), // You should generate unique order_id dynamically
    "customer_details" => [
        "customer_id" => $pid,
        "customer_phone" => $phone
    ],
    "order_meta" => [
        "return_url" => "https://battle.innovalogic.in/payment/success.php?order_id={order_id}&pid=".$pid
    ]
];

// Initialize cURL
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-client-id: ' . $client_id,
    'x-client-secret: ' . $client_secret,
    'Accept: application/json',
    'Content-Type: application/json',
    'x-api-version: 2023-08-01'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));

// Execute cURL and get the response
$response = curl_exec($ch);

// Check if there was any error
if(curl_errno($ch)){
    echo 'Curl error: ' . curl_error($ch);
    exit;
}

// Close cURL session
curl_close($ch);

// Decode the response
$responseData = json_decode($response, true);

// Check if the order creation was successful
if(isset($responseData['payment_session_id'])){
    // Pass the payment session ID to the HTML page
    $paymentSessionId = $responseData['payment_session_id'];
} else {
    // Handle error if the payment session ID is not returned
    echo "Order creation failed: " . $responseData['message'];
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cashfree Checkout Integration</title>
        <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
    </head>
    <body>
        <script>
            // Initialize Cashfree Payment SDK
            const cashfree = Cashfree({
                mode: "production", // Change this to "sandbox" for testing
            });

            // Automatically trigger the checkout page rendering
            window.onload = function() {
                let checkoutOptions = {
                    paymentSessionId: "<?php echo $paymentSessionId; ?>", // Use PHP to inject the payment session ID
                    redirectTarget: "_self", // opens in the current tab
                };
                // Automatically call the checkout process
                cashfree.checkout(checkoutOptions);
            };
        </script>
    </body>
</html>
