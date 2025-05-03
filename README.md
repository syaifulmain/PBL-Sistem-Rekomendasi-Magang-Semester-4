# 📦 SIMagang Project Setup

Panduan instalasi dan konfigurasi awal untuk proyek SIMagang.

---

## ✅ Requirements

Pastikan Anda telah menginstal:

- PHP >= 8.1
- Composer
- MySQL
- Git

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/syaifulmain/PBL-Sistem-Rekomendasi-Magang-Semester-4.git
cd PBL-Sistem-Rekomendasi-Magang-Semester-4
```

### 2. Install Dependency PHP
```bash
composer install
```

### 3. Copy File Environment
```bash
cp .env.example .env
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Konfigurasi Database
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simagang
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Migrasi
```bash
php artisan migrate
```

### 7. Seed Data Awal (Opsional)
```bash
php artisan db:seed
```

### 8. Jalankan Server Lokal
```bash
php artisan serve
```