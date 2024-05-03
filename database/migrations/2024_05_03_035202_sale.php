<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 8, 2)->nullable(false);
            $table->decimal('shipping', 8, 2)->nullable(false);
            $table->decimal('discount', 8, 2)->nullable(false);
            $table->string('paymentMethod')->nullable(false);
            $table->foreignId('client_id')->constrained('client');
            $table->foreignId('address_id')->constrained('address');
            $table->timestamps();
        });

        Schema::create('sale_product', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->nullable(false);
            $table->decimal('price', 8, 2)->nullable(false);
            $table->foreignId('sale_id')->constrained('sale');
            $table->foreignId('product_id')->constrained('product');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_product');
        Schema::dropIfExists('sale');
    }
};
