<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class PaymentService
{
    /**
     * Initialize transaction
     * 
     * @param  array  $data
     * @return array
     */
    public function intializeTransaction(User $user, Order $order): array
    {
        $url = "https://api.paystack.co/transaction/initialize";
        $data = [
            'email' => $user->email,
            'amount' => $order->total_amount * 100,
            'callback_url' => route('shopping_cart.payment_callback'),
            'metadata' => [
                'cancel_action' => route('shopping_cart.cancel_payment', compact('order')),
                'order_id' => $order->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => $user->phone ?? null,
            ],
        ];
        $data_string = http_build_query($data);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . config('paystack.secret_key'),
            "Cache-Control: no-cache",
        ));

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);
        $resultArray = json_decode($result, true);

        return !empty($resultArray['status']) ? $resultArray['data'] : [];
    }

    /**
     * Verify payment transaction
     * 
     * @param  string  $transactionRef
     * @return array
     */
    public function verify(string $transactionRef)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/{$transactionRef}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . config('paystack.secret_key'),
                "Cache-Control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return [];
        }

        $resultArray = json_decode($response, true);
        return !empty($resultArray['status']) ? $resultArray : [];
    }
}
