# 📧 Contact Management System - Documentation

## Overview
System manajemen kontak telah berhasil diimplementasikan dengan fitur:
1. ✅ Halaman Admin untuk manage contacts
2. ✅ Email notification otomatis saat ada kontak baru
3. ✅ Statistik kontak (Total, Today, This Week)
4. ✅ Delete functionality untuk menghapus kontak

---

## 🔗 Akses Halaman Admin

**URL:** `http://localhost/clients` (atau sesuai setup XAMPP Anda)

Kemudian akses:
```
http://localhost/admin/contacts
```

Atau dari terminal:
```bash
php artisan serve
```

Lalu buka:
```
http://localhost:8000/admin/contacts
```

---

## 📧 Email Notification Setup

### Current Setup (Development)
Saat ini email dikonfigurasi dengan `MAIL_MAILER=log`, yang berarti:
- Email **TIDAK dikirim secara real**
- Email **disimpan di log file** untuk debugging
- Lokasi log: `/storage/logs/laravel.log`

### Melihat Email yang Terkirim
```bash
tail -f storage/logs/laravel.log
```

### Setup untuk Production (Email Real)

#### Option 1: Gmail SMTP
Edit `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="contact@tigapagi.com"
MAIL_FROM_NAME="Studio Tigapagi"
MAIL_ADMIN_ADDRESS="your-admin@gmail.com"
```

**Note:** Untuk Gmail, gunakan App Password, bukan password biasa:
1. Go to Google Account → Security
2. Enable 2-Step Verification
3. Generate App Password
4. Use App Password in `.env`

#### Option 2: Mailtrap (Testing)
Edit `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_FROM_ADDRESS="contact@tigapagi.com"
MAIL_FROM_NAME="Studio Tigapagi"
MAIL_ADMIN_ADDRESS="admin@tigapagi.com"
```

Sign up: https://mailtrap.io

#### Option 3: SendGrid, Mailgun, SES
Lihat dokumentasi Laravel untuk konfigurasi lengkap.

---

## 🎯 Fitur-Fitur

### 1. Admin Dashboard (`/admin/contacts`)
- View semua kontak yang masuk
- Statistik real-time:
  - Total contacts
  - Contacts today
  - Contacts this week
- Sort by newest first
- Delete individual contacts

### 2. Email Notification
Setiap kali ada kontak baru, sistem akan:
- Kirim email ke admin (sesuai `MAIL_ADMIN_ADDRESS` di `.env`)
- Email berisi:
  - Nama pengunjung
  - Email pengunjung
  - Nomor telepon
  - Waktu submission
  - Link ke admin dashboard

### 3. Form Kontak (Homepage)
- Input: Name, Email, Phone
- Validasi otomatis
- Success message setelah submit
- Data tersimpan di database `contacts` table

---

## 🗄️ Database Structure

**Table:** `contacts`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar(255) | Contact name |
| email | varchar(255) | Contact email |
| phone | varchar(20) | Contact phone |
| created_at | timestamp | Submission time |
| updated_at | timestamp | Last update |

---

## 🛠️ Testing

### 1. Test Form Submission
1. Buka homepage: `http://localhost/`
2. Scroll ke form kontak
3. Isi form dan submit
4. Cek success message

### 2. Test Admin Dashboard
1. Buka: `http://localhost/admin/contacts`
2. Lihat kontak yang baru masuk
3. Cek statistik

### 3. Test Email Notification
```bash
# Watch log file
tail -f storage/logs/laravel.log

# Submit form di browser
# Lihat email content di log
```

### 4. Test Delete
1. Di admin dashboard
2. Klik tombol "Delete" pada kontak
3. Confirm deletion
4. Kontak terhapus

---

## 📝 Commands

### Clear Cache (Jika ada error)
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### View Routes
```bash
php artisan route:list | grep contact
```

### Check Mail Configuration
```bash
php artisan tinker

# Test send email
Mail::raw('Test', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

---

## 🎨 Customization

### Mengubah Admin Email
Edit `.env`:
```env
MAIL_ADMIN_ADDRESS="your-actual-email@domain.com"
```

### Mengubah Email Template
Edit file:
```
resources/views/emails/new-contact.blade.php
```

### Mengubah Admin Dashboard Design
Edit file:
```
resources/views/admin/contacts.blade.php
```

---

## 🔒 Security Notes

### Production Recommendations:
1. **Add Authentication** - Protect `/admin/contacts` route
   ```php
   Route::middleware(['auth'])->group(function () {
       Route::get('/admin/contacts', [ContactController::class, 'index']);
   });
   ```

2. **Rate Limiting** - Prevent spam submissions
   ```php
   Route::post('/contact', [ContactController::class, 'store'])
       ->middleware('throttle:5,1'); // 5 requests per minute
   ```

3. **CSRF Protection** - Already implemented via `@csrf` token

4. **Input Sanitization** - Already implemented via validation

---

## 📊 Future Enhancements

Ideas for improvement:
- [ ] Export contacts to CSV
- [ ] Search/filter contacts
- [ ] Reply to contacts from admin panel
- [ ] Mark contacts as "Read/Unread"
- [ ] Add message field to contact form
- [ ] Email templates customization UI
- [ ] SMS notification option
- [ ] Slack/Telegram integration

---

## 🐛 Troubleshooting

### Error: "Mail driver not set"
```bash
php artisan config:clear
```

### Error: "Class 'Mail' not found"
Check file has proper import:
```php
use Illuminate\Support\Facades\Mail;
```

### Email tidak terkirim
1. Check `.env` configuration
2. Check `storage/logs/laravel.log` for errors
3. Verify SMTP credentials

### Admin page tidak muncul
1. Clear cache
2. Check route: `php artisan route:list`
3. Verify file exists: `resources/views/admin/contacts.blade.php`

---

## 📞 Support

For issues or questions, check:
- Laravel Mail Documentation: https://laravel.com/docs/11.x/mail
- Laravel Database: https://laravel.com/docs/11.x/database

---

**Created:** February 25, 2026
**Version:** 1.0.0
