<?php

namespace App\Jobs;

use App\Models\Resource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function handle()
    {
        $videoFileName = $this->resource->file_path;
        $videoPath = Storage::disk('public')->path('resources/'.$videoFileName);

        // Generate a new unique name for the thumbnail
        $thumbnailName = Str::random(40).'.jpg';
        $thumbnailPath = Storage::disk('public')->path('resource_images/'.$thumbnailName);

        // Ensure the output directory exists
        if (! file_exists(dirname($thumbnailPath))) {
            mkdir(dirname($thumbnailPath), 0755, true);
        }

        // Update the ffmpeg path to use the one in your system PATH
        $ffmpeg = 'ffmpeg';

        // Use escapeshellarg to properly escape the file paths
        $videoPath = escapeshellarg($videoPath);
        $thumbnailPath = escapeshellarg($thumbnailPath);

        // Construct the FFmpeg command
        $command = "{$ffmpeg} -i {$videoPath} -vframes 1 -an -s 300x200 -ss 00:00:01 {$thumbnailPath}";

        // Execute the command
        exec($command, $output, $returnCode);

        if ($returnCode === 0 && file_exists(trim($thumbnailPath, "'"))) {
            $this->resource->resource_image_path = $thumbnailName;
            $this->resource->save();
            Log::info('Successfully generated video thumbnail for resource ID: '.$this->resource->id);
        } else {
            // If ffmpeg fails, log the error
            Log::error('Failed to generate video thumbnail for resource ID: '.$this->resource->id.". Command: {$command}, Return code: {$returnCode}, Output: ".implode("\n", $output));
        }
    }
}
