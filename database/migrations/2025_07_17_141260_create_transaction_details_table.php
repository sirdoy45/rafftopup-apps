<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transactions_id')->constrained('transactions');
            $table->foreignId('products_id')->constrained('products');
            $table->integer('price');
            $table->timestamps();
            $table->string('delivery_status', 50)->default('pending');
            $table->string('code', 50);
            $table->string('customer_phone', 30)->nullable();
            $table->integer('quantity');
            $table->string('id_game', 30)->nullable();
            $table->string('server', 20)->nullable();
            $table->string('user_id', 30)->nullable();
            $table->string('target_phone_number', 30)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
}