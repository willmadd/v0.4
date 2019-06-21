<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;
use App\User;

class SubscriptionController extends Controller
{
    public function create(Request $request, Plan $plan)
    {
        $data = $request->post('data');
        $user_id = $data['user_id'];
        $user = User::findOrFail($user_id);
        

        $user->newSubscription('main', $plan['braintree_plan'])->create($nonce);

        return response()->json([
            'plan' => $plan,
            'request'=> $request->post(),
            'paymentnonce' => $nonce,
            'user' => $user
        ], 200);
    }

    public function cancel(Request $request, Plan $plan)
    {
        $data = $request->post('data');
        $user_id = $data['user_id'];
        $user = User::findOrFail($user_id);
        $user->subscription('main')->cancel();

        return response()->json([
            'user' => $user
        ], 200);
    }
}