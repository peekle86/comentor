<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreCommentRequest",
 *     type="object",
 *     title="Store Comment Request",
 *     required={"article_id", "is_anonymous", "text"},
 *     @OA\Property(
 *         property="article_id",
 *         type="integer",
 *         description="Article ID",
 *         example=15
 *     ),
 *     @OA\Property(
 *         property="is_anonymous",
 *         type="boolean",
 *         description="Is comment anonymous",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="text",
 *         type="string",
 *         description="Comment text",
 *         maxLength=255,
 *         example="This is a comment text"
 *     )
 * )
 */
class StoreCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'article_id' => ['required', 'exists:articles,id'],
            'is_anonymous' => ['boolean'],
            'text' => ['required', 'string', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
