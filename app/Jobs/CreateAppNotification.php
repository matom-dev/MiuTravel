<?php

namespace App\Jobs;

use App\Models\AppNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateAppNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [10, 60, 300];

    public int $timeout = 20;

    public bool $failOnTimeout = true;

    public function __construct(public array $attributes)
    {
    }

    public function handle(): void
    {
        AppNotification::create($this->attributes);
    }

    public function failed(Throwable $exception): void
    {
        Log::warning('Create app notification job failed permanently.', [
            'receiver_guard' => $this->attributes['receiver_guard'] ?? null,
            'receiver_id' => $this->attributes['receiver_id'] ?? null,
            'type' => $this->attributes['type'] ?? null,
            'book_tour_id' => data_get($this->attributes, 'data.book_tour_id'),
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
        ]);
    }
}
