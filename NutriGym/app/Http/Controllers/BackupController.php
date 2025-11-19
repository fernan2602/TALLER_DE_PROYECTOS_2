<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function createBackup(Request $request)
    {
        try {
            $backupName = 'backup_' . Carbon::now()->format('Y-m-d_H-i-s');
            $backupPath = storage_path('app/backups/' . $backupName);
            
            // Crear directorio
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            // Backup de archivos
            $this->backupFiles($backupPath);
            
            // SIN compresión - solo copiamos los archivos
            $backupSize = $this->calculateFolderSize($backupPath);

            return response()->json([
                'success' => true,
                'message' => 'Backup creado exitosamente (sin compresión)',
                'backup_folder' => $backupName,
                'backup_path' => $backupPath,
                'backup_size' => $this->formatBytes($backupSize),
                'note' => 'Los archivos están en: storage/app/backups/' . $backupName
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear backup: ' . $e->getMessage()
            ], 500);
        }
    }

    private function backupFiles($backupPath)
    {
        $filesPath = $backupPath . '/files';
        File::makeDirectory($filesPath);

        // Archivos importantes
        $importantFiles = [
            '.env' => base_path('.env'),
            'composer.json' => base_path('composer.json'),
            'composer.lock' => base_path('composer.lock'),
            'package.json' => base_path('package.json'),
        ];

        foreach ($importantFiles as $name => $path) {
            if (File::exists($path)) {
                File::copy($path, $filesPath . '/' . $name);
            }
        }

        // Directorios importantes
        $importantDirs = [
            'app' => base_path('app'),
            'config' => base_path('config'),
            'database' => base_path('database'),
            'resources' => base_path('resources'),
            'routes' => base_path('routes'),
            'public' => base_path('public'),
        ];

        foreach ($importantDirs as $name => $path) {
            if (File::exists($path)) {
                File::copyDirectory($path, $filesPath . '/' . $name);
            }
        }
    }

    private function calculateFolderSize($path)
    {
        $size = 0;
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        
        return $size;
    }

    private function formatBytes($size, $precision = 2)
    {
        if ($size == 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($size, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}