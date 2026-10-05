<?php

namespace App\Core;

class Logger
{
    protected static function write(string $level, string $message): void
    {
        // 📂 path log
        $logDir  = __DIR__ . '/../../storage/logs/';
        $logFile = $logDir . date('Y-m-d') . '.log';

        // buat folder jika belum ada
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        // format waktu
        $time = date('Y-m-d H:i:s');

        // format log
        $log = "[{$time}] {$level}: {$message}" . PHP_EOL;

        // tulis ke file
        file_put_contents($logFile, $log, FILE_APPEND);
    }

    // 🔴 ERROR
    public static function error(string $message): void
    {
        self::write('ERROR', $message);
    }

    // 🟡 WARNING (optional)
    public static function warning(string $message): void
    {
        self::write('WARNING', $message);
    }

    // 🔵 INFO (optional)
    public static function info(string $message): void
    {
        self::write('INFO', $message);
    }
}
