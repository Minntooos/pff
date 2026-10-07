<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Resource;
use App\Models\Section;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Define date ranges
        $currentWeekStart = Carbon::now()->startOfWeek();
        $currentWeekEnd = Carbon::now()->endOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

        // Current counts
        $resourceCount = Resource::where('user_id', $userId)->count();
        $categoryCount = Category::where('user_id', $userId)->count();
        $tagCount = Tag::where('user_id', $userId)->count();

        // Last week's counts
        $resourceCountLastWeek = Resource::where('user_id', $userId)
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->count();

        $categoryCountLastWeek = Category::where('user_id', $userId)
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->count();

        $tagCountLastWeek = Tag::where('user_id', $userId)
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->count();

        // Calculate percentage changes
        $resourceChange = $this->calculatePercentageChange($resourceCountLastWeek, $resourceCount);
        $categoryChange = $this->calculatePercentageChange($categoryCountLastWeek, $categoryCount);
        $tagChange = $this->calculatePercentageChange($tagCountLastWeek, $tagCount);

        // Get recent activities, notifications, etc.
        $recent_activities = $this->getRecentActivities($userId);
        $unreadNotificationsCount = Notification::where('user_id', $userId)->whereNull('read_at')->count();

        $notifications = Notification::where('user_id', $userId)->latest()->take(5)->get();
        $resourceType = $request->input('type', 'all');
        $searchTerm = $request->input('search');
        $selectedCategory = $request->input('category');
        $selectedDocumentType = $request->input('document_type');

        $sections = Section::where('user_id', $userId)->get();
        $selectedSection = $request->input('section');

        $query = Resource::where('user_id', $userId)
            ->with('category', 'tags', 'section');

        if ($selectedSection) {
            $query->where('section_id', $selectedSection);
        }

        if ($resourceType !== 'all') {
            $query->where('type', $resourceType);
        }

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory);
        }

        if ($resourceType === 'document' && $selectedDocumentType) {
            $query->where('file_path', 'LIKE', "%{$selectedDocumentType}");
        }

        $resources = $query->latest()->paginate(11);

        $categories = Category::where('user_id', $userId)->get();

        // Get unique document types (file extensions) among the user's documents.
        $documentTypes = Resource::where('user_id', $userId)
            ->where('type', 'document')
            ->pluck('file_path')
            ->map(fn ($path) => strtolower(pathinfo($path, PATHINFO_EXTENSION)))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        return view('dashboard', [
            'resourceCount' => $resourceCount,
            'resourceChange' => $resourceChange,
            'categoryCount' => $categoryCount,
            'categoryChange' => $categoryChange,
            'tagCount' => $tagCount,
            'tagChange' => $tagChange,
            'recent_activities' => $recent_activities,
            'resources' => $resources,
            'categories' => $categories,
            'searchTerm' => $searchTerm,
            'selectedCategory' => $selectedCategory,
            'selectedDocumentType' => $selectedDocumentType,
            'resourceType' => $resourceType,
            'documentTypes' => $documentTypes,
            'unreadNotificationsCount' => $unreadNotificationsCount,
            'notifications' => $notifications,
            'sections' => $sections,
            'selectedSection' => $selectedSection,
        ]);
    }

    private function calculatePercentageChange($old, $new)
    {
        if ($old == 0) {
            return $new > 0 ? 100 : 0;
        }

        return round((($new - $old) / $old) * 100, 2);
    }

    public function createSection(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Section::create([
            'name' => $request->input('name'),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Section created successfully!');
    }

    public function deleteSection(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
        ]);

        $section = Section::findOrFail($request->section_id);

        if ($section->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
        }

        $section->delete();

        return redirect()->route('dashboard')->with('success', 'Section deleted successfully!');
    }

    public function fetchResources(Request $request)
    {
        $userId = Auth::id();
        $type = $request->query('type', 'all');

        $query = Resource::where('user_id', $userId);

        switch ($type) {
            case 'images':
                $query->where('type', 'image');
                break;
            case 'videos':
                $query->where('type', 'video');
                break;
            case 'audios':
                $query->where('type', 'audio');
                break;
            case 'documents':
                $query->where('type', 'document');
                break;
                // 'all' case is not needed as it will return all resources
        }

        $resources = $query->latest()->get();

        return response()->json($resources);
    }

    private function getRecentActivities($userId)
    {
        $activities = collect();

        // Get recent resources
        $recentResources = Resource::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        foreach ($recentResources as $resource) {
            $activities->push([
                'type' => 'resource',
                'action' => 'added',
                'name' => $resource->title,
                'created_at' => $resource->created_at,
                'icon' => 'fa-plus',
            ]);
        }

        // Get recent categories
        $recentCategories = Category::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        foreach ($recentCategories as $category) {
            $activities->push([
                'type' => 'category',
                'action' => 'created',
                'name' => $category->name,
                'created_at' => $category->created_at,
                'icon' => 'fa-folder-plus',
            ]);
        }

        // Get recent tags
        $recentTags = Tag::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        foreach ($recentTags as $tag) {
            $activities->push([
                'type' => 'tag',
                'action' => 'added',
                'name' => $tag->name,
                'created_at' => $tag->created_at,
                'icon' => 'fa-tag',
            ]);
        }

        return $activities->sortByDesc('created_at')->take(5);
    }

    public function filterDocuments(Request $request)
    {
        $userId = Auth::id();
        $query = Resource::where('user_id', $userId)->where('type', 'document');

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        $document_resources = $query->paginate(10);
        $categories = Category::where('user_id', $userId)->get();

        return view('dashboard', [
            'document_resources' => $document_resources,
            'categories' => $categories,
        ]);
    }

    public function uploadDocument(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,txt,image,png,jpeg,mp4,avi,mov|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('document');

            // Generate a unique filename
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $encryptedFileName = Crypt::encryptString(Str::random(40)).'.'.$extension;

            // Store the file in the 'public/temp_uploads' directory
            $filePath = $file->storeAs('temp_uploads', $encryptedFileName, 'public');

            // Optional: Log the upload
            Log::info('File uploaded successfully:', [
                'original_name' => $originalName,
                'encrypted_name' => $encryptedFileName,
                'path' => $filePath,
                'user_id' => auth()->id(),
            ]);

            // Return a JSON response with the encrypted filename
            return response()->json([
                'success' => true,
                'filename' => $encryptedFileName,
                'message' => 'File uploaded successfully.',
            ]);

        } catch (\Exception $e) {
            // Log the error
            Log::error('File upload failed:', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            // Return a JSON response with the error
            return response()->json([
                'success' => false,
                'message' => 'File upload failed. Please try again.',
            ], 500);
        }
    }

    public function deleteDocument(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:resources,id',
        ]);

        $document = Resource::findOrFail($request->document_id);

        // Ensure the user owns the document
        if ($document->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
        }

        // Soft delete the document
        $document->delete();

        return redirect()->route('dashboard')->with('success', 'Document moved to trash successfully!');
    }

    public function downloadDocument(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:resources,id',
        ]);

        $document = Resource::findOrFail($request->document_id);

        // Ensure the user owns the document or it's public
        if ($document->user_id !== Auth::id() && ! $document->is_public) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
        }

        $filePath = storage_path('app/public/'.$document->file_path);
        $fileName = basename($document->file_path);
        $decryptedFileName = Crypt::decryptString(pathinfo($fileName, PATHINFO_FILENAME)).'.'.pathinfo($fileName, PATHINFO_EXTENSION);

        return response()->download($filePath, $decryptedFileName);
    }
}
