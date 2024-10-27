<?php

namespace App\Console\Commands;

use App\Models\Blacklist;
use Illuminate\Console\Command;

class UnblockUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unblock:reported-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unblock users whose block period has expired';

    /*
        run this command:
        php artisan schedule:work

        or

        php artisan unblock:reported-users
    */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->unblockUsers();

        $this->info('Users who have completed their block period have been unblocked.');
    }

    protected function unblockUsers()
    {
        Blacklist::where('blocked_at', '<=', now())
                ->blocked()
                ->update(['blocked_at' => null]);
    }
}
