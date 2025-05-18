<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class DatabaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup {fileName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create A Backup file of the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getFilePath($filename)
    {
        return storage_path() . '/backups/' . $filename;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Database s Command');
        $filename = $this->argument('fileName');

        $filePath = $this->getFilePath($filename);

        // Create backup directory if it doesn't exist
        if (!file_exists(storage_path('backups'))) {
            mkdir(storage_path('backups'), 0755, true);
        }

        // Create mysqldump command with proper escaping
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > "%s"',
            escapeshellarg(env('DB_USERNAME')),
            escapeshellarg(env('DB_PASSWORD')),
            escapeshellarg(env('DB_HOST')),
            escapeshellarg(env('DB_DATABASE')),
            $filePath
        );

        Log::info('Executing command: ' . $command);

        $returnVar = null;
        $output = null;

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            Log::error('Backup failed with return code: ' . $returnVar);
            Log::error('Command output: ' . implode("\n", $output));
            throw new \Exception('Backup failed with return code: ' . $returnVar);
        }

        $this->info('Backup completed successfully: ' . $filePath);
        return 0;
    }
}