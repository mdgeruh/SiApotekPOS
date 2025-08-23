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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('Apotek POS');
            $table->string('pharmacy_name')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('license_number')->nullable();
            $table->string('owner_name')->nullable();
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('currency', 10)->default('IDR');
            $table->string('timezone')->default('Asia/Jakarta');
            $table->boolean('maintenance_mode')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_settings');
    }
};
