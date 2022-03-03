<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @OA\Get(
 * path="/api/posts/{post_id}/comments",
 * summary="Fetches comments from a blog post",
 * description="Fetches comments from a blog post with post_id",
 * operationId="fetchPostComments",
 * tags={"Comments"},
 *     @OA\Parameter(
 *          name="post_id",
 *          description="Post id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 * @OA\Response(
 *    response=404,
 *    description="Post not found by id",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="No record found matching the id you entered!")
 *        )
 *     )
 * )

 * @OA\Post(
 *      path="/api/posts/{post_id}/comment",
 *      operationId="savePostComment",
 *      tags={"Comments"},
 *      summary="Store new comment to a blog post",
 *      description="Used to save comments on a blog post.",
 *     @OA\Parameter(
 *          name="post_id",
 *          description="Post id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass comment credentials",
 *    @OA\JsonContent(
 *       required={"username","content"},
 *       @OA\Property(property="username", type="string", format="text", example="fayvaz"),
 *       @OA\Property(property="content", type="string", format="text", example="Lorem ipsum dolor sit amet"),
 *    ),
 * ),
 * @OA\Response(
 *    response=404,
 *    description="No data posted",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="No data posted")
 *        )
 *     )
 * )

 * @OA\Post(
 *      path="/api/comments/{comment_id}/comment",
 *      operationId="saveCommentComment",
 *      tags={"Comments"},
 *      summary="Store reply to another comment",
 *      description="Used to reply another comment.",
 *     @OA\Parameter(
 *          name="post_id",
 *          description="Post id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass comment credentials",
 *    @OA\JsonContent(
 *       required={"username","content"},
 *       @OA\Property(property="username", type="string", format="text", example="fayvaz"),
 *       @OA\Property(property="content", type="string", format="text", example="Lorem ipsum dolor sit amet"),
 *    ),
 * ),
 * @OA\Response(
 *    response=404,
 *    description="No data posted",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="No data posted")
 *        )
 *     )
 * )
 */


class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'content',
        'commentable_id',
        'commentable_type'
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('d.m.Y H:i');
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
