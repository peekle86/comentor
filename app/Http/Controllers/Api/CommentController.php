<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\CommentFilter;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $commentService
    ) {
        $this->authorizeResource(Comment::class, 'comment');
    }

    public function index(CommentFilter $filter)
    {
        $comments = $this->commentService->getFilteredWithPagination($filter);

        return CommentResource::collection($comments);
    }

    public function store(StoreCommentRequest $request)
    {
        $comment = $this->commentService->create($request->user(), $request->validated());

        return $comment
            ->toResource()
            ->response()
            ->setStatusCode(HttpResponse::HTTP_CREATED);
    }

    public function show(Comment $comment)
    {
        return $comment
            ->toResource();
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment = $this->commentService->update($comment, $request->validated());

        return $comment
            ->toResource();
    }

    public function destroy(Comment $comment)
    {
        $this->commentService->delete($comment);

        return Response::noContent();
    }
}
