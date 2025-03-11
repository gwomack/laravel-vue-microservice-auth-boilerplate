<?php

namespace Tests\Feature\Jobs;

use Tests\TestCase;
use App\Models\User;
use App\Jobs\UserRegisteredJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegisteredJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_is_dispatched_on_user_registration(): void
    {
        Queue::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        Queue::assertPushed(UserRegisteredJob::class, function ($job) {
            return $job->user->email === 'test@example.com';
        });
    }

    public function test_job_contains_correct_user_data(): void
    {
        $user = User::factory()->create();
        $job = new UserRegisteredJob($user);

        $this->assertEquals($user->id, $job->user->id);
        $this->assertEquals($user->email, $job->user->email);
        $this->assertEquals($user->name, $job->user->name);
    }
}
