<?php

use Illuminate\Support\Facades\Schedule;

// Scheduler proof: logs a heartbeat every 5 minutes.
// This proves the scheduler process is alive in prod.
Schedule::command('app:ping-health')->everyFiveMinutes();
