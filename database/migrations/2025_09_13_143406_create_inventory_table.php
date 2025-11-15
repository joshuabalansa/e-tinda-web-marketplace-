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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity_in')->default(0);
            $table->integer('quantity_out')->default(0);
            $table->integer('current_stock')->default(0);
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_value', 10, 2)->default(0);
            $table->string('transaction_type')->default('adjustment'); // adjustment, purchase, sale, loss
            $table->text('notes')->nullable();
            $table->date('transaction_date');
            $table->string('reference_number')->nullable(); // for tracking specific transactions
            $table->timestamps();

            // Indexes for better performance
            $table->index(['farmer_id', 'product_id']);
            $table->index('transaction_date');
            $table->index('transaction_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
