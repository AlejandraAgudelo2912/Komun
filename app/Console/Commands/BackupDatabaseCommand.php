<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'komun:backup-db';

    protected $description = 'Exporta todas las tablas de la base de datos a archivos JSON';

    public function handle()
    {
        $this->info('Exportando tablas a JSON...');

        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $keyName = 'Tables_in_'.$dbName;

        if (! Storage::exists('backups/json')) {
            Storage::makeDirectory('backups/json');
        }

        foreach ($tables as $table) {
            $tableName = $table->$keyName;
            $this->info("Exportando tabla: $tableName");

            $data = DB::table($tableName)->get();
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            Storage::put("backups/json/{$tableName}.json", $json);
        }

        $this->info('Exportación completada. Archivos guardados en: storage/app/backups/json');
    }
}
