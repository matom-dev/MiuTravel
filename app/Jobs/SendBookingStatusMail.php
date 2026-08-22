<?php

namespace App\Jobs;

use App\Models\BookTour;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendBookingStatusMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public int $timeout = 30;

    public bool $failOnTimeout = true;

    public function __construct(
        public int $userId,
        public int $bookTourId,
        public int $tourId,
        public array $mail
    ) {
    }

    public function handle(): void
    {
        $user = User::find($this->userId);
        $bookTour = BookTour::find($this->bookTourId);
        $tour = Tour::find($this->tourId);

        if (!$user || !$bookTour || !$tour || empty($this->mail['view']) || empty($this->mail['subject'])) {
            return;
        }

        $mailuser = $user->email;
        $mail = $this->mail;

        Mail::send($mail['view'], compact('user', 'bookTour', 'tour'), function ($email) use ($mailuser, $mail) {
            $email->subject($mail['subject']);
            $email->to($mailuser);
        });
    }

    public function failed(Throwable $exception): void
    {
        Log::warning('Booking status mail job failed permanently.', [
            'user_id' => $this->userId,
            'book_tour_id' => $this->bookTourId,
            'tour_id' => $this->tourId,
            'mail_view' => $this->mail['view'] ?? null,
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
        ]);
    }
}
