<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;

class ResizeImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $sizes = [8, 16, 32, 64];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private string $name) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tmpPath = "tmp/{$this->name}.png";
        $dstPath = "public/users/{$this->name}";

        Storage::makeDirectory($dstPath);
        Storage::copy($tmpPath, "$dstPath/full.png");

        foreach ($this->sizes as $size) {
            $path = "$dstPath/{$this->makeName($size)}.png";
            Storage::copy($tmpPath, $path);

            Image::load(Storage::path($path))->width($size)->height($size)->save();
        }

        Storage::delete($tmpPath);
    }

    public function makeName(int $n)
    {
        return "{$n}x$n";
    }

}
