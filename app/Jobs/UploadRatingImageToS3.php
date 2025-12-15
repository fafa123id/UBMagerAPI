<?php

namespace App\Jobs;

use App\Models\Rating;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadRatingImageToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ratingId;
    protected $imagePath;

    /**
     * Create a new job instance.
     */
    public function __construct($ratingId, $imagePath)
    {
        $this->ratingId = $ratingId;
        $this->imagePath = $imagePath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $rating = Rating::find($this->ratingId);
        
        if ($rating && $this->imagePath) {
            // Upload file ke S3
            $s3Path = Storage::disk('s3')->put('ratings', $this->imagePath);
            $imageUrl = config('filesystems.disks.s3.url') . $s3Path;
            
            // Update rating dengan image URL
            $rating->update(['image' => $imageUrl]);
        }
    }
}
