<?php

namespace App\Services;

use App\Http\Filters\CommentFilter;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function getFilteredWithPagination(CommentFilter $filter): LengthAwarePaginator
    {
        return Comment::filter($filter)
            ->paginate();
    }

    public function create(User $user, array $data): Comment
    {
        return $user->comments()->create($data);
    }

    public function update(Comment $comment, array $data): Comment
    {
        return tap($comment)
            ->update($data);
    }

    public function delete(Comment $comment): bool
    {
        return $comment->delete();
    }
}
