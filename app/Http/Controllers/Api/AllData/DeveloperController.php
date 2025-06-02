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
        $maxDetails = $request->query('maxDetails', 100); // Limit log details to 100 by default

        $logCountsByDate = [];
        $logLevelCounts = [];
        $logDetails = [];

        foreach (array_chunk($logFiles, 10) as $fileChunk) { // Process files in chunks of 10
            foreach ($fileChunk as $file) {
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
                            $logDate = $matches[1];
                            $level = strtoupper($matches[3]);
                            $message = $matches[4];

                            $logCountsByDate[$logDate] = ($logCountsByDate[$logDate] ?? 0) + 1;

                            if (!isset($logLevelCounts[$level][$logDate])) {
                                $logLevelCounts[$level][$logDate] = [];
                            }
                            if (!in_array($message, $logLevelCounts[$level][$logDate])) {
                                $logLevelCounts[$level][$logDate][] = $message;
                            }

                            $existingIndex = array_search($message, array_column($logDetails, 'message'));
                            if ($existingIndex !== false && $logDetails[$existingIndex]['date'] === $logDate) {
                                $logDetails[$existingIndex]['count']++;
                            } else {
                                $logDetails[] = [
                                    'date' => $logDate,
                                    'level' => $level,
                                    'message' => $message,
                                    'location' => 'N/A',
                                    'count' => 1,
                                ];
                            }

                            $currentMessage = $message;
                            $currentTrace = '';
                        } elseif ($currentMessage) {
                            $currentTrace .= $line . "\n";

                            if (preg_match('/(app\/Http\/Controllers\/[^\s]+\.php:\d+)/', $line, $locMatch)) {
                                $logDetails[count($logDetails) - 1]['location'] = $locMatch[1];
                            }
                        }

                        if ($currentMessage && trim($line) === '') {
                            $currentMessage = null;
                        }
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
                        return $carry + count($errors);
                    }, 0);
                }, $logLevelCounts),
            ],
            'list' => array_slice(array_map(function ($error) {
                return [
                    'date' => $error['date'],
                    'message' => strtok($error['message'], '{'),
                    'controller' => preg_match('/(app\/Http\/Controllers\/[^\s]+\.php:\d+)/', $error['location'], $match) ? $match[1] :
                                    (preg_match('/(app\/Models\/[^\s]+\.php:\d+)/', $error['location'], $match) ? $match[1] :
                                    (preg_match('/(resources\/views\/[^\s]+\.blade\.php:\d+)/', $error['location'], $match) ? $match[1] :
                                    (preg_match('/(routes\/[^\s]+\.php:\d+)/', $error['location'], $match) ? $match[1] :
                                    (preg_match('/(app\/[^\s]+\.php:\d+)/', $error['location'], $match) ? $match[1] : 'Location not found')))),
                    'count' => $error['count'],
                ];
            }, array_filter($logDetails, function ($error) {
                return $error['level'] === 'ERROR';
            })), 0, $maxDetails), // Apply maxDetails limit here
        ]);
    }
}
