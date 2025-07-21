<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductGalleriesTable extends Migration
{
    public function up()
    {
        Schema::create('product_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('photos');
            $table->foreignId('product_id')->constrained('products');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_galleries');
    }
}
