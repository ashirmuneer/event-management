<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    //

    public function user_profile_info(){

        try{
            $user  =  auth()->user();

              $response['code'] = 2;
              $response['data'] = $user;

       return response()->json($response,200);

      } catch (\Illuminate\Database\QueryException $e) {
          $response['code'] = 3;
          $response['message'] = 'something went wrong, please try again';

          return response()->json($response,500);
      }

    }

    public function user_profile_update(Request $request){

        try{
            $validator =   Validator::make($request->all(), [
                'email' => 'required|email',
                'name' => 'required',
                'company_name' => 'required_if:user_type,2'
            ]);

            if ($validator->fails()) {

                $response['code'] = 1;
                $response['error'] = $validator->errors()->first();
                return response()->json($response,500);
            }

            $user_id  = auth()->user()->id;

          $user_email_exist = User::where('email','=',$request->email)->where('id','!=',$user_id)->first();

          if($user_email_exist != null){

            $response['code'] = 1;
            $response['error'] = "email already taken";
            return response()->json($response,500);

          }

           $user = User::where('id','=',$user_id)->first();

           $user->name = $request->name;
           $user->email= $request->email;
          $user->update();

          $response['code'] = 2;
          $response['data'] = $user;


   return response()->json($response,200);



      } catch (\Illuminate\Database\QueryException $e) {
          $response['code'] = 3;
          $response['message'] = 'something went wrong, please try again';

          return response()->json($response,500);
      }

    }

    public function user_notifications(){

      try{
        $user_id  = auth()->user()->id;


        $unread_notification = Notifications::where('user_id','=',$user_id)
                                             ->where('is_read','=','0')
                                             ->orderby('id','desc')->get();

                $response['code'] = 2;
                $response['data'] = $unread_notification;


        return response()->json($response,200);


    } catch (\Illuminate\Database\QueryException $e) {
        $response['code'] = 3;
        $response['message'] = 'something went wrong, please try again';

        return response()->json($response,500);
    }

}


}
