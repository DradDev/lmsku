<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$dbName = DB::getDatabaseName();
echo "Backing up database: {$dbName}\n";

$tables = DB::select('SHOW TABLES');
$prop = 'Tables_in_' . $dbName;

$backupFile = storage_path('app/db_backup_pre_3nf.json');
$data = [];

foreach ($tables as $t) {
    $tableName = $t->$prop;
    $rows = DB::table($tableName)->get();
    $data[$tableName] = $rows;
}

file_put_contents($backupFile, json_encode($data, JSON_PRETTY_PRINT));
echo "Database backup successfully saved to: {$backupFile} (" . count($data) . " tables backed up)\n";
