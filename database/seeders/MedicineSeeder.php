<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil ID kategori yang sudah ada
        $antibiotikId = DB::table('categories')->where('name', 'Antibiotik')->value('id');
        $analgesikId = DB::table('categories')->where('name', 'Analgesik')->value('id');
        $antipiretikId = DB::table('categories')->where('name', 'Antipiretik')->value('id');
        $vitaminId = DB::table('categories')->where('name', 'Vitamin')->value('id');
        $obatLuarId = DB::table('categories')->where('name', 'Obat Luar')->value('id');

        // Ambil ID brand yang sudah ada
        $kimiaFarmaId = DB::table('brands')->where('name', 'Kimia Farma')->value('id');
        $dexaMedicaId = DB::table('brands')->where('name', 'Dexa Medica')->value('id');
        $kalbeFarmaId = DB::table('brands')->where('name', 'Kalbe Farma')->value('id');
        $pfizerId = DB::table('brands')->where('name', 'Pfizer')->value('id');

        // Ambil ID manufacturer yang sudah ada
        $kimiaFarmaManufacturerId = DB::table('manufacturers')->where('name', 'Kimia Farma')->value('id');
        $dexaMedicaManufacturerId = DB::table('manufacturers')->where('name', 'Dexa Medica')->value('id');
        $kalbeFarmaManufacturerId = DB::table('manufacturers')->where('name', 'Kalbe Farma')->value('id');
        $pfizerManufacturerId = DB::table('manufacturers')->where('name', 'Pfizer')->value('id');
        $bodrexManufacturerId = DB::table('manufacturers')->where('name', 'Bodrex')->value('id');
        $prorisManufacturerId = DB::table('manufacturers')->where('name', 'Proris')->value('id');
        $ponstanManufacturerId = DB::table('manufacturers')->where('name', 'Ponstan')->value('id');
        $voltarenManufacturerId = DB::table('manufacturers')->where('name', 'Voltaren')->value('id');
        $sanbeManufacturerId = DB::table('manufacturers')->where('name', 'Sanbe')->value('id');
        $bayerManufacturerId = DB::table('manufacturers')->where('name', 'Bayer')->value('id');
        $enervonManufacturerId = DB::table('manufacturers')->where('name', 'Enervon')->value('id');
        $sakatonikManufacturerId = DB::table('manufacturers')->where('name', 'Sakatonik ABC')->value('id');
        $blackmoresManufacturerId = DB::table('manufacturers')->where('name', 'Blackmores')->value('id');
        $naturePlusManufacturerId = DB::table('manufacturers')->where('name', 'Nature Plus')->value('id');
        $mundipharmaManufacturerId = DB::table('manufacturers')->where('name', 'Mundipharma')->value('id');
        $rohtoManufacturerId = DB::table('manufacturers')->where('name', 'Rohto')->value('id');
        $caladineManufacturerId = DB::table('manufacturers')->where('name', 'Caladine')->value('id');
        $bioplacentonManufacturerId = DB::table('manufacturers')->where('name', 'Bioplacenton')->value('id');
        $capLangManufacturerId = DB::table('manufacturers')->where('name', 'Cap Lang')->value('id');

        // Ambil ID unit yang sudah ada
        $tabletUnitId = DB::table('units')->where('name', 'tablet')->value('id');
        $botolUnitId = DB::table('units')->where('name', 'botol')->value('id');
        $suppositoriaUnitId = DB::table('units')->where('name', 'suppositoria')->value('id');
        $tubeUnitId = DB::table('units')->where('name', 'tube')->value('id');

        // Generate kode obat dengan format OBT + timestamp + random number
        $generateCode = function($index) {
            $timestamp = '202508'; // Bulan dan tahun tetap untuk konsistensi
            $random = str_pad($index, 3, '0', STR_PAD_LEFT);
            return 'OBT' . $timestamp . $random;
        };

        // Hapus data lama
        DB::table('medicines')->delete();

        DB::table('medicines')->insert([
            // ===== OBAT TERSEDIA (Stock > min_stock, belum expired) =====

            // 1. Amoxicillin 500mg - Tersedia
            [
                'name' => 'Amoxicillin 500mg',
                'description' => 'Antibiotik untuk infeksi saluran pernapasan dan saluran kemih',
                'code' => $generateCode(1),
                'category_id' => $antibiotikId,
                'brand_id' => $kimiaFarmaId,
                'manufacturer_id' => $kimiaFarmaManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 15000.00,
                'purchase_price' => 12000.00,
                'selling_price' => 15000.00,
                'stock' => 150,
                'min_stock' => 10,
                'expired_date' => '2025-08-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 2. Paracetamol 500mg - Tersedia
            [
                'name' => 'Paracetamol 500mg',
                'description' => 'Obat penghilang rasa sakit dan penurun demam',
                'code' => $generateCode(2),
                'category_id' => $antipiretikId,
                'brand_id' => $bodrexManufacturerId,
                'manufacturer_id' => $bodrexManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 5000.00,
                'purchase_price' => 4000.00,
                'selling_price' => 5000.00,
                'stock' => 300,
                'min_stock' => 20,
                'expired_date' => '2025-08-23',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 3. Ibuprofen 400mg - Tersedia
            [
                'name' => 'Ibuprofen 400mg',
                'description' => 'Obat anti inflamasi non steroid untuk nyeri dan demam',
                'code' => $generateCode(3),
                'category_id' => $analgesikId,
                'brand_id' => $prorisManufacturerId,
                'manufacturer_id' => $prorisManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 8000.00,
                'purchase_price' => 6500.00,
                'selling_price' => 8000.00,
                'stock' => 200,
                'min_stock' => 15,
                'expired_date' => '2027-03-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 4. Vitamin C 1000mg - Tersedia
            [
                'name' => 'Vitamin C 1000mg',
                'description' => 'Suplemen vitamin C untuk daya tahan tubuh',
                'code' => $generateCode(4),
                'category_id' => $vitaminId,
                'brand_id' => $enervonManufacturerId,
                'manufacturer_id' => $enervonManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 25000.00,
                'purchase_price' => 20000.00,
                'selling_price' => 25000.00,
                'stock' => 180,
                'min_stock' => 25,
                'expired_date' => '2027-08-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 5. Cetirizine 10mg - Tersedia
            [
                'name' => 'Cetirizine 10mg',
                'description' => 'Antihistamin untuk alergi dan rinitis',
                'code' => $generateCode(5),
                'category_id' => $analgesikId,
                'brand_id' => $dexaMedicaId,
                'manufacturer_id' => $dexaMedicaManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 12000.00,
                'purchase_price' => 10000.00,
                'selling_price' => 12000.00,
                'stock' => 120,
                'min_stock' => 15,
                'expired_date' => '2026-11-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===== OBAT AKAN HABIS (Stock mendekati min_stock) =====

            // 6. Ciprofloxacin 500mg - Akan habis
            [
                'name' => 'Ciprofloxacin 500mg',
                'description' => 'Antibiotik untuk infeksi saluran pencernaan',
                'code' => $generateCode(6),
                'category_id' => $antibiotikId,
                'brand_id' => $dexaMedicaId,
                'manufacturer_id' => $dexaMedicaManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 25000.00,
                'purchase_price' => 20000.00,
                'selling_price' => 25000.00,
                'stock' => 12,
                'min_stock' => 10,
                'expired_date' => '2026-10-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 7. Mefenamic Acid 500mg - Akan habis
            [
                'name' => 'Mefenamic Acid 500mg',
                'description' => 'Obat penghilang nyeri untuk sakit kepala dan nyeri otot',
                'code' => $generateCode(7),
                'category_id' => $analgesikId,
                'brand_id' => $ponstanManufacturerId,
                'manufacturer_id' => $ponstanManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 12000.00,
                'purchase_price' => 10000.00,
                'selling_price' => 12000.00,
                'stock' => 8,
                'min_stock' => 10,
                'expired_date' => '2026-09-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 8. Omeprazole 20mg - Akan habis
            [
                'name' => 'Omeprazole 20mg',
                'description' => 'Obat untuk mengatasi asam lambung dan maag',
                'code' => $generateCode(8),
                'category_id' => $analgesikId,
                'brand_id' => $kalbeFarmaId,
                'manufacturer_id' => $kalbeFarmaManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 18000.00,
                'purchase_price' => 15000.00,
                'selling_price' => 18000.00,
                'stock' => 15,
                'min_stock' => 10,
                'expired_date' => '2026-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 9. Ranitidine 150mg - Akan habis
            [
                'name' => 'Ranitidine 150mg',
                'description' => 'Obat untuk mengatasi asam lambung',
                'code' => $generateCode(9),
                'category_id' => $analgesikId,
                'brand_id' => $kimiaFarmaId,
                'manufacturer_id' => $kimiaFarmaManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 10000.00,
                'purchase_price' => 8000.00,
                'selling_price' => 10000.00,
                'stock' => 11,
                'min_stock' => 10,
                'expired_date' => '2026-08-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 10. Metformin 500mg - Akan habis
            [
                'name' => 'Metformin 500mg',
                'description' => 'Obat untuk mengontrol kadar gula darah pada diabetes',
                'code' => $generateCode(10),
                'category_id' => $analgesikId,
                'brand_id' => $dexaMedicaId,
                'manufacturer_id' => $dexaMedicaManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 15000.00,
                'purchase_price' => 12000.00,
                'selling_price' => 15000.00,
                'stock' => 13,
                'min_stock' => 10,
                'expired_date' => '2026-11-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===== OBAT HABIS/KOSONG (Stock = 0) =====

            // 11. Doxycycline 100mg - Habis
            [
                'name' => 'Doxycycline 100mg',
                'description' => 'Antibiotik untuk infeksi bakteri',
                'code' => $generateCode(11),
                'category_id' => $antibiotikId,
                'brand_id' => $kalbeFarmaId,
                'manufacturer_id' => $kalbeFarmaManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 18000.00,
                'purchase_price' => 15000.00,
                'selling_price' => 18000.00,
                'stock' => 0,
                'min_stock' => 10,
                'expired_date' => '2026-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 12. Azithromycin 500mg - Habis
            [
                'name' => 'Azithromycin 500mg',
                'description' => 'Antibiotik untuk infeksi saluran pernapasan',
                'code' => $generateCode(12),
                'category_id' => $antibiotikId,
                'brand_id' => $pfizerId,
                'manufacturer_id' => $pfizerManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 30000.00,
                'purchase_price' => 25000.00,
                'selling_price' => 30000.00,
                'stock' => 0,
                'min_stock' => 10,
                'expired_date' => '2026-08-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 13. Diclofenac 50mg - Habis
            [
                'name' => 'Diclofenac 50mg',
                'description' => 'Obat anti inflamasi untuk nyeri dan peradangan',
                'code' => $generateCode(13),
                'category_id' => $analgesikId,
                'brand_id' => $voltarenManufacturerId,
                'manufacturer_id' => $voltarenManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 15000.00,
                'purchase_price' => 12000.00,
                'selling_price' => 15000.00,
                'stock' => 0,
                'min_stock' => 10,
                'expired_date' => '2026-10-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 14. Vitamin B Complex - Habis
            [
                'name' => 'Vitamin B Complex',
                'description' => 'Suplemen vitamin B untuk metabolisme tubuh',
                'code' => $generateCode(14),
                'category_id' => $vitaminId,
                'brand_id' => $sakatonikManufacturerId,
                'manufacturer_id' => $sakatonikManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 20000.00,
                'purchase_price' => 16000.00,
                'selling_price' => 20000.00,
                'stock' => 0,
                'min_stock' => 15,
                'expired_date' => '2027-01-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 15. Calcium Carbonate 500mg - Habis
            [
                'name' => 'Calcium Carbonate 500mg',
                'description' => 'Suplemen kalsium untuk kesehatan tulang',
                'code' => $generateCode(15),
                'category_id' => $vitaminId,
                'brand_id' => $naturePlusManufacturerId,
                'manufacturer_id' => $naturePlusManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 18000.00,
                'purchase_price' => 14000.00,
                'selling_price' => 18000.00,
                'stock' => 0,
                'min_stock' => 20,
                'expired_date' => '2027-03-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===== OBAT AKAN EXPIRED (Expired dalam 3 bulan ke depan) =====

            // 16. Erythromycin 250mg - Akan expired
            [
                'name' => 'Erythromycin 250mg',
                'description' => 'Antibiotik untuk infeksi kulit',
                'code' => $generateCode(16),
                'category_id' => $antibiotikId,
                'brand_id' => $kimiaFarmaId,
                'manufacturer_id' => $kimiaFarmaManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 22000.00,
                'purchase_price' => 18000.00,
                'selling_price' => 22000.00,
                'stock' => 45,
                'min_stock' => 10,
                'expired_date' => '2025-03-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 17. Amlodipine 5mg - Akan expired
            [
                'name' => 'Amlodipine 5mg',
                'description' => 'Obat untuk tekanan darah tinggi',
                'code' => $generateCode(17),
                'category_id' => $analgesikId,
                'brand_id' => $dexaMedicaId,
                'manufacturer_id' => $dexaMedicaManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 12000.00,
                'purchase_price' => 10000.00,
                'selling_price' => 12000.00,
                'stock' => 60,
                'min_stock' => 10,
                'expired_date' => '2025-04-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 18. Simvastatin 20mg - Akan expired
            [
                'name' => 'Simvastatin 20mg',
                'description' => 'Obat untuk menurunkan kolesterol',
                'code' => $generateCode(18),
                'category_id' => $analgesikId,
                'brand_id' => $kalbeFarmaId,
                'manufacturer_id' => $kalbeFarmaManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 20000.00,
                'purchase_price' => 16000.00,
                'selling_price' => 20000.00,
                'stock' => 35,
                'min_stock' => 10,
                'expired_date' => '2025-05-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 19. Vitamin D3 1000 IU - Akan expired
            [
                'name' => 'Vitamin D3 1000 IU',
                'description' => 'Suplemen vitamin D untuk kesehatan tulang',
                'code' => $generateCode(19),
                'category_id' => $vitaminId,
                'brand_id' => $blackmoresManufacturerId,
                'manufacturer_id' => $blackmoresManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 35000.00,
                'purchase_price' => 28000.00,
                'selling_price' => 35000.00,
                'stock' => 25,
                'min_stock' => 15,
                'expired_date' => '2025-06-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 20. Fish Oil 1000mg - Akan expired
            [
                'name' => 'Fish Oil 1000mg',
                'description' => 'Suplemen omega-3 untuk kesehatan jantung',
                'code' => $generateCode(20),
                'category_id' => $vitaminId,
                'brand_id' => $blackmoresManufacturerId,
                'manufacturer_id' => $blackmoresManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 45000.00,
                'purchase_price' => 35000.00,
                'selling_price' => 45000.00,
                'stock' => 40,
                'min_stock' => 20,
                'expired_date' => '2025-04-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===== OBAT SUDAH EXPIRED =====

            // 21. Cefadroxil 500mg - Expired
            [
                'name' => 'Cefadroxil 500mg',
                'description' => 'Antibiotik untuk infeksi saluran kemih',
                'code' => $generateCode(21),
                'category_id' => $antibiotikId,
                'brand_id' => $dexaMedicaId,
                'manufacturer_id' => $dexaMedicaManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 28000.00,
                'purchase_price' => 22000.00,
                'selling_price' => 28000.00,
                'stock' => 15,
                'min_stock' => 10,
                'expired_date' => '2024-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 22. Ketoconazole 200mg - Expired
            [
                'name' => 'Ketoconazole 200mg',
                'description' => 'Antijamur untuk infeksi kulit',
                'code' => $generateCode(22),
                'category_id' => $antibiotikId,
                'brand_id' => $kimiaFarmaId,
                'manufacturer_id' => $kimiaFarmaManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 18000.00,
                'purchase_price' => 15000.00,
                'selling_price' => 18000.00,
                'stock' => 8,
                'min_stock' => 10,
                'expired_date' => '2024-11-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 23. Aspirin 100mg - Expired
            [
                'name' => 'Aspirin 100mg',
                'description' => 'Obat pengencer darah dan penghilang nyeri',
                'code' => $generateCode(23),
                'category_id' => $analgesikId,
                'brand_id' => $bayerManufacturerId,
                'manufacturer_id' => $bayerManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 10000.00,
                'purchase_price' => 8000.00,
                'selling_price' => 10000.00,
                'stock' => 25,
                'min_stock' => 15,
                'expired_date' => '2024-10-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 24. Multivitamin - Expired
            [
                'name' => 'Multivitamin',
                'description' => 'Suplemen multivitamin lengkap',
                'code' => $generateCode(24),
                'category_id' => $vitaminId,
                'brand_id' => $enervonManufacturerId,
                'manufacturer_id' => $enervonManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 30000.00,
                'purchase_price' => 24000.00,
                'selling_price' => 30000.00,
                'stock' => 30,
                'min_stock' => 20,
                'expired_date' => '2024-09-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 25. Eye Drops - Expired
            [
                'name' => 'Eye Drops',
                'description' => 'Obat tetes mata untuk mata merah dan iritasi',
                'code' => $generateCode(25),
                'category_id' => $obatLuarId,
                'brand_id' => $rohtoManufacturerId,
                'manufacturer_id' => $rohtoManufacturerId,
                'unit_id' => $botolUnitId,
                'price' => 25000.00,
                'purchase_price' => 20000.00,
                'selling_price' => 25000.00,
                'stock' => 12,
                'min_stock' => 10,
                'expired_date' => '2024-08-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===== OBAT TAMBAHAN UNTUK MELENGKAPI 30 OBAT =====

            // 26. Salbutamol 2mg - Tersedia
            [
                'name' => 'Salbutamol 2mg',
                'description' => 'Obat untuk asma dan sesak napas',
                'code' => $generateCode(26),
                'category_id' => $analgesikId,
                'brand_id' => $dexaMedicaId,
                'manufacturer_id' => $dexaMedicaManufacturerId,
                'unit_id' => $tabletUnitId,
                'price' => 15000.00,
                'purchase_price' => 12000.00,
                'selling_price' => 15000.00,
                'stock' => 80,
                'min_stock' => 10,
                'expired_date' => '2027-02-28',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 27. Betadine Solution - Tersedia
            [
                'name' => 'Betadine Solution',
                'description' => 'Antiseptik untuk luka dan infeksi kulit',
                'code' => $generateCode(27),
                'category_id' => $obatLuarId,
                'brand_id' => $mundipharmaManufacturerId,
                'manufacturer_id' => $mundipharmaManufacturerId,
                'unit_id' => $botolUnitId,
                'price' => 35000.00,
                'purchase_price' => 28000.00,
                'selling_price' => 35000.00,
                'stock' => 45,
                'min_stock' => 15,
                'expired_date' => '2027-05-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 28. Caladine Lotion - Tersedia
            [
                'name' => 'Caladine Lotion',
                'description' => 'Lotion untuk mengatasi gatal dan iritasi kulit',
                'code' => $generateCode(28),
                'category_id' => $obatLuarId,
                'brand_id' => $caladineManufacturerId,
                'manufacturer_id' => $caladineManufacturerId,
                'unit_id' => $tubeUnitId,
                'price' => 28000.00,
                'purchase_price' => 22000.00,
                'selling_price' => 28000.00,
                'stock' => 60,
                'min_stock' => 20,
                'expired_date' => '2027-07-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 29. Bioplacenton Cream - Tersedia
            [
                'name' => 'Bioplacenton Cream',
                'description' => 'Krim untuk luka dan bekas luka',
                'code' => $generateCode(29),
                'category_id' => $obatLuarId,
                'brand_id' => $bioplacentonManufacturerId,
                'manufacturer_id' => $bioplacentonManufacturerId,
                'unit_id' => $tubeUnitId,
                'price' => 40000.00,
                'purchase_price' => 32000.00,
                'selling_price' => 40000.00,
                'stock' => 35,
                'min_stock' => 15,
                'expired_date' => '2027-04-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 30. Cap Lang - Tersedia
            [
                'name' => 'Cap Lang',
                'description' => 'Obat herbal untuk masuk angin dan perut kembung',
                'code' => $generateCode(30),
                'category_id' => $obatLuarId,
                'brand_id' => $capLangManufacturerId,
                'manufacturer_id' => $capLangManufacturerId,
                'unit_id' => $botolUnitId,
                'price' => 15000.00,
                'purchase_price' => 12000.00,
                'selling_price' => 15000.00,
                'stock' => 90,
                'min_stock' => 25,
                'expired_date' => '2027-09-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('Medicine seeder berhasil dibuat dengan 30 obat yang bervariasi kondisi stoknya!');
        $this->command->info('Kondisi obat:');
        $this->command->info('- Tersedia: 15 obat');
        $this->command->info('- Akan habis: 5 obat');
        $this->command->info('- Habis/kosong: 5 obat');
        $this->command->info('- Akan expired: 5 obat');
        $this->command->info('- Sudah expired: 5 obat');
    }
}
