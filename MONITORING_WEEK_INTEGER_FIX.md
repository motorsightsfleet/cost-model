# Perbaikan Error "The week field must be an integer" pada Monitoring

## 🔍 Masalah yang Ditemukan

Error yang muncul:
```
POST http://127.0.0.1:9576/api/cost-model/upsert-monitoring 500 (Internal Server Error)
Error upserting monitoring data: Error: Terjadi kesalahan: The week field must be an integer.
```

## 🛠️ Analisis Masalah

### **Penyebab Utama**
1. **JavaScript mengirim string week**: Kode JavaScript mengambil week dari ID input (misal: "W1", "W2") dan menggunakan `week.replace('W', '')` yang mengembalikan string, bukan integer.

2. **Validasi Laravel mengharapkan integer**: Controller Laravel memiliki validasi `'week' => 'required|integer|min:1|max:52'` yang menolak string.

3. **Metadata menggunakan string 'metadata'**: Kode mencoba menyimpan metadata dengan week = 'metadata' yang tidak valid.

## 🔧 Perbaikan yang Dilakukan

### **1. Konversi Week ke Integer di JavaScript**

**File**: `public/js/cost-model-api.js`

**Sebelum**:
```javascript
await costModelAPI.saveMonitoringData(year, week.replace('W', ''), component, input.value);
```

**Sesudah**:
```javascript
const weekNumber = parseInt(week.replace('W', '')); // Konversi ke integer
await costModelAPI.saveMonitoringData(year, weekNumber, component, input.value);
```

**Lokasi yang diperbaiki**:
- Fungsi `autoSaveInput()` untuk monitoring fields
- Fungsi `autoSaveInput()` untuk existing monitoring fields  
- Fungsi `updateMonitoringTotals()`
- Fungsi `updateExistingMonitoringTotals()`

### **2. Perbaikan Metadata dengan Week = 0**

**Sebelum**:
```javascript
await costModelAPI.saveMonitoringData(year, 'metadata', 'unit_police_number', unitPoliceNumber);
```

**Sesudah**:
```javascript
await costModelAPI.saveMonitoringData(year, 0, 'unit_police_number', unitPoliceNumber);
```

### **3. Update Validasi Controller**

**File**: `app/Http/Controllers/CostModelController.php`

**Sebelum**:
```php
'week' => 'required|integer|min:1|max:52',
```

**Sesudah**:
```php
'week' => 'required|integer|min:0|max:52', // Allow week = 0 for metadata
```

### **4. Update Load Data Functions**

**File**: `public/js/cost-model-api.js`

**Sebelum**:
```javascript
if (item.week === 'metadata') {
    return;
}
const metadataItems = monitoringData.filter(item => item.week === 'metadata');
```

**Sesudah**:
```javascript
if (item.week === 0) {
    return;
}
const metadataItems = monitoringData.filter(item => item.week === 0);
```

## 📋 Checklist Perbaikan

### ✅ **JavaScript Fixes**
- [x] Konversi week string ke integer di `autoSaveInput()`
- [x] Konversi week string ke integer di `updateMonitoringTotals()`
- [x] Konversi week string ke integer di `updateExistingMonitoringTotals()`
- [x] Update metadata untuk menggunakan week = 0
- [x] Update load functions untuk menangani week = 0

### ✅ **Backend Fixes**
- [x] Update validasi controller untuk mengizinkan week = 0
- [x] Update validasi existing monitoring untuk mengizinkan week = 0

### ✅ **Testing**
- [x] Test auto-save monitoring data
- [x] Test auto-save existing monitoring data
- [x] Test metadata saving (unit police number, year selection)
- [x] Test data loading dari database

## 🧪 Cara Testing

### **1. Test Auto-Save Monitoring**
1. Buka halaman monitoring
2. Pilih year (misal: 1st year)
3. Isi input di kolom Service Berkala/PM, Week 1
4. Cek console untuk memastikan tidak ada error
5. Cek database untuk memastikan data tersimpan

### **2. Test Metadata**
1. Isi field "Unit Police Number"
2. Pilih year di dropdown
3. Cek console untuk memastikan metadata tersimpan dengan week = 0
4. Refresh halaman dan pastikan data dimuat kembali

### **3. Test Existing Monitoring**
1. Buka tab "Existing Monitoring"
2. Isi input di tabel existing monitoring
3. Cek console untuk memastikan tidak ada error
4. Cek database untuk memastikan data tersimpan

## 🎯 Expected Behavior

### **Sebelum Perbaikan**
```
Error: Terjadi kesalahan: The week field must be an integer.
```

### **Sesudah Perbaikan**
```
Auto-saved monitoring data for: Service_Berkala/PM_1_W1
Data monitoring berhasil disimpan otomatis!
```

## 📝 Catatan Penting

1. **Week = 0 untuk Metadata**: Metadata (unit police number, year selection) sekarang disimpan dengan week = 0 untuk membedakan dari data monitoring regular.

2. **Integer Conversion**: Semua week values sekarang dikonversi ke integer sebelum dikirim ke API.

3. **Backward Compatibility**: Perubahan ini kompatibel dengan data yang sudah ada di database.

4. **Validation Update**: Controller sekarang mengizinkan week = 0 untuk metadata.

## 🚀 Deployment

Perubahan ini sudah siap untuk deployment. Pastikan untuk:
1. Clear cache Laravel: `php artisan cache:clear`
2. Restart server jika diperlukan
3. Test semua fitur monitoring untuk memastikan berfungsi dengan baik 