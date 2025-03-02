<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade'); 
            $table->foreignId('gateway_id')->constrained('gateways')->onDelete('cascade');
            $table->string('external_id')->nullable(); 
            $table->enum('status', ['pending', 'completed', 'failed']); 
            $table->decimal('amount', 10, 2);
            $table->string('card_last_numbers', 4); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
