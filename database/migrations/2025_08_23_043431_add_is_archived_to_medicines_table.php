<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('expired_date');
            $table->text('archive_reason')->nullable()->after('is_archived');
            $table->timestamp('archived_at')->nullable()->after('archive_reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'archive_reason', 'archived_at']);
        });
    }
};
