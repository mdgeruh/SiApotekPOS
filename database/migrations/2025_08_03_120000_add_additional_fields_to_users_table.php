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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('profile_photo_path')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('profile_photo_path');
            $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
            $table->text('address')->nullable()->after('gender');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'phone', 'profile_photo_path', 'birth_date', 'gender', 'address', 'status']);
        });
    }
};
