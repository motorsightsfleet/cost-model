# Fitur Note Monitoring

## Deskripsi
Fitur note monitoring memungkinkan user untuk menambahkan catatan pada setiap komponen monitoring. Note akan disimpan ke database dan dapat diakses kembali saat dibutuhkan.

## Fitur yang Diimplementasikan

### 1. **Backend API Endpoints**

#### **Save Monitoring Note**
```php
POST /api/cost-model/save-monitoring-note
```

**Request Body:**
```json
{
    "unit_police_number": "B 1234 AB",
    "year": 1,
    "week": 1,
    "component": "Service_Berkala/PM",
    "note": "Catatan untuk komponen ini"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Note berhasil disimpan",
    "data": {
        "id": 1,
        "note": "Catatan untuk komponen ini"
    }
}
```

#### **Get Monitoring Note**
```php
GET /api/cost-model/get-monitoring-note?unit_police_number=B 1234 AB&year=1&week=1&component=Service_Berkala/PM
```

**Response:**
```json
{
    "success": true,
    "message": "Note berhasil diambil",
    "data": {
        "note": "Catatan untuk komponen ini"
    }
}
```

### 2. **Frontend JavaScript API**

#### **Save Monitoring Note**
```javascript
async saveMonitoringNote(unitPoliceNumber, year, week, component, note) {
    // Implementation untuk menyimpan note ke database
}
```

#### **Get Monitoring Note**
```javascript
async getMonitoringNote(unitPoliceNumber, year, week, component) {
    // Implementation untuk mengambil note dari database
}
```

### 3. **UI Integration**

#### **Note Button**
Setiap input field monitoring memiliki tombol "Note" yang dapat diklik:
```html
<span class="note-cell" onclick="showNote('${idBase}')">Note</span>
```

#### **Show Note Function**
```javascript
async function showNote(id) {
    // Parse ID untuk mendapatkan informasi komponen
    // Ambil note yang sudah ada
    // Tampilkan popup untuk input note
    // Simpan note ke database
}
```

## Cara Kerja

### 1. **Parsing ID Element**
Fungsi `showNote` akan mem-parse ID element untuk mendapatkan informasi:
- **Regular Monitoring**: `Component_year_week` (contoh: `Service_Berkala/PM_1_W1`)
- **Existing Monitoring**: `Component_existing_year_week` (contoh: `Service_Berkala/PM_existing_1_W1`)

### 2. **Validasi Input**
- Memastikan Nomor Polisi Unit sudah diisi
- Mengambil unit police number dari field yang sesuai (regular atau existing)

### 3. **Ambil Note Existing**
- Mengambil note yang sudah ada dari database
- Menampilkan note existing di popup sebagai default value

### 4. **Input Note**
- Menampilkan popup dengan prompt
- Menampilkan current value dan note existing
- User dapat mengedit atau menambah note

### 5. **Simpan Note**
- Menyimpan note ke database menggunakan API
- Menampilkan konfirmasi sukses atau error

## Database Schema

### **Tabel: cost_model_monitoring**
```sql
CREATE TABLE cost_model_monitoring (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    unit_police_number VARCHAR(255) NULL,
    year INT DEFAULT 1,
    week INT DEFAULT 1,
    component VARCHAR(255),
    value DECIMAL(15,2) DEFAULT 0,
    note TEXT NULL,  -- Kolom untuk menyimpan note
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_monitoring_lookup (unit_police_number, year, week),
    INDEX idx_component (component)
);
```

## Validasi

### 1. **Backend Validation**
```php
$request->validate([
    'unit_police_number' => 'required|string|max:255',
    'year' => 'required|integer|min:1',
    'week' => 'required|integer|min:1|max:52',
    'component' => 'required|string|max:255',
    'note' => 'nullable|string|max:1000',
]);
```

### 2. **Frontend Validation**
- Memastikan unit police number sudah diisi
- Memastikan costModelAPI tersedia
- Error handling untuk network issues

## Error Handling

### 1. **Backend Errors**
- Logging error dengan detail request
- Response error dengan message yang informatif
- Database transaction handling

### 2. **Frontend Errors**
- Try-catch untuk async operations
- User-friendly error messages
- Console logging untuk debugging

## Logging

### 1. **Save Note Logging**
```php
Log::info('Saving monitoring note', [
    'unit_police_number' => $unitPoliceNumber,
    'year' => $year,
    'week' => $week,
    'component' => $component,
    'note' => $note
]);
```

### 2. **Error Logging**
```php
Log::error('Error saving monitoring note', [
    'error' => $e->getMessage(),
    'request' => $request->all()
]);
```

## Testing

### Test Case 1: Save New Note
1. Klik tombol "Note" pada input field
2. Isi note baru
3. Klik OK
4. **Expected Result**: Note tersimpan ke database

### Test Case 2: Update Existing Note
1. Klik tombol "Note" pada field yang sudah ada note
2. Edit note existing
3. Klik OK
4. **Expected Result**: Note terupdate di database

### Test Case 3: Cancel Note
1. Klik tombol "Note"
2. Klik Cancel
3. **Expected Result**: Tidak ada perubahan di database

### Test Case 4: Empty Unit Police Number
1. Kosongkan field Nomor Polisi Unit
2. Klik tombol "Note"
3. **Expected Result**: Alert "Mohon isi Nomor Polisi Unit terlebih dahulu!"

### Test Case 5: Network Error
1. Simulasi network error
2. Klik tombol "Note"
3. **Expected Result**: Error message ditampilkan

## File yang Dimodifikasi

### Backend
1. `app/Http/Controllers/CostModelController.php`
   - Menambahkan method `saveMonitoringNote()`
   - Menambahkan method `getMonitoringNote()`

2. `routes/web.php`
   - Menambahkan route `POST /api/cost-model/save-monitoring-note`
   - Menambahkan route `GET /api/cost-model/get-monitoring-note`

### Frontend
1. `public/js/cost-model-api.js`
   - Menambahkan method `saveMonitoringNote()`
   - Menambahkan method `getMonitoringNote()`

2. `resources/views/index.blade.php`
   - Memodifikasi fungsi `showNote()` untuk menggunakan API
   - Menambahkan parsing ID untuk regular dan existing monitoring
   - Menambahkan error handling dan validation

## Keuntungan

### 1. **Data Persistence**
- Note tersimpan permanen di database
- Dapat diakses kembali saat dibutuhkan
- Tidak hilang saat refresh halaman

### 2. **User Experience**
- Interface yang familiar (prompt dialog)
- Menampilkan note existing sebagai default
- Feedback yang jelas (success/error messages)

### 3. **Data Integrity**
- Validasi input di backend dan frontend
- Error handling yang robust
- Logging untuk debugging

### 4. **Flexibility**
- Mendukung regular dan existing monitoring
- Note dapat diupdate kapan saja
- Tidak terbatas pada komponen tertentu

## Status
✅ **SELESAI** - Fitur note monitoring telah diimplementasikan dengan lengkap 