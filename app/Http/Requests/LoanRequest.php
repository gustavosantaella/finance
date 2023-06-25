<?php

namespace App\Http\Requests;

use App\Helpers\Log;
use App\Helpers\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoanRequest extends FormRequest
{
    use ResponseTrait;
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
        Log::write($this->all());
        return [
            "amount" => ['numeric', 'min:1', 'required'],
            "walletPk" => ['string', 'required', "min:10"],
            "description" => ['string', 'nullable'],
            'categoryPk' => ['required', 'min:10', 'string'],
            "isLoan" => ['required', "bool"],
            "who" => ['required', 'string'],
            "extra" => ['array', 'nullable']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->response($validator->errors()->first(), 400, [], error: true));
    }
}
