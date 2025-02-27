<?php
// Define Cashfree API details
$apiUrl = 'https://api.cashfree.com/pg/orders';
$clientId = '758675d89bda532f90ada309d4576857';
$clientSecret = 'cfsk_ma_prod_d671cee52ea5c065b45c4fe58028c063_c7ced21c';
$apiVersion = '2023-08-01';

// Order details
$orderAmount = 1.00;
$orderCurrency = 'INR';
$orderId = 'devstudio_' . time(); // Unique order ID
$customerId = 'devstudio_user';
$customerPhone = '8474090589';
$returnUrl = 'https://www.cashfree.com/devstudio/preview/pg/web/checkout?order_id={order_id}';

// Create an order in Cashfree system
$orderData = [
    "order_amount" => $orderAmount,
    "order_currency" => $orderCurrency,
    "order_id" => $orderId,
    "customer_details" => [
        "customer_id" => $customerId,
        "customer_phone" => $customerPhone
    ],
    "order_meta" => [
        "return_url" => $returnUrl
    ]
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-client-id: ' . $clientId,
    'x-client-secret: ' . $clientSecret,
    'Accept: application/json',
    'Content-Type: application/json',
    'x-api-version: ' . $apiVersion
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));

$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true);

// Check for successful order creation
if (!isset($responseData['payment_session_id'])) {
    die('Order creation failed: ' . $responseData['message']);
}

// Payment Session ID
$paymentSessionId = $responseData['payment_session_id'];
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
    <div class="row">
        <p>Redirecting to the checkout page...</p>
    </div>
    <script>
        const cashfree = Cashfree({
            mode: "production",
        });

        // Automatically load the checkout page on window load
        window.onload = function() {
            let checkoutOptions = {
                paymentSessionId: "<?php echo $paymentSessionId; ?>",
                redirectTarget: "_self",
            };
            cashfree.checkout(checkoutOptions);
        };
    </script>
</body>
</html>
