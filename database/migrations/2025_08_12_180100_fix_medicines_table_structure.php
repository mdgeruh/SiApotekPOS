<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Pastikan tabel units dan manufacturers sudah ada
        if (!Schema::hasTable('units') || !Schema::hasTable('manufacturers')) {
            throw new \Exception('Tabel units dan manufacturers harus sudah ada sebelum menjalankan migration ini');
        }

        // Update existing medicines to use proper foreign keys
        $medicines = DB::table('medicines')->get();

        foreach ($medicines as $medicine) {
            $updates = [];

            // Update unit_id based on old unit field
            if (isset($medicine->unit) && $medicine->unit) {
                $unit = DB::table('units')->where('name', $medicine->unit)->first();
                if ($unit) {
                    $updates['unit_id'] = $unit->id;
                } else {
                    // If unit doesn't exist, set to default (Tablet)
                    $defaultUnit = DB::table('units')->where('name', 'Tablet')->first();
                    if ($defaultUnit) {
                        $updates['unit_id'] = $defaultUnit->id;
                    }
                }
            }

            // Update manufacturer_id based on old manufacturer field
            if (isset($medicine->manufacturer) && $medicine->manufacturer) {
                $manufacturer = DB::table('manufacturers')->where('name', $medicine->manufacturer)->first();
                if ($manufacturer) {
                    $updates['manufacturer_id'] = $manufacturer->id;
                }
            }

            // Update medicine jika ada perubahan
            if (!empty($updates)) {
                DB::table('medicines')
                    ->where('id', $medicine->id)
                    ->update($updates);
            }
        }

        // Drop old columns setelah data berhasil diupdate
        Schema::table('medicines', function (Blueprint $table) {
            if (Schema::hasColumn('medicines', 'unit')) {
                $table->dropColumn('unit');
            }
            if (Schema::hasColumn('medicines', 'manufacturer')) {
                $table->dropColumn('manufacturer');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add back the old columns
        Schema::table('medicines', function (Blueprint $table) {
            $table->string('unit')->nullable();
            $table->string('manufacturer')->nullable();
        });

        // Restore old data
        $medicines = DB::table('medicines')->get();
        foreach ($medicines as $medicine) {
            $updates = [];

            if ($medicine->unit_id) {
                $unit = DB::table('units')->find($medicine->unit_id);
                if ($unit) {
                    $updates['unit'] = $unit->name;
                }
            }

            if ($medicine->manufacturer_id) {
                $manufacturer = DB::table('manufacturers')->find($medicine->manufacturer_id);
                if ($manufacturer) {
                    $updates['manufacturer'] = $manufacturer->name;
                }
            }

            if (!empty($updates)) {
                DB::table('medicines')
                    ->where('id', $medicine->id)
                    ->update($updates);
            }
        }
    }
};
