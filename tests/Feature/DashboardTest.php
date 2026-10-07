<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function createNotification(User $user, array $data = []): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'test',
            'data' => $data ?: ['message' => 'Your resource was liked.'],
            'notifiable_id' => $user->id,
            'notifiable_type' => User::class,
        ]);
    }

    public function test_dashboard_renders_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
    }

    public function test_dashboard_counts_unread_notifications(): void
    {
        $user = User::factory()->create();

        $this->createNotification($user, ['message' => 'You have a new resource.']);
        $this->createNotification($user, ['message' => 'You have a new resource.']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('2');
    }

    public function test_notifications_page_renders_messages(): void
    {
        $user = User::factory()->create();

        $this->createNotification($user, ['message' => 'Your report is ready.']);

        $response = $this->actingAs($user)->get('/notifications');

        $response->assertOk();
        $response->assertSee('Your report is ready.');
    }

    public function test_notification_can_be_marked_as_read(): void
    {
        $user = User::factory()->create();
        $notification = $this->createNotification($user);

        $this->actingAs($user)
            ->post("/notifications/{$notification->id}/mark-as-read")
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_all_notifications_can_be_marked_as_read(): void
    {
        $user = User::factory()->create();
        $this->createNotification($user);
        $this->createNotification($user);

        $this->actingAs($user)
            ->post('/notifications/mark-all-as-read')
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertSame(0, $user->notifications()->whereNull('read_at')->count());
    }

    public function test_users_cannot_mark_other_users_notifications_as_read(): void
    {
        $owner = User::factory()->create();
        $notification = $this->createNotification($owner);

        $this->actingAs(User::factory()->create())
            ->post("/notifications/{$notification->id}/mark-as-read")
            ->assertForbidden();

        $this->assertNull($notification->fresh()->read_at);
    }
}
