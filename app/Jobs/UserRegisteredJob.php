<?php

namespace App\Jobs;

use Log;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UserRegisteredJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Convert user data to JSON with just the ID
        $data = json_encode([
            'user_id' => $this->user->id
        ]);

        // Log the JSON data being sent to RabbitMQ
        isnotprod() &&
        Log::info('Sending user data to RabbitMQ:', ['data' => $data]);
    }
}
