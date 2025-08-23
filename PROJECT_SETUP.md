# SiApotekPOS - Setup dan Dokumentasi Proyek

## Daftar Isi
1. [Sistem Judul Dinamis](#sistem-judul-dinamis)
2. [Fitur Thumbnail Management](#fitur-thumbnail-management)
3. [Sistem Notifikasi](#sistem-notifikasi)
4. [Image Cleanup Feature](#image-cleanup-feature)
5. [Panduan Setup Local Server](#panduan-setup-siapotekpos---local-server)
6. [Konfigurasi Web Server](#konfigurasi-web-server)
7. [Setup dengan Laravel Sail](#setup-dengan-laravel-sail-docker)
8. [Troubleshooting](#troubleshooting)
9. [Verifikasi Instalasi](#verifikasi-instalasi)
10. [Development Workflow](#development-workflow)
11. [Backup dan Restore](#backup-dan-restore)
12. [Maintenance](#maintenance)
13. [Fitur yang Telah Diimplementasikan](#fitur-yang-telah-diimplementasikan)
14. [Changelog](#changelog)

---

## Sistem Judul Dinamis

### Overview
Sistem judul dinamis telah diimplementasikan untuk memberikan pengalaman pengguna yang lebih baik dengan menampilkan judul halaman yang sesuai dengan view yang aktif.

### Fitur Utama

#### 1. Judul Halaman Otomatis
- **Browser Title**: Judul di tab browser akan berubah sesuai halaman yang aktif
- **Format**: `Judul Halaman - Nama Aplikasi` (contoh: "Daftar Obat - Apotek POS")
- **Dashboard**: Hanya menampilkan nama aplikasi tanpa suffix

#### 2. Komponen Page Header
- **Judul Halaman**: Menampilkan judul halaman dengan ikon yang sesuai
- **Actions**: Slot untuk tombol aksi (tambah, edit, dll.)
- **Konsistensi**: Desain seragam di semua halaman

#### 3. Breadcrumb Navigation
- **Hierarki**: Menampilkan struktur navigasi halaman
- **Link Aktif**: Link ke halaman sebelumnya yang dapat diklik
- **Halaman Aktif**: Menampilkan halaman saat ini

### Implementasi

#### Helper Functions (`AppSettingHelper`)

```php
// Mendapatkan judul halaman berdasarkan route
AppSettingHelper::pageTitle()

// Mendapatkan judul lengkap dengan nama aplikasi
AppSettingHelper::fullPageTitle()

// Mendapatkan data breadcrumb
AppSettingHelper::breadcrumbs()

// Mendapatkan ikon halaman
AppSettingHelper::pageIcon()
```

#### Komponen Blade

```blade
<!-- Page Header dengan Actions -->
@component('components.page-header')
    @slot('actions')
        <a href="{{ route('medicines.create') }}" class="btn btn-primary">
            <i class="fas fa-plus fa-sm me-1"></i>
            Tambah Obat
        </a>
    @endslot
@endcomponent

<!-- Breadcrumb -->
@include('components.breadcrumb')
```

### Mapping Route ke Judul

| Route | Judul Halaman | Ikon |
|-------|---------------|------|
| `dashboard` | Dashboard | `fas fa-tachometer-alt` |
| `medicines.index` | Daftar Obat | `fas fa-pills` |
| `medicines.create` | Tambah Obat Baru | `fas fa-plus-circle` |
| `medicines.edit` | Edit Obat | `fas fa-edit` |
| `medicines.show` | Detail Obat | `fas fa-eye` |
| `categories.index` | Daftar Kategori | `fas fa-tags` |
| `categories.create` | Tambah Kategori | `fas fa-plus-circle` |
| `categories.edit` | Edit Kategori | `fas fa-edit` |
| `brands.index` | Daftar Merek | `fas fa-trademark` |
| `manufacturers.index` | Daftar Produsen | `fas fa-industry` |
| `units.index` | Daftar Satuan | `fas fa-ruler` |
| `sales.index` | Daftar Penjualan | `fas fa-shopping-cart` |
| `sales.create` | Transaksi Baru | `fas fa-plus-circle` |
| `sales.show` | Detail Penjualan | `fas fa-eye` |
| `reports.sales` | Laporan Penjualan | `fas fa-chart-bar` |
| `reports.statistics` | Laporan Statistik | `fas fa-chart-line` |
| `reports.stock` | Laporan Stok | `fas fa-boxes` |
| `users.index` | Manajemen Pengguna | `fas fa-users` |
| `users.create` | Tambah Pengguna | `fas fa-user-plus` |
| `users.edit` | Edit Pengguna | `fas fa-user-edit` |
| `app-settings.index` | Pengaturan Aplikasi | `fas fa-cog` |

### Halaman yang Telah Diupdate

1. **Dashboard** (`/dashboard`)
2. **Medicines** (`/medicines`, `/medicines/create`, `/medicines/edit`, `/medicines/show`)
3. **Categories** (`/categories`, `/categories/create`, `/categories/edit`, `/categories/show`)
4. **Brands** (`/brands`)
5. **Manufacturers** (`/manufacturers`)
6. **Units** (`/units`)
7. **Sales** (`/sales`, `/sales/create`, `/sales/show`)
8. **Reports** (`/reports/sales`, `/reports/statistics`, `/reports/stock`)
9. **Users** (`/users`, `/users/create`, `/users/edit`, `/users/show`)
10. **App Settings** (`/app-settings`)
11. **Profile** (`/profile`, `/profile/change-password`, `/profile/settings`, `/profile/activities`)

### Keuntungan

1. **UX yang Lebih Baik**: Pengguna tahu di halaman mana mereka berada
2. **Navigasi yang Jelas**: Breadcrumb membantu navigasi antar halaman
3. **Konsistensi Visual**: Desain seragam di semua halaman
4. **SEO Friendly**: Judul halaman yang deskriptif
5. **Maintenance Mudah**: Centralized management melalui helper

### Cara Menambahkan Halaman Baru

1. **Update Helper**: Tambahkan mapping di `AppSettingHelper::pageTitle()`
2. **Update Breadcrumb**: Tambahkan data breadcrumb di `AppSettingHelper::breadcrumbs()`
3. **Update Icon**: Tambahkan ikon di `AppSettingHelper::pageIcon()`
4. **Update View**: Gunakan komponen `@component('components.page-header')`

### Contoh Penggunaan

```php
// Di controller atau view
$pageTitle = AppSettingHelper::pageTitle();
$breadcrumbs = AppSettingHelper::breadcrumbs();
$pageIcon = AppSettingHelper::pageIcon();
```

```blade
<!-- Di view -->
<title>{{ \App\Helpers\AppSettingHelper::fullPageTitle() }}</title>

@component('components.page-header')
    @slot('actions')
        <!-- Tombol aksi -->
    @endslot
@endcomponent

@include('components.breadcrumb')
```

---

## Fitur Thumbnail Management

### Overview
Sistem thumbnail management telah diimplementasikan untuk memberikan pengalaman yang lebih baik dalam manajemen gambar obat.

### Fitur Utama
- **Upload Gambar**: Drag & drop atau click to upload
- **Preview Circular**: Tampilan gambar dalam bentuk lingkaran
- **Resize Otomatis**: Optimasi ukuran gambar
- **Thumbnail Manager**: JavaScript untuk manajemen gambar
- **Image Cleanup**: Pembersihan gambar yang tidak terpakai

### Implementasi
```javascript
// Thumbnail manager untuk obat
class ThumbnailManager {
    constructor() {
        this.initializeUpload();
        this.initializePreview();
        this.initializeCleanup();
    }
    
    initializeUpload() {
        // Logic untuk upload gambar
    }
    
    initializePreview() {
        // Logic untuk preview gambar
    }
    
    initializeCleanup() {
        // Logic untuk cleanup gambar
    }
}
```

### Image Cleanup Feature
- **Otomatis**: Pembersihan gambar yang tidak terpakai secara berkala
- **Manual**: Command artisan untuk cleanup manual
- **Logging**: Pencatatan aktivitas cleanup
- **Safety Check**: Verifikasi sebelum penghapusan

---

## Sistem Notifikasi

### Overview
Sistem notifikasi telah diimplementasikan untuk memberikan feedback yang lebih baik kepada pengguna.

### Fitur Utama
- **Toast Notifications**: Notifikasi popup yang tidak mengganggu
- **Alert Messages**: Pesan alert untuk informasi penting
- **Success/Error Handling**: Feedback untuk operasi berhasil/gagal
- **Auto-dismiss**: Notifikasi otomatis hilang setelah beberapa detik

### Implementasi
```javascript
// Notification system
class NotificationSystem {
    static show(message, type = 'info', duration = 5000) {
        // Logic untuk menampilkan notifikasi
    }
    
    static success(message, duration = 5000) {
        this.show(message, 'success', duration);
    }
    
    static error(message, duration = 8000) {
        this.show(message, 'error', duration);
    }
}
```

---

## Panduan Setup SiApotekPOS - Local Server

## Prerequisites (Persyaratan Sistem)

### Software yang Diperlukan
- **PHP**: Versi 8.1 atau lebih tinggi
- **Composer**: Versi 2.0 atau lebih tinggi
- **Node.js**: Versi 16.0 atau lebih tinggi
- **NPM**: Versi 8.0 atau lebih tinggi
- **Database**: MySQL 8.0+ atau PostgreSQL 13+
- **Web Server**: Apache/Nginx (atau gunakan Laravel Sail/Valet)

### Ekstensi PHP yang Diperlukan
```bash
# Ekstensi yang harus diaktifkan di php.ini
extension=bcmath
extension=ctype
extension=curl
extension=dom
extension=fileinfo
extension=gd
extension=iconv
extension=intl
extension=json
extension=mbstring
extension=openssl
extension=pdo
extension=pdo_mysql
extension=pdo_pgsql
extension=phar
extension=tokenizer
extension=xml
extension=zip
```

### Tools Development (Opsional)
- **Laravel Sail**: Docker environment untuk Laravel
- **Laravel Valet**: Development environment untuk macOS
- **XAMPP/WAMP**: Package web server untuk Windows
- **Git**: Version control system

## Langkah-langkah Instalasi

### 1. Clone Repository
```bash
# Clone repository dari Git
git clone [URL_REPOSITORY] SiApotekPOS
cd SiApotekPOS

# Atau jika sudah ada folder, pastikan berada di direktori yang benar
pwd
# Output harus menunjukkan path ke SiApotekPOS
```

### 2. Install Dependencies Backend
```bash
# Install PHP dependencies menggunakan Composer
composer install

# Jika ada masalah dengan memory limit
composer install --ignore-platform-reqs

# Atau dengan memory limit yang lebih tinggi
COMPOSER_MEMORY_LIMIT=-1 composer install
```

### 3. Setup Environment File
```bash
# Copy file environment
cp .env.example .env

# Edit file .env sesuai konfigurasi local
# Gunakan editor favorit Anda
code .env
# atau
nano .env
# atau
notepad .env
```

### 4. Konfigurasi Environment Variables
```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siapotekpos
DB_USERNAME=root
DB_PASSWORD=

# App Configuration
APP_NAME="SiApotekPOS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Mail Configuration (untuk development)
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Queue (untuk development)
QUEUE_CONNECTION=sync

# File Upload
FILESYSTEM_DISK=local
UPLOAD_MAX_FILESIZE=10M
POST_MAX_SIZE=10M
```

### 5. Setup Database
```bash
# Buat database baru
mysql -u root -p
CREATE DATABASE siapotekpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Atau menggunakan command line
mysql -u root -p -e "CREATE DATABASE siapotekpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 6. Generate Application Key
```bash
# Generate Laravel application key
php artisan key:generate

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 7. Run Database Migrations
```bash
# Jalankan migration untuk membuat struktur database
php artisan migrate

# Jika ada error, cek koneksi database
php artisan migrate:status

# Reset database jika diperlukan
php artisan migrate:fresh
```

### 8. Seed Database dengan Data Sample
```bash
# Jalankan seeder untuk data awal
php artisan db:seed

# Atau jalankan seeder tertentu
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=BrandSeeder
php artisan db:seed --class=ManufacturerSeeder
php artisan db:seed --class=UnitSeeder
php artisan db:seed --class=MedicineSeeder
php artisan db:seed --class=AppSettingSeeder
php artisan db:seed --class=InvoiceCounterSeeder
```

### 9. Install Dependencies Frontend
```bash
# Install Node.js dependencies
npm install

# Atau menggunakan Yarn
yarn install
```

### 10. Build Assets
```bash
# Build assets untuk development
npm run dev

# Atau untuk production
npm run build

# Watch mode untuk development
npm run watch
```

### 11. Setup Storage dan Permissions
```bash
# Buat symbolic link untuk storage
php artisan storage:link

# Set permissions (untuk Linux/Mac)
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set permissions (untuk Windows)
# Pastikan folder storage dan bootstrap/cache dapat diakses
```

### 12. Jalankan Aplikasi
```bash
# Jalankan development server
php artisan serve

# Aplikasi akan berjalan di http://localhost:8000
# Buka browser dan akses URL tersebut
```

### 13. Setup Cron Jobs (Opsional)
```bash
# Tambahkan ke crontab untuk fitur otomatis
* * * * * cd /path/to/SiApotekPOS && php artisan schedule:run >> /dev/null 2>&1

# Atau jalankan manual untuk testing
php artisan schedule:run
```

---

## Konfigurasi Web Server

### Apache Configuration
```apache
<VirtualHost *:80>
    ServerName siapotekpos.local
    DocumentRoot "/path/to/SiApotekPOS/public"
    
    <Directory "/path/to/SiApotekPOS/public">
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/siapotekpos_error.log
    CustomLog ${APACHE_LOG_DIR}/siapotekpos_access.log combined
</VirtualHost>
```

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name siapotekpos.local;
    root /path/to/SiApotekPOS/public;
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## Setup dengan Laravel Sail (Docker)

### 1. Install Laravel Sail
```bash
# Install Sail
composer require laravel/sail --dev

# Publish Sail configuration
php artisan sail:install

# Jalankan Sail
./vendor/bin/sail up -d
```

### 2. Akses Aplikasi
```bash
# Aplikasi akan berjalan di http://localhost
# Database dapat diakses di localhost:3306
# MailHog untuk testing email di localhost:8025
```

### 3. Sail Commands
```bash
# Jalankan Sail
./vendor/bin/sail up -d

# Stop Sail
./vendor/bin/sail down

# Jalankan command artisan
./vendor/bin/sail artisan migrate

# Jalankan composer
./vendor/bin/sail composer install

# Jalankan npm
./vendor/bin/sail npm install
```

---

## Troubleshooting

### Masalah Umum dan Solusi

#### 1. Composer Memory Limit
```bash
# Error: Fatal error: Allowed memory size exhausted
COMPOSER_MEMORY_LIMIT=-1 composer install
```

#### 2. Database Connection Error
```bash
# Cek koneksi database
php artisan tinker
DB::connection()->getPdo();

# Test koneksi
php artisan migrate:status
```

#### 3. Permission Denied
```bash
# Linux/Mac
sudo chown -R $USER:$USER storage
sudo chown -R $USER:$USER bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Windows
# Pastikan folder dapat diakses dan tidak di-block oleh antivirus
```

#### 4. Class Not Found Error
```bash
# Clear autoload cache
composer dump-autoload

# Clear Laravel cache
php artisan clear-compiled
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

#### 5. 500 Internal Server Error
```bash
# Cek error log
tail -f storage/logs/laravel.log

# Enable debug mode di .env
APP_DEBUG=true
```

#### 6. NPM Build Error
```bash
# Clear NPM cache
npm cache clean --force

# Delete node_modules dan reinstall
rm -rf node_modules package-lock.json
npm install
```

#### 7. Image Upload Error
```bash
# Cek permission storage
chmod -R 775 storage/app/public

# Cek symbolic link
php artisan storage:link

# Cek konfigurasi upload di php.ini
upload_max_filesize = 10M
post_max_size = 10M
```

#### 8. Livewire Error
```bash
# Clear Livewire cache
php artisan livewire:discover

# Clear view cache
php artisan view:clear

# Restart development server
php artisan serve
```

---

## Verifikasi Instalasi

### 1. Cek Status Aplikasi
```bash
# Cek versi Laravel
php artisan --version

# Cek status migration
php artisan migrate:status

# Cek route list
php artisan route:list

# Cek storage link
php artisan storage:link
```

### 2. Test Fitur Utama
- Buka http://localhost:8000
- Login dengan user default (cek UserSeeder)
- Test manajemen obat
- Test sistem penjualan
- Test generate laporan
- Test sistem judul dinamis
- Test thumbnail management
- Test sistem notifikasi
- Test image cleanup feature

### 3. Cek Log dan Error
```bash
# Cek log Laravel
tail -f storage/logs/laravel.log

# Cek log web server
# Apache: /var/log/apache2/
# Nginx: /var/log/nginx/
```

### 4. Test Database
```bash
# Test koneksi database
php artisan tinker
DB::connection()->getPdo();

# Cek data sample
User::count();
Medicine::count();
Category::count();
```

---

## Development Workflow

### 1. Development Mode
```bash
# Jalankan dalam mode development
php artisan serve
npm run dev

# Atau gunakan Laravel Mix watch
npm run watch
```

### 2. Testing
```bash
# Jalankan test
php artisan test

# Jalankan test dengan coverage
php artisan test --coverage

# Jalankan test tertentu
php artisan test --filter=MedicineTest
```

### 3. Code Quality
```bash
# Install PHP CS Fixer
composer require --dev friendsofphp/php-cs-fixer

# Fix coding standards
./vendor/bin/php-cs-fixer fix

# Install PHPStan
composer require --dev phpstan/phpstan

# Run static analysis
./vendor/bin/phpstan analyse
```

### 4. Database Management
```bash
# Refresh database dengan seeder
php artisan migrate:fresh --seed

# Rollback migration tertentu
php artisan migrate:rollback --step=1

# Cek status migration
php artisan migrate:status
```

---

## Backup dan Restore

### 1. Backup Database
```bash
# Backup database
mysqldump -u root -p siapotekpos > backup_$(date +%Y%m%d_%H%M%S).sql

# Atau menggunakan Laravel
php artisan db:backup

# Backup dengan compression
mysqldump -u root -p siapotekpos | gzip > backup_$(date +%Y%m%d_%H%M%S).sql.gz
```

### 2. Restore Database
```bash
# Restore database
mysql -u root -p siapotekpos < backup_file.sql

# Restore dari compressed file
gunzip < backup_file.sql.gz | mysql -u root -p siapotekpos
```

### 3. Backup Files
```bash
# Backup storage files
tar -czf storage_backup_$(date +%Y%m%d_%H%M%S).tar.gz storage/

# Backup uploads
tar -czf uploads_backup_$(date +%Y%m%d_%H%M%S).tar.gz storage/app/public/
```

---

## Maintenance

### 1. Update Dependencies
```bash
# Update Composer packages
composer update

# Update NPM packages
npm update

# Update Laravel
composer update laravel/framework

# Check for security vulnerabilities
composer audit
npm audit
```

### 2. Clear Cache
```bash
# Clear semua cache
php artisan optimize:clear

# Clear specific cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan livewire:discover
```

### 3. Maintenance Mode
```bash
# Enable maintenance mode
php artisan down

# Enable maintenance mode dengan secret
php artisan down --secret="1630542a-246b-4b66-afa1-dd72a4c43515"

# Disable maintenance mode
php artisan up
```

### 4. Log Management
```bash
# Clear old logs
php artisan log:clear

# Rotate logs
php artisan log:rotate

# Cek ukuran log files
du -sh storage/logs/*
```

---

## Fitur yang Telah Diimplementasikan

### 1. Sistem Judul Dinamis
- Browser title yang berubah sesuai halaman
- Page header dengan ikon dan actions
- Breadcrumb navigation yang informatif
- Helper functions untuk manajemen judul

### 2. Thumbnail Management
- Upload gambar dengan drag & drop
- Preview gambar circular
- Resize otomatis untuk optimasi
- JavaScript thumbnail manager

### 3. Advanced Search & Filtering
- Pencarian multi-kriteria
- Filter berdasarkan kategori, merek, stok
- Pagination dan sorting yang responsif

### 4. Sistem Notifikasi
- Toast notifications
- Alert messages
- Success/error handling
- Auto-dismiss functionality

### 5. Image Cleanup Feature
- Pembersihan gambar otomatis
- Command artisan untuk cleanup manual
- Logging aktivitas cleanup
- Safety checks

### 6. Komponen yang Diupdate
- Page header component yang konsisten
- Breadcrumb component yang informatif
- Thumbnail manager untuk gambar obat
- Notification components

### 7. API Endpoints
- Medicine API untuk integrasi
- RESTful endpoints
- JSON responses
- Authentication middleware

### 8. Export Features
- Excel export untuk laporan
- PDF generation
- Print-friendly views
- Multiple format support

---

## Changelog

### Version 1.0.0 (Latest)
- ✅ Sistem judul dinamis lengkap
- ✅ Thumbnail management system
- ✅ Image cleanup feature
- ✅ Sistem notifikasi
- ✅ Advanced search & filtering
- ✅ API endpoints
- ✅ Export features
- ✅ Responsive design
- ✅ Livewire components
- ✅ Database optimizations

### Version 0.9.0
- ✅ Basic CRUD operations
- ✅ User authentication
- ✅ Role-based access control
- ✅ Basic reporting
- ✅ Sales management

### Version 0.8.0
- ✅ Database structure
- ✅ Basic models
- ✅ Migration files
- ✅ Seeder data

---

## Roadmap

### Short Term (1-2 bulan)
- [ ] Multi-language support
- [ ] Advanced reporting dashboard
- [ ] Mobile app development
- [ ] Barcode scanner integration
- [ ] Inventory alerts

### Medium Term (3-6 bulan)
- [ ] Multi-branch support
- [ ] Advanced analytics
- [ ] Customer management
- [ ] Supplier management
- [ ] Purchase order system

### Long Term (6+ bulan)
- [ ] Cloud deployment
- [ ] API marketplace
- [ ] Third-party integrations
- [ ] Advanced security features
- [ ] Performance optimizations

---

## Support dan Kontribusi

### Getting Help
- **Documentation**: Baca dokumentasi ini dengan teliti
- **Issues**: Buat issue di repository untuk bug reports
- **Discussions**: Gunakan discussion tab untuk pertanyaan
- **Wiki**: Cek wiki untuk informasi tambahan

### Contributing
- Fork repository
- Buat feature branch
- Commit changes
- Push ke branch
- Buat Pull Request

### Code of Conduct
- Hormati semua kontributor
- Gunakan bahasa yang sopan
- Fokus pada masalah teknis
- Berikan feedback yang konstruktif

---

**Catatan**: 
- Pastikan semua service (database, web server) berjalan sebelum menjalankan aplikasi
- Gunakan environment yang sesuai untuk development
- Backup database secara berkala
- Update dependencies secara rutin untuk keamanan
- Test fitur sistem judul dinamis setelah instalasi
- Monitor log files untuk debugging
- Gunakan maintenance mode saat update production

**Support**: Jika mengalami masalah, cek log error dan dokumentasi Laravel resmi

**Version**: 1.0.0
**Last Updated**: {{ date('Y-m-d') }}
**Maintainer**: SiApotekPOS Team
