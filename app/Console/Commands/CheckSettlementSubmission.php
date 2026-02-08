<?php

namespace App\Console\Commands;

use App\Models\Settlement;
use App\Models\User;
use App\Notifications\SettlementNotSubmittedNotification;
use Illuminate\Console\Command;

class CheckSettlementSubmission extends Command
{
    protected $signature = 'settlements:check-pending';
    protected $description =
        'Notify if daily settlement is not submitted';

    public function handle()
    {
        if(now()->isSunday()) {
            return Command::SUCCESS;
        }

        if(now()->hour<20){
            return Command::SUCCESS;
        }

        $today = now()->toDateString();

        // ⏰ Run only after cutoff time
        if (now()->hour < 20) {
            return Command::SUCCESS;
        }

        $deliveryBoys = User::where('role', 'delivery_boy')
            ->where('is_active', true)
            ->get();

        foreach ($deliveryBoys as $boy) {

            $exists = Settlement::where('delivery_boy_id', $boy->id)
                ->where('settlement_date', $today)
                ->exists();

            if (!$exists) {
                $boy->notify(
                    new SettlementNotSubmittedNotification($today)
                );
            }
        }

        return Command::SUCCESS;
    }
}
