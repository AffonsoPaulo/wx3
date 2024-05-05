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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->nullable(false);
            $table->text('description')->nullable(false);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->nullable(false);
            $table->string('color')->nullable(false);
            $table->string('image')->nullable();
            $table->decimal('price', 8, 2)->nullable(false);
            $table->decimal('discount', 8, 2)->nullable(false);
            $table->text('description')->nullable(false);
            $table->decimal('weight', 8, 2)->nullable(false);
            $table->foreignId('category_id')->constrained('categories');
            $table->timestamps();
        });

        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->string('size')->nullable(false);
            $table->integer('quantity')->nullable(false);
            $table->foreignId('product_id')->constrained('products');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variations');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
