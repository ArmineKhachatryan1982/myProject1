<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends BaseController
{

    /**
     * Show the form for creating a new resource.
     */

     public function getClient(PaymentService $service){
        $k = $service->client();
        return response()->json($k);

     }
    public function create(Request $request,PaymentService $service)
    {

        $validate=[];
        $validate['amount'] = 'required';

        $validator = Validator::make($request->all(), $validate);
        if($validator->fails()){

            return $this->sendError('error message', "Вам следует добавить сумму");
        }

        $amount = $request->amount;

        $description = "Заказ №";


        $transaction = $service->transaction($amount);

        if($transaction){
            $link = $service->createPayment($amount, $description,[
                    'transaction_id' => $transaction->id,

                ]);
            $explode_link = explode('=', $link);

            $transaction->update([
                'order_id' => $explode_link[1]
            ]);
            $transaction->save();
            $link_options=[];

            $link_options['transaction_id']=$transaction->id;

            $link_options['order_id']=$explode_link[1];
            $link_options['link']=$link;

            return  $this->sendResponse($link_options, 'success');
        }

    }

}
