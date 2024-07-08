<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $customer = $request->user();
        $data = [];
        $data['id'] = $customer->id;
        $data['active'] = $customer->active;
        $data['first_name'] = $customer->first_name;
        $data['last_name'] = $customer->last_name;
        $data['email'] = $customer->email;
        $data['email_verified_at'] = $customer->email_verified_at;
        $data['mobile_number'] = $customer->mobile_number;
        $data['mobile_number_verified_at'] = $customer->mobile_number_verified_at;
        $data['avatar'] = $customer->avatar;
        return response()->json(['success' => true, 'profile' => $data]);
    }

    public function send_mobile_verification(Request $request)
    {
        $accountId = env('ACCOUNT_SID');
        $authToken = env('AUTH_TOKEN');
        $twilioNumber = env('TWILIO_NUMBER');
        $randomNumber = 102932;

        $client = new Client($accountId, $authToken);
        $client->messages->create($request['mobile_number'], array('from' => $twilioNumber, 'body' => 'This is a basic otp.'.$randomNumber.''));

    }
    
    public function verify_mobile_number(Request $request)
    {
        return response()->json(['success' => true, 'message' => 'Mobile Number sent!']);

    }
    public function send_verification_email(Request $request){}

    public function verify_email(Request $request){}

    public function update(Request $request){}

    public function remove_avatar(Request $request){}

    public function delete_account(Request $request){}
}
