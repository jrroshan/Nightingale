<?php

namespace App\Console\Commands;

use App\Models\Setting\Setting;
use App\Services\FileSynchronizer;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class SyncCommand extends Command
{
    private $media;
    private ProgressBar $progressBar;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nightingale:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search for New Music in folder';

    public function __construct(protected FileSynchronizer $fileSynchronizer)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->media = $this->getMediaPath();
        $this->fileSynchronizer->index($this->media->media_path);

    }

    public function getMediaPath()
    {
        $path = Setting::first();

        if ($path) {
            return $path;
        }

        $this->warn("Media path is not Configured");

        while (true) {
            $path = $this->ask('Absolute path to your media directory');

            if (is_dir($path) && is_readable($path)) {
                $path = Setting::create(['media_path'=> $path]);
                break;
            }

            $this->error('The path does not exist or is not readable. Try again.');
        }

        return $path;
    }
}
