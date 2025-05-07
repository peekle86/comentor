<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Comment */
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
