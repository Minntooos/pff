<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

// redirect on the / to the dashboard admin or user
// Add these routes near the bottom of the file, before the closing bracket
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/', [WelcomeController::class, 'index']);

Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create')->middleware('auth');
Route::get('/resources/manage', [ResourceController::class, 'manage'])->name('resources.manage')->middleware('auth');
Route::get('/resources/recent-activity', [ResourceController::class, 'recentActivity'])->name('resources.recent-activity')->middleware('auth');

Route::get('/resources/private/{token}', [ResourceController::class, 'viewPrivate'])->name('resources.view-private');
Route::post('/resources/{resource}/generate-private-link', action: [ResourceController::class, 'generatePrivateLink'])->name('resources.generate-private-link');

Route::resource('resources', ResourceController::class)->middleware('auth');

Route::resource('resources', ResourceController::class)->middleware('auth');

Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
Route::get('/resources/{resource}/restore', [ResourceController::class, 'restore'])->name('resources.restore')->middleware('auth');

Route::get('/resources/{resource}/view', [ResourceController::class, 'view'])->name('resources.view');

Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show')->middleware('public.resource');

Route::post('/sections/create', [DashboardController::class, 'createSection'])->name('sections.create');
Route::delete('/sections/{section}', [DashboardController::class, 'deleteSection'])->name('sections.delete');

Route::get('/resources/{resource}/pspdfkit', [ResourceController::class, 'viewWithPSPDFKit'])->name('resources.pspdfkit');
Route::post('/resources/{resource}/instant-save', [ResourceController::class, 'instantSave'])->name('resources.instant-save');
Route::post('/resources/{resource}/save-pdf', [ResourceController::class, 'savePdf'])->name('resources.save-pdf')->middleware('auth');
Route::post('/resources/{resource}/remove-file', [ResourceController::class, 'removeFile'])->name('resources.remove-file');
Route::get('/resources/{resource}/download', [ResourceController::class, 'download'])->name('resources.download')->middleware('auth');
Route::resource('categories', CategoryController::class)
    ->middleware('auth');

Route::resource('tags', TagController::class)
    ->middleware('auth');

// Favorites Routes
// Trash Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
    Route::post('/trash/{id}/restore', [TrashController::class, 'restore'])->name('trash.restore');
    Route::delete('/trash/{id}', [TrashController::class, 'forceDelete'])->name('trash.forceDelete');
});
Route::post('/generate-prompt', [PromptController::class, 'generatePrompt'])->name('generate.prompt');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update.picture');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

// documents.filter
Route::post('/upload-document', [DashboardController::class, 'uploadDocument'])->name('upload.document');
Route::post('/upload-file', [ResourceController::class, 'uploadFile'])->middleware(['auth'])->name('upload.file');

Route::get('/documents/filter', [DashboardController::class, 'filterDocuments'])
    ->middleware(['auth'])
    ->name('documents.filter');
Route::get('/dashboard/fetch-resources', [DashboardController::class, 'fetchResources'])->name('dashboard.fetch-resources');

// documents.upload
Route::get('/documents/upload', [ResourceController::class, 'create'])
    ->middleware(['auth'])
    ->name('documents.upload');
require __DIR__.'/auth.php';

// Admin routes

// Admin user management routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/manage-users', [AdminController::class, 'manageUsers'])->name('admin.manage-users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.destroy');
    Route::get('/pending-resources', [AdminController::class, 'pendingResources'])->name('admin.pending-resources');
    Route::post('/resources/{resource}/approve', [AdminController::class, 'approveResource'])->name('admin.approve-resource');
    Route::post('/resources/{resource}/reject', [AdminController::class, 'rejectResource'])->name('admin.reject-resource');
    Route::get('/admin/recent-activity', [AdminController::class, 'recentActivity'])->name('admin.recent-activity');
    Route::get('/admin/recent-activity/{activity}', [AdminController::class, 'activityDetails'])->name('admin.activity-details');
    Route::get('/admin/users/activity', [AdminController::class, 'usersActivity'])->name('admin.users.activity');
    Route::get('/admin/users/activity/download', [AdminController::class, 'downloadUserActivityReport'])->name('admin.users.activity.download');
    // Route::get('/reports', [ReportsController::class, 'index'])->name('admin.reports');
    Route::post('/reports/generate', [ReportsController::class, 'generate'])->name('admin.reports.generate');
    Route::get('/reports/preview/{type}', [ReportsController::class, 'preview'])->name('admin.reports.preview');
    Route::get('/reports/download/{type}', [ReportsController::class, 'download'])->name('admin.reports.download');
    Route::get('/admin/reports', [ReportsController::class, 'index'])->name('admin.reports');
    // Route::get('/reports', [ReportsController::class, 'index'])->name('admin.reports');
    Route::post('/reports/generate', [ReportsController::class, 'generate'])->name('admin.reports.generate');
    Route::get('/reports/preview/{type}', [ReportsController::class, 'preview'])->name('admin.reports.preview');
    Route::get('/reports/download/{type}', [ReportsController::class, 'download'])->name('admin.reports.download');
    Route::get('/admin/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::post('/admin/users/{user}/change-password', [AdminController::class, 'changePassword'])->name('admin.users.change-password');
});

Route::post('/resources/{resource}/like', [ResourceController::class, 'like'])->name('resources.like');
Route::post('/resources/{resource}/comment', [ResourceController::class, 'comment'])->name('resources.comment');
Route::post('/resources/{resource}/share', [ResourceController::class, 'share'])->name('resources.share');
Route::get('/shared/{token}', [ResourceController::class, 'showShared'])->name('resources.shared');

Route::post('/resources/{resource}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

// Route::post('/notifications/mark-as-read', [DashboardController::class, 'markNotificationsAsRead'])->name('notifications.markAsRead');
// Add these lines near the top of the file

// Social Login Routes
Route::get('auth/{provider}', [SocialController::class, 'redirect'])->name('social.redirect');
Route::get('auth/{provider}/callback', [SocialController::class, 'callback'])->name('social.callback');
