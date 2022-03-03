<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $comments = Comment::with(['comments']);
        return CommentResource::collection($comments->orderBy('created_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return CommentResource
     */
    public function store(Request $request): CommentResource
    {
        $comment = Comment::create($request->validated());
        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Comment $comment
     * @return CommentResource
     */
    public function show(Comment $comment): CommentResource
    {
        return new CommentResource($comment::with(['comments']));
    }

    public function commentToPost(Request $request, Post $post): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $request->validate([
            'username' => 'required|min:5|max:255|string',
            'content' => 'required|min:5|string'
        ]);
        $comment = new Comment($request->all());
        $post->comments()->save($comment);
        return response($comment, 200);
    }

    public function commentToComment(Request $request, Comment $comment): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $request->validate([
            'username' => 'required|min:5|max:255|string',
            'content' => 'required|string'
        ]);
        $subComment = new Comment($request->all());
        $comment->comments()->save($subComment);
        return response($subComment, 200);
    }

    public function postComments(Request $request, Post $post): CommentResource
    {
        $comments = Post::with(['comments' => function ($query) {
            $query->orderBy('id', 'DESC');
        }, 'comments.comments' => function ($query) {
            $query->orderBy('id', 'DESC');
        },'comments.comments.comments' => function ($query) {
            $query->orderBy('id', 'DESC');
        }])
            ->where('id', '=', $post->id)
            ->get();

        return new CommentResource($comments->pluck('comments'));
    }
}
