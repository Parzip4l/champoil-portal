
<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('uploadToS3')) {
   
    if (!function_exists('uploadToS3')) {
        function uploadToS3($file, $path = 'uploads', $filename = null)
        {
            try {
                if (!$filename) {
                    $filename = is_string($file)
                        ? basename($file)
                        : $file->getClientOriginalName();
                }
    
                // Full key
                $key = trim($path, '/') . '/' . $filename;
    
                // Upload ke S3
                Storage::disk('s3')->putFileAs($path, $file, $filename, [
                    'visibility' => 'public',
                ]);
    
                // Return public URL
                return Storage::disk('s3')->url($key);
            } catch (\Exception $e) {
                \Log::error('Upload to S3 failed: ' . $e->getMessage());
                return false;
            }
        }
    }
    

}
