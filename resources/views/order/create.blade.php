<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
</head>
<body>
    <h1>Create Order Response</h1>

    @if ($orderId)
        <p>Order ID: {{ $orderId }}</p>
    @else
        <p>Failed to create the order. Please try again.</p>
    @endif

    <h2>Full Response:</h2>
    <pre>{{ json_encode($orderResponse, JSON_PRETTY_PRINT) }}</pre>
</body>
</html>
