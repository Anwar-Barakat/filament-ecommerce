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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('number')->unique();
            $table->decimal('unit_price', 8, 2)->default(0);
            $table->decimal('total_price', 8, 2)->default(0);

            $table->integer('taxes')->nullable();
            $table->integer('discount')->nullable();

            $table->enum('status', ['pending', 'processing', 'completed', 'declined', 'cancelled'])->default('pending');
            $table->decimal('shipping_price')->nullable();
            $table->longText('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
