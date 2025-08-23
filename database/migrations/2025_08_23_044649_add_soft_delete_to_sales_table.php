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
        Schema::table('sales', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('notes');
            $table->string('archive_reason')->nullable()->after('is_archived');
            $table->timestamp('archived_at')->nullable()->after('archive_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'archive_reason', 'archived_at']);
        });
    }
};
