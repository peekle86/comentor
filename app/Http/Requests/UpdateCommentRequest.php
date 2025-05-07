<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
