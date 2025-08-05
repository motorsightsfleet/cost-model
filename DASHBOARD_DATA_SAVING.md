# Fitur Penyimpanan Data Dashboard ke Database

## Overview

Fitur ini memungkinkan penyimpanan data dashboard (hasil perhitungan tabel) ke database secara otomatis setiap kali ada perhitungan atau perubahan data.

## Komponen yang Disimpan

### 1. Dashboard Rows
Data baris-baris dalam tabel dashboard yang mencakup:
- **Actual Category**:
  - Harga Units
  - Uang Muka (30%)
  - Pembiayaan (70%)
  - First Payment
  - Pajak & STNK
  - Asuransi
  - KIR
  - Telematics Module

- **Assumption Category**:
  - Service Berkala/PM
  - Service General/GM
  - BBM
  - AdBlue
  - Driver Cost
  - Ban
  - Downtime (1%)

### 2. Totals Data
- Yearly Totals (total per tahun)
- Actual Totals (total kategori actual)
- Assumption Totals (total kategori assumption)
- Grand Total (total keseluruhan)

## Struktur Database

### Migration
```php
// database/migrations/2025_08_05_013455_add_dashboard_data_to_cost_model_calculations_table.php
$table->json('dashboard_data')->nullable()->after('yearly_breakdown');
```

### Model
```php
// app/Models/CostModelCalculation.php
protected $fillable = [
    // ... existing fields
    'dashboard_data',
];

protected $casts = [
    // ... existing casts
    'dashboard_data' => 'array',
];
```

## API Endpoints

### 1. Save Dashboard Data
- **Method**: POST
- **URL**: `/api/cost-model/calculate`
- **Description**: Menyimpan data dashboard saat perhitungan dilakukan

### 2. Get Dashboard Data
- **Method**: GET
- **URL**: `/api/cost-model/dashboard-data`
- **Description**: Mengambil data dashboard dari database

## Implementasi

### 1. Controller (PHP)
```php
// app/Http/Controllers/CostModelController.php

// Dashboard data untuk disimpan ke database
$dashboardRows = [
    ['category' => 'Actual', 'label' => 'Harga Units', 'assumption' => $setting->units_price ?? 0, 'values' => array_fill(0, 10, 0)],
    // ... more rows
];

// Hitung yearly totals
$yearlyTotals = array_fill(0, 10, 0);
$actualTotals = array_fill(0, 10, 0);
$assumptionTotals = array_fill(0, 10, 0);

// Simpan ke database
'dashboard_data' => json_encode([
    'rows' => $dashboardRows,
    'yearly_totals' => $yearlyTotals,
    'actual_totals' => $actualTotals,
    'assumption_totals' => $assumptionTotals,
    'grand_total' => array_sum($yearlyTotals)
])
```

### 2. JavaScript (Frontend)
```javascript
// public/js/cost-model-api.js

// Mengambil data dashboard dari database
async getDashboardData() {
    try {
        const response = await fetch(`${this.baseURL}/dashboard-data`);
        const data = await response.json();
        
        if (data.success) {
            return data.data;
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Error fetching dashboard data:', error);
        return null;
    }
}
```

### 3. Auto-Save Function
```javascript
// resources/views/index.blade.php

// Save dashboard data to database
async function saveDashboardDataToDatabase(rows, yearlyTotals, actualTotals, assumptionTotals) {
    try {
        if (typeof costModelAPI !== 'undefined') {
            await costModelAPI.calculate();
            console.log('Dashboard data saved to database successfully');
            showAutoSaveNotification('Data dashboard berhasil disimpan ke database!', 'success');
        }
    } catch (error) {
        console.error('Error saving dashboard data:', error);
        showAutoSaveNotification('Gagal menyimpan data dashboard: ' + error.message, 'error');
    }
}
```

## Data Structure

### Dashboard Data JSON Structure
```json
{
    "rows": [
        {
            "category": "Actual",
            "label": "Harga Units",
            "assumption": 100000000,
            "values": [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },
        {
            "category": "Actual",
            "label": "Uang Muka (30%)",
            "assumption": 30000000,
            "values": [30000000, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        }
        // ... more rows
    ],
    "yearly_totals": [50000000, 45000000, 45000000, 40000000, 40000000, ...],
    "actual_totals": [30000000, 20000000, 20000000, 15000000, 15000000, ...],
    "assumption_totals": [20000000, 25000000, 25000000, 25000000, 25000000, ...],
    "grand_total": 450000000
}
```

## Trigger Points

Data dashboard akan disimpan ke database pada saat:
1. **Perhitungan Manual**: Ketika user menekan tombol "Calculate"
2. **Auto-Save**: Ketika ada perubahan input yang memicu perhitungan otomatis
3. **Page Load**: Ketika halaman dimuat dan ada data tersimpan

## Benefits

1. **Data Persistence**: Data dashboard tersimpan permanen di database
2. **Historical Tracking**: Bisa melacak perubahan data dari waktu ke waktu
3. **Backup & Restore**: Data bisa dipulihkan jika terjadi error
4. **Analytics**: Data bisa digunakan untuk analisis dan reporting
5. **Multi-User**: Data bisa diakses oleh multiple users

## Error Handling

- **Database Error**: Menampilkan notifikasi error jika gagal menyimpan
- **Network Error**: Retry mechanism untuk koneksi yang terputus
- **Validation Error**: Validasi data sebelum disimpan ke database
- **Null Safety**: Penanganan nilai null dan default values

## Testing

Untuk memastikan fitur berfungsi:
1. Masukkan data di form
2. Tekan tombol "Calculate"
3. Periksa database untuk memastikan data tersimpan
4. Refresh halaman dan verifikasi data tetap ada
5. Test dengan nilai null/empty untuk memastikan tidak ada error 