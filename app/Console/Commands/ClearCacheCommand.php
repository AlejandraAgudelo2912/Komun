<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearCacheCommand extends Command
{
    protected $signature = 'komun:clear-cache';

    protected $description = 'Clean application caches';

    public function handle()
    {
        $this->info('Cleaning caches...');

        $this->callSilent('cache:clear');
        $this->callSilent('config:clear');
        $this->callSilent('route:clear');
        $this->callSilent('view:clear');
        $this->callSilent('event:clear');

        $this->info('Caches cleared!');

        return 0;
    }
}
