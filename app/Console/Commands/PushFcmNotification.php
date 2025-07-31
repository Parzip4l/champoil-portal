<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\ModelCG\Schedule; // Ensure this line is present
use App\User;
use App\Absen;
use App\Models\FirebaseToken;

class PushFcmNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:push {token?} {title?} {body?} {--data=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an FCM notification to a specific device';

    protected $firebaseService;

    /**
     * Create a new command instance.
     *
     * @param FirebaseService $firebaseService
     */
    public function __construct(FirebaseService $firebaseService)
    {
        parent::__construct();
        $this->firebaseService = $firebaseService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $token = $this->argument('token') ?? 'default_token'; // Provide a default token or handle null
        $title = $this->argument('title') ?? 'Default Title'; // Provide a default title
        $body = $this->argument('body') ?? 'Default Body'; // Provide a default body

        // Determine the shift based on the current time
        $currentHour = (int) date('H'); // Get the current hour in 24-hour format
        if ($currentHour >= 8 && $currentHour < 10) {
            $shift = "PG";
        } elseif ($currentHour >= 14 && $currentHour < 16) {
            $shift = "MD";
        } elseif ($currentHour >= 18 && $currentHour <= 23) {
            $shift = "ML";
        } else {
            $shift = "OFF"; // Default to "OFF" if no shift matches
        }

        // Fetch schedule and join with users and Firebase tokens
        $schedule = Schedule::join('users', 'schedules.employee', '=', 'users.name')
            ->join('firebase_tokens', 'users.id', '=', 'firebase_tokens.user_id')
            ->join('karyawan', 'schedules.employee', '=', 'karyawan.nik') // Ensure this join is correct
            ->where('schedules.shift',$shift) // Example condition, adjust as needed
            ->where('schedules.tanggal',date('Y-m-d')) // Exclude OFF shifts
            ->get();

        // Print the result of the query
        // dd($schedule); // Use dd() to dump and die
        // Alternatively, you can use:
        // print_r($schedule->toArray()); // Convert to array and print

        if (!$schedule) {
            $this->error('No pending schedules found.');
            return Command::FAILURE;
        }

        // Iterate over the firebaseTokens of the user associated with the schedule
        foreach ($schedule as $user_data) {

            $cekabsen = Absen::where('nik', $user_data->nik)
                ->whereDate('tanggal', date('Y-m-d'))
                ->count();

            if ($cekabsen > 0) {
                continue;
            }

            $token = $user_data->token;
            $title = 'Reminder Absen'; // Set title to "Reminder Absen"
            $body = "Halo {$user_data->nama}, Anda belum melakukan absen untuk {$user_data->shift}. Silahkan absen."; // Set body with user name
            $data = $this->option('data');

            // Debug $data using dd
            // dd($data);

            // Parse --data into a key-value array
            $parsedData = [];
            foreach ($data as $item) {
                if (str_contains($item, '=')) {
                    [$key, $value] = explode('=', $item, 2);
                    $parsedData[$key] = $value;
                }
            }

            // Ensure $parsedData is a valid associative array
            if (!is_array($parsedData)) {
                $parsedData = [];
            }

            $response = $this->firebaseService->sendNotification($token, $title, $body, $parsedData);

            if ($response['status'] === 'success') {
                $this->info("Notification sent to token: $token");
            } else {
                $this->error("Failed to send notification to token: $token");

                // Log the full exception for debugging
                if (isset($response['exception'])) {
                    \Log::error('FCM Error', [
                        'exception' => $response['exception'],
                        'message' => $response['exception']->getMessage(),
                        'trace' => $response['exception']->getTraceAsString()
                    ]);
                }
            }
        }

        return Command::SUCCESS;
    }
}
