<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // ID unik (UUID)
            $table->string('type'); // Tipe notifikasi (misalnya App\Notifications\NewTransactionNotification)
            $table->morphs('notifiable'); // Polimorfisme: bisa user, admin, dll
            $table->text('data'); // Data JSON dari notifikasi
            $table->timestamp('read_at')->nullable(); // Waktu dibaca
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
