<?php

namespace App\Http\Controllers\API\v1\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) 
    {
        $validated_data = $this->validate($request,[
            'first_name' => ['required'],
             'last_name' => ['required'],
             'email' => ['required','email','unique:sellers,email'],
             'mobile_number' => ['required','nullable','unique:sellers,mobile_number'],
             'password' => ['required'],
             'is_owner' => ['boolean'],
             'bank_name' => [],
             'account_number' => [],
             // if owner ....
            ]
        );

        $seller = Seller::create([
            'first_name' => $validated_data['first_name'],
            'last_name' => $validated_data['last_name'],
            'email' => $validated_data['email'],
            'mobile_number' => $validated_data['mobile_number'],
            'password' => Hash::make($validated_data['password']),
            'is_owner' => $validated_data['is_owner']
        ]);

        if($seller){
            $token = $seller->createToken('plaintext');
            return response()->json(['seller' => $seller,'token' => $token->plainTextToken]);
        }
    }

    public function login(Request $request)
    {
        $validated_data = $this->validate($request, 
            [   
                'email' => ['required'],
                'password' => ['required']
            ],['email.exists' => 'This email does not exists']
        );
        $seller = Seller::where('email',$request->get('email'))->first();
        if($seller){
            if(Hash::check($validated_data['password'], $seller->password)){
                $token = $seller->createToken('plaintexttoken');
                return response()->json(['seller' => $seller, 'token' => $token->plainTextToken]);
            } else {
                return response()->json(['error' => 'Password is incorrect']);
            }
        } else {
            return response()->json(['email' => 'This email does not exists!']);
        }
    }
}
