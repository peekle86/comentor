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
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Auth API Endpoints"
 * )
 */
class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $commentService
    )
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    /**
     * @OA\Get(
     *     path="/api/comments",
     *     summary="Get filtered comments",
     *     tags={"Comments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="article_id",
     *         in="query",
     *         required=false,
     *         description="ID of the related article",
     *         @OA\Schema(type="integer", example=42)
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         description="ID of the user who posted the comment",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Parameter(
     *         name="is_anonymous",
     *         in="query",
     *         required=false,
     *         description="Whether the comment was posted anonymously",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search string in comment text",
     *         @OA\Schema(type="string", example="interesting opinion")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of filtered comments",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CommentResource")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="path", type="string", example="https://example.com/api/comments"),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="to", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=150),
     *                     @OA\Property(
     *                     property="links",
     *                     type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="url", type="string", nullable=true, example=null),
     *                             @OA\Property(property="label", type="string", example="« Previous"),
     *                             @OA\Property(property="active", type="boolean", example=false)
     *                         )
     *                     )
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", example="https://example.com/api/comments?page=1"),
     *                 @OA\Property(property="last", type="string", example="https://example.com/api/comments?page=10"),
     *                 @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                 @OA\Property(property="next", type="string", example="https://example.com/api/comments?page=2")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized – invalid or missing token"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Some error"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"email": {"Some error"}}
     *             )
     *         )
     *     )
     * )
     */
    public function index(CommentFilter $filter)
    {
        $comments = $this->commentService->getFilteredWithPagination($filter);

        return CommentResource::collection($comments);
    }

    /**
     * @OA\Post(
     *     path="/api/comments",
     *     summary="Create a new comment",
     *     tags={"Comments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreCommentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/CommentResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized – invalid or missing token"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Some error"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"email": {"Some error"}}
     *             )
     *         )
     *     )
     * )
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = $this->commentService->create($request->user(), $request->validated());

        return $comment
            ->toResource()
            ->response()
            ->setStatusCode(HttpResponse::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/comments/{id}",
     *     summary="Get a comment by ID",
     *     tags={"Comments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="The ID of the comment"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/CommentResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized – invalid or missing token"
     *     ),
     *     @OA\Response(response=404, description="Comment not found")
     * )
     */
    public function show(Comment $comment)
    {
        return $comment
            ->toResource();
    }

    /**
     * @OA\Put(
     *     path="/api/comments/{id}",
     *     summary="Update an existing comment",
     *     tags={"Comments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="The ID of the comment"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCommentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated comment details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/CommentResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized – invalid or missing token"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized – not enough rights"
     *     ),
     *     @OA\Response(response=404, description="Comment not found"),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Some error"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"email": {"Some error"}}
     *             )
     *         )
     *     )
     * )
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment = $this->commentService->update($comment, $request->validated());

        return $comment
            ->toResource();
    }

    /**
     * @OA\Delete(
     *     path="/api/comments/{id}",
     *     summary="Delete a comment",
     *     tags={"Comments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="The ID of the comment"
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Comment deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized – invalid or missing token"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized – not enough rights"
     *     ),
     *     @OA\Response(response=404, description="Comment not found")
     * )
     */
    public function destroy(Comment $comment)
    {
        $this->commentService->delete($comment);

        return Response::noContent();
    }
}
