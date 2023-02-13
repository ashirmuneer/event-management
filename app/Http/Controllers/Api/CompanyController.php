<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyFollowers;
use App\Models\User;
use Illuminate\Http\Request;
use  App\Mail\NewEventNotify;

class CompanyController extends Controller
{
    //

    public function company_detail($id){

        try{
        $user = User::where('user_type','=','2')->where('id','=',$id)->first();

        if($user==null){

            $response['code'] = 1;
            $response['error'] = "company not found";

            return response()->json($response,200);

        }

        $response['code'] = 2;
        $response['data'] = $user;

        return response()->json($response,200);

    } catch (\Illuminate\Database\QueryException $e) {
        $response['code'] = 3;
        $response['error'] = 'something went wrong, please try again';

        return response()->json($response,500);
    }


    }

    public function follow_company($company_id){

                try{
                  $user = User::where('user_type','=','2')
                                ->where('id','=',$company_id)->first();

                        if($user==null){

                            $response['code'] = 1;
                            $response['error'] = 'company not found';

                            return response()->json($response,500);
                        }

                        $user_id  =   $user_id  = auth()->user()->id;

                        $userfollower = CompanyFollowers::where('company_user_id','=',$company_id)
                                                     ->where('user_id','=',$user_id)->first();

                            if($userfollower != null){
                                $response['code'] = 1;
                                $response['error'] = 'already followed';

                                return response()->json($response,500);
                            }

                            $newfollower = new CompanyFollowers();
                            $newfollower->company_user_id = $company_id;
                            $newfollower->user_id =  $user_id;
                            $newfollower->save();

                            $response['code'] = 2;
                            $response['message'] = 'followed succssfuly';

                             return response()->json($response,200);



                } catch (\Illuminate\Database\QueryException $e) {
                    $response['code'] = 3;
                    $response['error'] = 'something went wrong, please try again';

                    return response()->json($response,500);
                }

    }



}
