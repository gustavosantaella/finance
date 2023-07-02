<?php

namespace App\Http\Requests;

use App\Helpers\Log;
use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class CreateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "lang" => ['string', 'required'],
            "name" => ['string', 'required'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->response($validator->errors()->first(), 400, [], error: true));
    }
}
