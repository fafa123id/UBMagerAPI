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
use Illuminate\Support\Str;

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

        // Cek apakah rating ada dan konten file tidak kosong
        if (!$rating || empty($this->fileContents)) {
            return;
        }

        $binaryContents = base64_decode($this->fileContents);

        try {
            // Tentukan path S3 
            $extension = pathinfo($this->fileName, PATHINFO_EXTENSION);
            $s3FileName = 'ratings/' . Str::uuid() . '.' . $extension; // Gunakan Str::uuid() untuk nama file unik

            // Upload konten file (binary) langsung ke S3
            // Gunakan $binaryContents di sini
            Storage::disk('s3')->put($s3FileName, $binaryContents, 'public');

            $imageUrl = Storage::disk('s3')->url($s3FileName);

            // Update rating
            $rating->update(['image' => $imageUrl]);
        } catch (\Exception $e) {
            Log::error("S3 Upload Failed for Rating ID: " . $this->ratingId . " Error: " . $e->getMessage());
            throw $e;
        }
    }
}
