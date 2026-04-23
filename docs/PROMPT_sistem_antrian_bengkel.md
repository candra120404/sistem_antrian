# PROMPT: Sistem Informasi Antrian Bengkel
> Gunakan file ini sebagai master prompt untuk Claude Agent / Antigravity  
> Stack: Laravel 11 (Backend REST API) + Blade/Tailwind (Web Frontend)

---

## 🎯 KONTEKS PROYEK

Kamu adalah senior full-stack developer. Tugasmu adalah membangun **Sistem Informasi Antrian Bengkel** berbasis web menggunakan Laravel. Sistem ini mengelola antrian cuci/servis kendaraan secara digital dan real-time, menggantikan sistem manual yang tidak terstruktur.

**Dua jenis pengguna:**
- **Admin** (pemilik/operator bengkel): mengelola antrian, harga layanan, dan melihat data harian
- **Pelanggan**: registrasi, masuk antrian, pantau posisi antrian secara real-time

---

## 🏗️ ARSITEKTUR YANG HARUS DIBANGUN

```
sistem-antrian-bengkel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php
│   │   │   ├── Admin/
│   │   │   │   ├── AntrianController.php
│   │   │   │   ├── HargaController.php
│   │   │   │   └── DataHarianController.php
│   │   │   ├── Pelanggan/
│   │   │   │   └── AntrianPelangganController.php
│   │   │   └── Api/
│   │   │       ├── AntrianApiController.php
│   │   │       └── AuthApiController.php
│   │   ├── Middleware/
│   │   │   ├── IsAdmin.php
│   │   │   └── IsPelanggan.php
│   │   └── Requests/
│   │       ├── LoginRequest.php
│   │       ├── RegisterRequest.php
│   │       └── AntrianRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Antrian.php
│   │   ├── JenisLayanan.php
│   │   └── Transaksi.php
│   └── Services/
│       └── AntrianService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── admin.blade.php
│       │   └── pelanggan.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── antrian/index.blade.php
│       │   ├── harga/index.blade.php
│       │   └── data-harian/index.blade.php
│       └── pelanggan/
│           ├── dashboard.blade.php
│           ├── antrian/create.blade.php
│           └── antrian/status.blade.php
└── routes/
    ├── web.php
    └── api.php
```

---

## 🗄️ DATABASE — MIGRATION & MODEL

### Buat semua migration berikut secara berurutan:

---

### Migration 1: `users`
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('username')->unique();
    $table->string('email')->unique();
    $table->string('password');
    $table->enum('role', ['admin', 'pelanggan'])->default('pelanggan');
    $table->timestamps();
});
```

---

### Migration 2: `jenis_layanans`
```php
Schema::create('jenis_layanans', function (Blueprint $table) {
    $table->id();
    $table->string('nama_layanan');           // contoh: "Cuci Motor", "Cuci Mobil"
    $table->enum('jenis_kendaraan', ['motor', 'mobil']);
    $table->decimal('harga', 10, 2);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

---

### Migration 3: `antrians`
```php
Schema::create('antrians', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('jenis_layanan_id')->constrained('jenis_layanans');
    $table->string('nomor_antrian', 10);       // format: A001, A002, dst
    $table->string('nama_pelanggan');
    $table->string('no_plat');
    $table->enum('jenis_kendaraan', ['motor', 'mobil']);
    $table->enum('status', ['menunggu', 'diproses', 'selesai', 'batal'])->default('menunggu');
    $table->integer('posisi_antrian')->nullable();
    $table->timestamp('selesai_at')->nullable();
    $table->timestamps();
});
```

---

### Migration 4: `transaksis`
```php
Schema::create('transaksis', function (Blueprint $table) {
    $table->id();
    $table->foreignId('antrian_id')->constrained()->onDelete('cascade');
    $table->decimal('total_bayar', 10, 2);
    $table->enum('status_bayar', ['belum', 'lunas'])->default('belum');
    $table->date('tanggal');
    $table->timestamps();
});
```

---

### Model: `User.php`
```php
<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = ['name', 'username', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    public function antrians()
    {
        return $this->hasMany(Antrian::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
```

---

### Model: `Antrian.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $fillable = [
        'user_id', 'jenis_layanan_id', 'nomor_antrian',
        'nama_pelanggan', 'no_plat', 'jenis_kendaraan',
        'status', 'posisi_antrian', 'selesai_at'
    ];

    protected $casts = ['selesai_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function jenisLayanan() { return $this->belongsTo(JenisLayanan::class); }
    public function transaksi() { return $this->hasOne(Transaksi::class); }

    // Generate nomor antrian otomatis: A001, A002, dst
    public static function generateNomor(): string
    {
        $last = self::whereDate('created_at', today())->latest()->first();
        $number = $last ? ((int) substr($last->nomor_antrian, 1)) + 1 : 1;
        return 'A' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    // Hitung posisi dalam antrian aktif
    public static function hitungPosisi(int $antrianId): int
    {
        return self::where('status', 'menunggu')
            ->where('id', '<=', $antrianId)
            ->count();
    }
}
```

---

### Model: `JenisLayanan.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisLayanan extends Model
{
    protected $fillable = ['nama_layanan', 'jenis_kendaraan', 'harga', 'is_active'];
    protected $casts = ['harga' => 'decimal:2'];

    public function antrians() { return $this->hasMany(Antrian::class); }
}
```

---

### Model: `Transaksi.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['antrian_id', 'total_bayar', 'status_bayar', 'tanggal'];
    protected $casts = ['total_bayar' => 'decimal:2', 'tanggal' => 'date'];

    public function antrian() { return $this->belongsTo(Antrian::class); }
}
```

---

## 🌱 SEEDER

### `DatabaseSeeder.php`
```php
// Buat data awal:
// 1. Admin default
User::create([
    'name'     => 'Admin Bengkel',
    'username' => 'admin',
    'email'    => 'admin@bengkel.com',
    'password' => bcrypt('password123'),
    'role'     => 'admin',
]);

// 2. Jenis Layanan default
JenisLayanan::insert([
    ['nama_layanan' => 'Cuci Motor Standar',  'jenis_kendaraan' => 'motor', 'harga' => 15000, 'is_active' => true],
    ['nama_layanan' => 'Cuci Mobil Standar',  'jenis_kendaraan' => 'mobil', 'harga' => 35000, 'is_active' => true],
    ['nama_layanan' => 'Cuci Motor Premium',  'jenis_kendaraan' => 'motor', 'harga' => 25000, 'is_active' => true],
    ['nama_layanan' => 'Cuci Mobil Premium',  'jenis_kendaraan' => 'mobil', 'harga' => 60000, 'is_active' => true],
]);
```

---

## 🔐 AUTENTIKASI

### `AuthController.php` — Web (Session)
Buat controller dengan method:

| Method | Route | Fungsi |
|--------|-------|--------|
| `showLogin()` | GET `/login` | Tampilkan form login |
| `login()` | POST `/login` | Validasi login, redirect sesuai role |
| `showRegister()` | GET `/register` | Tampilkan form registrasi pelanggan |
| `register()` | POST `/register` | Buat akun pelanggan baru |
| `logout()` | POST `/logout` | Logout dan hapus session |

**Logika login:**
```php
// Setelah login berhasil:
if (auth()->user()->isAdmin()) {
    return redirect()->route('admin.dashboard');
} else {
    return redirect()->route('pelanggan.dashboard');
}
```

**Validasi Register:**
```php
$rules = [
    'name'     => 'required|string|max:100',
    'username' => 'required|string|unique:users|max:50',
    'email'    => 'required|email|unique:users',
    'password' => 'required|min:8|confirmed',
];
```

---

### `AuthApiController.php` — API (Sanctum Token)
Buat REST API untuk mobile (Flutter):

| Method | Endpoint | Fungsi |
|--------|----------|--------|
| POST | `/api/login` | Login, return token Sanctum |
| POST | `/api/register` | Registrasi pelanggan baru |
| POST | `/api/logout` | Revoke token |
| GET | `/api/me` | Info user yang sedang login |

**Response format:**
```json
{
    "status": true,
    "message": "Login berhasil",
    "data": {
        "user": { "id": 1, "name": "...", "role": "pelanggan" },
        "token": "1|abc123..."
    }
}
```

---

## 🚦 ROUTING — `routes/web.php`

```php
// Public
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin (middleware: auth + IsAdmin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', [Admin\AntrianController::class, 'index'])->name('dashboard');

    // Kelola Antrian
    Route::get('/antrian', [Admin\AntrianController::class, 'index'])->name('antrian.index');
    Route::patch('/antrian/{antrian}/selesai', [Admin\AntrianController::class, 'selesai'])->name('antrian.selesai');
    Route::patch('/antrian/{antrian}/proses', [Admin\AntrianController::class, 'proses'])->name('antrian.proses');
    Route::delete('/antrian/{antrian}', [Admin\AntrianController::class, 'destroy'])->name('antrian.destroy');

    // Kelola Harga
    Route::get('/harga', [Admin\HargaController::class, 'index'])->name('harga.index');
    Route::put('/harga/{jenisLayanan}', [Admin\HargaController::class, 'update'])->name('harga.update');

    // Data Harian
    Route::get('/data-harian', [Admin\DataHarianController::class, 'index'])->name('data-harian.index');
});

// Pelanggan (middleware: auth + IsPelanggan)
Route::prefix('pelanggan')->name('pelanggan.')->middleware(['auth', 'is_pelanggan'])->group(function () {
    Route::get('/dashboard', [Pelanggan\AntrianPelangganController::class, 'dashboard'])->name('dashboard');
    Route::get('/antrian/buat', [Pelanggan\AntrianPelangganController::class, 'create'])->name('antrian.create');
    Route::post('/antrian', [Pelanggan\AntrianPelangganController::class, 'store'])->name('antrian.store');
    Route::get('/antrian/status', [Pelanggan\AntrianPelangganController::class, 'status'])->name('antrian.status');
});
```

---

## 🔌 ROUTING — `routes/api.php`

```php
// Public API
Route::post('/login', [Api\AuthApiController::class, 'login']);
Route::post('/register', [Api\AuthApiController::class, 'register']);

// Protected API (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [Api\AuthApiController::class, 'logout']);
    Route::get('/me', [Api\AuthApiController::class, 'me']);

    // Antrian API (untuk Flutter mobile)
    Route::get('/antrian', [Api\AntrianApiController::class, 'index']);
    Route::post('/antrian', [Api\AntrianApiController::class, 'store']);
    Route::get('/antrian/saya', [Api\AntrianApiController::class, 'milikSaya']);
    Route::get('/antrian/{id}/posisi', [Api\AntrianApiController::class, 'posisi']);
    Route::patch('/antrian/{id}/selesai', [Api\AntrianApiController::class, 'selesai']);
    Route::delete('/antrian/{id}', [Api\AntrianApiController::class, 'destroy']);

    // Harga API
    Route::get('/harga', [Api\AntrianApiController::class, 'harga']);
    Route::put('/harga/{id}', [Api\AntrianApiController::class, 'updateHarga']);

    // Data Harian
    Route::get('/data-harian', [Api\AntrianApiController::class, 'dataHarian']);
});
```

---

## ⚙️ CONTROLLER — LOGIKA BISNIS

### `Admin\AntrianController.php`
```php
// index() — tampilkan semua antrian hari ini, dikelompokkan per status
public function index() {
    $antrians = Antrian::with(['user', 'jenisLayanan'])
        ->whereDate('created_at', today())
        ->orderBy('created_at', 'asc')
        ->get()
        ->groupBy('status'); // menunggu, diproses, selesai

    return view('admin.antrian.index', compact('antrians'));
}

// selesai() — tandai selesai, buat transaksi, update posisi antrian
public function selesai(Antrian $antrian) {
    $antrian->update(['status' => 'selesai', 'selesai_at' => now()]);

    Transaksi::create([
        'antrian_id'  => $antrian->id,
        'total_bayar' => $antrian->jenisLayanan->harga,
        'status_bayar' => 'lunas',
        'tanggal'     => today(),
    ]);

    // Re-hitung posisi untuk antrian yg masih menunggu
    Antrian::where('status', 'menunggu')
        ->orderBy('created_at')
        ->each(function ($a, $i) {
            $a->update(['posisi_antrian' => $i + 1]);
        });

    return back()->with('success', 'Antrian berhasil diselesaikan.');
}
```

---

### `Pelanggan\AntrianPelangganController.php`
```php
// store() — daftarkan ke antrian
public function store(AntrianRequest $request) {
    $layanan = JenisLayanan::findOrFail($request->jenis_layanan_id);

    $antrian = Antrian::create([
        'user_id'          => auth()->id(),
        'jenis_layanan_id' => $layanan->id,
        'nomor_antrian'    => Antrian::generateNomor(),
        'nama_pelanggan'   => auth()->user()->name,
        'no_plat'          => strtoupper($request->no_plat),
        'jenis_kendaraan'  => $layanan->jenis_kendaraan,
        'status'           => 'menunggu',
        'posisi_antrian'   => Antrian::where('status', 'menunggu')->count() + 1,
    ]);

    return redirect()->route('pelanggan.antrian.status')
        ->with('success', "Nomor antrian Anda: {$antrian->nomor_antrian}");
}

// status() — tampilkan posisi antrian real-time
public function status() {
    $antrian = Antrian::where('user_id', auth()->id())
        ->whereIn('status', ['menunggu', 'diproses'])
        ->whereDate('created_at', today())
        ->latest()
        ->first();

    $totalMenunggu = Antrian::where('status', 'menunggu')
        ->whereDate('created_at', today())
        ->count();

    return view('pelanggan.antrian.status', compact('antrian', 'totalMenunggu'));
}
```

---

### `Api\AntrianApiController.php` (untuk Flutter)
Semua method harus return JSON:

```php
// Contoh response standar:
return response()->json([
    'status'  => true,
    'message' => 'Berhasil',
    'data'    => $data,
], 200);

// Error:
return response()->json([
    'status'  => false,
    'message' => 'Pesan error',
    'errors'  => $validator->errors(),
], 422);
```

**Method yang wajib dibuat:**
- `index()` → semua antrian hari ini (admin only)
- `store()` → buat antrian baru (pelanggan)
- `milikSaya()` → antrian milik user login
- `posisi($id)` → posisi antrian + estimasi waktu tunggu
- `selesai($id)` → tandai selesai (admin only)
- `destroy($id)` → batalkan antrian
- `harga()` → daftar semua jenis layanan aktif
- `updateHarga($id)` → update harga (admin only)
- `dataHarian()` → statistik harian (admin only)

---

## 🛡️ MIDDLEWARE

### `IsAdmin.php`
```php
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check() || !auth()->user()->isAdmin()) {
        abort(403, 'Akses ditolak. Hanya admin yang diizinkan.');
    }
    return $next($request);
}
```

### `IsPelanggan.php`
```php
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check() || auth()->user()->isAdmin()) {
        abort(403, 'Akses ditolak.');
    }
    return $next($request);
}
```

### Daftarkan di `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'is_admin'     => \App\Http\Middleware\IsAdmin::class,
        'is_pelanggan' => \App\Http\Middleware\IsPelanggan::class,
    ]);
})
```

---

## 🎨 FRONTEND — VIEWS (Blade + Tailwind CSS)

> Gunakan Tailwind CSS via CDN: `https://cdn.tailwindcss.com`  
> Semua halaman harus **responsive** (mobile-first)

---

### Layout Admin: `layouts/admin.blade.php`
Komponen wajib:
- Sidebar navigasi: Dashboard, Kelola Antrian, Harga Layanan, Data Harian
- Header: nama bengkel + tombol logout
- Badge counter antrian menunggu (real-time via polling setiap 10 detik)
- Flash message (success/error)
- Color scheme: biru tua (`#1E3A5F`) dan putih

---

### Layout Pelanggan: `layouts/pelanggan.blade.php`
Komponen wajib:
- Header simpel: logo bengkel + nama user + tombol logout
- Navigasi bawah (mobile): Dashboard, Status Antrian
- Flash message

---

### Halaman Login: `auth/login.blade.php`
```
- Form: email/username + password
- Tombol: "Masuk"
- Link: "Belum punya akun? Daftar di sini"
- Logo/nama bengkel di atas form
- Desain kartu (card) di tengah halaman
```

---

### Halaman Register: `auth/register.blade.php`
```
- Form: nama lengkap, username, email, password, konfirmasi password
- Tombol: "Daftar"
- Link: "Sudah punya akun? Login"
```

---

### Dashboard Admin: `admin/antrian/index.blade.php`
```
WAJIB tampilkan:
1. Kartu statistik: Total Hari Ini | Sedang Antri | Diproses | Selesai
2. Tabel antrian REAL-TIME dengan kolom:
   - No Antrian | Nama Pelanggan | No Plat | Jenis | Layanan | Status | Aksi
3. Aksi per baris:
   - Tombol "Proses" (kuning) → ubah status ke diproses
   - Tombol "Selesai" (hijau) → tandai selesai + catat transaksi
   - Tombol "Batalkan" (merah) → hapus dari antrian
4. Badge status berwarna: menunggu (kuning), diproses (biru), selesai (hijau), batal (abu)
5. Auto-refresh tabel setiap 10 detik (JavaScript setInterval + fetch/axios)
```

---

### Halaman Harga: `admin/harga/index.blade.php`
```
- Tabel: Nama Layanan | Jenis Kendaraan | Harga Saat Ini | Aksi
- Tombol Edit per baris → tampilkan modal/inline form input harga baru
- Validasi: harga harus angka > 0
- Notifikasi sukses setelah update
```

---

### Data Harian: `admin/data-harian/index.blade.php`
```
- Filter tanggal (default: hari ini)
- Kartu: Total Kendaraan | Total Pendapatan | Motor | Mobil
- Tabel riwayat antrian sesuai tanggal filter
- Tampilkan "Tidak ada data" jika kosong
```

---

### Dashboard Pelanggan: `pelanggan/dashboard.blade.php`
```
- Sambutan: "Halo, [nama]!"
- Jika belum antri hari ini:
  → Tampilkan 2 tombol besar: [🏍️ Cuci Motor] dan [🚗 Cuci Mobil]
  → Setiap tombol mengarah ke halaman buat antrian dengan jenis pre-selected
- Jika sudah antri:
  → Tampilkan kartu status antrian (nomor, posisi, status)
  → Tombol "Pantau Antrian Saya"
```

---

### Buat Antrian: `pelanggan/antrian/create.blade.php`
```
- Form:
  - Dropdown: pilih layanan (motor/mobil sesuai pilihan dashboard)
  - Input: nomor plat kendaraan (uppercase otomatis)
  - Tampilkan harga layanan yang dipilih secara dinamis (JS)
- Tombol: "Masuk Antrian"
- Konfirmasi sebelum submit: modal "Yakin ingin mendaftar antrian?"
```

---

### Status Antrian: `pelanggan/antrian/status.blade.php`
```
TAMPILKAN (jika ada antrian aktif):
- Nomor antrian besar dan mencolok (contoh: A007)
- Status badge berwarna
- Posisi dalam antrian: "Anda ke-3 dalam antrian"
- Total yang menunggu: "3 kendaraan menunggu"
- Jenis kendaraan dan layanan
- Nomor plat kendaraan
- Auto-refresh setiap 10 detik
- Animasi loading/pulse saat menunggu

TAMPILKAN (jika tidak ada antrian aktif):
- Pesan "Belum ada antrian aktif hari ini"
- Tombol "Daftar Antrian Sekarang"
```

---

## 🔄 REAL-TIME UPDATE (JavaScript)

Buat file `public/js/antrian.js`:

```javascript
// Auto-refresh antrian admin
function refreshAntrianAdmin() {
    fetch('/admin/antrian?ajax=1', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        // Update tabel antrian tanpa reload halaman
        updateTabelAntrian(data.antrians);
        updateBadgeCounter(data.stats);
    });
}

// Auto-refresh posisi antrian pelanggan
function refreshPosisiPelanggan(antrianId) {
    fetch(`/api/antrian/${antrianId}/posisi`, {
        headers: {
            'Authorization': `Bearer ${getToken()}`,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('posisi-display').textContent = data.data.posisi;
        document.getElementById('status-display').textContent = data.data.status;
    });
}

// Jalankan polling
setInterval(refreshAntrianAdmin, 10000);  // setiap 10 detik
setInterval(() => refreshPosisiPelanggan(currentAntrianId), 10000);
```

---

## 📋 FORM REQUEST VALIDATION

### `AntrianRequest.php`
```php
public function rules(): array
{
    return [
        'jenis_layanan_id' => 'required|exists:jenis_layanans,id',
        'no_plat'          => 'required|string|max:15|regex:/^[A-Z0-9\s]+$/i',
    ];
}

public function messages(): array
{
    return [
        'jenis_layanan_id.required' => 'Pilih jenis layanan terlebih dahulu.',
        'no_plat.required'          => 'Nomor plat kendaraan wajib diisi.',
        'no_plat.regex'             => 'Format nomor plat tidak valid.',
    ];
}
```

---

## ⚙️ KONFIGURASI LARAVEL

### `.env` (wajib diset)
```
APP_NAME="Sistem Antrian Bengkel"
APP_URL=http://localhost:8000
DB_DATABASE=db_antrian_bengkel
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000
SESSION_DRIVER=database
```

### `config/sanctum.php`
Pastikan Sanctum diinstal:
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

---

## 🚀 PERINTAH SETUP (Jalankan Berurutan)

```bash
# 1. Buat project baru
composer create-project laravel/laravel sistem-antrian-bengkel
cd sistem-antrian-bengkel

# 2. Install dependency
composer require laravel/sanctum

# 3. Setup database
php artisan migrate
php artisan db:seed

# 4. Publish Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

# 5. Jalankan server
php artisan serve
```

---

## ✅ CHECKLIST FITUR

### Admin
- [ ] Login admin
- [ ] Dashboard antrian real-time
- [ ] Tombol Proses → ubah status ke diproses
- [ ] Tombol Selesai → tandai selesai + auto-catat transaksi
- [ ] Tombol Batalkan → hapus antrian
- [ ] Kelola harga layanan (motor & mobil)
- [ ] Lihat data harian dengan filter tanggal
- [ ] Logout

### Pelanggan
- [ ] Registrasi akun baru
- [ ] Login pelanggan
- [ ] Pilih jenis kendaraan (motor/mobil)
- [ ] Input nomor plat kendaraan
- [ ] Terima nomor antrian otomatis
- [ ] Pantau posisi antrian real-time
- [ ] Logout

### API (untuk Flutter mobile)
- [ ] POST `/api/login`
- [ ] POST `/api/register`
- [ ] GET `/api/antrian` (admin)
- [ ] POST `/api/antrian` (pelanggan)
- [ ] GET `/api/antrian/saya`
- [ ] GET `/api/antrian/{id}/posisi`
- [ ] PATCH `/api/antrian/{id}/selesai`
- [ ] DELETE `/api/antrian/{id}`
- [ ] GET `/api/harga`
- [ ] PUT `/api/harga/{id}`
- [ ] GET `/api/data-harian`

---

## 🎯 INSTRUKSI KHUSUS UNTUK AGENT

1. **Buat semua file secara lengkap** — jangan skip atau singkat kode
2. **Setiap controller harus punya error handling** dengan try-catch
3. **Semua response API harus konsisten** menggunakan format `{status, message, data}`
4. **Validasi wajib ada** di semua form (web + API)
5. **Gunakan Tailwind CSS** untuk semua tampilan web
6. **Semua tabel di halaman admin harus auto-refresh** setiap 10 detik
7. **Nomor antrian harus reset setiap hari** (mulai dari A001 setiap pagi)
8. **Status antrian harus berurutan**: menunggu → diproses → selesai
9. **Setelah antrian selesai, transaksi otomatis terbuat** dengan harga dari jenis layanan
10. **Beri komentar di setiap method** yang menjelaskan fungsinya dalam Bahasa Indonesia

---

*Prompt ini dibuat berdasarkan BAB III Laporan Penelitian — Sistem Informasi Antrian Bengkel*  
*Stack: Laravel 11 + Blade + Tailwind CSS + MySQL + Laravel Sanctum*
