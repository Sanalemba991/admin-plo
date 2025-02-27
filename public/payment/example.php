<?php
$amount = $_GET['amount'];
$upidid = $_GET['upi_id']; // Updated to get UPI ID dynamically from query parameters
$name = $_GET['name'];
$email = $_GET['email'];
$pid = $_GET['Player_ID'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code for Manual Payment</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        #qr-code {
            margin: 20px auto;
        }
        .payment-details {
            margin: 20px;
            text-align: left;
        }
        .payment-details pre {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Manual Payment</h1>
    <p>Scan the QR code below to make a payment:</p>
    <canvas id="qr-code"></canvas>

    <div class="payment-details">
        <h3>Payment Details:</h3>
        <pre>
Recipient Name: <?php echo $name; ?>
UPI ID: <?php echo $upidid; ?>
Amount: ₹<?php echo $amount; ?>
        </pre>
    </div>

    <script>
        // Get PHP variables
        const upiId = "<?php echo $upidid; ?>";
        const amount = "<?php echo $amount; ?>";
        const name = "<?php echo $name; ?>";

        // Generate QR Code
        const qr = new QRious({
            element: document.getElementById('qr-code'),
            value: Kaiztren-1@okhdfcbank${encodeURIComponent(upiId)}&pn=${encodeURIComponent(name)}&am=${encodeURIComponent(amount)}&cu=INR`,
            size: 250,
        });
    </script>

    <h2>After Payment Fill This Form</h2>
    <center>
        <form action="https://ludo.kaiztren.com/payment/verify.php" method="post">
            <input type="hidden" name="pid" value="<?php echo $pid; ?>"/>
            <input type="hidden" name="name" value="<?php echo $name; ?>"/>
            <input type="hidden" name="email" value="<?php echo $email; ?>"/>
            <input type="hidden" name="amount" value="<?php echo $amount; ?>"/>
            <label>Enter Transaction ID</label>
            <input type="text" name="txnid" required />
            <input type="submit" name="submit" value="Submit"/>
        </form>
    </center>
</body>
</html>
