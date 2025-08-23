# Database Migrations - SiApotekPOS

## Urutan Migration yang Benar

### 1. Migration Dasar (Sudah Ada)
- `2014_10_12_000000_create_users_table.php`
- `2014_10_12_100000_create_password_resets_table.php`
- `2019_08_19_000000_create_failed_jobs_table.php`
- `2019_12_14_000001_create_personal_access_tokens_table.php`

### 2. Migration Struktur Utama
- `2025_01_15_000000_create_brands_table.php`
- `2025_08_03_112316_create_roles_table.php`
- `2025_08_03_112326_create_categories_table.php`
- `2025_08_03_112334_create_medicines_table.php`
- `2025_08_03_112342_create_sales_table.php`
- `2025_08_03_112350_create_sale_details_table.php`
- `2025_08_03_112453_add_role_id_to_users_table.php`
- `2025_08_03_120000_add_additional_fields_to_users_table.php`

### 3. Migration Pengaturan
- `2025_08_11_021358_create_app_settings_table.php`
- `2025_08_12_110310_add_settings_to_users_table.php`

### 4. Migration Master Data
- `2025_08_12_164843_create_manufacturers_table.php`
- `2025_08_12_165135_create_units_table.php`

### 5. Migration Struktur Tabel
- `2025_08_12_171904_add_manufacturer_id_and_unit_id_to_medicines_table.php`

### 6. Migration Invoice Counter
- `2025_08_12_180000_create_invoice_counters_table.php`

### 7. Migration Perbaikan Struktur (PENTING!)
- `2025_08_12_180100_fix_medicines_table_structure.php`

## Migration yang Deprecated

### ⚠️ JANGAN JALANKAN
- `2025_08_12_173345_update_existing_medicines_with_foreign_keys.php` - **DEPRECATED**

Migration ini sudah digantikan dengan `fix_medicines_table_structure.php` yang lebih aman.

## Catatan Penting

1. **Urutan Penting**: Pastikan tabel `units` dan `manufacturers` dibuat sebelum menjalankan migration yang mengupdate struktur tabel `medicines`.

2. **Migration yang Aman**: Gunakan `fix_medicines_table_structure.php` untuk memperbaiki struktur tabel medicines.

3. **Invoice Counter**: Migration untuk tabel `invoice_counters` sudah dibuat dan aman untuk dijalankan.

## Cara Menjalankan Migration

```bash
# Jalankan semua migration
php artisan migrate

# Jika ada error, jalankan satu per satu sesuai urutan di atas
php artisan migrate --path=database/migrations/2014_10_12_000000_create_users_table.php
# dst...
```

## Troubleshooting

Jika ada error saat menjalankan migration:

1. Pastikan semua tabel dependencies sudah dibuat
2. Jalankan migration satu per satu sesuai urutan
3. Gunakan `php artisan migrate:status` untuk melihat status migration
4. Gunakan `php artisan migrate:rollback` jika perlu rollback
