# Implementasi Tabel Master Nomor Polisi

## Ringkasan Perubahan

Sistem Cost Model Calculator telah diperbarui untuk menggunakan tabel master tersendiri untuk nomor polisi di halaman monitoring. Perubahan ini meningkatkan normalisasi database dan memudahkan pengelolaan data nomor polisi.

## Perubahan yang Dilakukan

### 1. Tabel Master Baru: `police_units`

**Migration:** `2025_08_05_040000_create_police_units_table.php`

```sql
CREATE TABLE police_units (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    police_number VARCHAR(255) UNIQUE NOT NULL,
    unit_name VARCHAR(255) NULL,
    unit_type VARCHAR(255) NULL,
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_police_number (police_number),
    INDEX idx_is_active (is_active)
);
```

**Kolom:**
- `police_number`: Nomor polisi (B 1234 AB) - UNIQUE
- `unit_name`: Nama unit (opsional)
- `unit_type`: Jenis unit (Kendaraan, Motor, Truk, dll)
- `description`: Deskripsi unit (opsional)
- `is_active`: Status aktif unit

### 2. Relasi dengan Tabel Monitoring

**Migration:** `2025_08_05_040100_add_police_unit_id_to_cost_model_monitoring_table.php`

- Menambahkan kolom `police_unit_id` sebagai foreign key ke tabel `police_units`
- Mengubah unique constraint dari `unit_police_number` ke `police_unit_id`
- Tetap menyimpan `unit_police_number` untuk backward compatibility

### 3. Model Baru: `PoliceUnit`

**File:** `app/Models/PoliceUnit.php`

```php
class PoliceUnit extends Model
{
    protected $fillable = [
        'police_number',
        'unit_name', 
        'unit_type',
        'description',
        'is_active',
    ];

    // Relasi dengan CostModelMonitoring
    public function monitoringRecords()
    {
        return $this->hasMany(CostModelMonitoring::class);
    }

    // Scope untuk unit aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
```

### 4. Update Model: `CostModelMonitoring`

**File:** `app/Models/CostModelMonitoring.php`

```php
class CostModelMonitoring extends Model
{
    protected $fillable = [
        'police_unit_id',
        'unit_police_number', // Backward compatibility
        'year',
        'week', 
        'component',
        'value',
        'note',
    ];

    // Relasi dengan PoliceUnit
    public function policeUnit()
    {
        return $this->belongsTo(PoliceUnit::class);
    }

    // Accessor untuk mendapatkan nomor polisi
    public function getPoliceNumberAttribute()
    {
        return $this->policeUnit ? $this->policeUnit->police_number : $this->unit_police_number;
    }
}
```

### 5. API Endpoints Baru

**Routes:** `routes/web.php`

```php
// API untuk master nomor polisi
Route::get('/police-units', [CostModelController::class, 'getAllPoliceUnits']);
Route::post('/police-units', [CostModelController::class, 'savePoliceUnit']);
Route::delete('/police-units', [CostModelController::class, 'deletePoliceUnit']);
```

**Methods di CostModelController:**

- `getAllPoliceUnits()`: Mengambil semua data master
- `savePoliceUnit()`: Menyimpan/edit data master
- `deletePoliceUnit()`: Menghapus data master (dengan validasi relasi)

### 6. Halaman Master Nomor Polisi

**File:** `resources/views/police-units.blade.php`

Fitur:
- Form tambah/edit nomor polisi
- Tabel daftar semua nomor polisi
- Filter berdasarkan status aktif
- Validasi sebelum menghapus (cek relasi)
- Interface yang user-friendly

### 7. Update Controller Methods

**Methods yang diupdate:**

- `upsertMonitoringData()`: Menggunakan `police_unit_id`
- `upsertExistingMonitoringData()`: Menggunakan `police_unit_id`
- `getMonitoringData()`: Query dengan relasi `policeUnit`
- `getAllUnitPoliceNumbers()`: Mengambil dari tabel master

### 8. Data Migration

**Migration:** `2025_08_05_040200_populate_police_units_from_existing_data.php`

- Mengambil semua nomor polisi unik dari `cost_model_monitoring`
- Membuat record di tabel `police_units`
- Update `police_unit_id` di `cost_model_monitoring`

## Keuntungan Implementasi

### 1. Normalisasi Database
- Menghilangkan duplikasi data nomor polisi
- Konsistensi data lebih terjamin
- Ukuran database lebih efisien

### 2. Pengelolaan Data Lebih Mudah
- Halaman khusus untuk master nomor polisi
- Validasi data terpusat
- Kemudahan maintenance

### 3. Relasi yang Lebih Baik
- Foreign key constraint
- Cascade operations
- Data integrity

### 4. Backward Compatibility
- Data lama tetap bisa diakses
- Migrasi bertahap
- Tidak ada breaking changes

## Cara Penggunaan

### 1. Akses Halaman Master
```
http://localhost/police-units
```

### 2. Tambah Nomor Polisi Baru
- Isi form dengan data lengkap
- Klik "Simpan"
- Data akan tersimpan di tabel master

### 3. Edit Nomor Polisi
- Klik tombol edit pada baris yang diinginkan
- Form akan terisi dengan data existing
- Lakukan perubahan dan simpan

### 4. Hapus Nomor Polisi
- Klik tombol hapus
- Konfirmasi penghapusan
- Sistem akan validasi relasi terlebih dahulu

### 5. Monitoring Data
- Data monitoring akan otomatis menggunakan `police_unit_id`
- Nomor polisi baru akan otomatis dibuat di master
- Relasi terjaga dengan baik

## Struktur Database Setelah Update

```
police_units (Master)
├── id (PK)
├── police_number (UNIQUE)
├── unit_name
├── unit_type
├── description
├── is_active
└── timestamps

cost_model_monitoring
├── id (PK)
├── police_unit_id (FK -> police_units.id)
├── unit_police_number (Backward compatibility)
├── year
├── week
├── component
├── value
├── note
└── timestamps
```

## Testing

### 1. Test API Endpoints
```bash
# Get all police units
GET /api/cost-model/police-units

# Save police unit
POST /api/cost-model/police-units
{
    "police_number": "B 1234 AB",
    "unit_name": "Unit Patroli 1",
    "unit_type": "Kendaraan",
    "description": "Kendaraan patroli utama",
    "is_active": true
}

# Delete police unit
DELETE /api/cost-model/police-units
{
    "id": 1
}
```

### 2. Test Monitoring Integration
- Input nomor polisi baru di monitoring
- Cek apakah otomatis dibuat di master
- Verifikasi relasi berfungsi dengan baik

## Kesimpulan

Implementasi tabel master nomor polisi telah berhasil meningkatkan struktur database dan memberikan kemudahan dalam pengelolaan data. Sistem tetap backward compatible dan tidak mengganggu fungsionalitas yang sudah ada. 