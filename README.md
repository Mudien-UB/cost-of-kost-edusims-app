# 🛠️ EduSims Backend - Cost of Kost

**EduSims** adalah aplikasi web interaktif yang bertujuan untuk membantu mahasiswa dan pencari kost dalam merencanakan pengeluaran bulanan secara edukatif dan menyenangkan.

Repositori ini adalah bagian **backend** dari sistem **EduSims**, dibangun menggunakan **Laravel**, yang berfungsi sebagai REST API untuk menangani data pengeluaran, pemasukan, simulasi, dan edukasi keuangan.

---

## 🎯 Tujuan Backend
- Menyediakan API untuk frontend (React/Remix/Vue/dsb.)
- Mengelola data pengguna, pemasukan, pengeluaran, dan simulasi biaya
- Mendukung autentikasi dan otorisasi pengguna
- Memberikan data statistik sederhana dan analitik keuangan

---

## 🔧 Teknologi yang Digunakan
- **Laravel** 10.x
- **PHP** 8.1+
- **MySQL** / SQLite
- **JWT Auth** / Laravel Sanctum (pilih sesuai kebutuhan)
- **Composer**

---

## 🚀 Cara Menjalankan Proyek (Development)

### 1. Clone repositori
```bash
git clone https://github.com/Mudien-UB/cost-of-kost-edusims-app
cd cost-of-kost-edusims-app
```

### 2. Install dependencies
```bash
composer install
```

### 3. Copy file .env
```bash
cp .env.example .env
```
### 4. Atur konfigurasi database di .env

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=costofkost
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate key & migrasi database
```bash
php artisan key:generate
php artisan migrate
```

### 6. Jalankan server Laravel
```bash
php artisan serve
```

## 📦 Fitur API yang Direncanakan
Register/Login pengguna

- ✅ Input pengeluaran & pemasukan

- ✅ Dapatkan statistik bulanan

- ✅ Simulasi pengeluaran harian/mingguan/bulanan

- 🔜 Tips dan edukasi keuangan via API

