# Penjelasan Upgrade Project POS Raula (untuk UKK RPL)

Dokumen ini menjelaskan **apa yang diubah**, **mengapa**, dan **bagaimana** agar kamu siap menjawab pertanyaan asesor.

---

## 1. Tech Stack Project

- **Framework**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade + Tailwind CSS + Vite
- **Database**: MySQL (`pos_raula2`)
- **Auth**: Session-based (Laravel Auth) + Role Middleware
- **Password**: Hashing dengan Bcrypt (`Hash::make` / `Auth::attempt`)

---

## 2. Perubahan yang Dilakukan

### A. Sidebar (layouts/sidebar.blade.php)

**Sebelum:**
- Menu campur aduk
- Ada menu "Profil Saya" di bawah Pengaturan
- Ada tombol Logout di footer sidebar

**Sesudah (sesuai request):**
- Menu dikelompokkan jelas:
  - **Dashboard**
  - **Produk** (Kategori, Produk, Pemasok)
  - **Pembelian**
  - **Penjualan**
  - **Report** (Ringkasan, Penjualan, Pembelian, Stok)
  - **People** (Pengguna – hanya admin)
- Menu **Profil Saya** dihapus dari sidebar
- Tombol **Logout di sidebar dihapus**
- Avatar + nama user di atas sidebar **tetap bisa diklik** → masuk ke halaman Profil (mirip WhatsApp)

**File yang diubah:** `resources/views/layouts/sidebar.blade.php`

### B. Logout hanya di Navbar Atas

Logout sudah ada di kanan atas navbar (`layouts/app.blade.php`).  
Sidebar tidak lagi punya tombol logout, sesuai permintaan.

### C. Fitur Login – Remember Me

**Masalah sebelumnya:**  
Checkbox "Ingat saya" ada di form, tapi **tidak diproses** di controller.

**Perbaikan:**
```php
// AuthController.php
$remember = $request->boolean('remember');
Auth::attempt($credentials, $remember);
```

Jika dicentang, Laravel akan set cookie `remember_web_...` sehingga user tetap login meski browser ditutup (selama session lifetime + remember token masih valid).

**File yang diubah:**
- `app/Http/Controllers/AuthController.php`
- `app/Http/Requests/LoginRequest.php` (tambah validasi `remember`)
- `resources/views/login.blade.php` (value="1" + old value)

### D. Fitur Lupa Password

Ditambahkan fitur lengkap:

| Route | Method | Keterangan |
|-------|--------|------------|
| `/forgot-password` | GET | Form input email |
| `/forgot-password` | POST | Kirim link reset |
| `/reset-password/{token}` | GET | Form password baru |
| `/reset-password` | POST | Simpan password baru |

**File baru:**
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`

**File diubah:**
- `routes/web.php` (tambah route guest)
- `app/Http/Controllers/AuthController.php` (method `showForgotForm`, `sendResetLink`, `showResetForm`, `resetPassword`)

**Catatan untuk demo UKK:**
- Laravel menggunakan `Password::sendResetLink()`.
- Jika email (MAIL_MAILER) belum dikonfigurasi di `.env`, link tetap bisa di-generate, tapi email tidak terkirim.
- Untuk production: set `MAIL_MAILER=smtp` + kredensial di `.env`.

### E. Logo / Foto Keranjang Bisa Diganti

Logo yang tampil di:
- Favicon
- Navbar atas
- Halaman login (ikon)

tersimpan di folder:

```
public/imagelogo/
├── lopos.jpg          ← logo utama (ganti file ini)
├── foto.jpeg
├── image.jpg
└── smkn4 logo.jpg
```

**Cara ganti logo:**
1. Siapkan file gambar (jpg/png, disarankan square).
2. Rename menjadi `lopos.jpg`.
3. Timpa file di `public/imagelogo/lopos.jpg`.
4. Refresh browser (Ctrl+F5).

Tidak perlu ubah kode.

### F. Relasi Model

Diperbaiki relasi yang kurang lengkap:

**Kategori.php**
```php
public function produks()
{
    return $this->hasMany(Produk::class, 'jenis_id');
}
```

**Catatan penting (untuk dijawab asesor):**
- Tabel `produk` memakai kolom foreign key bernama **`jenis_id`** (bukan `kategori_id`).
- Itu sebabnya di model `Produk` relasinya:
  ```php
  return $this->belongsTo(Kategori::class, 'jenis_id');
  ```

Relasi lain sudah benar:
- User → Role, Produk, Penjualan
- Penjualan → User, ItemPenjualan / DetailPenjualan
- Pembelian → Pemasok, User, PembelianDetail
- dll.

### G. Data Dummy & Database

Project sudah punya seeder:

```
database/seeders/
├── DatabaseSeeder.php
├── RoleSeeder.php
├── UserSeeder.php
├── KategoriSeeder.php
├── ProdukSeeder.php
└── PenjualanSeeder.php
```

**Cara isi data dummy:**
```bash
php artisan migrate:fresh --seed
```

Atau import file `database.sql` yang sudah ada di root project.

Pastikan di `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_raula2
DB_USERNAME=root
DB_PASSWORD=
```

---

## 3. Keamanan yang Sudah Ada (siap diuji asesor)

| Aspek | Status | Keterangan |
|-------|--------|------------|
| CSRF Protection | ✅ | `@csrf` di semua form + middleware VerifyCsrfToken |
| Password Hashing | ✅ | Bcrypt via `Hash::make` / Auth::attempt |
| SQL Injection | ✅ | Eloquent / Query Builder (parameter binding) |
| XSS | ✅ | Blade `{{ }}` otomatis escape |
| Authorization | ✅ | Middleware `role:admin` / `role:admin,kasir` + Policy |
| Session | ✅ | Session database + regenerate on login |
| Remember Me | ✅ | Sudah diperbaiki |
| Mass Assignment | ✅ | `$fillable` di setiap Model |
| Soft Delete (sebagian) | ✅ | Produk pakai withTrashed di ItemPenjualan |

---

## 4. Cara Menjalankan Project (setelah extract ZIP)

```bash
# 1. Masuk folder
cd pos_raul

# 2. Install dependency PHP
composer install

# 3. Install dependency JS
npm install

# 4. Copy environment
cp .env.example .env
# lalu edit DB_* sesuai MySQL kamu

# 5. Generate key
php artisan key:generate

# 6. Migrasi + seeder
php artisan migrate:fresh --seed

# 7. Link storage (untuk foto produk)
php artisan storage:link

# 8. Build asset
npm run build
# atau development: npm run dev

# 9. Jalankan server
php artisan serve
```

Buka: `http://127.0.0.1:8000/login`

**Akun default** (cek UserSeeder):
- Biasanya email admin + password yang di-hash di seeder.

---

## 5. Struktur Folder Penting

```
app/
├── Http/
│   ├── Controllers/     ← logic bisnis
│   ├── Middleware/      ← RoleMiddleware
│   └── Requests/        ← validasi form
├── Models/              ← Eloquent + relasi
├── Policies/            ← authorization
└── Services/            ← logic laporan & monitoring stok

resources/views/
├── layouts/             ← app, sidebar, guest
├── auth/                ← forgot & reset password (baru)
├── produk/, penjualan/, pembelian/, ...
└── components/

routes/web.php           ← semua route
database/seeders/        ← data dummy
public/imagelogo/        ← logo (bisa diganti)
```

---

## 6. Jawaban Siap untuk Pertanyaan Asesor

**Q: Bagaimana sistem autentikasi bekerja?**  
A: Menggunakan Laravel Auth (session). Login lewat `Auth::attempt($credentials, $remember)`. Password di-hash bcrypt. Setelah login sukses, session di-regenerate untuk cegah session fixation.

**Q: Bagaimana pembatasan akses role?**  
A: Middleware `RoleMiddleware` dicek di route group (`role:admin` atau `role:admin,kasir`). Ada juga Policy untuk authorization yang lebih detail.

**Q: Bagaimana mencegah SQL Injection?**  
A: Semua query memakai Eloquent / Query Builder yang otomatis menggunakan parameter binding. Tidak ada raw query yang menerima input user secara langsung.

**Q: Apa yang terjadi saat centang "Ingat saya"?**  
A: Parameter `$remember = true` dikirim ke `Auth::attempt`. Laravel menyimpan remember token di tabel `users` dan set cookie jangka panjang.

**Q: Bagaimana cara ganti logo?**  
A: Cukup ganti file `public/imagelogo/lopos.jpg` tanpa ubah kode.

---

## 7. Yang Masih Bisa Ditingkatkan (opsional, kalau waktu cukup)

1. Halaman penjualan/create dibuat lebih mirip BuildWithAngga (grid kartu produk + kategori tab + cart kanan yang lebih visual).
2. Upload avatar user di halaman profil.
3. Notifikasi real (stok rendah dari database, bukan hardcode).
4. Export laporan ke PDF/Excel.
5. Activity log / audit trail.

---

**Dibuat untuk persiapan UKK RPL – POS Raula**  
Semoga sukses ujikom!
