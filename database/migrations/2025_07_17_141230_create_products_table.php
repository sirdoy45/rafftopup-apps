<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('categories_id')->constrained('categories');
            $table->string('kode_produk', 50);
            $table->enum('status', ['aktif', 'tidak aktif']);
            $table->integer('price');
            $table->longText('description');
            $table->string('provider', 100);
            $table->softDeletes();
            $table->timestamps();
            $table->string('slug', 150);
            $table->integer('quantity')->default(0);
            $table->string('input_type', 50);
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
