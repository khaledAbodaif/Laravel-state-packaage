<?php
use Illuminate\Support\Facades\Route;
use Khaleds\State\StatePattern\PendingState;
use Khaleds\State\StatePattern\PaymentStatusEnum;
use \Khaleds\State\Interfaces\StateNameAbstract;
Route::get('khaleds', function (){
//   return \Khaleds\State\Models\Payment::all();
//    dd(\Khaleds\State\Models\Payment_status::all());

//    dd(PaymentStatusEnum::{'Paid'}) ;
    $payment = \Khaleds\State\Models\Payment::find(5);


//    echo $payment->state->handel();

        $payment->state->transitionTo(StateNameAbstract::find('pending'));
    echo $payment->state->handel();
//    return \Khaleds\State\Models\Payment::getStates();
    return $payment;

});
