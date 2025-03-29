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
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // Sender (foreign key to users table)
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('cascade'); // Receiver (foreign key to users table)
            $table->text('message'); // Message content
            $table->timestamps(); // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
