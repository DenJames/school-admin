<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GroupFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('groups')->where(function ($query) {
                return $query->where('team_id', Auth::user()->current_team_id);
            }),],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
