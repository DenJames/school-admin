<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'recipient.id' => 'required|exists:users,id',
            'recipient.name' => 'required|exists:users,name',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
