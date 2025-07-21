<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained('users');
            $table->integer('tax_price');
            $table->integer('total_price');
            $table->softDeletes();
            $table->timestamps();
            $table->string('code', 100);
            $table->string('payment_method', 50);
            $table->string('snap_token', 255)->nullable();
            $table->string('status', 20)->default('pending');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}