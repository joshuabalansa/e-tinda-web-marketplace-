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
        Schema::table('associations', function (Blueprint $table) {
            $table->string('slug', 255)->nullable()->unique()->after('name');
            $table->text('description')->nullable()->after('location');
            $table->string('logo_url', 255)->nullable()->after('description');
            $table->string('banner_url', 255)->nullable()->after('logo_url');
            $table->boolean('is_active')->default(true)->after('banner_url');
            $table->boolean('featured')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('associations', function (Blueprint $table) {
            $table->dropColumn(['slug', 'description', 'logo_url', 'banner_url', 'is_active', 'featured']);
        });
    }
};
