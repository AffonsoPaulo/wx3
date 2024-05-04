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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 8, 2)->nullable(false);
            $table->decimal('shipping', 8, 2)->nullable(false);
            $table->decimal('discount', 8, 2)->nullable(false);
            $table->string('paymentMethod')->nullable(false);
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('address_id')->constrained('addresses');
            $table->timestamps();
        });

        Schema::create('sales_products', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->nullable(false);
            $table->decimal('price', 8, 2)->nullable(false);
            $table->foreignId('sale_id')->constrained('sales');
            $table->foreignId('product_id')->constrained('products');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_products');
        Schema::dropIfExists('sales');
    }
};
