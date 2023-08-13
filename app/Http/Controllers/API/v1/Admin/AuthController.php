<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated_data = $this->validate($request, 
            [   
                'email' => ['required'],
                'password' => ['required']
            ],['email.exists' => 'This email does not exists']
        );
        $admin = Admin::where('email',$request->get('email'))->first();
        if($admin){
            if(Hash::check($validated_data['password'], $admin->password)){
                $token = $admin->createToken('plaintexttoken');
                return response()->json(['admin' => $admin,'token' => $token->plainTextToken]);
            } else {
                return response()->json(['error' => 'Password is incorrect']);
            }
        } else {
            return response()->json(['email' => 'This email does not exists!']);
        }
    }
}
