<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearCacheCommand extends Command
{
    protected $signature = 'komun:clear-cache';

    protected $description = 'Limpia todas las cachés de Laravel de forma segura';

    public function handle()
    {
        $this->info('Limpiando cachés...');

        $this->callSilent('cache:clear');
        $this->callSilent('config:clear');
        $this->callSilent('route:clear');
        $this->callSilent('view:clear');
        $this->callSilent('event:clear');

        $this->info('✅ Cachés limpiadas correctamente.');

        return 0;
    }
}
