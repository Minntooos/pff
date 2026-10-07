<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TrashController extends Controller
{
    /**
     * Display a listing of trashed resources.
     *
     * @return View
     */
    public function index()
    {
        $userId = Auth::id();
        $trashedResources = Resource::onlyTrashed()->where('user_id', $userId)->paginate(10);

        return view('trash.index', compact('trashedResources'));
    }

    /**
     * Restore a trashed resource.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function restore($id)
    {
        $userId = Auth::id();
        $resource = Resource::onlyTrashed()->where('user_id', $userId)->findOrFail($id);
        $resource->restore();

        return redirect()->route('trash.index')->with('success', 'Resource restored successfully.');
    }

    /**
     * Permanently delete a trashed resource.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function forceDelete($id)
    {
        $userId = Auth::id();
        $resource = Resource::onlyTrashed()->where('user_id', $userId)->findOrFail($id);

        // Permanently delete the document file
        Storage::disk('public')->delete($resource->file_path);

        // Permanently delete the resource
        $resource->forceDelete();

        return redirect()->route('trash.index')->with('success', 'Resource permanently deleted.');
    }
}
