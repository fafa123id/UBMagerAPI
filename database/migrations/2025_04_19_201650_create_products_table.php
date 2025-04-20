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
            $table->string('name'); 
            $table->enum('type', ['goods', 'service']);
            $table->text('description')->nullable();
            $table->integer('quantity')->nullable(); 
            $table->decimal('price', 10, 2); 
            $table->enum('status', ['available', 'unavailable'])->default('available'); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}

