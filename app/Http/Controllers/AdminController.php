<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        Config::set('auth.defaults.guard', 'admin-api');
    }
/**
     * @OA\Post(
     *     path="/api/admin/auth/login",
     *     tags={"Login Admin"},
     *     summary="Login Admin",
     *     description="Test endpoint login admin.",
     *     operationId="login",
     *     @OA\Parameter(
     *         name="email",
     *         description="Masukkan Email",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         description="Masukkan Password",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     ),
     * )
     */

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
            'email' => 'required|email|unique:admins',
            'password' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->toJson(),400);
        }
        $admin = Admin::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        return response()->json([
            'message' => 'Admin berhasil registrasi.',
            'admin' => $admin
        ], 201);
    }

    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'Admin berhasil logout.']);
    }

    public function userProfile(){
        $data = Customer::paginate(request()->all());    
        return json_encode($data, 5);
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => strtotime(date('Y-m-d H:i:s', strtotime('+60 min'))),
            'user' => auth()->user()
        ]);
    }

    public function viewProfile($id){
        $data = Customer::where('id', $id)->get();
        return json_encode($data);
    }
}
