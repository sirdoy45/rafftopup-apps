<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTransactionNotification extends Notification
{
    use Queueable;

    protected $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Transaksi Baru',
            'message' => 'Transaksi baru dengan invoice ' . $this->transaction->code,
            'transaction_id' => $this->transaction->id,
            'invoice' => $this->transaction->code,
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
