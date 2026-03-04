<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class UpdateBookingStatus extends Command
{
    protected $signature = 'booking:update-status';
    protected $description = 'Update booking status from 0 to 2 after 1 minute';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get all bookings where status is 0 and created more than 1 minute ago
        Booking::where('Booking_status', 0)
            ->where('created_at', '<=', Carbon::now()->subMinute())
            ->update(['Booking_status' => 2]);

        $this->info('Booking statuses updated successfully.');
    }
}
