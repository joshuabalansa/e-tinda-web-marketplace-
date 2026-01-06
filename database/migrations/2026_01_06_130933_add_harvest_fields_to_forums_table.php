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
        Schema::table('forums', function (Blueprint $table) {
            $table->string('product_name')->nullable()->after('category');
            $table->date('harvest_start_date')->nullable()->after('product_name');
            $table->date('harvest_end_date')->nullable()->after('harvest_start_date');
            $table->string('harvest_season')->nullable()->after('harvest_end_date');
            $table->boolean('is_harvest_post')->default(false)->after('harvest_season');
            $table->string('product_category')->nullable()->after('is_harvest_post');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forums', function (Blueprint $table) {
            $table->dropColumn([
                'product_name',
                'harvest_start_date',
                'harvest_end_date',
                'harvest_season',
                'is_harvest_post',
                'product_category'
            ]);
        });
    }
};
