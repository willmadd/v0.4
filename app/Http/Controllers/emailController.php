<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class emailController extends Controller
{
    public function sendemail(Request $request)
    {
        Mail::send(new ContactMail($request));

        return response()->json([
            'message' => 'Email Sent.'
        ], 200);
        
    }
}
