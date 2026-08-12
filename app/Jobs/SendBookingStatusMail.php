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
use Illuminate\Support\Facades\Mail;

class SendBookingStatusMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
}
