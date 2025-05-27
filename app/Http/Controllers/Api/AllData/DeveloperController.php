<?php

namespace App\Http\Controllers\Api\AllData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DeveloperController extends Controller
{
    public function index(Request $request)
    {
        $logDir = storage_path('logs');
        $logFiles = File::files($logDir);

        $singleDate = $request->query('date');
        $startDate = $request->query('start');
        $endDate = $request->query('end');

        $logCountsByDate = [];
        $logLevelCounts = [];
        $logDetails = []; // detail error: date, level, message, trace/controller

        foreach ($logFiles as $file) {
            if (preg_match('/laravel-(\d{4}-\d{2}-\d{2})\.log$/', $file->getFilename(), $match)) {
                $fileDate = $match[1];

                if ($singleDate && $fileDate !== $singleDate) continue;
                if ($startDate && $endDate && ($fileDate < $startDate || $fileDate > $endDate)) continue;

                $logData = File::get($file->getRealPath());
                $lines = explode("\n", $logData);

                $currentMessage = null;
                $currentTrace = '';

                foreach ($lines as $line) {
                    if (preg_match('/\[(\d{4}-\d{2}-\d{2}) [^\]]+\] (\w+)\.(\w+): (.*)/', $line, $matches)) {
                        // Baris utama log
                        $logDate = $matches[1];
                        $level = strtoupper($matches[3]);
                        $message = $matches[4];

                        $logCountsByDate[$logDate] = ($logCountsByDate[$logDate] ?? 0) + 1;
                        $logLevelCounts[$level] = ($logLevelCounts[$level] ?? 0) + 1;

                        // Simpan pesan dan reset trace
                        $currentMessage = $message;
                        $currentTrace = '';
                    } elseif ($currentMessage) {
                        // Tambahkan baris trace
                        $currentTrace .= $line . "\n";

                        // Jika mengandung Controller.php: atau .php: maka simpan ke detail
                        if (preg_match('/(app\/Http\/Controllers\/[^\s]+\.php:\d+)/', $line, $locMatch)) {
                            $logDetails[] = [
                                'date' => $logDate,
                                'level' => $level,
                                'message' => $currentMessage,
                                'location' => $locMatch[1],
                            ];
                        }
                    }

                    // Pastikan error tanpa trace juga disimpan
                    if ($currentMessage && trim($line) === '') {
                        $logDetails[] = [
                            'date' => $logDate,
                            'level' => $level,
                            'message' => $currentMessage,
                            'location' => $currentTrace ? $currentTrace : 'N/A',
                        ];
                        $currentMessage = null; // Reset supaya tidak duplikat
                    }

                    // Ensure this condition is met even if there are extra spaces or characters
                    if ($currentMessage && empty(trim($line))) {
                        $logDetails[] = [
                            'date' => $logDate,
                            'level' => $level,
                            'message' => $currentMessage,
                            'location' => $currentTrace ? $currentTrace : 'N/A',
                        ];
                        $currentMessage = null; // Reset supaya tidak duplikat
                    }
                }
            }
        }

        ksort($logCountsByDate);

        return response()->json([
            'count' => [
                'logDates' => array_keys($logCountsByDate),
                'logCounts' => array_values($logCountsByDate),
                'logLevels' => array_keys($logLevelCounts),
                'levelCounts' => array_values($logLevelCounts),
            ],
            'list' => array_map(function ($error) {
                return [
                    'message' => strtok($error['message'], '{'), // Truncate message at the first '{'
                    'controller' => preg_match('/(app\/Http\/Controllers\/[^\s]+\.php:\d+)/', $error['location'], $match) ? $match[1] :
                                    (preg_match('/(app\/Models\/[^\s]+\.php:\d+)/', $error['location'], $match) ? $match[1] :
                                    (preg_match('/(resources\/views\/[^\s]+\.blade\.php:\d+)/', $error['location'], $match) ? $match[1] :
                                    (preg_match('/(routes\/[^\s]+\.php:\d+)/', $error['location'], $match) ? $match[1] :
                                    (preg_match('/(app\/[^\s]+\.php:\d+)/', $error['location'], $match) ? $match[1] : 'Location not found')))), // Check for route files and other app files
                ];
            }, $logDetails),
        ]);
    }
}
