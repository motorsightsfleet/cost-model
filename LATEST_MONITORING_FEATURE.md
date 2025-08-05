# Fitur Data Monitoring Terakhir

## Deskripsi
Fitur ini menampilkan semua data monitoring berdasarkan nopol yang terakhir kali diinputkan. Sistem akan otomatis mendeteksi nopol terakhir berdasarkan timestamp `updated_at` terbaru dan menampilkan semua data week untuk nopol tersebut.

## Cara Kerja

### 1. Deteksi Nopol Terakhir
- Sistem mencari record monitoring dengan `updated_at` terbaru
- Mengambil `unit_police_number` dari record tersebut
- Ini menjadi nopol yang terakhir kali diinputkan

### 2. Pengambilan Data
- Mengambil semua data monitoring untuk nopol terakhir
- Data dikelompokkan berdasarkan tahun dan minggu
- Diurutkan berdasarkan tahun (asc), minggu (asc), dan komponen (asc)

### 3. Tampilan Data
- Data ditampilkan dalam tabel yang rapi
- Menampilkan: Tahun, Minggu, Komponen, Nilai, Catatan, Terakhir Update
- Format angka menggunakan separator (koma untuk ribuan)
- Format tanggal menggunakan locale Indonesia

## Endpoint API

### 1. Get Latest Monitoring Data
```
GET /api/cost-model/latest-monitoring-data
```

**Response:**
```json
{
    "success": true,
    "data": {
        "1": {
            "1": [
                {
                    "id": 17,
                    "component": "teset",
                    "value": "3000.00",
                    "note": "Test data 3",
                    "created_at": "2025-08-05T03:22:21.000000Z",
                    "updated_at": "2025-08-05T03:22:21.000000Z"
                }
            ],
            "2": [
                {
                    "id": 18,
                    "component": "teset",
                    "value": "5000.00",
                    "note": "Test data 4",
                    "created_at": "2025-08-05T03:22:21.000000Z",
                    "updated_at": "2025-08-05T03:22:21.000000Z"
                }
            ]
        }
    },
    "latest_unit": "AE 2222 BB",
    "message": "Data monitoring berhasil diambil"
}
```

### 2. Get All Unit Police Numbers
```
GET /api/cost-model/all-unit-police-numbers
```

**Response:**
```json
{
    "success": true,
    "data": ["AE 1111 BA", "AE 2222 BB"],
    "message": "Daftar nopol berhasil diambil"
}
```

## Implementasi Frontend

### 1. Auto-load saat halaman dimuat
```javascript
// Load stored data when page loads
document.addEventListener('DOMContentLoaded', async function() {
    try {
        const storedData = await costModelAPI.getStoredData();
        if (storedData) {
            costModelAPI.populateFormWithStoredData(storedData);
        }
        
        // Setup auto-save setelah data dimuat
        setupAutoSave();
        
        // Load dan tampilkan data monitoring terakhir
        await loadAndDisplayLatestMonitoringData();
    } catch (error) {
        console.error('Error loading stored data:', error);
    }
});
```

### 2. Fungsi untuk memuat data
```javascript
async function loadAndDisplayLatestMonitoringData() {
    try {
        const latestData = await costModelAPI.getLatestMonitoringData();
        
        if (latestData && latestData.data && Object.keys(latestData.data).length > 0) {
            displayLatestMonitoringData(latestData);
        } else {
            console.log('Tidak ada data monitoring terakhir yang ditemukan');
        }
    } catch (error) {
        console.error('Error loading latest monitoring data:', error);
    }
}
```

### 3. Fungsi untuk menampilkan data
```javascript
function displayLatestMonitoringData(latestData) {
    const { data, latest_unit } = latestData;
    
    // Buat container dan tabel untuk menampilkan data
    // ... implementasi detail ...
}
```

## Fitur UI

### 1. Container Styling
- Margin: 20px 0
- Padding: 20px
- Border: 1px solid #ddd
- Border-radius: 8px
- Background-color: #f9f9f9

### 2. Tabel Styling
- Width: 100%
- Border-collapse: collapse
- Header dengan background-color: #f0f0f0
- Cell padding: 8px-10px
- Border: 1px solid #ddd

### 3. Tombol Refresh
- Background-color: #007bff
- Color: white
- Border-radius: 4px
- Padding: 8px 16px
- Margin-top: 15px

## Testing

### Test Case 1: Multiple Nopol
1. Input data untuk nopol "AE 1111 BA" (week 1, value 200)
2. Input data untuk nopol "AE 1111 BA" (week 2, value 4000)
3. Input data untuk nopol "AE 2222 BB" (week 1, value 3000)
4. Input data untuk nopol "AE 2222 BB" (week 2, value 5000)
5. Update timestamp "AE 2222 BB" agar menjadi terakhir
6. **Expected Result**: Sistem menampilkan semua data untuk "AE 2222 BB" (week 1 dan 2)

### Test Case 2: No Data
1. Hapus semua data monitoring
2. **Expected Result**: Sistem menampilkan pesan "Tidak ada data monitoring yang ditemukan"

## File yang Dimodifikasi

### Backend
1. `app/Http/Controllers/CostModelController.php`
   - Menambahkan method `getLatestMonitoringData()`
   - Menambahkan method `getAllUnitPoliceNumbers()`

2. `routes/web.php`
   - Menambahkan route `/latest-monitoring-data`
   - Menambahkan route `/all-unit-police-numbers`

### Frontend
1. `public/js/cost-model-api.js`
   - Menambahkan method `getLatestMonitoringData()`
   - Menambahkan method `getAllUnitPoliceNumbers()`
   - Menambahkan fungsi `loadAndDisplayLatestMonitoringData()`
   - Menambahkan fungsi `displayLatestMonitoringData()`

## Status
✅ **SELESAI** - Fitur menampilkan data monitoring terakhir sudah berfungsi dengan baik 