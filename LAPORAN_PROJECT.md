# LAPORAN TEKNIS PENGEMBANGAN APLIKASI WEB TIGAPAGI

---

## HALAMAN IDENTITAS

**Judul Proyek:** Sistem Informasi Company Profile dan Tracking Progress Klien Tigapagi  
**Nama Proyek:** webtigapagi  
**Pengembang:** agsetiawannn  
**Tanggal Penyusunan:** 4 Januari 2026  
**Versi Dokumen:** 1.0  

---

## ABSTRAK

Laporan ini menjelaskan tentang pengembangan aplikasi web Tigapagi yang merupakan sistem terintegrasi untuk company profile dan tracking progress klien. Aplikasi dikembangkan menggunakan framework Laravel 12.0 dengan PHP 8.4 sebagai bahasa pemrograman utama. Sistem ini mengimplementasikan arsitektur hybrid yang menggabungkan Laravel MVC pattern untuk fitur company profile dengan native PHP untuk sistem tracking progress klien. Aplikasi dilengkapi dengan fitur keamanan modern, responsive design, dan database relasional untuk manajemen data. Hasil pengembangan menunjukkan bahwa aplikasi berhasil memenuhi kebutuhan fungsional dengan performa yang baik, meskipun masih terdapat beberapa aspek yang memerlukan peningkatan dari sisi keamanan dan arsitektur kode.

**Kata Kunci:** Laravel, Web Development, Tracking System, Company Profile, PHP, MVC Architecture

---

## BAB I: PENDAHULUAN

### 1.1 Latar Belakang

Dalam era digital saat ini, keberadaan company profile berbasis web menjadi kebutuhan esensial bagi perusahaan untuk membangun kredibilitas dan jangkauan pasar. Tigapagi sebagai perusahaan memerlukan platform digital yang tidak hanya berfungsi sebagai media informasi, tetapi juga menyediakan sistem tracking progress untuk memantau perkembangan proyek klien secara real-time.

### 1.2 Ruang Lingkup Proyek

Proyek ini mencakup pengembangan aplikasi web dengan ruang lingkup sebagai berikut:
1. Pengembangan landing page company profile responsif
2. Implementasi sistem kontak terintegrasi dengan database
3. Pembangunan sistem tracking progress klien dengan dashboard
4. Pengembangan panel administrasi untuk manajemen klien
5. Implementasi sistem catatan klien (client notes)

### 1.3 Tujuan Pengembangan

Tujuan utama pengembangan aplikasi ini adalah:
1. Menyediakan platform digital profesional untuk company profile Tigapagi
2. Memfasilitasi komunikasi antara klien dan perusahaan melalui sistem kontak
3. Memberikan transparansi progress proyek kepada klien melalui dashboard tracking
4. Meningkatkan efisiensi manajemen proyek dengan sistem tracking terintegrasi

### 1.4 Manfaat

Manfaat yang diharapkan dari pengembangan sistem ini:
1. **Bagi Perusahaan:** Meningkatkan profesionalisme dan kredibilitas brand
2. **Bagi Klien:** Mendapatkan akses real-time terhadap progress proyek
3. **Bagi Tim Internal:** Memudahkan koordinasi dan dokumentasi progress proyek

---

## BAB II: LANDASAN TEORI

### 2.1 Framework Laravel

Laravel adalah framework PHP open-source berbasis MVC (Model-View-Controller) yang dikembangkan oleh Taylor Otwell. Framework ini menyediakan fitur-fitur modern seperti routing, eloquent ORM, blade templating, dan migration system yang memudahkan pengembangan aplikasi web.

**Versi yang digunakan:** Laravel 12.0 dengan PHP 8.4

### 2.2 Arsitektur MVC (Model-View-Controller)

MVC adalah pola arsitektur yang memisahkan aplikasi menjadi tiga komponen utama:
- **Model:** Mengelola data dan logika bisnis
- **View:** Menampilkan data kepada pengguna
- **Controller:** Menghubungkan model dan view

### 2.3 Database Relasional

Database relasional mengorganisir data dalam tabel-tabel yang saling berelasi. Proyek ini menggunakan SQLite sebagai database default dengan dukungan MySQL untuk skalabilitas.

### 2.4 Content Security Policy (CSP)

CSP adalah mekanisme keamanan yang membantu mendeteksi dan mencegah serangan XSS (Cross-Site Scripting) dengan membatasi sumber resource yang dapat dimuat oleh browser.

---

## BAB III: METODOLOGI PENGEMBANGAN

### 3.1 Teknologi yang Digunakan

#### 3.1.1 Backend Technologies
- **Laravel Framework:** 12.0
- **PHP Version:** 8.4
- **Database:** SQLite (primary), MySQL (supported)
- **Package Manager:** Composer 2.x

#### 3.1.2 Frontend Technologies
- **Template Engine:** Blade (Laravel)
- **CSS Framework:** TailwindCSS 4.1.17
- **Build Tool:** Vite 7.0.7
- **HTTP Client:** Axios 1.11.0

#### 3.1.3 Development Tools
- **Concurrency Manager:** Concurrently 9.0.1
- **Testing Framework:** PHPUnit 11.5.3
- **Faker:** FakerPHP 1.23 (data seeding)

### 3.2 Arsitektur Sistem

Sistem dibangun dengan arsitektur hybrid yang terdiri dari:

```
Application Layer
├── Laravel MVC (Company Profile)
│   ├── Routes (web.php)
│   ├── Controllers (ContactController)
│   ├── Models (Contact, User)
│   └── Views (Blade Templates)
│
└── Native PHP (Tracking System)
    ├── Session Management
    ├── Database Operations
    └── Business Logic
```

### 3.3 Struktur Direktori

```
tigapagiweb/
├── app/
│   ├── Http/Controllers/     # Controller layer
│   ├── Models/               # Data models
│   └── Providers/            # Service providers
├── database/
│   ├── migrations/           # Database schema
│   └── seeders/              # Data seeding
├── resources/
│   ├── views/                # Blade templates & PHP views
│   ├── css/                  # Stylesheets
│   └── js/                   # JavaScript files
├── routes/
│   └── web.php               # Route definitions
├── public/                   # Public assets
└── config/                   # Application configuration
```

---

## BAB IV: PERANCANGAN SISTEM

### 4.1 Perancangan Database

#### 4.1.1 Tabel Contacts
Tabel ini menyimpan data kontak dari pengunjung website.

| Field      | Type         | Constraints | Deskripsi                |
|------------|--------------|-------------|--------------------------|
| id         | BIGINT       | PRIMARY KEY | Identifier unik          |
| name       | VARCHAR(255) | NOT NULL    | Nama pengirim            |
| email      | VARCHAR(255) | NOT NULL    | Email pengirim           |
| phone      | VARCHAR(20)  | NOT NULL    | Nomor telepon            |
| created_at | TIMESTAMP    | NULL        | Waktu pembuatan record   |
| updated_at | TIMESTAMP    | NULL        | Waktu update terakhir    |

#### 4.1.2 Tabel Client Notes
Tabel untuk menyimpan catatan komunikasi klien dan admin.

| Field      | Type         | Constraints    | Deskripsi                |
|------------|--------------|----------------|--------------------------|
| id         | BIGINT       | PRIMARY KEY    | Identifier unik          |
| client_id  | BIGINT       | FOREIGN KEY    | Reference ke klien       |
| note_text  | TEXT         | NOT NULL       | Isi catatan              |
| created_by | VARCHAR(50)  | NOT NULL       | admin/client             |
| created_at | TIMESTAMP    | DEFAULT NOW()  | Waktu pembuatan          |

#### 4.1.3 Tabel Client Progress
Tabel untuk tracking progress proyek klien.

| Field             | Type         | Constraints  | Deskripsi                    |
|-------------------|--------------|--------------|------------------------------|
| client_id         | BIGINT       | FOREIGN KEY  | Reference ke klien           |
| onboard           | INT          | NULL         | Progress onboarding (%)      |
| presprint         | INT          | NULL         | Progress pre-sprint (%)      |
| sprint            | INT          | NULL         | Progress sprint (%)          |
| client_view       | VARCHAR(50)  | DEFAULT none | Preferensi tampilan          |
| sprint_week_focus | VARCHAR(255) | NULL         | Fokus minggu sprint          |

### 4.2 Perancangan Routing

#### 4.2.1 Laravel Routes (Blade-based)
```php
Route::get('/', IndexController)           // Landing page
Route::get('/clients', ClientController)   // Client page
Route::get('/tracking', TrackingController) // Tracking overview
```

#### 4.2.2 Native PHP Routes (Session-based)
```php
Route::any('/login.php')           // Client authentication
Route::any('/dashboard.php')       // Client dashboard
Route::any('/admin_login.php')     // Admin authentication
Route::any('/admin_dashboard.php') // Admin panel
Route::any('/save_progress.php')   // Progress update handler
Route::any('/edit_client.php')     // Client data editor
Route::any('/logout.php')          // Session termination
```

**Catatan:** Routes native PHP menggunakan `withoutMiddleware(['web'])` untuk bypass CSRF protection.

### 4.3 Perancangan Interface

#### 4.3.1 Landing Page (index.blade.php)
**Komponen Utama:**
1. **Loading Screen**
   - Video loading adaptif (landscape/portrait)
   - Automatic hiding setelah video selesai
   
2. **Navigation Bar**
   - Logo brand positioning (top-left)
   - Menu navigasi (top-right)
   - Hamburger menu untuk mobile
   - WhatsApp contact button

3. **Security Headers**
   - Content-Security-Policy implementation
   - X-Content-Type-Options: nosniff
   - Referrer-Policy: strict-origin-when-cross-origin

#### 4.3.2 Client Dashboard
**Fitur Interface:**
1. Session-based authentication check
2. Progress visualization (Onboard, Presprint, Sprint)
3. Client notes submission form
4. Notes history dengan timestamp
5. Sprint week focus display

---

## BAB V: IMPLEMENTASI SISTEM

### 5.1 Implementasi Contact System

#### 5.1.1 Model Implementation
```php
namespace App\Models;

class Contact extends Model
{
    protected $fillable = ['name', 'email', 'phone'];
}
```

Model Contact menggunakan mass assignment protection dengan fillable attributes untuk keamanan.

#### 5.1.2 Controller Implementation
```php
class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        Contact::create($validated);

        return redirect()->back()
               ->with('success', 'Message sent successfully!');
    }
}
```

**Validasi Input:**
- Name: Wajib diisi, tipe string, maksimal 255 karakter
- Email: Wajib diisi, format email valid, maksimal 255 karakter
- Phone: Wajib diisi, tipe string, maksimal 20 karakter

### 5.2 Implementasi Tracking System

#### 5.2.1 Session Management
```php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}
```

Sistem menggunakan PHP session untuk autentikasi dengan validasi keberadaan session client_id.

#### 5.2.2 Note Submission Handler
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_note'])) {
    $note_text = trim($_POST['note_text'] ?? '');
    if (!empty($note_text)) {
        $stmt = $conn->prepare(
            "INSERT INTO client_notes 
             (client_id, note_text, created_by) 
             VALUES (?, ?, 'client')"
        );
        $stmt->bind_param("is", $client_id, $note_text);
        $stmt->execute();
    }
}
```

**Keamanan:** Menggunakan prepared statements untuk mencegah SQL injection.

### 5.3 Implementasi Keamanan

#### 5.3.1 Content Security Policy
```html
<meta http-equiv="Content-Security-Policy" 
      content="default-src 'self'; 
               script-src 'self' 'unsafe-inline'; 
               style-src 'self' 'unsafe-inline'; 
               img-src 'self' data: https:; 
               media-src 'self';">
```

#### 5.3.2 Input Sanitization
```php
$name = htmlspecialchars($_SESSION['client_name'] ?? 'Client');
$client_id = intval($_SESSION['client_id']);
```

Menggunakan `htmlspecialchars()` untuk escape HTML characters dan `intval()` untuk type casting.

### 5.4 Database Migration

Migration file untuk tabel contacts:
```php
Schema::create('contacts', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email');
    $table->string('phone');
    $table->timestamps();
});
```

---

## BAB VI: PENGUJIAN SISTEM

### 6.1 Testing Framework

Aplikasi menggunakan PHPUnit 11.5.3 sebagai testing framework dengan konfigurasi di `phpunit.xml`.

### 6.2 Testing Scripts

```bash
# Menjalankan seluruh test suite
composer test

# Equivalent dengan:
php artisan config:clear --ansi
php artisan test
```

### 6.3 Test Coverage

Test files terletak di direktori `tests/`:
- `tests/Feature/` - Integration tests
- `tests/Unit/` - Unit tests

---

## BAB VII: DEPLOYMENT

### 7.1 Setup Procedure

#### 7.1.1 Initial Setup
```bash
composer install                    # Install PHP dependencies
cp .env.example .env               # Create environment file
php artisan key:generate           # Generate application key
php artisan migrate --force        # Run database migrations
npm install                        # Install Node dependencies
npm run build                      # Build frontend assets
```

#### 7.1.2 Development Mode
```bash
# Automated development (recommended)
composer dev

# Manual development
php artisan serve                  # Start Laravel server
php artisan queue:listen           # Start queue worker
php artisan pail                   # Start log viewer
npm run dev                        # Start Vite dev server
```

### 7.2 Production Deployment

**Persyaratan Server:**
- PHP >= 8.4
- Composer
- Node.js & NPM
- Web Server (Apache/Nginx)
- Database (SQLite/MySQL)

**Environment Configuration:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tigapagi.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tigapagi_db
DB_USERNAME=root
DB_PASSWORD=secret
```

---

## BAB VIII: ANALISIS DAN EVALUASI

### 8.1 Analisis Kelebihan Sistem

1. **Framework Modern**
   - Menggunakan Laravel 12.0 (versi terbaru per Januari 2026)
   - PHP 8.4 dengan fitur-fitur modern
   - Vite 7.0.7 untuk build optimization

2. **Keamanan**
   - Implementasi Content Security Policy (CSP)
   - Prepared statements untuk query database
   - Input validation di controller layer
   - HTML special characters escaping

3. **User Experience**
   - Responsive design (landscape/portrait adaptation)
   - Loading screen dengan video interaktif
   - Real-time progress tracking
   - Client notes untuk komunikasi

4. **Maintainability**
   - Database migration system
   - Structured directory organization
   - Separation of concerns (MVC pattern)
   - Version control ready

### 8.2 Analisis Kelemahan Sistem

1. **Keamanan**
   - CSRF protection dinonaktifkan untuk tracking routes
   - Belum implementasi rate limiting untuk login
   - Tidak ada two-factor authentication
   - Session management menggunakan native PHP

2. **Arsitektur**
   - Hybrid architecture (Laravel + Native PHP) kurang konsisten
   - Business logic tercampur dengan view layer
   - Tidak ada API layer untuk scalability
   - Duplikasi authentication logic

3. **Performance**
   - Belum ada caching mechanism
   - Query optimization belum maksimal
   - Tidak ada lazy loading untuk resources
   - Frontend assets belum fully optimized

4. **Code Quality**
   - Native PHP tidak mengikuti PSR standards
   - Tidak ada form request classes untuk validasi kompleks
   - Logging belum comprehensive
   - Error handling belum standar

### 8.3 Rekomendasi Perbaikan

#### 8.3.1 Security Improvements
1. **Aktifkan CSRF Protection**
   ```php
   // Hapus withoutMiddleware(['web']) pada tracking routes
   Route::post('/save_progress', [ProgressController::class, 'store'])
        ->middleware(['web', 'auth']);
   ```

2. **Implement Rate Limiting**
   ```php
   Route::middleware(['throttle:5,1'])->group(function () {
       Route::post('/login', [AuthController::class, 'login']);
       Route::post('/admin_login', [AdminController::class, 'login']);
   });
   ```

3. **Two-Factor Authentication**
   - Gunakan package `laravel/fortify` atau `pragmarx/google2fa`

#### 8.3.2 Architecture Improvements
1. **Migrasi Native PHP ke Laravel**
   - Convert `login.php` → `AuthController@login`
   - Convert `dashboard.php` → `DashboardController@index`
   - Gunakan Laravel Authentication system

2. **Implement Repository Pattern**
   ```php
   interface ContactRepositoryInterface {
       public function create(array $data);
       public function findById(int $id);
   }
   ```

3. **API Development**
   ```php
   Route::prefix('api')->group(function () {
       Route::apiResource('contacts', ContactController::class);
       Route::apiResource('progress', ProgressController::class);
   });
   ```

#### 8.3.3 Performance Improvements
1. **Caching Strategy**
   ```php
   // Cache static content
   Cache::remember('client_progress_'.$id, 3600, function () {
       return Progress::find($id);
   });
   ```

2. **Query Optimization**
   ```php
   // Eager loading
   $clients = Client::with(['progress', 'notes'])->get();
   ```

3. **Asset Optimization**
   ```javascript
   // vite.config.js
   export default {
       build: {
           minify: 'terser',
           cssMinify: true
       }
   }
   ```

#### 8.3.4 Code Quality Improvements
1. **Form Request Validation**
   ```php
   class StoreContactRequest extends FormRequest {
       public function rules() {
           return [
               'name' => 'required|string|max:255',
               'email' => 'required|email|unique:contacts',
               'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
           ];
       }
   }
   ```

2. **Logging Implementation**
   ```php
   Log::channel('tracking')->info('Client login', [
       'client_id' => $clientId,
       'ip' => $request->ip()
   ]);
   ```

3. **Exception Handling**
   ```php
   try {
       Contact::create($validated);
   } catch (\Exception $e) {
       Log::error('Contact creation failed: ' . $e->getMessage());
       return back()->withErrors('Failed to save contact');
   }
   ```

---

## BAB IX: KESIMPULAN DAN SARAN

### 9.1 Kesimpulan

Berdasarkan analisis dan evaluasi yang telah dilakukan, dapat disimpulkan bahwa:

1. **Fungsionalitas:** Aplikasi web Tigapagi telah berhasil diimplementasikan dengan fitur-fitur utama yang mencakup company profile, sistem kontak, dan tracking progress klien. Semua fitur berfungsi sesuai dengan kebutuhan fungsional yang telah ditentukan.

2. **Teknologi:** Penggunaan Laravel 12.0 dengan PHP 8.4 memberikan fondasi yang solid untuk pengembangan aplikasi modern dengan dukungan fitur-fitur terkini dan performa optimal.

3. **Arsitektur:** Sistem menggunakan arsitektur hybrid yang menggabungkan Laravel MVC untuk company profile dan native PHP untuk tracking system. Pendekatan ini memberikan fleksibilitas namun menimbulkan kompleksitas dalam maintenance.

4. **Keamanan:** Implementasi keamanan dasar telah diterapkan termasuk CSP, prepared statements, dan input validation. Namun, masih terdapat beberapa aspek keamanan yang perlu ditingkatkan seperti CSRF protection untuk tracking routes dan implementasi rate limiting.

5. **Kualitas Kode:** Struktur kode mengikuti standar Laravel dengan database migration, routing yang terorganisir, dan separation of concerns. Namun, bagian native PHP masih memerlukan refactoring untuk konsistensi.

**Status Akhir Proyek:**
- Fungsionalitas: Lengkap dan Operational
- Keamanan: Moderate (memerlukan enhancement)
- Code Quality: Good (dapat ditingkatkan)
- Maintainability: Fair (perlu standardisasi)

### 9.2 Saran Pengembangan Lanjutan

#### 9.2.1 Jangka Pendek (1-3 Bulan)
1. Implementasi CSRF protection untuk semua routes
2. Migrasi authentication system ke Laravel Sanctum/Fortify
3. Penambahan rate limiting untuk endpoint sensitif
4. Implementasi comprehensive logging
5. Unit testing untuk critical features

#### 9.2.2 Jangka Menengah (3-6 Bulan)
1. Refactoring native PHP ke Laravel controllers
2. Implementasi RESTful API untuk mobile development
3. Penambahan fitur notifications (email/SMS)
4. Dashboard analytics untuk admin
5. Performance optimization dengan caching

#### 9.2.3 Jangka Panjang (6-12 Bulan)
1. Microservices architecture untuk scalability
2. Real-time updates menggunakan WebSocket/Pusher
3. Multi-language support (i18n)
4. Advanced reporting dan export functionality
5. Integration dengan third-party services (CRM, Project Management)

### 9.3 Penutup

Pengembangan aplikasi web Tigapagi telah mencapai tahap fungsional dengan implementasi fitur-fitur esensial. Sistem ini memberikan solusi digital yang efektif untuk kebutuhan company profile dan tracking progress klien. Dengan penerapan rekomendasi perbaikan yang telah diuraikan, aplikasi ini memiliki potensi untuk berkembang menjadi platform yang lebih robust, secure, dan scalable.

---

## DAFTAR PUSTAKA

1. Laravel Documentation Team. (2026). *Laravel 12.x Documentation*. Laravel LLC. https://laravel.com/docs/12.x

2. Otwell, T. (2026). *Laravel: The PHP Framework for Web Artisans*. Laravel LLC.

3. PHP Group. (2026). *PHP 8.4 Manual*. The PHP Documentation Group. https://www.php.net/manual/en/

4. OWASP Foundation. (2025). *OWASP Top Ten Web Application Security Risks*. OWASP Foundation.

5. Mozilla Developer Network. (2026). *Content Security Policy (CSP)*. Mozilla Foundation.

6. Tailwind Labs. (2026). *TailwindCSS v4 Documentation*. Tailwind Labs Inc.

7. Evan You. (2026). *Vite Documentation*. Vite Team.

8. Symfony. (2026). *HTTP Foundation Component*. Symfony SAS.

---

## LAMPIRAN

### Lampiran A: Dependency List

#### PHP Dependencies (composer.json)
```json
{
    "require": {
        "php": "^8.4",
        "laravel/framework": "^12.0",
        "laravel/tinker": "^2.10.1"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pail": "^1.2.2",
        "laravel/pint": "^1.24",
        "laravel/sail": "^1.41",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.6",
        "phpunit/phpunit": "^11.5.3"
    }
}
```

#### JavaScript Dependencies (package.json)
```json
{
    "devDependencies": {
        "@tailwindcss/vite": "^4.1.17",
        "axios": "^1.11.0",
        "concurrently": "^9.0.1",
        "laravel-vite-plugin": "^2.0.0",
        "tailwindcss": "^4.1.17",
        "vite": "^7.0.7"
    }
}
```

### Lampiran B: File Structure Detail

```
tigapagiweb/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── ContactController.php
│   │       └── Controller.php
│   ├── Models/
│   │   ├── Contact.php
│   │   └── User.php
│   └── Providers/
│       └── AppServiceProvider.php
├── bootstrap/
│   ├── app.php
│   ├── providers.php
│   └── cache/
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   └── [other config files]
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   └── 2025_12_03_055455_create_contacts_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── public/
│   ├── index.php
│   ├── css/
│   └── img/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── index.blade.php
│       ├── client.blade.php
│       ├── dashboard.php
│       └── [other views]
├── routes/
│   ├── web.php
│   └── console.php
├── storage/
├── tests/
│   ├── Feature/
│   └── Unit/
├── vendor/
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
└── vite.config.js
```

### Lampiran C: Environment Variables

```env
APP_NAME=Tigapagi
APP_ENV=local
APP_KEY=base64:generated_key_here
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

---

**Disusun oleh:** Tim Pengembang  
**Tanggal:** 4 Januari 2026  
**Versi:** 1.0  
**Status:** Final Draft
