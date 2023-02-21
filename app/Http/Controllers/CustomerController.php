<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;


class CustomerController extends Controller
{
    public function __construct()
    {
        Config::set('auth.defaults.guard', 'customer-api');
    }

    
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if($validator->fails()) {
            return response()->json($validator->error(),422);
        }
        if(! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }
        return $this->createNewToken($token);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'fullname' => 'required|string',
            'email' => 'required|email|unique:customers',
            'password' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->toJson(),400);
        }
        $customer = Customer::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        return response()->json([
            'message' => 'Customer berhasil registrasi.',
            'customer' => $customer
        ], 201);
    }

    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'Customer berhasil logout.']);
    }



    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => strtotime(date('Y-m-d H:i:s', strtotime('+60 min'))),
            'user' => auth()->user()
        ]);
    }
}

