<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Notifications\NewTransactionNotification;
use App\Events\TransactionCreated;

class SendTransactionNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TransactionCreated  $event
     * @return void
     */
    public function handle(TransactionCreated $event)
    {
        // Ambil semua admin
        $admins = User::where('roles', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewTransactionNotification($event->transaction));
        }
    }
}
