<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:ping-health')]
#[Description('Scheduler proof-of-life: logs a heartbeat every run')]
class PingHealth extends Command
{
    public function handle(): void
    {
        $this->info('['.now()->toIso8601String().'] scheduler alive');
        \Log::info('scheduler.ping', ['at' => now()->toIso8601String()]);
    }
}
