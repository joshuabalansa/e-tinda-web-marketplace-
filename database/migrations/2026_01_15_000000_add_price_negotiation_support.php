<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add price negotiation support to orders and order_items
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_negotiation')->default(false)->after('status');
            $table->text('negotiation_notes')->nullable()->after('is_negotiation');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('negotiated_price', 10, 2)->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_negotiation', 'negotiation_notes']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('negotiated_price');
        });
    }
};

