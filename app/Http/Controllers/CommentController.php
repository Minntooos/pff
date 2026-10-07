<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Comment;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Resource $resource)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = new Comment([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
        ]);

        $resource->comments()->save($comment);
        $activity = new Activity;
        $activity->user_id = auth()->id();
        $activity->resource_id = $resource->id;
        $activity->action = 'Comment';
        $activity->save();

        return redirect()->route('resources.show', $resource)->with('success', 'Comment added successfully.');
    }

    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $reply = new Comment([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'resource_id' => $comment->resource_id,
            'parent_id' => $comment->id,
        ]);

        $reply->save();

        return redirect()->route('resources.show', $comment->resource_id)->with('success', 'Reply added successfully.');
    }

    public function edit(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            abort(403);
        }

        return response()->json($comment);
    }

    public function update(Request $request, Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
            'edited' => true,
        ]);

        return response()->json([
            'success' => true,
            'content' => $comment->content,
            'edited' => $comment->edited,
        ]);
    }

    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();

        return redirect()->route('resources.show', $comment->resource_id)->with('success', 'Comment deleted successfully.');
    }
}
