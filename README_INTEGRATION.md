# Cost Model Calculator - API Integration

## Overview
Sistem Cost Model Calculator telah diintegrasikan dengan API Laravel untuk menyimpan dan mengambil data secara otomatis. Sistem menggunakan pendekatan "upsert" (insert or update) yang hanya menyimpan satu record untuk setiap tabel.

## Fitur Utama

### 1. Auto Save
- Semua inputan pada form akan otomatis tersimpan ke database saat tombol "Calculate" ditekan
- Data yang tersimpan akan dimuat kembali saat halaman dibuka
- Tidak ada penambahan data baru, hanya update data yang ada

### 2. Monitoring Data
- Data monitoring untuk setiap komponen tersimpan per minggu
- Mendukung existing monitoring dengan prefix 'existing_'
- Data dapat difilter berdasarkan tahun, minggu, dan nomor polisi unit

### 3. Real-time Calculation
- Perhitungan dilakukan secara otomatis saat data disimpan
- Hasil perhitungan tersimpan di database
- Dashboard menampilkan breakdown per tahun

## Cara Penggunaan

### 1. Setup Database
```bash
# Jalankan migration
php artisan migrate

# Jika perlu, jalankan seeder
php artisan db:seed
```

### 2. Akses Aplikasi
```bash
# Jalankan server Laravel
php artisan serve

# Buka browser ke
http://localhost:8000
```

### 3. Menggunakan Form

#### Tab Settings
1. Isi semua field pada tab "Settings"
2. Data akan tersimpan saat tombol "Calculate" ditekan di tab "Expense"

#### Tab Expense
1. Isi semua field pada tab "Expense"
2. Klik tombol "Calculate" untuk menyimpan data dan melakukan perhitungan
3. Hasil perhitungan akan ditampilkan di bagian "Results"

#### Tab Dashboard
1. Pilih jumlah tahun yang ingin ditampilkan (5-10 tahun)
2. Tabel akan menampilkan breakdown biaya per tahun
3. Data diambil dari hasil perhitungan yang tersimpan

#### Tab Monitoring
1. Pilih tahun yang ingin dimonitor
2. Masukkan nomor polisi unit (opsional)
3. Isi data monitoring untuk setiap komponen per minggu
4. Data akan otomatis tersimpan saat diisi

#### Tab Existing Monitoring
1. Pilih tahun yang ingin dimonitor
2. Masukkan nomor polisi unit (opsional)
3. Isi data existing monitoring untuk setiap komponen per minggu
4. Data akan otomatis tersimpan saat diisi

## Struktur Database

### Tabel: cost_model_settings
Menyimpan data settings (Actual dan Assumption)
- Hanya satu record yang tersimpan
- Data lama akan dihapus saat data baru disimpan

### Tabel: cost_model_expenses
Menyimpan data expenses (Actual dan Assumption)
- Hanya satu record yang tersimpan
- Data lama akan dihapus saat data baru disimpan

### Tabel: cost_model_calculations
Menyimpan hasil perhitungan
- Hanya satu record yang tersimpan
- Data lama akan dihapus saat perhitungan baru dilakukan

### Tabel: cost_model_monitoring
Menyimpan data monitoring
- Multiple records berdasarkan tahun, minggu, dan komponen
- Mendukung existing monitoring dengan prefix 'existing_'

## API Endpoints

### 1. Upsert All Data
```
POST /api/cost-model/upsert-all
```
Menyimpan semua data settings dan expenses

### 2. Get Stored Data
```
GET /api/cost-model/stored-data
```
Mengambil data yang tersimpan

### 3. Upsert Monitoring
```
POST /api/cost-model/upsert-monitoring
```
Menyimpan data monitoring

### 4. Upsert Existing Monitoring
```
POST /api/cost-model/upsert-existing-monitoring
```
Menyimpan data existing monitoring

### 5. Get Monitoring Data
```
GET /api/cost-model/monitoring-data
```
Mengambil data monitoring dengan filter

### 6. Calculate
```
POST /api/cost-model/calculate
```
Melakukan perhitungan

## JavaScript Integration

File `public/js/cost-model-api.js` berisi class `CostModelAPI` yang menangani:

1. **Auto Load Data**: Memuat data tersimpan saat halaman dibuka
2. **Auto Save**: Menyimpan data saat tombol Calculate ditekan
3. **Monitoring Save**: Menyimpan data monitoring secara otomatis
4. **Data Population**: Mengisi form dengan data dari database

### Fungsi Utama

```javascript
// Initialize API
const costModelAPI = new CostModelAPI();

// Collect form data
const formData = costModelAPI.collectFormData();

// Save data
await costModelAPI.upsertAllData(formData);

// Load stored data
const storedData = await costModelAPI.getStoredData();
costModelAPI.populateFormWithStoredData(storedData);
```

## Override Functions

Sistem menggunakan override untuk fungsi JavaScript yang sudah ada:

1. **calculateTCO()**: Ditambahkan auto save
2. **updateMonitoringTotals()**: Ditambahkan auto save monitoring
3. **updateExistingMonitoringTotals()**: Ditambahkan auto save existing monitoring
4. **updateMonitoringTable()**: Ditambahkan auto load monitoring data
5. **updateExistingMonitoringTable()**: Ditambahkan auto load existing monitoring data

## Error Handling

1. **Validation Error**: Data tidak valid akan ditampilkan pesan error
2. **Network Error**: Error koneksi akan ditampilkan di console
3. **Database Error**: Error database akan ditampilkan pesan error

## Keamanan

1. **CSRF Protection**: Semua request POST memerlukan CSRF token
2. **Input Validation**: Semua input divalidasi sebelum disimpan
3. **SQL Injection Protection**: Menggunakan Eloquent ORM

## Troubleshooting

### Data tidak tersimpan
1. Periksa console browser untuk error JavaScript
2. Periksa log Laravel di `storage/logs/laravel.log`
3. Pastikan CSRF token tersedia

### Data tidak dimuat
1. Periksa apakah ada data di database
2. Periksa response API di Network tab browser
3. Pastikan endpoint API berfungsi

### Perhitungan tidak akurat
1. Periksa data input di form
2. Periksa logika perhitungan di controller
3. Pastikan semua field terisi dengan benar

## Development

### Menambah Field Baru
1. Tambah kolom di migration
2. Update model dengan field baru
3. Update controller untuk handle field baru
4. Update JavaScript untuk collect dan populate field baru

### Menambah Endpoint Baru
1. Tambah method di controller
2. Tambah route di `routes/web.php`
3. Update JavaScript jika diperlukan
4. Update dokumentasi API

## Testing

### Manual Testing
1. Isi form dengan data lengkap
2. Klik Calculate dan periksa hasil
3. Refresh halaman dan periksa data ter-load
4. Test monitoring data

### API Testing
Gunakan tools seperti Postman atau curl untuk test endpoint API secara langsung.

## Deployment

1. Jalankan migration di production
2. Pastikan file JavaScript tersedia di public folder
3. Set environment variables yang diperlukan
4. Test semua fitur setelah deployment 