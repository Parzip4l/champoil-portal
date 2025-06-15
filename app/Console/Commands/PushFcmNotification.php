<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;


class PushFcmNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:push {token} {title} {body} {--data=*}';

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
    public function handle(){

        $get_schedule = 


        $token = $this->argument('token');
        $title = $this->argument('title');
        $body = $this->argument('body');
        $data = $this->option('data');

        // Parse --data into a key-value array
        $parsedData = [];
        foreach ($data as $item) {
            if (str_contains($item, '=')) {
                [$key, $value] = explode('=', $item, 2);
                $parsedData[$key] = $value;
            }
        }

        $response = $this->firebaseService->sendNotification($token, $title, $body, $parsedData);

        if ($response['status'] === 'success') {
            $this->info($response['message']);
            return Command::SUCCESS;
        } else {
            $this->error($response['message']);
            
            // For debugging, you can log the full exception
            if (isset($response['exception'])) {
                \Log::error('FCM Error', [
                    'exception' => $response['exception'],
                    'message' => $response['exception']->getMessage(),
                    'trace' => $response['exception']->getTraceAsString()
                ]);
            }
            
            return Command::FAILURE;
        }
    }
}
