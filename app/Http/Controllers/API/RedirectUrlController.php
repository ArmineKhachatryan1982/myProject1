<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class RedirectUrlController extends BaseController
{
    public function redirectUserUrl(Request $request,PaymentService $service){


        $getpaymentinfo = $service->redirectURL($request->order_id);


        $updated_transaction_status = $service->updateTransaction($request->transaction_id,$request->order_id,$getpaymentinfo['status']);
        // dd($updated_transaction_status);
        $message = '';
        if($getpaymentinfo['status'] == "pending"){
            $message=$getpaymentinfo['status'];

        }elseif($getpaymentinfo['status']=="succeeded"){
            $message=$getpaymentinfo['status'];
        }
        return  $this->sendResponse($message, 'success');



    }
    public function checkStatus($id,PaymentService $service){

        $transaction = Transaction::where('id',$id)->first();

        $getpaymentinfo = $service->redirectURL($transaction->order_id);
        
        $updated_transaction_status = $service->updateTransaction($id,$transaction->order_id,$getpaymentinfo['status']);

        $message = '';
        if($getpaymentinfo['status'] == "pending"){
            $message=$getpaymentinfo['status'];

        }elseif($getpaymentinfo['status']=="succeeded"){
            $message=$getpaymentinfo['status'];
        }
        return  $this->sendResponse($message, 'success');



    }
}
