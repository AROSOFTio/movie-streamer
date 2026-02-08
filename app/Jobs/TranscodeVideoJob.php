<?php

namespace App\Jobs;

use App\Models\VideoFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranscodeVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public VideoFile $videoFile)
    {
    }

    public function handle(): void
    {
        Log::info('Transcode placeholder for video file', [
            'video_file_id' => $this->videoFile->id,
        ]);
    }
}
