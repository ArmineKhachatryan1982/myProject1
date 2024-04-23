<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckTicketStatusRequest;
use App\Models\Transaction;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckTicketStatus extends BaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request,PaymentService $service)
    {

        $validate=[];
        $validate['transaction_id'] = 'required';
        $validate['order_id'] = 'required';



        $validator = Validator::make($request->all(), $validate);


        $transaction=Transaction::where([
                            ['id','=',$request->transaction_id],
                            ['order_id','=',$request->order_id],
        ])->first();
        $getpaymentinfo = $service->redirectURL($transaction->order_id);
       


        $updated_transaction_status = $service->updateTransaction($transaction->id,$transaction->order_id,$getpaymentinfo);

        if($transaction){

            return  $this->sendResponse($getpaymentinfo, 'success');

        }else{


            $message="There is no record in DB";

            return $this->sendError('error message', $message);

        }

    }
}
