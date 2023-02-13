<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        try {

        $validator =   Validator::make($request->all(), [
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'user_type' => 'required',
            'password' => 'required|min:6',
            'company_name' => 'required_if:user_type,2'
        ]);

        if ($validator->fails()) {

            $response['code'] = 1;
            $response['error'] = $validator->errors()->first();
            return response()->json($response,500);
        }

       $user = new User();

       $user->name = $request->name;
       $user->email =  $request->email;
       $user->password =  bcrypt($request->password);
       $user->user_type =  $request->user_type;
       $user->company_name = $request->company_name;
       $user->save();

       $token = $user->createToken('GamerOnTheMove'.$request->email)->accessToken;

       $response['code'] = 2;
       $response['message'] = 'Registration Successful';

       return response()->json($response, 200);

    } catch (\Illuminate\Database\QueryException $e) {
        $response['code'] = 3;
        $response['message'] = 'Registration Failed';

        return response()->json($response,500);
    }



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
