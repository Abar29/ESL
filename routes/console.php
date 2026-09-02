<?php

use App\Jobs\ReleaseExpiredHolds;
use App\Jobs\SendSessionReminders;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new ReleaseExpiredHolds)->everyFiveMinutes();
Schedule::job(new SendSessionReminders)->everyMinute();
