<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated_data = $this->validate($request, [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'mobile_number' => ['required','numeric','unique:customers,mobile_number'],
            'email' => ['nullable', 'email','unique:customers,email'],
            'password' => ['required']
        ],[]);

        $customer = new Customer();
        $customer->first_name = $validated_data['first_name'];
        $customer->last_name = $validated_data['last_name'];
        $customer->mobile_number = $validated_data['mobile_number'];
        $customer->email = $validated_data['email'];
        $customer->password = Hash::make($validated_data['password']);
        $customer->save();
        $token = $customer->createToken('plaintext');
        return response()->json(['customer' => $customer, 'token' => $token->plainTextToken]);
    }

    public function login(Request $request)
    {
        $validated_data = $this->validate(
            $request,
            ['email' => ['required'],'password' => ['required']],['email.exists' => 'This Email does not exists']);

        $customer = Customer::where('email',$request->get('email'))->first();
        if($customer){
            if(Hash::check($validated_data['password'], $customer->password)){
                $token = $customer->createToken('plaintexttoken');
                return response()->json(['customer' => $customer, 'token' => $token->plainTextToken]);
            } else {
                return response()->json(['error' => 'Password is Incorrect']);
            }
        } else {
            return response()->json(['email' => 'This email does not exists!']);
        }

    }
}
