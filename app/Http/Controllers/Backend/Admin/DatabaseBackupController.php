<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class DatabaseBackupController extends Controller
{
    public function backup()
    {
        try {
            $fileName = env('DB_DATABASE') . '_' . Carbon::now()->getTimestamp() . '.sql';
            Artisan::call('database:backup', ['fileName' => $fileName]);
            $pathToFile = storage_path('backups/' . $fileName);

            // Check if file exists
            if (!file_exists($pathToFile)) {
                Log::error('Backup file not found at: ' . $pathToFile);
                return back()->with('error', 'Backup file was not created');
            }

            // Check if file is empty
            if (filesize($pathToFile) === 0) {
                Log::error('Backup file is empty: ' . $pathToFile);
                return back()->with('error', 'Backup file is empty');
            }

            // Set headers for file download
            $headers = [
                'Content-Description' => 'File Transfer',
                'Content-Type' => 'application/sql',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Content-Length' => filesize($pathToFile),
            ];

            // Download and delete the file
            return response()->download($pathToFile, $fileName, $headers)->deleteFileAfterSend(true);

        } catch (Exception $e) {
            Log::error('Backup failed: ' . $e->getMessage());
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }
}
