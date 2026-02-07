
<div align="center">

  <img src="https://img.icons8.com/fluency/96/life-guard.png" alt="logo" width="100" height="100" />
  
  # SiagaBencana v2.0
  
  **Platform Edukasi & Mitigasi Bencana Alam Berbasis Web**
  
  <p>
    <a href="#tentang">Tentang</a> •
    <a href="#fitur-unggulan">Fitur</a> •
    <a href="#teknologi">Teknologi</a> •
    <a href="#instalasi">Instalasi</a> •
    <a href="#progress">Status</a>
  </p>

  ![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)
  ![Filament](https://img.shields.io/badge/Filament-v3-FPS?style=for-the-badge&logo=livewire&color=orange)
  ![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css)
  ![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql)
  ![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

</div>

---

## 📖 Tentang Projek

**SiagaBencana** adalah inisiatif platform edukasi digital yang bertujuan untuk meningkatkan kesadaran dan kesiapsiagaan masyarakat terhadap bencana alam. 

Aplikasi ini dirancang untuk menjembatani kesenjangan informasi dengan menyediakan materi mitigasi yang **terstruktur**, **mudah diakses**, dan **interaktif** (Video & Buku Saku).

**Misi Utama:**
> "Mengubah ketidaktahuan menjadi kesiapsiagaan, satu pengguna pada satu waktu."

---

## 🚀 Fitur Unggulan

### 🛡️ Panel Admin (Powered by Filament)
Panel kontrol canggih untuk pengelola konten tanpa perlu menyentuh kodingan.
- **Manajemen Kategori:** Kelola fase bencana (Pra, Tanggap Darurat, Pasca).
- **Manajemen Topik:** Buat topik spesifik (Gempa, Banjir, Tsunami).
- **Video Library:** Input Video edukasi via YouTube ID (Auto-embed).
- **Digital Library:** Upload dan kelola modul PDF/Buku Saku.
- **Dashboard Statistik:** Pantau jumlah konten dan aset.

### 👤 User Interface (Frontend Modern)
Pengalaman belajar yang immersive untuk masyarakat umum.
- **Private Access:** Sistem Login/Register aman (Laravel Breeze).
- **Pusat Belajar:** Navigasi materi berdasarkan kategori bencana.
- **Cinema Mode:** Pemutar video edukasi yang fokus dan responsif.
- **PDF Reader:** Baca atau download buku saku langsung di browser.
- **Dashboard Personal:** Sidebar menu dengan akses cepat ke materi terbaru.

---

## 🛠️ Teknologi & Arsitektur

Projek ini dibangun di atas fondasi teknologi web modern yang **Scalable** dan **Secure**.

| Komponen | Teknologi | Deskripsi |
| :--- | :--- | :--- |
| **Framework** | **Laravel 11** | Framework PHP terpopuler, aman & cepat. |
| **Admin Panel** | **FilamentPHP v3** | TALL Stack admin panel generator. |
| **Styling** | **Tailwind CSS** | Utility-first CSS framework untuk UI modern. |
| **Database** | **MySQL** | Relational Database Management System. |
| **Auth** | **Laravel Breeze** | Sistem autentikasi ringan dan aman. |
| **Icons** | **FontAwesome 6** | Ikon vektor untuk UI yang cantik. |

---

## 🔌 Panduan Instalasi (Localhost)

Ikuti langkah ini untuk menjalankan projek di komputer lokal (Laragon/XAMPP).

**1. Clone Repository**
```bash
git clone [https://github.com/username/siaga-bencana-v2.git](https://github.com/username/siaga-bencana-v2.git)
cd siaga-bencana-v2

```

**2. Install Dependencies**

```bash
composer install
npm install

```

**3. Setup Environment**
Duplikat file `.env.example` menjadi `.env`, lalu atur database.

```bash
cp .env.example .env
php artisan key:generate

```

*Pastikan buat database baru bernama `siaga_bencana_v2` di MySQL.*

**4. Migrasi & Seeding Data**
Ini akan membuat tabel dan mengisi data contoh otomatis.

```bash
php artisan migrate:fresh --seed

```

**5. Jalankan Aplikasi**
Buka dua terminal berbeda untuk menjalankan backend dan frontend asset.

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev

```

Akses web di: `http://127.0.0.1:8000`

---

## 🔑 Akun Demo

Gunakan akun ini untuk masuk dan mencoba fitur aplikasi.

| Role | Email | Password | Akses |
| --- | --- | --- | --- |
| **Super Admin** | `admin@siaga.com` | `password` | Full Akses (Filament Panel) |
| **User Warga** | `user@gmail.com` | `password` | Akses Materi Belajar |

> **URL Admin Panel:** `http://127.0.0.1:8000/admin`

---

## 🚧 Status Pengembangan (Roadmap)

Berikut adalah status pengerjaan fitur saat ini:

* [x] **Core Framework & Database**     
* [x] **Admin Panel CRUD**  
* [x] **User Authentication**   
* [x] **Frontend Landing Page**     
* [x] **Video Player Integration** 
* [x] **PDF Module Viewer** 
* [x] **User Dashboard (Sidebar Layout)** 

---

<div align="center">
<p>Dibuat dengan ❤️ dan ☕ untuk Kemanusiaan.</p>
<p>&copy; 2024 SiagaBencana Team.</p>
</div>
