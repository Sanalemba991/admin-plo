<?php

function generateXVerify($payload, $saltKey, $saltIndex) {
    $dataToHash = base64_encode($payload) . "/pg/v1/pay" . $saltKey;
    return hash("sha256", $dataToHash) . "###" . $saltIndex;
}

$url = "https://api.phonepe.com/apis/hermes/pg/v1/pay";

// Generate a random transaction ID with a timestamp
$transactionId = "MT" . rand(100000, 999999) . time();

$payload = json_encode([
    "merchantId" => "M22R25NR7O6SJ",
    "merchantTransactionId" => $transactionId,
    "merchantUserId" => "MUID123",
    "amount" => 100,
    "redirectUrl" => "https://battle.innovalogic.in/payment/success2.php",
    "redirectMode" => "REDIRECT",
    "mobileNumber"=> "8910542626",
    "callbackUrl" => "https://battle.innovalogic.in/payment/success2.php",
    "paymentInstrument" => ["type" => "PAY_PAGE"]
]);

$saltKey = "ac1deba7-3fe5-40fe-bdbf-cd2c1433433d";
$saltIndex = "1";
$xVerify = generateXVerify($payload, $saltKey, $saltIndex);

$headers = [
    "Content-Type: application/json",
    "X-VERIFY: $xVerify"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["request" => base64_encode($payload)]));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// Access the URL
$url = $data['data']['instrumentResponse']['redirectInfo']['url'];

// Redirect to the URL
// Redirect to the URL
echo "<script>window.location.href = '$url';</script>";
exit();
?>
