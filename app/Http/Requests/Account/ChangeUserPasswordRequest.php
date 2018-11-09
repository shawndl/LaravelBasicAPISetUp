<?php

namespace App\Http\Requests\Account;

use App\Rules\CurrentPasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ChangeUserPasswordRequest extends FormRequest
{
    /**
     * Determine if the User is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => [
                'required',
                new CurrentPasswordRule
            ],
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
