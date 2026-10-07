<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Resource;
use App\Models\ShareLink;
use App\Models\Tag;
use App\Models\User;
use FFMpeg;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Mews\Purifier\Facades\Purifier;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Resource::where('is_public', true)->with('category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'date':
                    $query->latest();
                    break;
                case 'popularity':
                    $query->withCount('likes')->orderByDesc('likes_count');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $resources = $query->paginate(9);
        $categories = Category::all();

        if ($request->ajax()) {
            return view('partials.public-resource-grid', compact('resources'))->render();
        }

        return view('resources.index', compact('resources', 'categories'));
    }

    public function manage(Request $request)
    {
        $userId = Auth::id();

        // Query resources belonging to the authenticated user
        $query = Resource::where('user_id', $userId);
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        $resources = $query->paginate(12);

        foreach ($resources as $resource) {
            $icon = '';
            if ($resource->default_icon) {
                $icon = $resource->default_icon;
            } elseif ($resource->resource_image_path) {
                $icon = asset('storage/resource_images/'.$resource->resource_image_path);
            } else {
                $icon = $resource->default_icon;
            }
            $resource->icon = $icon;
        }

        $categories = Category::where('user_id', $userId)->get();

        if ($request->ajax()) {
            return view('partials.resource-grid', compact('resources'))->render();
        }

        return view('resources.manage', compact('resources', 'categories'));
    }

    private function getResourceIcon($type)
    {
        switch ($type) {
            case 'pdf':
                return '<i class="fas fa-file-pdf"></i>';
            case 'doc':
            case 'docx':
                return '<i class="fas fa-file-word"></i>';
            case 'xls':
            case 'xlsx':
                return '<i class="fas fa-file-excel"></i>';
            case 'ppt':
            case 'pptx':
                return '<i class="fas fa-file-powerpoint"></i>';
            case 'txt':
                return '<i class="fas fa-file-alt"></i>';
            case 'audio':
                return '<i class="fas fa-file-audio"></i>';
            case 'video':
                return '<i class="fas fa-file-video"></i>';
            default:
                return '<i class="fas fa-file"></i>';
        }
    }

    public function recentActivity(Request $request)
    {
        $userId = Auth::id();
        $query = Activity::where('user_id', $userId)->with('resource')->latest();

        // Apply filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('resource_type')) {
            $query->whereHas('resource', function ($q) use ($request) {
                $q->where('type', $request->resource_type);
            });
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        // Search
        if ($request->filled('search')) {
            $query->whereHas('resource', function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%');
            });
        }

        $activities = $query->paginate(10);

        if ($request->ajax()) {
            return view('partials.activity-list', compact('activities'))->render();
        }

        return view('resources.recent-activity', compact('activities'));
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('temp_uploads', $filename, 'public');

            return response()->json([
                'success' => true,
                'filename' => $filename,
                'message' => 'File uploaded successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file uploaded',
        ]);
    }

    private function getFileType($extension)
    {
        $documentTypes = ['pdf', 'doc', 'docx', 'txt'];
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $videoTypes = ['mp4', 'avi', 'mov'];
        $audioTypes = ['mp3', 'wav', 'ogg'];

        if (in_array($extension, $documentTypes)) {
            return 'document';
        }
        if (in_array($extension, $imageTypes)) {
            return 'image';
        }
        if (in_array($extension, $videoTypes)) {
            return 'video';
        }
        if (in_array($extension, $audioTypes)) {
            return 'audio';
        }

        return 'document'; // default type
    }

    public function create(Request $request)
    {
        $categories = Category::where('user_id', Auth::id())->get();
        $tags = Tag::where('user_id', Auth::id())->get();
        $uploadedFile = null;
        $fileType = null;

        if ($request->has('file')) {
            $filename = $request->get('file');
            $tempPath = storage_path('app/public/temp_uploads/'.$filename);
            Log::info('ResourceController@create: Checking file at '.$tempPath);

            if (file_exists($tempPath)) {
                $uploadedFile = $filename;
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                $fileType = $this->getFileType($extension);
                Log::info('File found: '.$filename.' with type '.$fileType);
            } else {
                Log::error('Pre-uploaded file not found: '.$tempPath);

                return redirect()->back()->with('error', 'Uploaded file could not be found. Please try uploading again.');
            }
        }

        return view('resources.create', compact('categories', 'tags', 'uploadedFile', 'fileType'));
    }

    private function generateVideoThumbnail($videoFileName)
    {
        $videoPath = Storage::disk('public')->path('resources/'.$videoFileName);
        $thumbnailName = pathinfo($videoFileName, PATHINFO_FILENAME).'_thumbnail.jpg';

        // Generate a new unique name for the thumbnail
        $thumbnailPath = Storage::disk('public')->path('resource_images/'.$thumbnailName);
        // Ensure the output directory exists
        if (! file_exists(dirname($thumbnailPath))) {
            mkdir(dirname($thumbnailPath), 0755, true);
        }

        // Update the ffmpeg path to use the one in your system PATH
        $ffmpeg = 'ffmpeg';

        // Use escapeshellarg to properly escape the file paths
        $videoPath = escapeshellarg($videoPath);
        $thumbnailPath = escapeshellarg($thumbnailPath);

        // Construct the FFmpeg command
        $command = "{$ffmpeg} -i {$videoPath} -vframes 1 -an -s 300x200 -ss 00:00:01 -y {$thumbnailPath}";

        // Execute the command
        exec($command, $output, $returnCode);

        if (file_exists(storage_path('app/public/resource_images/'.$thumbnailName))) {
            return $thumbnailName;
        }
        // If ffmpeg fails, log the error and return null
        Log::error("Failed to generate video thumbnail. Command: {$command}, Return code: {$returnCode}, Output: ".implode("\n", $output));

        return null;
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        try {
            $validated = $request->validate([
                'id' => '',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'type' => 'required|string|in:document,image,video,audio',
                'tags' => 'nullable|string',
                'file-upload' => 'required_without:pre_uploaded_file|file|max:10000240',
                'pre_uploaded_file' => 'required_without:file-upload|string',
                'resource_image' => 'nullable|file|max:1000048',
                'category_id' => 'required|exists:categories,id',
                'image-source' => 'required|in:default,ai,upload',
                'default_icon' => 'required_if:image-source,default|nullable|string',
            ]);

            $validated['visibility'] = $request->input('visibility') ? 1 : 0;
            $sanitizedDescription = Purifier::clean($request->input('description'), 'default');

            $resource = new Resource;
            $resource->title = $validated['title'];
            $resource->description = $sanitizedDescription;
            $resource->type = $validated['type'];
            $resource->user_id = Auth::id();
            $resource->is_public = $validated['visibility'];
            $resource->category_id = $validated['category_id'];

            if ($user->usertype === 'admin') {
                $resource->approval_status = 'approved';
            } elseif ($user->usertype === 'user') {
                $resource->approval_status = $request->visibility ? 'pending' : 'approved';
            }

            // Handle file upload
            // Handle file upload
            if ($request->hasFile('file-upload')) {
                $file = $request->file('file-upload');
            } elseif ($request->has('pre_uploaded_file')) {
                $preUploadedFile = $request->input('pre_uploaded_file');
                $tempPath = storage_path('app/public/temp_uploads/'.$preUploadedFile);
                if (file_exists($tempPath)) {
                    $file = new UploadedFile($tempPath, $preUploadedFile);
                } else {
                    throw new \Exception('Pre-uploaded file not found.');
                }
            } else {
                throw new \Exception('No file was uploaded.');
            }

            $extension = $file->getClientOriginalExtension();
            $fileName = time().'_'.Str::random(10).'.'.$extension;
            $path = $file->storeAs('resources', $fileName, 'public');

            if ($path) {
                $resource->file_path = $fileName;
                $resource->file_extension = $extension;

                if (Str::startsWith($file->getMimeType(), 'video/')) {
                    $thumbnailName = $this->generateVideoThumbnail($fileName);
                    $resource->resource_image_path = $thumbnailName;
                } elseif (Str::startsWith($file->getMimeType(), 'image/')) {
                    $resource->resource_image_path = $fileName;
                    Storage::disk('public')->copy('resources/'.$fileName, 'resource_images/'.$fileName);
                }
            } else {
                throw new \Exception('Failed to store the file.');
            }

            // Handle resource image based on image source
            if ($validated['image-source'] === 'default') {
                $resource->default_icon = $validated['default_icon'];
            } elseif ($validated['image-source'] === 'upload' || $validated['image-source'] === 'ai') {
                if ($request->hasFile('resource_image')) {
                    $image = $request->file('resource_image');
                    $extension = $image->getClientOriginalExtension();
                    $imageName = time().'_'.Str::random(10).'.'.$extension;
                    $image->storeAs('resource_images', $imageName, 'public');
                    $resource->resource_image_path = $imageName;
                }
            }

            $resource->save();

            // Handle tags
            if (isset($validated['tags'])) {
                $tags = json_decode($validated['tags']);
                $tagIds = [];
                foreach ($tags as $tagName) {
                    $tag = Tag::firstOrCreate(
                        ['name' => $tagName, 'user_id' => Auth::id()],
                        ['user_id' => Auth::id()]
                    );
                    $tagIds[] = $tag->id;
                }
                $resource->tags()->sync($tagIds);
            }

            $activity = new Activity;
            $activity->user_id = auth()->id();
            $activity->resource_id = $resource->id;
            $activity->action = 'create';
            $activity->save();

            // Check if the request is an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Resource created successfully',
                    'redirect' => route('dashboard'),
                ]);
            }

            // If it's a regular form submission, redirect to dashboard
            return redirect()->route('dashboard')->with('success', 'Resource created successfully');
        } catch (\Exception $e) {
            // If an error occurs
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: '.$e->getMessage(),
                ], 422);
            }

            // If it's a regular form submission, redirect back with error
            return redirect()->back()->with('error', 'An error occurred: '.$e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function viewPrivate($token)
    {
        Log::info('Accessing private resource with token:', ['token' => $token]);

        // Retrieve the ShareLink using the token and ensure it's not expired
        $shareLink = ShareLink::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (! $shareLink) {
            Log::warning('Invalid or expired token:', ['token' => $token]);
            abort(404, 'Resource not found.');
        }

        // Retrieve the associated resource
        $resource = $shareLink->resource;

        if (! $resource) {
            Log::warning('Resource not found for token:', ['token' => $token]);
            abort(404, 'Resource not found.');
        }

        // Define variables for the Blade view
        $canEdit = false; // Guests cannot edit
        $tags = $resource->tags;
        $isPrivateAccess = true;

        Log::info('Displaying resource via private link:', ['resource_id' => $resource->id]);

        // Pass variables to the Blade view
        return view('resources.show', compact('resource', 'canEdit', 'tags', 'isPrivateAccess'));
    }

    public function generatePrivateLink(Request $request, Resource $resource)
    {
        // Validate the request if necessary
        $request->validate([
            // Add validation rules if needed
        ]);

        // Generate a unique token
        $token = Str::random(64);

        // Create a ShareLink entry
        ShareLink::create([
            'resource_id' => $resource->id,
            'token' => $token,
            'expires_at' => now()->addDays(7), // Adjust expiration as needed
        ]);

        // Generate the private link URL
        $privateLink = route('resources.view-private', ['token' => $token]);

        // Optionally, log the creation
        Log::info('Generated private link:', ['link' => $privateLink]);

        // Return the private link (modify as per your requirements)
        return response()->json(['private_link' => $privateLink]);
    }

    public function show(Resource $resource)
    {
        $viewerUrl = route('resources.view', $resource);
        $canEdit = auth()->check() && auth()->user()->id === $resource->user_id;
        $categories = $canEdit ? Category::where('user_id', Auth::id())->get() : null;
        $tags = $resource->tags;
        $isPrivateAccess = false; // Standard access

        $comments = $resource->comments()->whereNull('parent_id')->with('replies')->get();
        $usePSPDFKit = in_array($resource->file_extension, ['pdf', 'docx', 'xlsx', 'pptx', 'xls']);

        if ($resource->user_id === Auth::id()) {
            return view('resources.show', compact('resource', 'viewerUrl', 'canEdit', 'categories', 'tags', 'comments', 'usePSPDFKit', 'isPrivateAccess'));
        } else {
            return view('resources.show', compact('resource', 'viewerUrl', 'canEdit', 'categories', 'tags', 'comments', 'usePSPDFKit', 'isPrivateAccess'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Resource $resource)
    // {
    //     $categories = Category::all();
    //     $tags = Tag::all();
    //     return view('resources.edit', compact('resource', 'categories', 'tags'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function saveDocument(Request $request, Resource $resource)
    {
        $request->validate([
            'document' => 'required|file|max:10240', // 10MB max
            'file_type' => 'required|string|in:pdf,docx,xlsx,pptx',
        ]);

        if ($request->file('document')->isValid()) {
            $file = $request->file('document');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('resources', $filename, 'public');

            if ($path) {
                // Update the resource's file path and extension
                $resource->file_path = $filename;
                $resource->file_extension = $extension;
                $resource->save();

                // Clear any caches that might be storing the old version
                Cache::forget('resource_'.$resource->id);

                return response()->json(['message' => 'Document saved successfully']);
            }
        }

        return response()->json(['message' => 'Failed to save document'], 500);
    }

    public function instantSave(Request $request, Resource $resource)
    {
        // $this->authorize('update', $resource);

        $request->validate([
            'operations' => 'required|array',
        ]);

        // Here you would process the operations and apply them to the PDF
        // This is a placeholder - you'll need to implement the actual saving logic
        // using a server-side PDF library that can apply these operations

        return response()->json(['message' => 'Changes saved successfully']);
    }

    public function update(Request $request, Resource $resource)
    {
        $user = Auth::user();
        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:document,image,video,audio',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'image_source' => 'required|in:default,ai,upload',
            'default_icon' => 'nullable|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,txt|max:2048',
            'resource_image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30048',
            'visibility' => 'sometimes|boolean',
            'ai_generated_image' => 'nullable',

        ]);

        $sanitizedDescription = Purifier::clean($request->input('description'), 'default');

        // Update resource fields
        $resource->title = $request->title;
        $resource->description = Purifier::clean($request->description);
        $resource->type = $request->type;
        $resource->category_id = $request->category_id;
        $resource->is_public = $request->has('visibility');
        $resource->approval_status = $request->has('visibility') ? 'pending' : 'approved';
        try {
            if ($request->image_source === 'default') {
                // Handle default icon (existing code)
                $resource->resource_image_path = null;
                $resource->default_icon = $request->input('default_icon');
            } elseif ($request->image_source === 'ai' || $request->image_source === 'upload') {
                Log::info('Image source: '.$request->image_source);

                if ($request->hasFile('resource_image_path')) {
                    Log::info('Uploaded image received');
                    // Handle uploaded image
                    $uploadedFile = $request->file('resource_image_path');
                    $filename = time().'_'.$uploadedFile->getClientOriginalName();
                    $path = $uploadedFile->storeAs('public/resource_images', $filename);
                    $resource->resource_image_path = $filename;
                    Log::info('New uploaded image saved', ['path' => $resource->resource_image_path]);
                } elseif ($request->filled('ai_generated_image')) {
                    Log::info('AI generated image data received');
                    $aiImageData = $request->input('ai_generated_image');

                    // Check if the data is a valid base64 encoded image
                    if (preg_match('/^data:image\/(\w+);base64,/', $aiImageData, $type)) {
                        $aiImageData = substr($aiImageData, strpos($aiImageData, ',') + 1);
                        $type = strtolower($type[1]); // jpg, png, gif

                        if (! in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                            throw new \Exception('Invalid image type');
                        }

                        $aiImageData = base64_decode($aiImageData);

                        if ($aiImageData === false) {
                            throw new \Exception('Failed to decode base64 image data');
                        }
                    } else {
                        throw new \Exception('Invalid base64 image data');
                    }

                    $aiImageName = 'ai_'.time().'.'.$type;
                    $saved = Storage::disk('public')->put('resource_images/'.$aiImageName, $aiImageData);

                    if (! $saved) {
                        throw new \Exception('Failed to save AI generated image');
                    }

                    $resource->resource_image_path = $aiImageName;
                    Log::info('New AI-generated image saved', ['path' => $resource->resource_image_path]);
                } else {
                    Log::info('No new resource image uploaded or generated');
                }

                // Clear default icon
            }

            // Handle File Upload
            if ($request->hasFile('file_path')) {
                // Delete old file if exists
                if ($resource->file_path && Storage::exists('public/resources/'.$resource->file_path)) {
                    Storage::delete('public/resources/'.$resource->file_path);
                }

                // Store new file
                $filePath = $request->file('file_path')->store('public/resources');
                $resource->file_path = basename($filePath);
                $resource->file_extension = $request->file('file_path')->getClientOriginalExtension();

                // Log the new file path
                Log::info('New file uploaded', ['path' => $resource->file_path]);
            } else {
                Log::info('No new file uploaded');
            }

            // Sync tags
            if ($request->has('tags')) {
                $resource->tags()->sync($request->tags);
            } else {
                $resource->tags()->detach();
            }

            $resource->save();
            Activity::create([
                'user_id' => Auth::id(),
                'resource_id' => $resource->id,
                'action' => 'update',
            ]);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Resource updated successfully.']);
            }

            return redirect()->route('resources.show', $resource)->with('success', 'Resource updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating resource', ['error' => $e->getMessage()]);
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'An error occurred: '.$e->getMessage()], 422);
            }

            return redirect()->back()->with('error', 'An error occurred: '.$e->getMessage())->withInput();
        }
    }

    /**
     * Placeholder method for AI image generation.
     * Replace this with actual implementation. */
    // private function generateAIImage($prompt)
    //     {
    //         $apiUrl = "https://api-inference.huggingface.co/models/black-forest-labs/FLUX.1-schnell";
    //         $apiKey = "YOUR_HF_TOKEN";

    //         $response = Http::withHeaders([
    //             'Authorization' => "Bearer $apiKey",
    //         ])->post($apiUrl, [
    //             'inputs' => $prompt,
    //         ]);

    //         if ($response->successful()) {
    //             $data = $response->json();
    //             // Assuming the API returns a URL to the generated image
    //             return $data['generated_image_url'] ?? null;
    //         }

    //         Log::error('Failed to generate AI image', ['response' => $response->body()]);
    //         return null;
    //     }

    public function removeFile(Resource $resource)
    {
        if (Auth::id() !== $resource->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Remove the file from storage
        Storage::disk('public')->delete($resource->file_path);

        // Update the resource record
        $resource->update(['file_path' => null]);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     *
     * @return RedirectResponse
     */
    public function destroy(Resource $resource)
    {
        Log::info(message: 'Destroy method called for Resource ID: '.$resource->id);

        // Existing ownership check and deletion
        if ($resource->user_id !== Auth::id()) {
            Log::warning('Unauthorized delete attempt by User ID: '.Auth::id());

            return redirect()->route('resources.manage')->with('error', 'Unauthorized action.');
        }
        $activity = new Activity;
        $activity->user_id = auth()->id();
        $activity->resource_id = $resource->id;
        $activity->action = 'delete';
        $activity->save();
        $resource->delete();

        Log::info('Resource ID: '.$resource->id.' soft deleted by User ID: '.Auth::id());

        return redirect()->route('resources.manage')->with('success', 'Resource moved to trash successfully!');
    }

    public function download(Resource $resource)
    {
        // Check if the user is authorized to download the resource
        if (Auth::id() !== $resource->user_id && ! $resource->is_public) {
            abort(403, 'Unauthorized action.');
        }

        $filePath = storage_path('app/public/resources/'.$resource->file_path);

        if (! file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $fileName = Str::slug($resource->title).'.'.$extension;

        return response()->download($filePath, $fileName);
    }

    public function viewWithPSPDFKit(Resource $resource)
    {
        // $this->authorize('view', $resource);
        $viewerUrl = route('resources.view', $resource);
        $canEdit = Gate::allows('update', $resource);

        return view('resources.view-pspdfkit', compact('resource', 'viewerUrl', 'canEdit'));
    }

    public function view(Resource $resource)
    {
        Log::info('View method called', ['resource_id' => $resource->id]);

        $filePath = storage_path('app/public/resources/'.$resource->file_path);
        Log::info('File path', ['path' => $filePath]);

        if (! file_exists($filePath)) {
            Log::error('File not found', ['file_path' => $filePath]);
            abort(404);
        }

        $mimeType = mime_content_type($filePath);
        Log::info('MIME type', ['type' => $mimeType]);

        $headers = [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="'.basename($filePath).'"',
        ];

        // For Office documents, we might want to convert them to PDF for viewing
        if (in_array($resource->file_extension, ['docx', 'xlsx', 'pptx'])) {
            // Here you would implement the conversion logic
            // For now, we'll just return the file as-is
            Log::info('Office document detected, conversion not implemented');
        }

        Log::info('Returning file response');

        return response()->file($filePath, $headers);
    }

    public function edit(Resource $resource)
    {
        if (Auth::id() !== $resource->user_id) {
            abort(403);
        }

        $categories = Category::where('user_id', Auth::id())->get();
        $tags = Tag::where('user_id', Auth::id())->get();

        return view('resources.edit', compact('resource', 'categories', 'tags'));
    }

    // Add these methods to the ResourceController class

    public function like(Resource $resource)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $liked = $resource->likes()->toggle($user->id);
            $likesCount = $resource->likes()->count();

            if ($liked['attached']) {
                // Manually create a notification
                Notification::create([
                    'user_id' => $resource->user_id, // Recipient user ID
                    'type' => 'like',
                    'data' => [
                        'message' => "{$user->name} liked your resource: {$resource->title}",
                        'sender_id' => $user->id, // ID of the user who liked
                    ],
                    'notifiable_id' => $resource->user_id, // Recipient user ID
                    'notifiable_type' => User::class, // Recipient model
                ]);
            }

            // Log Activity
            Activity::create([
                'user_id' => $user->id,
                'resource_id' => $resource->id,
                'action' => 'like',
            ]);

            return response()->json([
                'success' => true,
                'liked' => $liked['attached'],
                'likes_count' => $likesCount,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in liking resource: '.$e->getMessage());

            return response()->json(['error' => 'Failed to like the resource'], 500);
        }
    }

    public function comment(Request $request, Resource $resource)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $resource->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $this->createNotification($resource->user, 'comment', Auth::user()->name.' commented on your resource: '.$resource->title);

        return response()->json([
            'success' => true,
            'user_id' => $comment->user_id,
            'user_name' => $comment->user->name,
            'content' => $comment->content,
        ]);
    }

    private function createNotification($user, $type, $message)
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'data' => [
                'message' => $message,
            ],
            'notifiable_id' => $user->id,
            'notifiable_type' => User::class,
            // 'read_at' => null, // No need to set explicitly as it's nullable by default
        ]);
    }

    public function share(Request $request, Resource $resource)
    {
        // Generate a unique token
        $token = Str::random(64);

        // Create a ShareLink entry
        ShareLink::create([
            'resource_id' => $resource->id,
            'token' => $token,
            'expires_at' => now()->addDays(7), // Set expiration as needed
        ]);

        // Generate the private link URL
        $privateLink = route('resources.view-private', ['token' => $token]);

        // Return the private link (adjust as per your application's response handling)
        return response()->json(['private_link' => $privateLink]);
    }

    public function showShared($token)
    {
        $shareLink = ShareLink::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $resource = $shareLink->resource;
        $canEdit = false; // Typically, shared links do not grant edit permissions
        $tags = $resource->tags;
        $isPrivateAccess = true;

        return view('resources.show', compact('resource', 'canEdit', 'tags', 'isPrivateAccess'));
    }

    public function preview(Resource $resource)
    {
        $canEdit = Auth::check() && (Auth::id() === $resource->user_id || Auth::user()->usertype === 'admin');
        $tags = $resource->tags;
        $isPrivateAccess = false; // Public preview

        return view('resources.show', compact('resource', 'canEdit', 'tags', 'isPrivateAccess'));
    }

    public function editComment(Request $request, $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if (Auth::id() !== $comment->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update(['content' => $request->content]);

        return response()->json([
            'success' => true,
            'content' => $comment->content,
        ]);
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if (Auth::id() !== $comment->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }

    public function replyToComment(Request $request, $commentId)
    {
        $parentComment = Comment::findOrFail($commentId);

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $reply = Comment::create([
            'user_id' => Auth::id(),
            'resource_id' => $parentComment->resource_id,
            'content' => $request->content,
            'parent_id' => $parentComment->id,
        ]);

        $activity = new Activity;
        $activity->user_id = auth()->id();
        $activity->resource_id = $parentComment->resource_id;
        $activity->action = 'reply';
        $activity->save();

        return response()->json([
            'success' => true,
            'user_id' => $reply->user_id,
            'user_name' => $reply->user->name,
            'content' => $reply->content,
            'parent_id' => $reply->parent_id,
        ]);
    }
}
