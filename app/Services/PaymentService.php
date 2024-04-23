<?php

namespace App\Services;

use App\Models\Transaction;
use YooKassa\Client;
use YooKassa\Model\Notification\NotificationEventType;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;

class PaymentService
{
    private function getClient():Client
    {
        $client = new Client();
        $client->setAuth(config('services.yookassa.shop_id'),config('services.yookassa.secret_key'));

        return $client;
    }

    public function transaction(int $amount){

        $transaction = Transaction::create([

            'amount' => $amount
        ]);
        return $transaction;

    }
    public function createPayment(int $amount, string $description, array $options=[])
    {


        $client = $this->getClient();

        $payment = $client->createPayment(
            [
                'amount' => [
                    'value' => $amount,
                    'currency' => 'RUB',
                ],
                'capture' => true,
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => route('payment.callback',$options['transaction_id']),
                ],
                'metadata'=>[
                    'transaction_id'=>$options['transaction_id'],

                ],


                "receipt" => [
                    "customer" => [
                        // "full_name" => "Иванов Иван Иванович",
                        "phone" => "79000000000",
                    ],
                    "items" => [
                        [
                            'description' => $description.$options['transaction_id'],
                            "quantity" => "1.00",
                            "amount" => [
                                "value" => $amount,
                                "currency" => "RUB"
                            ],
                            "vat_code" => "2",
                            "payment_mode" => "full_prepayment",
                            "payment_subject" => "commodity"
                        ],

                    ]
                ]


            ], uniqid('', true)

        );
     
        return $payment->getConfirmation()->getConfirmationURL();
    }
    public function getPaymentInfo($order_id){

        $client = $this->getClient();
        $payment = $client->getPaymentInfo($order_id);

        return $payment;
    }
    public function redirectURL($order_id){

        $client = $this->getClient();
        $payment = $client->getPaymentInfo($order_id);

        return $payment['status'];
        // return $payment;
    }
    public function updateTransaction(string $transaction_id,string $order_id, string $status){

        $transaction = Transaction::where([
            ['id',$transaction_id],
            ['order_id',$order_id]
        ])->first();

        $transaction->status = $status;
        $update=$transaction->save();

        return  $transaction->status;

    }

    public function callback($order_id){
        $client = $this->getClient();
        $payment = $client->getPaymentInfo($order_id);

        return $payment;

    }
    public function notification($id){

        $client = $this->getClient();
        $response = $client->addWebhook([
            "event" => NotificationEventType::PAYMENT_SUCCEEDED,
            "url"   => route('payment.callback',$id),
        ]);
        return $response;
    }
    public function client(){

        return $this->getClient();
    }



}
