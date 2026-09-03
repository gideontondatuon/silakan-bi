<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--filename= : Custom filename for the backup SQL file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cadangkan database MySQL ke direktori database/backups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pencadangan database...');

        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if ($connection !== 'mysql') {
            $this->error("Koneksi '{$connection}' saat ini belum didukung untuk dump otomatis.");
            return self::FAILURE;
        }

        $host = $config['host'] ?? '127.0.0.1';
        $port = $config['port'] ?? '3306';
        $database = $config['database'];
        $username = $config['username'] ?? 'root';
        $password = $config['password'] ?? '';

        $mysqldumpPath = $this->findMysqldumpPath();
        if (!$mysqldumpPath) {
            $this->error('mysqldump executable tidak ditemukan di sistem. Pastikan MySQL/XAMPP/Laragon terpasang.');
            return self::FAILURE;
        }

        $backupDir = database_path('backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $customName = $this->option('filename');
        $filename = $customName 
            ? (str_ends_with($customName, '.sql') ? $customName : $customName . '.sql')
            : 'silakan_backup_' . date('Y-m-d_H-i-s') . '.sql';

        $targetPath = $backupDir . DIRECTORY_SEPARATOR . $filename;

        $cmd = [
            $mysqldumpPath,
            "--host={$host}",
            "--port={$port}",
            "--user={$username}",
        ];

        if (!empty($password)) {
            $cmd[] = "--password={$password}";
        }

        $cmd = array_merge($cmd, [
            '--routines',
            '--triggers',
            '--events',
            '--single-transaction',
            '--quick',
            $database,
            "--result-file={$targetPath}"
        ]);

        $this->comment("Menjalankan mysqldump untuk database: {$database}...");

        $process = new Process($cmd);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful() || !file_exists($targetPath)) {
            $this->error('Gagal mencadangkan database: ' . $process->getErrorOutput());
            return self::FAILURE;
        }

        $filesize = filesize($targetPath);
        $filesizeFormatted = $this->formatBytes($filesize);

        $this->info('✓ Database berhasil dicadangkan!');
        $this->table(
            ['Informasi', 'Detail'],
            [
                ['Database', $database],
                ['Lokasi File', $targetPath],
                ['Ukuran File', $filesizeFormatted],
                ['Waktu Selesai', date('Y-m-d H:i:s')],
            ]
        );

        return self::SUCCESS;
    }

    /**
     * Cari path mysqldump di environment Windows / Linux / macOS.
     */
    protected function findMysqldumpPath(): ?string
    {
        // 1. Cek dari PATH bawaan
        $checkCommand = (PHP_OS_FAMILY === 'Windows') ? 'where.exe mysqldump' : 'which mysqldump';
        $pathCheck = @shell_exec($checkCommand);
        if ($pathCheck) {
            $lines = explode("\n", trim($pathCheck));
            $first = trim($lines[0] ?? '');
            if (!empty($first) && file_exists($first)) {
                return $first;
            }
        }

        // 2. Cek lokasi populer Windows (XAMPP, Laragon, MySQL Server)
        $candidates = [
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'D:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
        ];

        // Glob untuk Laragon & Program Files
        $globs = [
            'C:\\laragon\\bin\\mysql\\*\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\*\\bin\\mysqldump.exe',
            'C:\\Program Files\\MariaDB*\\bin\\mysqldump.exe',
        ];

        foreach ($globs as $globPattern) {
            $matches = glob($globPattern);
            if (!empty($matches)) {
                $candidates = array_merge($candidates, $matches);
            }
        }

        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Format bytes to human readable format.
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
