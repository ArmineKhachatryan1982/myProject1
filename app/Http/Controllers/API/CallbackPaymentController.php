<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class CallbackPaymentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    // public function callback(Request $request,$id,PaymentService $service)
    // {
    //     // dd($id);
    //     $transaction = Transaction::where('id',$id)->first();
    //     // dd($transaction->order_id);
    //    $create_hook= $service->notification($transaction->order_id);


    //     dd($create_hook);


    // }
    public function callback(Request $request,$id,PaymentService $service)
    {


        // $transaction = Transaction::where('id',$id)->first();


        // $getpaymentinfo = $service->callback($transaction->order_id);
        // $updated_transaction_status = $service->updateTransaction( $transaction->id,$transaction->order_id,$getpaymentinfo['status']);

        // return  $this->sendResponse($getpaymentinfo, 'success');

        $transaction = Transaction::where('id',$id)->first();


        $getpaymentinfo = $service->redirectURL($transaction->order_id);
        // dd($getpaymentinfo);

        $updated_transaction_status = $service->updateTransaction($id,$transaction->order_id,$getpaymentinfo);

        if($updated_transaction_status){

            echo "<script type='text/javascript'>
             window.location = 'unitydl://pddshock.ru?".$getpaymentinfo."'

             </script>";

         }



    }


}
