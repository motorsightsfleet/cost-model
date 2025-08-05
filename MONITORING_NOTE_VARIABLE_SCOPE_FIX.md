# Fix: Variable Scope di Monitoring Note

## Masalah
Error terjadi saat mengklik tombol "Note" di monitoring:
```
ReferenceError: Cannot access 'unitPoliceNumber' before initialization
```

## Root Cause
Variabel `unitPoliceNumber` digunakan di console.log sebelum dideklarasikan, menyebabkan ReferenceError.

## Solusi

### 1. **Memindahkan Deklarasi Variabel**
```javascript
// Sebelum (SALAH)
console.log('DEBUG - Parsed ID:', {
    // ...
    unitPoliceNumber: unitPoliceNumber // Error: belum dideklarasikan
});

// Ambil unit police number
const unitPoliceNumber = isExisting 
    ? document.getElementById('existingUnitPoliceNumber')?.value || ''
    : document.getElementById('unitPoliceNumber')?.value || '';

// Sesudah (BENAR)
// Ambil unit police number
const unitPoliceNumber = isExisting 
    ? document.getElementById('existingUnitPoliceNumber')?.value || ''
    : document.getElementById('unitPoliceNumber')?.value || '';

console.log('DEBUG - Parsed ID:', {
    // ...
    unitPoliceNumber: unitPoliceNumber // OK: sudah dideklarasikan
});
```

### 2. **Urutan Deklarasi yang Benar**
1. Parse ID untuk mendapatkan component, year, week
2. Deklarasi unitPoliceNumber
3. Console log dengan semua variabel
4. Validasi unitPoliceNumber
5. Ambil existing note
6. Tampilkan prompt
7. Simpan note

## Kode yang Diperbaiki

### **Sebelum (SALAH)**
```javascript
async function showNote(id) {
    try {
        // Parse ID
        const idParts = id.split('_');
        let component, year, week;
        const isExisting = id.includes('_existing_');
        
        if (isExisting) {
            // Parse existing monitoring
        } else {
            // Parse regular monitoring
        }
        
        // Console log SEBELUM deklarasi unitPoliceNumber
        console.log('DEBUG - Parsed ID:', {
            unitPoliceNumber: unitPoliceNumber // ❌ ERROR
        });
        
        // Deklarasi unitPoliceNumber SETELAH digunakan
        const unitPoliceNumber = isExisting 
            ? document.getElementById('existingUnitPoliceNumber')?.value || ''
            : document.getElementById('unitPoliceNumber')?.value || '';
        
        // ... rest of function
    } catch (error) {
        console.error('Error in showNote:', error);
    }
}
```

### **Sesudah (BENAR)**
```javascript
async function showNote(id) {
    try {
        // Parse ID
        const idParts = id.split('_');
        let component, year, week;
        const isExisting = id.includes('_existing_');
        
        if (isExisting) {
            // Parse existing monitoring
        } else {
            // Parse regular monitoring
        }
        
        // Deklarasi unitPoliceNumber SEBELUM digunakan
        const unitPoliceNumber = isExisting 
            ? document.getElementById('existingUnitPoliceNumber')?.value || ''
            : document.getElementById('unitPoliceNumber')?.value || '';
        
        // Console log SETELAH deklarasi unitPoliceNumber
        console.log('DEBUG - Parsed ID:', {
            unitPoliceNumber: unitPoliceNumber // ✅ OK
        });
        
        // ... rest of function
    } catch (error) {
        console.error('Error in showNote:', error);
    }
}
```

## Testing

### Test Case 1: Regular Monitoring Note
1. Buka tab monitoring
2. Isi Nomor Polisi Unit
3. Klik tombol "Note" pada field monitoring
4. **Expected Result**: Tidak ada error, popup note muncul

### Test Case 2: Existing Monitoring Note
1. Buka tab existing monitoring
2. Isi Nomor Polisi Unit
3. Klik tombol "Note" pada field existing monitoring
4. **Expected Result**: Tidak ada error, popup note muncul

### Test Case 3: Empty Unit Police Number
1. Kosongkan field Nomor Polisi Unit
2. Klik tombol "Note"
3. **Expected Result**: Alert "Mohon isi Nomor Polisi Unit terlebih dahulu!"

### Test Case 4: Console Logs
1. Buka Developer Tools (F12)
2. Klik tombol "Note"
3. **Expected Result**: Console logs menunjukkan parsed data dengan benar

## API Testing

### Test API Endpoint
```bash
curl -X POST http://localhost:8000/api/cost-model/save-monitoring-note \
  -H "Content-Type: application/json" \
  -d '{
    "unit_police_number": "TEST 1234 AB",
    "year": 1,
    "week": 1,
    "component": "Service_Berkala/PM",
    "note": "Test note"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Note berhasil disimpan",
  "data": {
    "id": 45,
    "note": "Test note"
  }
}
```

## File yang Dimodifikasi

### Frontend
1. `resources/views/index.blade.php`
   - Memindahkan deklarasi `unitPoliceNumber` sebelum console.log
   - Memperbaiki urutan eksekusi kode

## Status
✅ **SELESAI** - Variable scope fix telah diimplementasikan 