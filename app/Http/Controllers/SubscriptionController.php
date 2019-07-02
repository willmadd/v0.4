<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;
use App\User;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function create(Request $request, Plan $plan)
    {

        $data = $request->post('data');
        $plan = Plan::findOrFail($data['plan']);
        $user_id = $data['user_id'];
        $user = User::findOrFail($user_id);
        $nonce = $data['nonce'];
        

        $user->newSubscription('main', $plan['braintree_plan'])->create($nonce);

        return response()->json([
            'plan' => $plan,
            'request'=> $request->post(),
            'paymentnonce' => $nonce,
            'user' => $user,
            'plan passed to new sub'=>$plan['braintree_plan']
        ], 200);
    }

    public function update(Request $request, Plan $plan)
    {

        // $data = $request->post('data');
        // $plan = Plan::findOrFail($data['plan']);
        // $user_id = $data['user_id'];
        // $user = User::findOrFail($user_id);
        // $nonce = $data['nonce'];
        

        // $user->newSubscription('main', $plan['braintree_plan'])->create($nonce);

        // return response()->json([
        //     'plan' => $plan,
        //     'request'=> $request->post(),
        //     'paymentnonce' => $nonce,
        //     'user' => $user,
        //     'plan passed to new sub'=>$plan['braintree_plan']
        // ], 200);

        $data = $request->post('data');
        $plan = Plan::findOrFail($data['plan']);
        $user_id = $data['user_id'];
        $user = User::findOrFail($user_id);

$user->subscription('main')->swap($plan['braintree_plan']);

    }

    public function cancel(Request $request, Plan $plan)
    {
        $data = $request->post('data');
        $user_id = $data['user_id'];
        $user = User::findOrFail($user_id);
        $user->subscription('main')->cancel();


        $newPlanLimit =  DB::table('plans')->where('id', 1)->value('monthly_limit');

        DB::table('users')
        ->where('id', $user_id)
        ->update(['api' => $user['api'], 'limit'=>$newPlanLimit]);

            $user = DB::table('users')->where('id', $user['id'])->first();


        return response()->json([
            'user' => $user
        ], 200);
    }
}