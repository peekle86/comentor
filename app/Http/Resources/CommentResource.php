<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CommentResource",
 *     type="object",
 *     title="Comment Resource",
 *     description="Single comment data structure",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=101
 *     ),
 *     @OA\Property(
 *         property="text",
 *         type="string",
 *         example="This is a great article!"
 *     ),
 *     @OA\Property(
 *         property="is_anonymous",
 *         type="boolean",
 *         example=false
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-08-30T14:12:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-09-01T10:45:00Z"
 *     ),
 *     @OA\Property(
 *         property="article_id",
 *         type="integer",
 *         example=15
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="is_modified",
 *         type="boolean",
 *         example=true,
 *         description="True if the comment was updated after creation"
 *     )
 * )
 *
 * @mixin Comment
 */
class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'is_anonymous' => $this->is_anonymous,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'article_id' => $this->article_id,
            'user_id' => $this->user_id,

            'is_modified' => $this->created_at->timestamp !== $this->updated_at->timestamp,
        ];
    }
}
