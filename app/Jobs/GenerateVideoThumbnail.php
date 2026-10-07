<?php

namespace App\Jobs;

use App\Models\Resource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateVideoThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $resourceId;

    protected $fileName;

    public function __construct($resourceId, $fileName)
    {
        $this->resourceId = $resourceId;
        $this->fileName = $fileName;
    }

    public function handle()
    {
        $resource = Resource::find($this->resourceId);
        if (! $resource) {
            Log::error("Resource not found for ID: {$this->resourceId}");

            return;
        }

        $videoPath = storage_path('app/public/resources/'.$this->fileName);
        $thumbnailName = pathinfo($this->fileName, PATHINFO_FILENAME).'_thumbnail.jpg';
        $thumbnailPath = storage_path('app/public/resource_images/'.$thumbnailName);

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
            $resource->resource_image_path = $thumbnailName;
            $resource->save();
        } else {
            Log::error("Failed to generate video thumbnail. Command: {$command}, Return code: {$returnCode}, Output: ".implode("\n", $output));
        }
    }
}
