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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('delivery_option', ['pickup', 'delivery'])->default('pickup');
            $table->enum('payment_method', ['cash', 'gcash', 'bank_transfer'])->default('cash');
            $table->text('special_instructions')->nullable();
            $table->timestamp('pickup_date')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->string('farmer_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_option',
                'payment_method',
                'special_instructions',
                'pickup_date',
                'delivery_date',
                'farmer_notes'
            ]);
        });
    }
};