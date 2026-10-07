<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Resource;
use App\Models\Tag;

class WelcomeController extends Controller
{
    public function index()
    {
        $publicResources = Resource::where('is_public', true)
            ->with(['category', 'tags'])
            ->latest()
            ->take(6)
            ->get();

        $resourceCount = Resource::where('is_public', true)->count();
        $categoryCount = Category::count();
        $tagCount = Tag::count();

        $resourcesByCategory = Resource::where('is_public', true)
            ->selectRaw('category_id, count(*) as count')
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->pluck('count', 'category.name');
        $categories = Category::withCount('resources')
            ->whereHas('resources', function ($query) {
                $query->where('is_public', true);
            })
            ->take(3)
            ->get();

        $categories = $categories->map(function ($category) {
            $lastResource = $category->resources()
                ->where('is_public', true)
                ->latest()
                ->first();
            $category->last_resource_image = $lastResource ? $lastResource->resource_image_path : null;

            return $category;
        });
        $recentActivities = Activity::where('action', 'create')
            ->whereHas('resource', function ($query) {
                $query->where('is_public', true);
            })
            ->with(['user', 'resource'])
            ->latest()
            ->take(5)
            ->get();

        return view('welcome', compact('publicResources', 'resourceCount', 'categoryCount', 'tagCount', 'resourcesByCategory', 'categories', 'recentActivities'));

    }
}
