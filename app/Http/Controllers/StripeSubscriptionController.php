<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;
use App\User;
use Illuminate\Support\Facades\DB;


class StripeSubscriptionController extends Controller
{
    public function create(Request $request, Plan $plan)
    {
        $user_id = $request['user_id'];
        $token = $request['stripeToken'];
        $amount = $request['amount'];
        $plan = Plan::findOrFail($request['plan']);
        $user = User::findOrFail($user_id);
        try {
        $user->newSubscription('main', $plan['stripe_plan'])->create($token['id']);
        }catch(\Stripe\Error\Card $e){
            $body = $e->getJsonBody();
            $err  = $body['error'];
            return response()->json([
            "error" => $err
            ], 400);
        }

        // $user->newSubscription('main', $plan['braintree_plan'])->create($nonce);

        // return response()->json([
        //     'plan' => $plan,
        //     'request'=> $request->post(),
        //     'paymentnonce' => $nonce,
        //     'user' => $user,
        //     'plan passed to new sub'=>$plan['braintree_plan']
        // ], 200);

        return response()->json([
            // 'plan' => $plan,
            'plan'=>$plan['id'],
            'token' => $token,
            'amount' => $amount,
            'user_id' => $user_id,
            'user' => $user,
            'reqest' => $request->all(),
            // 'plan passed to new sub'=>$plan['braintree_plan']
        ], 200);

    }

    public function update(Request $request, Plan $plan)
    {
        $plan = Plan::findOrFail($request['plan']);
        $user_id = $request['user_id'];
        $user = User::findOrFail($user_id);

        $user->subscription('main')->swap($plan['stripe_plan']);

        return response()->json([
            'user' => $user,
        ], 200);

    }

    public function cancel(Request $request, Plan $plan)
    {
        $user_id = $request['user_id'];
        $user = User::findOrFail($user_id);
        $user->subscription('main')->cancel();


        $newPlanLimit =  DB::table('plans')->where('id', 1)->value('monthly_limit');

        DB::table('users')
        ->where('id', $user_id)
        ->update(['api' => "1", 'limit'=>$newPlanLimit]);

            $newuser = DB::table('users')->where('id', $user['id'])->first();


        return response()->json([
            'user' => $newuser
        ], 200);
    }

    public function getSubscriptionRenewDate(Request $request)
    {

        $user_id = $request['user_id'];
        $user = User::findOrFail($user_id);

        if($user->hasStripeId()){
            $sub = $user->subscription('main')->asStripeSubscription();
            return response()->json([
                'cancel_at_period_end' => $sub['cancel_at_period_end'],
                'next_bill_due' => $sub['current_period_end']
            ], 200);
        }else{
            return response()->json([
                'cancel_at_period_end' => null,
                'next_bill_due' => null
            ], 200);
        }

        
    }

    public function getInvoices($user_id)
    {
        $user = User::findOrFail($user_id);
        // $sub = $user->subscription('main')->asStripeSubscription();

        if($user->hasStripeId()) {
            $invoices = $user->invoices();
        } else {
            $invoices = [];
        }

        $invoicesArray=[];

        foreach ($invoices as $invoice)
        {
        $date = $invoice->date()->toFormattedDateString();
        $total = $invoice->total;
        $id=$invoice->id;
        $download = '/user/download/invoice/' . $invoice->id;

        $invoiceDetails = Array(
            'date'=>$date,
            'total'=>$total,
            'download'=>$download,
            'id'=>$id,
        );

        array_push($invoicesArray, $invoiceDetails);

        }

        

        return response()->json([
            'user_id' => $user_id,
            'user' => $user,
            'has_stripe_is' => $user->hasStripeId(),
            'invoices' =>$invoicesArray,
        ], 200);
    }

    public function view_invoice($user_id, $invoice_id)
    {

        $user = User::findOrFail($user_id);
        //  $user = Auth::user();
         return $user->downloadInvoice($invoice_id, [
               'vendor'  => 'PNR Converter',
               'product' => 'PNR Converter API Subscription',
           ]);
   }

   public function updatecard(Request $request)
   {
       $token = $request['stripeToken'];
       $user_id = $request['user_id'];

       $user = User::findOrFail($user_id);

       $user->updateCard($token);
       return response()->json([
        'user' => $user,
    ], 200);
  }

}






