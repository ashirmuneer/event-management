<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        if ($this->isMethod('PUT')) {
            // update
                return [
                    'title' => 'required|unique:events,title,'.$this->route('event'),
                    'date_time' => 'required',
                    'description' => 'required',
                    'location' => 'required'
            ];
        }else{
            //store

            return [
                'title' => 'required|unique:events',
                'date_time' => 'required',
                'description' => 'required',
                'location' => 'required'
                ];


        }

    }

    public function failedValidation(Validator $validator)
    {

            $response['code'] = 1;
            $response['error'] = $validator->errors()->first();

        throw new HttpResponseException(response()->json($response,500));

    }
}
