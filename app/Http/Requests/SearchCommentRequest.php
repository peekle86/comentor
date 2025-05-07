<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class SearchCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'article_id' => ['nullable', 'exists:articles,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'is_anonymous' => ['nullable', 'boolean'],
            'search' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('is_anonymous')) {
            $this->merge([
                'is_anonymous' => $this->boolean('is_anonymous'),
            ]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }
}
