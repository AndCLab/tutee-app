<?php

namespace App\Console\Commands;

use App\Models\Blacklist;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BlockReportedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'block:reported-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and block reported users based on report count';

    /*
        run this command:
        php artisan schedule:work

        or

        php artisan block:reported-users
    */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // block users with 10+ report_count for 20 years
        $this->blockUsers(10, now()->addYears(10));

        // block users with 6+ report_count for 3 days
        $this->blockUsers(6, now()->addDays(3));

        // block users with 3+ report_count for 1 day
        $this->blockUsers(3, now()->addDay());

        $this->info('Users have been blocked based on report count.');
    }

    protected function blockUsers(int $reportCount, Carbon $blockUntil)
    {
        Blacklist::where('report_count', '>=', $reportCount)
                ->notBlocked() // only block users that are not already blocked
                ->update(['blocked_at' => $blockUntil]);
    }
}
