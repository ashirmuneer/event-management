<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Events;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
              $events  =  Events::all();

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


            $response['code'] = 2;
            $response['message'] = 'event has been created successfully';

            return response()->json($response,500);



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
                'title' => 'required|unique:events'.$id,
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


            $response['code'] = 2;
            $response['message'] = 'event has been created successfully';

            return response()->json($response,500);



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
        //
    }
}
