<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateCommentRequest",
 *     type="object",
 *     title="Update Comment Request",
 *     required={"text"},
 *     @OA\Property(
 *         property="text",
 *         type="string",
 *         description="Comment text",
 *         maxLength=255,
 *         example="This is a comment text"
 *     )
 * )
 */
class UpdateCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
