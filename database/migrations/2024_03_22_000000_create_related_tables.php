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
        Schema::create('associations', function (Blueprint $table) {
            $table->id('association_id');
            $table->string('name', 100);
            $table->text('location');
            $table->integer('total_members')->default(0);
            $table->date('date_established');
            $table->string('contact_person', 100);
            $table->timestamps();
        });

        Schema::create('buyers', function (Blueprint $table) {
            $table->id('buyer_id');
            $table->enum('preferred_payment_method', ['GCash', 'Palawan Pay', 'COD']);
            $table->decimal('buyer_rating', 3, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('farmers', function (Blueprint $table) {
            $table->id('farmer_id');
            $table->foreignId('association_id')->nullable()->constrained('associations', 'association_id')->nullOnDelete();
            $table->text('farm_location');
            $table->decimal('land_size', 10, 2);
            $table->integer('years_of_experience')->default(0);
            $table->string('government_id_url', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->foreignId('farmer_id')->constrained('farmers', 'farmer_id')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products', 'id')->cascadeOnDelete();
            $table->integer('quantity_sold');
            $table->integer('remaining_stock');
            $table->timestamp('report_date')->useCurrent();
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->foreignId('order_id')->constrained('orders', 'id')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('buyers', 'buyer_id')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products', 'id')->cascadeOnDelete();
            $table->tinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamp('review_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('inventory_reports');
        Schema::dropIfExists('farmers');
        Schema::dropIfExists('buyers');
        Schema::dropIfExists('associations');
    }
};