<?php

use App\Http\Controllers\API\CallbackPaymentController;
use App\Http\Controllers\API\CheckTicketStatus;
use App\Http\Controllers\API\GetPaymentInfoController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\RedirectUrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get ('getClient',[PaymentController::class,'getClient']);
Route::post('createPayment',[PaymentController::class,'create']);
Route::post('getPaymentInfo',[GetPaymentInfoController::class,'getPaymentInfo']);
// Route::post('redirectUrl',[RedirectUrlController::class,'redirectUserUrl']);
Route::get('payments/callback/{id}',[CallbackPaymentController::class,'callback'])->name('payment.callback');
// Route::get('checkstatus/{id}',[RedirectUrlController::class,'checkStatus'])->name('checkstatus');
Route::get('checkTicketStatus',CheckTicketStatus::class);

