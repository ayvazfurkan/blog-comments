<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @OA\Get(
 * path="/api/posts/{post_id}",
 * summary="Fetches a blog post with post_id",
 * description="Fetches a blog post with post_id",
 * operationId="fetchPostDetail",
 * tags={"Blog Posts"},
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
 */

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'content',
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('d.m.Y H:i');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }


}
