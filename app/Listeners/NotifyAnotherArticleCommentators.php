<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Models\User;
use App\Notifications\ArticleCommented;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAnotherArticleCommentators implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentCreated $event): void
    {
        $commentators = User::whereIn('id', function ($query) use ($event) {
            $query->select('user_id')
                ->from('comments')
                ->where('article_id', $event->comment->article_id)
                ->where('user_id', '!=', $event->comment->user_id)
                ->distinct();
        })->get();

        $commentators->each(function ($commentator) use ($event) {
            $commentator->notify(new ArticleCommented($event->comment));
        });
    }
}
