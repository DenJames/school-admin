<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageReplyFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
        ];
    }
}
