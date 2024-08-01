<?php

namespace App\Http\Requests;

use App\Constants\Messages as MessagesConstant;
use App\Enums\UserRole;
use App\Services\Common\ApiResponseService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
            'confirm_password' => 'required|same:password',
            'role' => ['required', 'string', Rule::in(array_column(UserRole::cases(), 'value'))],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = ApiResponseService::error(
            MessagesConstant::VALIDATION_ERROR,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $validator->errors()
        );

        throw new HttpResponseException($response);
    }
}
