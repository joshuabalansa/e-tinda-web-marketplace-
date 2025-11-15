<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('forum_replies', function (Blueprint $table) {
            // Use string with check constraint for better cross-database compatibility
            $table->string('status')->default('active');
            $table->boolean('is_flagged')->default(false);
            $table->text('moderation_notes')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('moderated_at')->nullable();
        });

        // Note: CHECK constraints are handled at the application level for better cross-database compatibility
        // SQLite doesn't support adding CHECK constraints to existing tables
        // MySQL/PostgreSQL support is handled through the string column type
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forum_replies', function (Blueprint $table) {
            $table->dropForeign(['moderated_by']);
            $table->dropColumn(['status', 'is_flagged', 'moderation_notes', 'moderated_by', 'moderated_at']);
        });
    }
};
