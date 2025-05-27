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
                        // Main log line
                        $logDate = $matches[1];
                        $level = strtoupper($matches[3]);
                        $message = $matches[4];

                        $logCountsByDate[$logDate] = ($logCountsByDate[$logDate] ?? 0) + 1;

                        // Ensure unique error messages per date
                        if (!isset($logLevelCounts[$level][$logDate])) {
                            $logLevelCounts[$level][$logDate] = [];
                        }
                        if (!in_array($message, $logLevelCounts[$level][$logDate])) {
                            $logLevelCounts[$level][$logDate][] = $message;
                        }

                        // Check if the same error message already exists for the same date
                        $existingIndex = array_search($message, array_column($logDetails, 'message'));
                        if ($existingIndex !== false && $logDetails[$existingIndex]['date'] === $logDate) {
                            // Increment the count for the existing error
                            $logDetails[$existingIndex]['count']++;
                        } else {
                            // Add new error entry with count = 1
                            $logDetails[] = [
                                'date' => $logDate,
                                'level' => $level,
                                'message' => $message,
                                'location' => 'N/A', // Default location if no trace is found
                                'count' => 1, // Initialize count
                            ];
                        }

                        // Reset trace
                        $currentMessage = $message;
                        $currentTrace = '';
                    } elseif ($currentMessage) {
                        // Append trace lines
                        $currentTrace .= $line . "\n";

                        // If trace contains Controller.php: or .php:, update the last logDetails entry
                        if (preg_match('/(app\/Http\/Controllers\/[^\s]+\.php:\d+)/', $line, $locMatch)) {
                            $logDetails[count($logDetails) - 1]['location'] = $locMatch[1];
                        }
                    }

                    // Ensure errors without trace are saved
                    if ($currentMessage && trim($line) === '') {
                        $currentMessage = null; // Reset to avoid duplicates
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
                'levelCounts' => array_map(function ($levelErrors) {
                    return array_reduce($levelErrors, function ($carry, $errors) {
                        return $carry + count($errors); // Count unique errors per date
                    }, 0);
                }, $logLevelCounts),
            ],
            'list' => array_map(function ($error) {
                return [
                    'date' => $error['date'], // Include the log date
                    'message' => strtok($error['message'], '{'), // Truncate message at the first '{'
                    'controller' => preg_match('/(app\/Http\/Controllers\/[^\s]+\.php:\d+)/', $error['location'], $match) ? $match[1] :
                                    (preg_match('/(app\/Models\/[^\s]+\.php:\d+)/', $error['location'], $match) ? $match[1] :
                                    (preg_match('/(resources\/views\/[^\s]+\.blade\.php:\d+)/', $error['location'], $match) ? $match[1] :
                                    (preg_match('/(routes\/[^\s]+\.php:\d+)/', $error['location'], $match) ? $match[1] :
                                    (preg_match('/(app\/[^\s]+\.php:\d+)/', $error['location'], $match) ? $match[1] : 'Location not found')))), // Check for route files and other app files
                    'count' => $error['count'], // Include the count of occurrences
                ];
            }, array_filter($logDetails, function ($error) {
                return $error['level'] === 'ERROR'; // Include all logs with level ERROR
            })),
        ]);
    }
}
