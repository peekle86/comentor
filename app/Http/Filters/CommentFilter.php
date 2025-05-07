<?php

namespace App\Http\Filters;

use App\Http\Requests\SearchCommentRequest;

class CommentFilter extends QueryFilter
{
    public function __construct(SearchCommentRequest $request)
    {
        parent::__construct($request);
    }

    public function articleId(string|int $articleId)
    {
        $this->query->where('article_id', $articleId);
    }

    public function userId(string|int $userId)
    {
        $this->query->where('user_id', $userId);
    }

    public function isAnonymous(bool $isAnonymous)
    {
        $this->query->where('is_anonymous', $isAnonymous);
    }

    public function search(string $query)
    {
        $this->query->whereLike('text', "%$query%");
    }
}
