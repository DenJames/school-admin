<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupInvitationFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'recipient.id' => ['sometimes', 'exists:users,id'],
            'recipient.name' => ['sometimes', 'exists:users,name'],
            'group_role_id' => ['nullable', 'exists:group_roles,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
