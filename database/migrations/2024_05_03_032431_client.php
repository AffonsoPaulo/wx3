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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->nullable(false);
            $table->string('cpf')->unique()->nullable(false);
            $table->date('birthDate')->nullable(false);
            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street')->nullable(false);
            $table->string('city')->nullable(false);
            $table->string('neighborhood')->nullable(false);
            $table->string('state')->nullable(false);
            $table->integer('number')->nullable(false);
            $table->string('zipCode')->nullable(false);
            $table->foreignId('client_id')->constrained('clients');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('clients');
    }
};
