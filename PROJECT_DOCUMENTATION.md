# 📚 Dokumentasi Lengkap SiApotekPOS

## 🎯 Deskripsi Proyek
**SiApotekPOS** adalah sistem Point of Sale (POS) modern yang dirancang khusus untuk apotek, toko obat, dan bisnis farmasi. Sistem ini menggabungkan kemudahan penggunaan dengan fitur-fitur canggih untuk manajemen inventori, transaksi, dan pelaporan yang komprehensif.

## 🛠️ Teknologi yang Digunakan

### Backend Stack
- **Framework**: Laravel 10.x (PHP 8.1+)
- **Database**: MySQL/PostgreSQL
- **ORM**: Eloquent ORM
- **Authentication**: Laravel Breeze/Sanctum
- **API**: RESTful API dengan JSON responses

### Frontend Stack
- **Templates**: Blade Templates dengan Livewire
- **CSS Framework**: Bootstrap 5 + SB Admin 2 Theme
- **JavaScript**: Alpine.js, Livewire, Vanilla JS
- **Build Tool**: Vite
- **Styling**: SCSS dengan arsitektur komponen modular

## 🏗️ Arsitektur Sistem

### 1. Model-View-Controller (MVC) Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     Models      │    │   Controllers   │    │      Views      │
│                 │    │                 │    │                 │
│ • User          │◄──►│ • Auth/*        │◄──►│ • Blade        │
│ • Medicine      │    │ • Dashboard     │    │ • Livewire     │
│ • Sale          │    │ • Medicine      │    │ • Components   │
│ • Category      │    │ • Sale          │    │ • Layouts      │
│ • Brand         │    │ • Report        │    │                 │
│ • Manufacturer  │    │ • User          │    │                 │
│ • Unit          │    │ • AppSetting    │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### 2. Service Layer Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Controllers   │    │     Services    │    │   Repositories  │
│                 │    │                 │    │                 │
│ • HTTP Logic    │◄──►│ • Business      │◄──►│ • Data Access   │
│ • Request/      │    │   Logic         │    │ • Query         │
│   Response      │    │ • Validation    │    │ • CRUD          │
│ • Middleware    │    │ • Processing    │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## 📁 Struktur Proyek Detail

### App Directory Structure
```
app/
├── Console/           # Artisan commands
├── Exceptions/        # Exception handlers
├── Exports/          # Excel/PDF export classes
├── Helpers/          # Utility helper classes
├── Http/             # HTTP layer
│   ├── Controllers/  # Application controllers
│   ├── Livewire/     # Livewire components
│   ├── Middleware/   # Custom middleware
│   ├── Requests/     # Form request validation
│   └── Resources/    # API resources
├── Models/           # Eloquent models
├── Providers/        # Service providers
├── Repositories/     # Data access layer
├── Services/         # Business logic layer
└── Traits/          # Reusable traits
```

## 🔐 Sistem Autentikasi & Otorisasi

### Role-Based Access Control (RBAC)
- **Admin**: Akses penuh ke semua fitur
- **Manager**: Manajemen obat, penjualan, dan laporan
- **Cashier**: Transaksi penjualan dan laporan dasar
- **Viewer**: Hanya dapat melihat data (read-only)

### Permission System
```php
// CheckRole Middleware
class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('login');
        }
        
        $userRole = auth()->user()->role->name;
        
        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized access');
        }
        
        return $next($request);
    }
}
```

## 💊 Manajemen Obat

### Fitur Utama
1. **CRUD Operations**
   - Create, Read, Update, Delete obat
   - Bulk operations untuk multiple items
   - Soft delete dengan archive system

2. **Advanced Search & Filtering**
   - Pencarian berdasarkan nama, kategori, merek
   - Filter berdasarkan stok, harga, tanggal
   - Pagination dan sorting

3. **Stock Management**
   - Update stok real-time
   - Stock alerts untuk stok menipis
   - Stock history dan log

4. **Image Management**
   - Upload gambar dengan validasi
   - Auto-resize dan thumbnail generation
   - Image optimization

### Livewire Components
```php
// MedicineManagement Component
class MedicineManagement extends Component
{
    public $search = '';
    public $category_filter = '';
    public $brand_filter = '';
    public $stock_filter = '';
    
    public function render()
    {
        $medicines = Medicine::with(['category', 'brand', 'manufacturer', 'unit'])
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->category_filter, function($query) {
                $query->where('category_id', $this->category_filter);
            })
            ->paginate(15);
            
        return view('livewire.medicine.medicine-management', compact('medicines'));
    }
}
```

## 🛒 Sistem Penjualan (POS)

### Fitur Transaksi
1. **Quick Search Medicine**
   - Pencarian real-time dengan autocomplete
   - Barcode scanning support
   - Stock availability check

2. **Cart Management**
   - Add/remove items
   - Quantity adjustment
   - Price calculation
   - Discount application

3. **Payment Processing**
   - Multiple payment methods
   - Change calculation
   - Receipt generation

4. **Invoice System**
   - Auto-increment numbering
   - Custom invoice format
   - PDF generation

### Sales Flow
```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   Search    │───►│   Add to    │───►│  Calculate  │───►│   Payment   │
│  Medicine   │    │    Cart     │    │   Total     │    │  Process    │
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
                                                              │
                                                              ▼
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   Receipt   │◄───│   Invoice   │◄───│  Database   │◄───│  Complete   │
│ Generation  │    │ Generation  │    │   Update    │    │   Sale      │
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
```

## 📊 Sistem Pelaporan

### Report Types
1. **Sales Report**
   - Daily, weekly, monthly reports
   - Top selling products
   - Revenue analysis
   - Customer insights

2. **Stock Report**
   - Current stock levels
   - Low stock alerts
   - Stock movement history
   - Expiry date tracking

3. **Statistics Report**
   - Sales trends
   - Product performance
   - Customer behavior
   - Financial metrics

### Export Features
```php
// SalesReportExport Class
class SalesReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Sale::with(['user', 'details.medicine'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();
    }
    
    public function headings(): array
    {
        return [
            'Invoice No',
            'Date',
            'Customer',
            'Items',
            'Total Amount',
            'Cashier'
        ];
    }
}
```

## 🎨 User Interface & Experience

### Design System
- **Color Palette**: Professional healthcare theme
- **Typography**: Readable fonts dengan hierarchy yang jelas
- **Icons**: Consistent icon set (FontAwesome/Feather)
- **Spacing**: Consistent spacing system (8px grid)

### Responsive Design
```scss
// _variables.scss
$breakpoints: (
  xs: 0,
  sm: 576px,
  md: 768px,
  lg: 992px,
  xl: 1200px,
  xxl: 1400px
);

// _responsive.scss
@mixin respond-to($breakpoint) {
  @if map-has-key($breakpoints, $breakpoint) {
    @media (min-width: map-get($breakpoints, $breakpoint)) {
      @content;
    }
  }
}
```

## 🗄️ Database Design

### Entity Relationship Diagram
```
Users (1) ──── (1) Roles
  │
  │ (1)
  │
  └─── (M) Sales
         │
         │ (1)
         │
         └─── (M) SaleDetails
                │
                │ (M)
                │
                └─── (1) Medicines
                       │
                       │ (M)
                       │
                       ├─── (1) Categories
                       ├─── (1) Brands
                       ├─── (1) Manufacturers
                       └─── (1) Units
```

### Migration Examples
```php
// Create medicines table
Schema::create('medicines', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2);
    $table->integer('stock')->default(0);
    $table->string('image_path')->nullable();
    $table->boolean('is_archived')->default(false);
    
    // Foreign keys
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->foreignId('brand_id')->constrained()->onDelete('cascade');
    $table->foreignId('manufacturer_id')->constrained()->onDelete('cascade');
    $table->foreignId('unit_id')->constrained()->onDelete('cascade');
    
    $table->timestamps();
    $table->softDeletes();
    
    // Indexes
    $table->index(['name', 'category_id']);
    $table->index('stock');
    $table->index('is_archived');
});
```

## 🚀 Performance & Optimization

### Database Optimization
1. **Indexing Strategy**
   - Primary keys pada semua tabel
   - Composite indexes untuk queries yang sering digunakan
   - Foreign key indexes

2. **Query Optimization**
   - Eager loading untuk relationships
   - Query caching untuk data statis
   - Database connection pooling

### Frontend Optimization
1. **Asset Optimization**
   - CSS/JS minification
   - Image compression
   - Lazy loading untuk images
   - CDN integration

## 🔒 Security Features

### Authentication Security
- **Password Hashing**: Bcrypt dengan cost factor 12
- **Session Security**: Secure session configuration
- **CSRF Protection**: Token-based CSRF protection
- **Rate Limiting**: API rate limiting

### Data Protection
- **Input Validation**: Comprehensive form validation
- **SQL Injection Prevention**: Eloquent ORM protection
- **XSS Protection**: Output escaping
- **File Upload Security**: File type and size validation

## 📱 API Documentation

### RESTful Endpoints
```php
// Medicine API Routes
Route::prefix('api/medicines')->group(function () {
    Route::get('/', [MedicineApiController::class, 'index']);
    Route::post('/', [MedicineApiController::class, 'store']);
    Route::get('/{medicine}', [MedicineApiController::class, 'show']);
    Route::put('/{medicine}', [MedicineApiController::class, 'update']);
    Route::delete('/{medicine}', [MedicineApiController::class, 'destroy']);
    Route::get('/search', [MedicineApiController::class, 'search']);
});
```

### API Response Format
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Paracetamol 500mg",
    "price": 5000,
    "stock": 100,
    "category": {
      "id": 1,
      "name": "Analgesik"
    }
  },
  "message": "Medicine retrieved successfully"
}
```

## 🧪 Testing Strategy

### Testing Types
1. **Unit Tests**
   - Model testing
   - Service testing
   - Helper function testing

2. **Feature Tests**
   - Controller testing
   - API endpoint testing
   - Livewire component testing

### Test Examples
```php
// MedicineTest
class MedicineTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_can_create_medicine()
    {
        $medicineData = [
            'name' => 'Test Medicine',
            'price' => 10000,
            'stock' => 50,
            'category_id' => 1,
            'brand_id' => 1
        ];
        
        $response = $this->post('/medicines', $medicineData);
        
        $response->assertStatus(302);
        $this->assertDatabaseHas('medicines', $medicineData);
    }
}
```

## 🚀 Getting Started

### Prerequisites
- PHP 8.1 or higher
- Composer 2.0+
- MySQL 8.0+ or PostgreSQL 13+
- Node.js 16+ and NPM
- Git

### Installation Steps
```bash
# 1. Clone repository
git clone https://github.com/your-org/siapotek-pos.git
cd siapotek-pos

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Environment setup
cp .env.example .env
php artisan key:generate

# 5. Database configuration
# Edit .env file with your database credentials

# 6. Run migrations and seeders
php artisan migrate --seed

# 7. Build frontend assets
npm run dev

# 8. Start development server
php artisan serve
```

### Environment Configuration
```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siapotek_pos
DB_USERNAME=root
DB_PASSWORD=

# Application
APP_NAME="SiApotekPOS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Mail
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

## 🤝 Contributing

### Development Workflow
1. **Fork Repository**
2. **Create Feature Branch**: `git checkout -b feature/amazing-feature`
3. **Make Changes**: Follow coding standards
4. **Test Changes**: Run test suite
5. **Commit Changes**: Use conventional commit format
6. **Push Changes**: `git push origin feature/amazing-feature`
7. **Create Pull Request**: Detailed description of changes

### Coding Standards
- **PSR-12**: PHP coding standards
- **Laravel Best Practices**: Framework-specific guidelines
- **ESLint**: JavaScript code quality
- **Prettier**: Code formatting

## 📚 Additional Resources

### Documentation
- [Laravel Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://laravel-livewire.com/docs)
- [Bootstrap Documentation](https://getbootstrap.com/docs)
- [SB Admin 2 Documentation](https://startbootstrap.com/theme/sb-admin-2)

### Community
- [Laravel Community](https://laravel.io/)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/laravel)
- [GitHub Discussions](https://github.com/laravel/laravel/discussions)

## 📞 Support & Contact

### Technical Support
- **GitHub Issues**: [Repository Issues](https://github.com/your-org/siapotek-pos/issues)
- **Email Support**: support@siapotek-pos.com
- **Documentation**: [Wiki](https://github.com/your-org/siapotek-pos/wiki)

### Development Team
- **Lead Developer**: [Your Name](mailto:your.email@example.com)
- **Project Manager**: [PM Name](mailto:pm.email@example.com)
- **UI/UX Designer**: [Designer Name](mailto:designer.email@example.com)

---

## 📋 Changelog

### Version 1.2.0 (Current)
- ✨ Added advanced search functionality
- 🎨 Improved UI/UX with modern design
- 🔒 Enhanced security features
- 📱 Better mobile responsiveness
- 🚀 Performance optimizations

### Version 1.1.0
- 🏥 Medicine management system
- 💰 Basic POS functionality
- 👥 User role management
- 📊 Basic reporting

### Version 1.0.0
- 🎯 Initial release
- 🔐 Authentication system
- 📱 Basic responsive design
- 🗄️ Database structure

---

**📄 License**: MIT License  
**🌐 Website**: [https://siapotek-pos.com](https://siapotek-pos.com)  
**📧 Email**: info@siapotek-pos.com  
**📱 Phone**: +62-xxx-xxx-xxxx  
**🏢 Company**: Your Company Name  
**📍 Address**: Your Company Address  

---

*Dokumentasi ini dibuat dengan ❤️ untuk komunitas developer Indonesia. Terima kasih telah menggunakan SiApotekPOS!*
