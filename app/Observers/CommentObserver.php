<?php

namespace App\Observers;

use App\Events\CommentCreated;
use App\Models\Comment;

class CommentObserver
{
    public function created(Comment $comment): void
    {
        CommentCreated::dispatch($comment);
    }
}
