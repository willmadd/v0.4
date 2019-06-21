<?php

namespace App\Http\Controllers;
use App\User;


use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function subscription(Request $request)
    {
        $user = User::find(2);

        $user->newSubscription('level1', 'level1')->create($token);

        return $user;
    }
}
