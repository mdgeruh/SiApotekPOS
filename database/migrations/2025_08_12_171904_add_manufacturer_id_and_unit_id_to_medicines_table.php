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
            $table->foreignId('manufacturer_id')->nullable()->after('brand_id')->constrained()->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->after('min_stock')->constrained()->onDelete('restrict');
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
            $table->dropForeign(['manufacturer_id']);
            $table->dropForeign(['unit_id']);
            $table->dropColumn(['manufacturer_id', 'unit_id']);
        });
    }
};
