<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\CsrfHelper;

class ClearExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:clear-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear expired sessions from storage';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Clearing expired sessions...');

        try {
            $cleared = CsrfHelper::clearExpiredSessions();

            if ($cleared) {
                $this->info('Expired sessions cleared successfully!');
                return 0;
            } else {
                $this->error('Failed to clear expired sessions.');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('Error clearing expired sessions: ' . $e->getMessage());
            return 1;
        }
    }
}
