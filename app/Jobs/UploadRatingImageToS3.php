<?php

namespace App\Jobs;

use App\Models\Rating;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadRatingImageToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ratingId;
    protected $fileContents; // Konten binary file
    protected $fileName;     // Nama file untuk disimpan
    
    // Sesuaikan constructor
    public function __construct($ratingId, $fileContents, $fileName)
    {
        $this->ratingId = $ratingId;
        $this->fileContents = $fileContents; // Sekarang menyimpan data binary
        $this->fileName = $fileName;
    }

    public function handle()
    {
        $rating = Rating::find($this->ratingId);
        
        if (!$rating || empty($this->fileContents)) {
            return;
        }

        try {
            // Tentukan path S3 (gunakan hash/unik nama file untuk menghindari konflik)
            $s3FileName = 'ratings/' . md5($this->fileContents . time()) . '.' . pathinfo($this->fileName, PATHINFO_EXTENSION);

            // Upload konten file langsung ke S3
            // Storage::put(path, contents, visibility)
            $s3Path = Storage::disk('s3')->put($s3FileName, $this->fileContents, 'public'); 
            
            // Dapatkan URL
            $imageUrl = Storage::disk('s3')->url($s3FileName);
            
            // Update rating
            $rating->update(['image' => $imageUrl]);
            
        } catch (\Exception $e) {
            Log::error("S3 Upload Failed for Rating ID: {$this->ratingId}. Error: " . $e->getMessage());
            // Biarkan job gagal dan di-retry
            throw $e;
        }
    }
}
