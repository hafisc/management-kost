<?php

namespace App\Console\Commands;

use App\Models\Stay;
use App\Notifications\PaymentReminderNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('reminders:payment')]
#[Description('Send payment reminders to active tenants')]
class SendPaymentReminders extends Command
{
    public function handle()
    {
        $activeStays = Stay::active()
            ->with('tenant')
            ->get();

        $count = 0;

        foreach ($activeStays as $stay) {
            $lastPayment = $stay->tenant->payments()
                ->latest('payment_date')
                ->first();

            if (! $lastPayment || $lastPayment->payment_date->addMonth()->isPast()) {
                $stay->tenant->notify(new PaymentReminderNotification($stay));
                $count++;
            }
        }

        $this->info("Sent {$count} payment reminders.");

        return Command::SUCCESS;
    }
}
