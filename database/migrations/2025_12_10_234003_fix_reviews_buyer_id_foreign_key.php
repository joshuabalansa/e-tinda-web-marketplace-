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
        // Drop the old foreign key constraint if it exists
        try {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropForeign(['buyer_id']);
            });
        } catch (\Exception $e) {
            // Try alternative constraint name
            try {
                \DB::statement('ALTER TABLE reviews DROP FOREIGN KEY reviews_buyer_id_foreign');
            } catch (\Exception $e2) {
                // Constraint might not exist or have different name, continue
            }
        }

        Schema::table('reviews', function (Blueprint $table) {
            // Add new foreign key constraint referencing users table
            $table->foreign('buyer_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Drop the users foreign key
            $table->dropForeign(['buyer_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            // Restore the original buyers foreign key
            $table->foreign('buyer_id')
                  ->references('buyer_id')
                  ->on('buyers')
                  ->onDelete('cascade');
        });
    }
};
