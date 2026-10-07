<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalResources = Resource::count();
        $activeUsers = User::where('last_active_at', '>=', now()->subMonth())->count();
        $serverLoad = $this->getServerLoad();
        $pendingResourcesCount = Resource::pending()->count();
        $recentActivity = Activity::whereHas('resource', function ($query) {
            $query->where('approval_status', 'pending');
        })->with('user')->latest()->take(5)->get();
        $resourceDistribution = Resource::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalResources',
            'activeUsers',
            'serverLoad',
            'recentActivity',
            'resourceDistribution',
            'pendingResourcesCount'
        ));
    }

    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:8',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['success' => true]);
    }

    public function downloadUserActivityReport()
    {
        $data = $this->getUserActivityData();
        $pdf = app('dompdf.wrapper')->loadView('admin.pdf.user_activity_report', $data);

        return $pdf->download('user_activity_report.pdf');
    }

    public function showUser(User $user)
    {
        $activities = $user->activities()->with('resource')->latest()->paginate(10);
        $totalActivities = $user->activities()->count();
        $totalResources = $user->resources()->count();
        $lastActivity = $user->activities()->latest()->first();

        return view('admin.users.show', compact('user', 'activities', 'totalActivities', 'totalResources', 'lastActivity'));
    }

    private function getUserActivityData()
    {
        $totalActiveUsers = User::where('last_active_at', '>=', now()->subDays(30))->count();
        $averageDailySessions = Activity::where('created_at', '>=', now()->subDays(30))
            ->groupBy('user_id')
            ->selectRaw('user_id, COUNT(DISTINCT DATE(created_at)) as session_count')
            ->get()
            ->avg('session_count');
        $resourceInteractions = Activity::where('created_at', '>=', now()->subDays(30))->count();
        $newUserRegistrations = User::where('created_at', '>=', now()->subDays(30))->count();

        $activityOverTime = Activity::where('created_at', '>=', now()->subDay())
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour');

        $mostActiveUsers = User::withCount(['activities' => function ($query) {
            $query->where('created_at', '>=', now()->subDays(30));
        }])
            ->orderByDesc('activities_count')
            ->take(5)
            ->get();

        $detailedUserActivity = User::withCount('activities')
            ->withMax('activities as last_active', 'created_at')
            ->orderByDesc('activities_count')
            ->take(10)
            ->get();

        return compact(
            'totalActiveUsers',
            'averageDailySessions',
            'resourceInteractions',
            'newUserRegistrations',
            'activityOverTime',
            'mostActiveUsers',
            'detailedUserActivity'
        );
    }

    public function usersActivity()
    {
        $totalActiveUsers = User::where('last_active_at', '>=', now()->subDays(30))->count();
        $averageDailySessions = Activity::where('created_at', '>=', now()->subDays(30))
            ->groupBy('user_id')
            ->selectRaw('user_id, COUNT(DISTINCT DATE(created_at)) as session_count')
            ->get()
            ->avg('session_count');
        $resourceInteractions = Activity::where('created_at', '>=', now()->subDays(30))->count();
        $newUserRegistrations = User::where('created_at', '>=', now()->subDays(30))->count();

        $activityOverTime = Activity::where('created_at', '>=', now()->subDay())
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour');

        $mostActiveUsers = User::withCount(['activities' => function ($query) {
            $query->where('created_at', '>=', now()->subDays(30));
        }])
            ->orderByDesc('activities_count')
            ->take(5)
            ->get();

        $detailedUserActivity = User::withCount('activities')
            ->withMax('activities as last_active', 'created_at')
            ->orderByDesc('activities_count')
            ->take(10)
            ->get();

        return view('admin.users_activity', compact(
            'totalActiveUsers',
            'averageDailySessions',
            'resourceInteractions',
            'newUserRegistrations',
            'activityOverTime',
            'mostActiveUsers',
            'detailedUserActivity'
        ));
    }

    public function recentActivity(Request $request)
    {
        $query = Activity::with('resource', 'user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('action', 'like', "%{$request->search}%")
                    ->orWhereHas('resource', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%");
                    });
            })
            ->when($request->filled('action'), function ($query) use ($request) {
                $query->where('action', $request->action);
            })
            ->when($request->filled('resource_type'), function ($query) use ($request) {
                $query->whereHas('resource', function ($q) use ($request) {
                    $q->where('type', $request->resource_type);
                });
            })
            ->when($request->filled('date_range'), function ($query) use ($request) {
                $dates = explode(' to ', $request->date_range);
                $query->whereBetween('created_at', $dates);
            });

        $activities = $query->latest()->paginate(15);

        if ($request->ajax()) {
            return view('admin.partials.activity_items', compact('activities'));
        }

        $actionTypes = Activity::distinct('action')->pluck('action');
        $resourceTypes = Resource::distinct('type')->pluck('type');

        return view('admin.recent_activity', compact('activities', 'actionTypes', 'resourceTypes'));
    }

    public function activityDetails(Activity $activity)
    {
        return view('admin.activity_details', compact('activity'));
    }

    public function pendingResources()
    {
        $pendingResources = Resource::pending()->with('user')->paginate(10);

        return view('admin.pending_resources', compact('pendingResources'));
    }

    public function manageUsers(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by usertype
        if ($request->has('usertype') && $request->usertype != 'all') {
            $query->where('usertype', $request->usertype);
        }

        // Sort functionality
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $users = $query->paginate(10)->appends($request->query());

        return view('admin.manage_users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'usertype' => 'required|in:user,admin',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'usertype' => $validatedData['usertype'],
            ]);

            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $user->profile_picture = $path;
                $user->save();
            }

            return redirect()->route('admin.manage-users')->with('success', 'User created successfully. User ID: '.$user->id);
        } catch (\Exception $e) {
            Log::error('Error creating user: '.$e->getMessage());

            return back()->withInput()->with('error', 'Failed to create user. Error: '.$e->getMessage());
        }
    }

    public function editUser(User $user)
    {
        return view('admin.edit_user', compact('user'));
    }

    public function createUser()
    {
        return view('admin.create_user');
    }

    public function updateUser(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'usertype' => ['required', Rule::in(['user', 'admin'])],
            'profile_picture' => 'nullable|image|max:1024', // max 1MB
        ]);

        $user->fill($validatedData);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return redirect()->route('admin.manage-users')->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        return redirect()->route('admin.manage-users')->with('success', 'User deleted successfully.');
    }

    public function approveResource(Resource $resource)
    {
        $resource->approval_status = 'approved';
        $resource->save();

        $activity = new Activity;
        $activity->user_id = auth()->id();
        $activity->resource_id = $resource->id;
        $activity->action = 'approve';
        $activity->save();

        return response()->json(['success' => true, 'message' => 'Resource approved successfully.']);
    }

    public function rejectResource(Resource $resource)
    {
        $resource->approval_status = 'rejected';
        $resource->is_public = false;
        $resource->save();

        $activity = new Activity;
        $activity->user_id = auth()->id();
        $activity->resource_id = $resource->id;
        $activity->action = 'reject';
        $activity->save();

        return response()->json(['success' => true, 'message' => 'Resource rejected successfully.']);
    }

    private function getServerLoad()
    {
        // Implement server load calculation logic here
        return 75; // Placeholder value
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
