<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProfileUpdateRequest extends FormRequest
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
                'email' => 'required|unique:users,email,'.$this->user()->id,
                'name' => 'required',
                'company_name' => 'required_if:user_type,2'
        ];
    }


    public function failedValidation(Validator $validator)
    {

            $response['code'] = 1;
            $response['error'] = $validator->errors()->first();

        throw new HttpResponseException(response()->json($response,500));

    }
}
