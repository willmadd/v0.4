<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



class PaypalController extends Controller
{
    public function paypal(Request $request)
    {

        // Autoload SDK package for composer based installations
require 'vendor/autoload.php';

$apiContext = new \PayPal\Rest\ApiContext(
  new \PayPal\Auth\OAuthTokenCredential(
    'AedIVbiADiRsvL3jFM6Z6Kcx5wSgwyBIMJFFQq0UFcBfrew-mhHGMZVpqWJhvQGbn-HkUpt5F023HH4n',
    'EIs32ISB07N21Ey0z2a4Qthy5Obo173s1wD9Yx9hhiYJoC2bxdnNJVLpb2MvnT5QTYK74RBMg84FvPd4'
  )
);

        return response()->json([
            'message' => 'paypal route hit',
            'request' => $request->all()
        ]);
    }
}
