<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
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

    public function user_profile_update(ProfileUpdateRequest $request){

        try{


            $user_id  = auth()->user()->id;



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
