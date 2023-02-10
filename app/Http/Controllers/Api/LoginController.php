<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    //

    public function login(Request $request){

        try {



            $validator =   Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {

                $response['code'] = 1;
                $response['error'] = $validator->errors()->first();
                return response()->json($response,500);
            }

            $data = [
                'email' => $request->email,
                'password' => $request->password
            ];

            if (auth()->attempt($data)) {
                $token = auth()->user()->createToken('GamerOnTheMove'.$request->email)->accessToken;
                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['error' => 'Unauthorised'], 401);
            }




        } catch (\Illuminate\Database\QueryException $e) {
            $response['code'] = 3;
            $response['message'] = 'something went wrong, please try again';

            return response()->json($response,500);
        }


    }
}
