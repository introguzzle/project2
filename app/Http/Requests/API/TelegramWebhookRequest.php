<?php

namespace App\Http\Requests\API;

use App\Rules\API\TelegramBotRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TelegramWebhookRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
//            'message.from.is_bot'        => new TelegramBotRule(),
//            'callback_query.from.is_bot' => new TelegramBotRule(),
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $data = ['status' => false, 'message' => 'failed'];

        throw new HttpResponseException(
            response()->json()
                ->setStatusCode(422)
                ->setData($data)
        );
    }
}
