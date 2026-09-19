# 📌 Panduan Integrasi API Laravel untuk Aplikasi Flutter Pelanggan
**Sistem Antrian Bengkel / Jasa Cuci (JLN JGMT R)**

Dokumen ini merupakan panduan teknis yang dirancang khusus untuk memandu Anda dalam membangun aplikasi **Flutter** di sisi pelanggan (*customer app*). Dokumentasi ini mencakup daftar endpoint API Laravel yang khusus digunakan oleh pelanggan, skema request/response, implementasi model Dart, hingga pembuatan API Client yang aman dan terstruktur di Flutter.

---

## 📂 Struktur Folder Flutter yang Direkomendasikan
Untuk menjaga kode tetap bersih, modular, dan mudah dikelola seiring berkembangnya aplikasi, sangat disarankan menggunakan arsitektur berbasis **Feature-driven** atau **Clean Architecture (Simplified)**. Berikut adalah rekomendasi struktur folder Flutter Anda:

```text
lib/
├── core/
│   ├── constants/
│   │   └── api_endpoints.dart    # Menyimpan URL dasar & endpoint API
│   ├── network/
│   │   └── dio_client.dart       # Konfigurasi Dio & Interceptor Token
│   └── utils/
│       └── secure_storage.dart   # Manajemen penyimpanan Token JWT/Sanctum
├── data/
│   ├── models/
│   │   ├── user_model.dart
│   │   ├── layanan_model.dart
│   │   ├── antrian_model.dart
│   │   └── posisi_antrian_model.dart
│   └── repositories/
│       ├── auth_repository.dart
│       └── antrian_repository.dart
├── providers/                     # State Management (Provider/Bloc)
│   ├── auth_provider.dart
│   └── antrian_provider.dart
└── presentation/                 # Tampilan UI Aplikasi
    ├── screens/
    │   ├── auth/
    │   │   ├── login_screen.dart
    │   │   └── register_screen.dart
    │   ├── dashboard/
    │   │   └── dashboard_screen.dart
    │   ├── antrian/
    │   │   ├── buat_antrian_screen.dart
    │   │   └── detail_antrian_screen.dart
    │   └── profile/
    │       └── profile_screen.dart
    └── widgets/
        └── custom_button.dart
```

---

## 🛠️ Persiapan Dependensi Flutter (`pubspec.yaml`)
Tambahkan beberapa paket (*packages*) berikut di file `pubspec.yaml` proyek Flutter Anda untuk menangani request jaringan, penyimpanan lokal token yang aman, serta manajemen status aplikasi:

```yaml
dependencies:
  flutter:
    sdk: flutter
  
  # HTTP Client yang powerful (mendukung interceptors, pembatalan request, dll.)
  dio: ^5.4.3
  
  # Penyimpanan lokal yang terenkripsi untuk mengamankan Token Sanctum
  flutter_secure_storage: ^9.0.0
  
  # State Management sederhana namun handal dan resmi direkomendasikan
  provider: ^6.1.2
```

---

## 🌐 Informasi Dasar API
* **Base URL (Local/Development):** 
  * Jika menggunakan **Emulator Android**: `http://10.0.2.2:8000/api`
  * Jika menggunakan **Device Fisik** (satu jaringan Wi-Fi): `http://<IP_LAPTOP_ANDA>:8000/api`
  * Jika menggunakan **Simulator iOS**: `http://127.0.0.1:8000/api`
* **Metode Autentikasi:** Laravel Sanctum (Bearer Token).
* **Format Response:** Selalu mengembalikan objek JSON standar berikut:
  ```json
  {
    "status": true / false,
    "message": "Pesan informasi dari server",
    "data": { ... } // opsional, berisi data response utama
  }
  ```

---

## 🔑 Bagian 1: Endpoint Autentikasi (Auth)

Semua endpoint autentikasi dikelola oleh `AuthApiController`.

### 1. Registrasi Pelanggan Baru (`POST /register`)
Digunakan untuk mendaftarkan akun baru pelanggan. Akun yang dibuat melalui endpoint ini otomatis akan memiliki role `pelanggan`.

* **URL:** `/register`
* **Metode:** `POST`
* **Headers:** 
  * `Accept: application/json`
* **Request Body (JSON):**
  ```json
  {
    "name": "Budi Santoso",
    "username": "budisantoso",
    "email": "budi@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }
  ```
* **Aturan Validasi (Laravel):**
  * `name`: Wajib diisi, string, maksimal 100 karakter.
  * `username`: Wajib diisi, string, unik (belum terdaftar di database), maksimal 50 karakter.
  * `email`: Wajib diisi, format email valid, unik (belum terdaftar).
  * `password`: Wajib diisi, minimal 8 karakter, harus cocok dengan `password_confirmation`.

* **Response Sukses (201 Created):**
  ```json
  {
    "status": true,
    "message": "Registrasi berhasil.",
    "data": {
      "user": {
        "id": 4,
        "name": "Budi Santoso",
        "username": "budisantoso",
        "email": "budi@example.com",
        "role": "pelanggan"
      },
      "token": "4|abc123xyz456..." // Gunakan ini sebagai Bearer Token
    }
  }
  ```

* **Response Gagal Validasi (422 Unprocessable Entity):**
  ```json
  {
    "message": "Username sudah digunakan, pilih yang lain.",
    "errors": {
      "username": [
        "Username sudah digunakan, pilih yang lain."
      ]
    }
  }
  ```

---

### 2. Login Pelanggan (`POST /login`)
Digunakan untuk masuk ke aplikasi menggunakan kombinasi `email` atau `username` dengan `password`.

* **URL:** `/login`
* **Metode:** `POST`
* **Headers:**
  * `Accept: application/json`
* **Request Body (JSON):**
  ```json
  {
    "login": "budisantoso", // Bisa berupa username ATAU email
    "password": "password123"
  }
  ```

* **Response Sukses (200 OK):**
  ```json
  {
    "status": true,
    "message": "Login berhasil.",
    "data": {
      "user": {
        "id": 4,
        "name": "Budi Santoso",
        "username": "budisantoso",
        "email": "budi@example.com",
        "role": "pelanggan"
      },
      "token": "5|def456uvw789..." // Simpan secara aman di Flutter Secure Storage
    }
  }
  ```

* **Response Gagal Autentikasi (401 Unauthorized):**
  ```json
  {
    "status": false,
    "message": "Email/username atau password salah."
  }
  ```

---

### 3. Ambil Profil Saya (`GET /me`)
Digunakan untuk mengambil data profil lengkap pengguna yang sedang aktif menggunakan token autentikasi.

* **URL:** `/me`
* **Metode:** `GET`
* **Headers:**
  * `Accept: application/json`
  * `Authorization: Bearer <token>`
* **Response Sukses (200 OK):**
  ```json
  {
    "status": true,
    "data": {
      "id": 4,
      "name": "Budi Santoso",
      "username": "budisantoso",
      "email": "budi@example.com",
      "role": "pelanggan",
      "created_at": "2026-05-17T12:00:00.000000Z",
      "updated_at": "2026-05-17T12:00:00.000000Z"
    }
  }
  ```

---

### 4. Logout (`POST /logout`)
Digunakan untuk keluar dari aplikasi dan menghapus (*revoke*) token aktif saat ini di server Laravel.

* **URL:** `/logout`
* **Metode:** `POST`
* **Headers:**
  * `Accept: application/json`
  * `Authorization: Bearer <token>`
* **Response Sukses (200 OK):**
  ```json
  {
    "status": true,
    "message": "Logout berhasil."
  }
  ```

---

## 🏍️ Bagian 2: Endpoint Layanan & Harga

Endpoint ini dikelola oleh `AntrianApiController`.

### 5. Ambil Daftar Layanan & Harga Aktif (`GET /harga`)
Digunakan untuk memuat semua jenis layanan (seperti Cuci Motor, Cuci Mobil Premium, Servis Berkala, dll.) yang aktif beserta harganya untuk ditampilkan di halaman awal atau pilihan formulir antrian pelanggan.

* **URL:** `/harga`
* **Metode:** `GET`
* **Headers:**
  * `Accept: application/json`
  * `Authorization: Bearer <token>`
* **Response Sukses (200 OK):**
  ```json
  {
    "status": true,
    "message": "Berhasil.",
    "data": [
      {
        "id": 1,
        "nama_layanan": "Cuci Motor Biasa",
        "jenis_kendaraan": "motor",
        "harga": "15000.00",
        "is_active": true,
        "created_at": "2026-05-10T12:00:00.000000Z",
        "updated_at": "2026-05-10T12:00:00.000000Z"
      },
      {
        "id": 2,
        "nama_layanan": "Cuci Mobil Premium",
        "jenis_kendaraan": "mobil",
        "harga": "50000.00",
        "is_active": true,
        "created_at": "2026-05-10T12:00:00.000000Z",
        "updated_at": "2026-05-10T12:00:00.000000Z"
      }
    ]
  }
  ```

---

## 📋 Bagian 3: Endpoint Kelola Antrian Pelanggan

Endpoint-endpoint ini digunakan pelanggan untuk mendaftar antrian, melacak, hingga membatalkan antrian mereka sendiri.

### 6. Daftar Antrian Baru (`POST /antrian`)
Digunakan pelanggan untuk mengambil tiket nomor antrian baru secara online.

* **URL:** `/antrian`
* **Metode:** `POST`
* **Headers:**
  * `Accept: application/json`
  * `Authorization: Bearer <token>`
* **Request Body (JSON):**
  ```json
  {
    "jenis_layanan_id": 2,
    "no_plat": "B 1234 ABC"
  }
  ```
* **Aturan Validasi (Laravel):**
  * `jenis_layanan_id`: Wajib diisi, harus valid ada di tabel `jenis_layanans`.
  * `no_plat`: Wajib diisi, string, maksimal 15 karakter, hanya huruf, angka, dan spasi (regex: `/^[A-Z0-9\s]+$/i`).

* **Response Sukses (201 Created):**
  ```json
  {
    "status": true,
    "message": "Berhasil mendaftar antrian.",
    "data": {
      "id": 18,
      "user_id": 4,
      "jenis_layanan_id": 2,
      "nomor_antrian": "A005",        // Digenerate otomatis di backend (e.g. A001, A002)
      "nama_pelanggan": "Budi Santoso",
      "no_plat": "B 1234 ABC",
      "jenis_kendaraan": "mobil",     // Otomatis mengambil dari jenis kendaraan pada layanan
      "status": "menunggu",           // Nilai awal: 'menunggu'
      "posisi_antrian": 3,            // Posisi antrian riil saat pendaftaran
      "selesai_at": null,
      "created_at": "2026-05-17T12:15:30.000000Z",
      "updated_at": "2026-05-17T12:15:30.000000Z",
      "jenis_layanan": {
        "id": 2,
        "nama_layanan": "Cuci Mobil Premium",
        "jenis_kendaraan": "mobil",
        "harga": "50000.00",
        "is_active": true
      }
    }
  }
  ```

---

### 7. Daftar Antrian Saya Hari Ini (`GET /antrian/saya`)
Mengambil riwayat antrian milik pelanggan yang sedang login khusus untuk hari ini, diurutkan dari yang terbaru.

* **URL:** `/antrian/saya`
* **Metode:** `GET`
* **Headers:**
  * `Accept: application/json`
  * `Authorization: Bearer <token>`
* **Response Sukses (200 OK):**
  ```json
  {
    "status": true,
    "message": "Berhasil.",
    "data": [
      {
        "id": 18,
        "user_id": 4,
        "jenis_layanan_id": 2,
        "nomor_antrian": "A005",
        "nama_pelanggan": "Budi Santoso",
        "no_plat": "B 1234 ABC",
        "jenis_kendaraan": "mobil",
        "status": "menunggu",
        "posisi_antrian": 3,
        "selesai_at": null,
        "created_at": "2026-05-17T12:15:30.000000Z",
        "jenis_layanan": {
          "id": 2,
          "nama_layanan": "Cuci Mobil Premium",
          "jenis_kendaraan": "mobil",
          "harga": "50000.00"
        }
      }
    ]
  }
  ```

---

### 8. Cek Posisi & Estimasi Waktu Tunggu (`GET /antrian/{id}/posisi`)
Digunakan untuk melacak nomor antrian secara *real-time*. Menghitung berapa kendaraan lagi yang harus dilayani sebelum kendaraan milik pelanggan diproses.

* **URL:** `/antrian/{id}/posisi`  *(Ganti `{id}` dengan ID antrian)*
* **Metode:** `GET`
* **Headers:**
  * `Accept: application/json`
  * `Authorization: Bearer <token>`
* **Response Sukses (200 OK):**
  ```json
  {
    "status": true,
    "message": "Berhasil.",
    "data": {
      "nomor_antrian": "A005",
      "status": "menunggu",       // Status: menunggu / diproses / selesai / batal
      "posisi": 2,                // Jumlah kendaraan berstatus 'menunggu' di depan & termasuk antrian ini
      "estimasi_menit": 30,       // Dihitung otomatis: posisi * 15 menit
      "total_menunggu": 4         // Total seluruh antrian berstatus 'menunggu' hari ini di bengkel
    }
  }
  ```

---

### 9. Batalkan Antrian (`DELETE /antrian/{id}`)
Digunakan pelanggan jika ingin membatalkan antrian mereka sendiri. Sistem otomatis akan mengubah status antrian menjadi `batal` dan me-rekalkulasi posisi antrian pelanggan aktif lainnya secara otomatis.

* **URL:** `/antrian/{id}` *(Ganti `{id}` dengan ID antrian)*
* **Metode:** `DELETE`
* **Headers:**
  * `Accept: application/json`
  * `Authorization: Bearer <token>`
* **Response Sukses (200 OK):**
  ```json
  {
    "status": true,
    "message": "Antrian berhasil dibatalkan."
  }
  ```

---

## 📦 Bagian 4: Implementasi Kode Flutter (Dart)

### 1. Data Models (Dart)
Gunakan model ini untuk mengonversi data JSON yang diperoleh dari API menjadi objek Dart.

#### A. User Model (`lib/data/models/user_model.dart`)
```dart
class UserModel {
  final int id;
  final String name;
  final String username;
  final String email;
  final String role;

  UserModel({
    required this.id,
    required this.name,
    required this.username,
    required this.email,
    required this.role,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      username: json['username'] ?? '',
      email: json['email'] ?? '',
      role: json['role'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'username': username,
      'email': email,
      'role': role,
    };
  }
}
```

#### B. Layanan Model (`lib/data/models/layanan_model.dart`)
```dart
class LayananModel {
  final int id;
  final String namaLayanan;
  final String jenisKendaraan;
  final double harga;
  final bool isActive;

  LayananModel({
    required this.id,
    required this.namaLayanan,
    required this.jenisKendaraan,
    required this.harga,
    required this.isActive,
  });

  factory LayananModel.fromJson(Map<String, dynamic> json) {
    return LayananModel(
      id: json['id'] ?? 0,
      namaLayanan: json['nama_layanan'] ?? '',
      jenisKendaraan: json['jenis_kendaraan'] ?? '',
      harga: double.tryParse(json['harga'].toString()) ?? 0.0,
      isActive: json['is_active'] == true || json['is_active'] == 1,
    );
  }
}
```

#### C. Antrian Model (`lib/data/models/antrian_model.dart`)
```dart
import 'layanan_model.dart';

class AntrianModel {
  final int id;
  final int userId;
  final int jenisLayananId;
  final String nomorAntrian;
  final String namaPelanggan;
  final String noPlat;
  final String jenisKendaraan;
  final String status;
  final int posisiAntrian;
  final DateTime? selesaiAt;
  final DateTime createdAt;
  final LayananModel? jenisLayanan;

  AntrianModel({
    required this.id,
    required this.userId,
    required this.jenisLayananId,
    required this.nomorAntrian,
    required this.namaPelanggan,
    required this.noPlat,
    required this.jenisKendaraan,
    required this.status,
    required this.posisiAntrian,
    this.selesaiAt,
    required this.createdAt,
    this.jenisLayanan,
  });

  factory AntrianModel.fromJson(Map<String, dynamic> json) {
    return AntrianModel(
      id: json['id'] ?? 0,
      userId: json['user_id'] ?? 0,
      jenisLayananId: json['jenis_layanan_id'] ?? 0,
      nomorAntrian: json['nomor_antrian'] ?? '',
      namaPelanggan: json['nama_pelanggan'] ?? '',
      noPlat: json['no_plat'] ?? '',
      jenisKendaraan: json['jenis_kendaraan'] ?? '',
      status: json['status'] ?? 'menunggu',
      posisiAntrian: json['posisi_antrian'] ?? 0,
      selesaiAt: json['selesai_at'] != null ? DateTime.parse(json['selesai_at']) : null,
      createdAt: DateTime.parse(json['created_at']),
      jenisLayanan: json['jenis_layanan'] != null
          ? LayananModel.fromJson(json['jenis_layanan'])
          : null,
    );
  }
}
```

#### D. Posisi Antrian Model (`lib/data/models/posisi_antrian_model.dart`)
```dart
class PosisiAntrianModel {
  final String nomorAntrian;
  final String status;
  final int posisi;
  final int estimasiMenit;
  final int totalMenunggu;

  PosisiAntrianModel({
    required this.nomorAntrian,
    required this.status,
    required this.posisi,
    required this.estimasiMenit,
    required this.totalMenunggu,
  });

  factory PosisiAntrianModel.fromJson(Map<String, dynamic> json) {
    return PosisiAntrianModel(
      nomorAntrian: json['nomor_antrian'] ?? '',
      status: json['status'] ?? '',
      posisi: json['posisi'] ?? 0,
      estimasiMenit: json['estimasi_menit'] ?? 0,
      totalMenunggu: json['total_menunggu'] ?? 0,
    );
  }
}
```

---

### 2. Network Client & Interceptor (`lib/core/network/dio_client.dart`)
Menggunakan Dio untuk mengirim request dan menambahkan interceptor agar token autentikasi disisipkan secara otomatis pada setiap request ke server.

```dart
import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class DioClient {
  final Dio _dio = Dio();
  final _storage = const FlutterSecureStorage();

  // Ganti IP dengan IP Laravel Server Anda saat testing / deployment
  static const String baseUrl = 'http://10.0.2.2:8000/api';

  DioClient() {
    _dio.options.baseUrl = baseUrl;
    _dio.options.connectTimeout = const Duration(seconds: 10);
    _dio.options.receiveTimeout = const Duration(seconds: 10);
    _dio.options.headers = {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    };

    // Menambahkan Interceptor untuk menyisipkan Bearer Token secara otomatis
    _dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) async {
          final token = await _storage.read(key: 'auth_token');
          if (token != null) {
            options.headers['Authorization'] = 'Bearer $token';
          }
          return handler.next(options);
        },
        onError: (DioException e, handler) {
          // Logika penanganan error global (misal: jika 401, redirect ke halaman login)
          if (e.response?.statusCode == 401) {
            // Lakukan hapus token lokal karena token sudah kadaluarsa/tidak valid
            _storage.delete(key: 'auth_token');
          }
          return handler.next(e);
        },
      ),
    );
  }

  Dio get dio => _dio;
}
```

---

### 3. Autentikasi Repository (`lib/data/repositories/auth_repository.dart`)
Menangani interaksi endpoint registrasi, login, logout, dan penyimpanan token lokal secara aman.

```dart
import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../../core/network/dio_client.dart';
import '../models/user_model.dart';

class AuthRepository {
  final _dioClient = DioClient();
  final _storage = const FlutterSecureStorage();

  // Login
  Future<UserModel> login(String login, String password) async {
    try {
      final response = await _dioClient.dio.post('/login', data: {
        'login': login,
        'password': password,
      });

      if (response.statusCode == 200 && response.data['status'] == true) {
        final token = response.data['data']['token'];
        await _storage.write(key: 'auth_token', value: token);

        return UserModel.fromJson(response.data['data']['user']);
      } else {
        throw Exception(response.data['message'] ?? 'Gagal login.');
      }
    } on DioException catch (e) {
      final errorMsg = e.response?.data['message'] ?? 'Terjadi kesalahan jaringan.';
      throw Exception(errorMsg);
    }
  }

  // Register
  Future<UserModel> register({
    required String name,
    required String username,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    try {
      final response = await _dioClient.dio.post('/register', data: {
        'name': name,
        'username': username,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
      });

      if (response.statusCode == 201 && response.data['status'] == true) {
        final token = response.data['data']['token'];
        await _storage.write(key: 'auth_token', value: token);

        return UserModel.fromJson(response.data['data']['user']);
      } else {
        throw Exception(response.data['message'] ?? 'Gagal registrasi.');
      }
    } on DioException catch (e) {
      if (e.response?.statusCode == 422) {
        // Ambil error pertama dari form validasi laravel
        final Map<String, dynamic> errors = e.response?.data['errors'] ?? {};
        final firstError = errors.values.first[0];
        throw Exception(firstError);
      }
      throw Exception('Registrasi gagal. Coba lagi.');
    }
  }

  // Ambil data user saat ini
  Future<UserModel> getProfile() async {
    try {
      final response = await _dioClient.dio.get('/me');
      if (response.statusCode == 200 && response.data['status'] == true) {
        return UserModel.fromJson(response.data['data']);
      } else {
        throw Exception('Gagal memuat profil.');
      }
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Koneksi gagal.');
    }
  }

  // Logout
  Future<void> logout() async {
    try {
      await _dioClient.dio.post('/logout');
    } finally {
      // Selalu bersihkan token lokal walaupun koneksi ke server gagal
      await _storage.delete(key: 'auth_token');
    }
  }
}
```

---

### 4. Antrian Repository (`lib/data/repositories/antrian_repository.dart`)
Menangani pengambilan daftar harga layanan, pembuatan tiket antrian, monitoring posisi antrian, dan pembatalan antrian.

```dart
import 'package:dio/dio.dart';
import '../../core/network/dio_client.dart';
import '../models/layanan_model.dart';
import '../models/antrian_model.dart';
import '../models/posisi_antrian_model.dart';

class AntrianRepository {
  final _dioClient = DioClient();

  // Dapatkan daftar harga layanan yang aktif
  Future<List<LayananModel>> fetchHargaLayanan() async {
    try {
      final response = await _dioClient.dio.get('/harga');
      if (response.statusCode == 200 && response.data['status'] == true) {
        final List listData = response.data['data'] ?? [];
        return listData.map((e) => LayananModel.fromJson(e)).toList();
      }
      throw Exception('Gagal memuat daftar harga.');
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Masalah koneksi.');
    }
  }

  // Buat antrian baru
  Future<AntrianModel> buatAntrianBaru(int jenisLayananId, String noPlat) async {
    try {
      final response = await _dioClient.dio.post('/antrian', data: {
        'jenis_layanan_id': jenisLayananId,
        'no_plat': noPlat,
      });

      if (response.statusCode == 201 && response.data['status'] == true) {
        return AntrianModel.fromJson(response.data['data']);
      }
      throw Exception(response.data['message'] ?? 'Gagal membuat antrian.');
    } on DioException catch (e) {
      if (e.response?.statusCode == 422) {
        final Map<String, dynamic> errors = e.response?.data['errors'] ?? {};
        final firstError = errors.values.first[0];
        throw Exception(firstError);
      }
      throw Exception(e.response?.data['message'] ?? 'Gagal mendaftar antrian.');
    }
  }

  // Dapatkan antrian milik saya hari ini
  Future<List<AntrianModel>> fetchAntrianSaya() async {
    try {
      final response = await _dioClient.dio.get('/antrian/saya');
      if (response.statusCode == 200 && response.data['status'] == true) {
        final List listData = response.data['data'] ?? [];
        return listData.map((e) => AntrianModel.fromJson(e)).toList();
      }
      throw Exception('Gagal memuat antrian Anda.');
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Gagal terhubung.');
    }
  }

  // Cek posisi antrian
  Future<PosisiAntrianModel> cekPosisiAntrian(int antrianId) async {
    try {
      final response = await _dioClient.dio.get('/antrian/$antrianId/posisi');
      if (response.statusCode == 200 && response.data['status'] == true) {
        return PosisiAntrianModel.fromJson(response.data['data']);
      }
      throw Exception('Gagal memuat posisi antrian.');
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Gagal melacak posisi.');
    }
  }

  // Batalkan antrian
  Future<void> batalkanAntrian(int antrianId) async {
    try {
      final response = await _dioClient.dio.delete('/antrian/$antrianId');
      if (response.statusCode != 200 || response.data['status'] != true) {
        throw Exception(response.data['message'] ?? 'Gagal membatalkan antrian.');
      }
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Kesalahan membatalkan antrian.');
    }
  }
}
```

---

## 🌟 Tips & Trik Pengembangan Aplikasi Flutter
1. **Penyimpanan Token Aman:** Selalu gunakan `flutter_secure_storage` untuk menyimpan JWT/Sanctum Token daripada `shared_preferences` biasa, agar kredensial pelanggan terenkripsi secara aman di tingkat sistem operasi (Keystore untuk Android / Keychain untuk iOS).
2. **Validasi Formulir:** Pastikan melakukan regex validasi di Flutter pada nomor plat kendaraan sebelum mengirim request (`POST /antrian`) untuk mengurangi beban server. Format plat biasanya hanya berupa huruf, angka, dan spasi.
3. **Pemberitahuan Posisi Real-time (Polling):** Karena tidak menggunakan WebSockets, Anda dapat menyimulasikan pembaruan real-time pada halaman detail posisi antrian dengan memanggil method `cekPosisiAntrian` secara periodik setiap 15-30 detik menggunakan objek `Timer` bawaan Dart:
   ```dart
   import 'dart:async';
   
   Timer? _timer;
   
   void startTrackingPosisi(int antrianId) {
     _timer = Timer.periodic(const Duration(seconds: 30), (timer) {
       // Panggil method repo untuk cek posisi terbaru
       antrianRepository.cekPosisiAntrian(antrianId).then((posisi) {
         // Update state UI Anda di sini
       });
     });
   }
   
   @override
   void dispose() {
     _timer?.cancel(); // Pastikan untuk mematikan timer saat UI ditutup!
     super.dispose();
   }
   ```
4. **Pencegahan Double-Register (Double-Submit):** Saat pelanggan menekan tombol "Ambil Antrian", nonaktifkan (*disable*) tombol tersebut untuk mencegah pelanggan melakukan klik berulang-kali (double tap) yang berpotensi menghasilkan banyak tiket antrian ganda.

---
*Selamat ngoding! Jika Anda membutuhkan bantuan lebih lanjut untuk membuat UI Dashboard Antrian di Flutter atau setting routing halaman, jangan ragu untuk menanyakannya.* 🚀
