<?php

namespace App\Http\Controllers\Api;

use App\Events\MyEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Events;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use  App\Mail\NewEventNotify;
use App\Models\CompanyFollowers;
use App\Models\Notifications;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try{
            $user_id = auth()->user()->id;
              $events  =  Events::where('user_id','=',$user_id)->get();

                $response['code'] = 2;
                $response['data'] = $events;

         return response()->json($response,200);

        } catch (\Illuminate\Database\QueryException $e) {
            $response['code'] = 3;
            $response['message'] = 'something went wrong, please try again';

            return response()->json($response,500);
        }

    }

    public function all_event($keyword=null)
    {
        //
        try{
            if($keyword!=null){
                $events =  Events::where('title','LIKE',"%{$keyword}%")
                                    ->orwhere('date_time','LIKE',"%{$keyword}%")
                                    ->orwhere('location','LIKE',"%{$keyword}%")
                                    ->get();

            }else{
                $events  =  Events::get();
            }




                $response['code'] = 2;
                $response['data'] = $events;

         return response()->json($response,200);

        } catch (\Illuminate\Database\QueryException $e) {
            $response['code'] = 3;
            $response['message'] = 'something went wrong, please try again';

            return response()->json($response,500);
        }

    }

    public function event_detail($id,$user_id=null){

        try{

            if($user_id!=null){
                $notification = Notifications::where('event_id','=',$id)
                                              ->where('user_id','=',$user_id)->first();
                    if($notification!=null){
                        $notification->is_read = "1";
                        $notification->update();
                    }

            }

            $events  =  Events::find($id);

              $response['code'] = 2;
              $response['data'] = $events;

       return response()->json($response,200);

      } catch (\Illuminate\Database\QueryException $e) {
          $response['code'] = 3;
          $response['message'] = 'something went wrong, please try again';

          return response()->json($response,500);
      }

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

        try{



            $validator =   Validator::make($request->all(), [
                'title' => 'required|unique:events',
                'date_time' => 'required',
                'description' => 'required',
                'location' => 'required'
            ]);

            if ($validator->fails()) {

                $response['code'] = 1;
                $response['error'] = $validator->errors()->first();
                return response()->json($response,500);
            }



            $events  = new Events();
            $events->title = $request->title;
            $events->date_time =  $request->date_time;
            $events->description = $request->description;
            $events->location =  $request->location;
            $events->user_id =  Auth::user()->id;
            $events->save();

            $event_last_id = $events->id;

            $event_detail =  array(
                'name' => $request->title,
                'date_time' => $request->date_time,
                'description' => $request->description,
                'location' => $request->location
            );


          $companyfollower = CompanyFollowers::with('followeUser')->where('company_user_id','=', Auth::user()->id)->get();


          if(isset($companyfollower)){
            foreach($companyfollower as $user){

                if(isset($user->followeUser)){
                    $gamer = Mail::to($user->followeUser->email)->send(new NewEventNotify($event_detail));
                }

                $notification = new Notifications();
                $notification->user_id =  $user->user_id;
                $notification->title =  $request->title;
                $notification->description =  $request->description;
                $notification->event_id =  $event_last_id;
                $notification->save();

                $event_detail =  array(
                    'name' => $request->title,
                    'user_id' => $user->user_id,
                    'date_time' => $request->date_time,
                    'description' => $request->description,
                    'location' => $request->location
                );


                event(new MyEvent($user->user_id));

            }

          }


            $response['code'] = 2;
            $response['message'] = 'event has been created successfully';

            return response()->json($response,200);



        } catch (\Illuminate\Database\QueryException $e) {
            $response['code'] = 3;
            $response['message'] = 'something went wrong, please try again';

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
        try{
            $event = Events::find($id);

            if($event==null){
                $response['code'] = 1;
                $response['error'] = 'Data not found';

                return response()->json($response,500);
            }

            $response['code'] = 2;
            $response['data'] = $event;

            return response()->json($response,200);

            } catch (\Illuminate\Database\QueryException $e) {
                $response['code'] = 3;
                $response['message'] = 'something went wrong, please try again';

                return response()->json($response,500);
            }
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

        try{


            $validator =   Validator::make($request->all(), [
                'title' => 'required|unique:events,title,'.$id,
                'date_time' => 'required',
                'description' => 'required',
                'location' => 'required'
            ]);

            if ($validator->fails()) {

                $response['code'] = 1;
                $response['error'] = $validator->errors()->first();
                return response()->json($response,500);
            }

            $events  =  Events::find($id);
            if($events == null){
                $response['code'] = 1;
                $response['error'] = "Id not found.";
                return response()->json($response,500);
            }
            $events->title = $request->title;
            $events->date_time =  $request->date_time;
            $events->description = $request->description;
            $events->location =  $request->location;
            $events->user_id =  Auth::user()->id;
            $events->save();


            $response['code'] = 2;
            $response['message'] = 'event has been created successfully';

            return response()->json($response,200);



        } catch (\Illuminate\Database\QueryException $e) {
            $response['code'] = 3;
            $response['message'] = 'something went wrong, please try again';

            return response()->json($response,500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

              try{
                 $event = Events::find($id);
                 if($event ==null){

                    $response['code'] = 1;
                    $response['message'] = 'Id not found';

                    return response()->json($response,500);

                 }
                 $event->delete();

                 $response['code'] = 2;
                 $response['message'] = 'event has been deleted successfully';

                 return response()->json($response,200);

                } catch (\Illuminate\Database\QueryException $e) {
                    $response['code'] = 3;
                    $response['message'] = 'something went wrong, please try again';

                    return response()->json($response,500);
                }

    }
}
