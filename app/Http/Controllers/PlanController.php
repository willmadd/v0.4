<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        
        return response()->json([
            'plans' => $plans
        ], 200);
    }

    public function planbyslug($slug)
    {

        $plan = DB::table('plans')->where('name', $slug)->first();

        return response()->json([
            'plan' => $plan
        ], 200);
    }

}
