<?php

namespace App\Jobs;

use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Image;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $design;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Design $design)
    {
        $this->design = $design;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = $this->design->disk;
        $original_file = storage_path() . '/uploads/original' . $this->design->image;

        try {
            // Large image
            Image::make($original_file)
                ->fit(800, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($large = storage_path('uploads/large/' . $this->design->image));

            // Thumbnail image
            Image::make($original_file)
            ->fit(250, 200, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($thumbnail = storage_path('uploads/thumbnail/' . $this->design->image));

            // Store images
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}