<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegistrationRequest extends FormRequest
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
        return [
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'user_type' => 'required',
            'password' => 'required|min:6',
            'company_name' => 'required_if:user_type,2'
            //
        ];
    }


    public function failedValidation(Validator $validator)

    {

            $response['code'] = 1;
            $response['error'] = $validator->errors()->first();

        throw new HttpResponseException(response()->json($response,500));

    }

    // public function messages()
    // {
    //     return [
    //         'email.required' => 'Email is required!',
    //         'name.required' => 'Name is required!',  
    //         'password.required' => 'Password is required!'
    //     ];
    // }
}
