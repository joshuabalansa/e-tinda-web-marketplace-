<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add enhanced multi-vendor order support
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add pickup scheduling fields
            $table->json('pickup_schedule')->nullable(); // Store pickup times per farmer
            $table->text('delivery_notes')->nullable(); // Additional delivery instructions
            $table->boolean('is_multi_vendor')->default(false); // Flag for multi-vendor orders
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Add farmer-specific delivery information
            $table->string('farmer_delivery_status')->default('pending'); // Individual item status
            $table->timestamp('farmer_ready_at')->nullable(); // When farmer has item ready
            $table->text('farmer_notes')->nullable(); // Farmer-specific notes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['pickup_schedule', 'delivery_notes', 'is_multi_vendor']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['farmer_delivery_status', 'farmer_ready_at', 'farmer_notes']);
        });
    }
};
