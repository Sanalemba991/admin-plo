<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function createOrder()
    {
        $clientId = '758675d89bda532f90ada309d4576857';
        $clientSecret = 'cfsk_ma_prod_d671cee52ea5c065b45c4fe58028c063_c7ced21c';
        $orderAmount = 1;
        $orderCurrency = 'INR';
        $customerDetails = array(
            'customer_id' => 'asdkjfhalsjkhf',
            'customer_name' => 'ravi kumar',
            'customer_email' => 'ravi@gmail.com',
            'customer_phone' => '9854401258'
        );
        $orderMeta = array(
            'return_url' => 'https://kandasolution.in/order_status.php'
        );
        $orderNote = '';

        $data = array(
            'order_amount' => $orderAmount,
            'order_currency' => $orderCurrency,
            'customer_details' => $customerDetails,
            'order_meta' => $orderMeta,
            'order_note' => $orderNote
        );

        $headers = array(
            'X-Client-Id: ' . $clientId,
            'X-Client-Secret: ' . $clientSecret,
            'Content-Type: application/json'
        );

        // Initialize cURL request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.cashfree.com/pg/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        // Decode the response
        $orderResponse = json_decode($response, true);

        if (isset($orderResponse['order_id'])) {
            $orderId = $orderResponse['order_id'];
        } else {
            $orderId = null;  // Handle error scenario
        }

        // Pass the response to the Blade view
        return view('order.create', compact('orderId', $response));
    }
}
