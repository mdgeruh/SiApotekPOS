<?php

namespace App\Console\Commands;

use App\Helpers\ImageResizeHelper;
use Illuminate\Console\Command;

class CleanupOldImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:cleanup {--days=30 : Number of days old to consider for cleanup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old images from storage to free up space';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysOld = $this->option('days');

        $this->info("Starting cleanup of images older than {$daysOld} days...");

        // Clean up old logos
        $deletedLogos = ImageResizeHelper::cleanupOldImages('logos', $daysOld);
        $this->info("Deleted {$deletedLogos} old logo files from logos/ directory");

        // Clean up old favicons
        $deletedFavicons = ImageResizeHelper::cleanupOldImages('favicons', $daysOld);
        $this->info("Deleted {$deletedFavicons} old favicon files from favicons/ directory");

        // Clean up old profile photos
        $deletedProfiles = ImageResizeHelper::cleanupOldImages('profile-photos', $daysOld);
        $this->info("Deleted {$deletedProfiles} old profile photo files from profile-photos/ directory");

        $totalDeleted = $deletedLogos + $deletedFavicons + $deletedProfiles;

        if ($totalDeleted > 0) {
            $this->info("Cleanup completed! Total {$totalDeleted} old image files deleted.");
        } else {
            $this->info("No old images found to delete.");
        }

        return Command::SUCCESS;
    }
}
