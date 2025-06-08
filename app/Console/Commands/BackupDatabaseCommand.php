<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'komun:backup-db';

    protected $description = 'Export database tables to JSON files';

    public function handle()
    {
        $this->info('Exporting tables...');

        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $keyName = 'Tables_in_'.$dbName;

        if (! Storage::exists('backups/json')) {
            Storage::makeDirectory('backups/json');
        }

        foreach ($tables as $table) {
            $tableName = $table->$keyName;
            $this->info("Exporting table: $tableName");

            $data = DB::table($tableName)->get();
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            Storage::put("backups/json/{$tableName}.json", $json);
        }

        $this->info('Tables exported to: storage/app/backups/json');
    }
}
