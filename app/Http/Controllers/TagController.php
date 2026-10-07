<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    // Display a listing of the tags
    public function index()
    {
        $tags = Tag::all();

        return view('tags.index', compact('tags'));
    }

    // Show the form for creating a new tag
    public function create()
    {
        return view('tags.create');
    }

    // Store a newly created tag in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag = new Tag;
        $tag->name = $request->name;
        // user_id and tag id only
        $tag->user_id = Auth::id();

        $tag->save();

        return redirect()->route('tags.index')->with('success', 'Tag created successfully.');
    }

    // Display the specified tag
    public function show(Tag $tag)
    {
        return view('tags.show', compact('tag'));
    }

    // Show the form for editing the specified tag
    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    // Update the specified tag in storage
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag->name = $request->name;
        $tag->save();

        return redirect()->route('tags.index')->with('success', 'Tag updated successfully.');
    }

    // Remove the specified tag from storage
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('tags.index')->with('success', 'Tag deleted successfully.');
    }
}
