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
        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->nullable(false);
            $table->text('description')->nullable(false);
            $table->timestamps();
        });

        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->nullable(false);
            $table->string('color')->unique()->nullable(false);
            $table->string('imagePath')->nullable();
            $table->decimal('price', 8, 2)->nullable(false);
            $table->decimal('discount', 8, 2)->nullable(false);
            $table->text('description')->nullable(false);
            $table->decimal('weight', 8, 2)->nullable(false);
            $table->foreignId('category_id')->constrained('category');
            $table->timestamps();
        });

        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->string('size')->unique()->nullable(false);
            $table->integer('quantity')->nullable(false);
            $table->foreignId('product_id')->constrained('product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
        Schema::dropIfExists('product');
        Schema::dropIfExists('variations');
    }
};
