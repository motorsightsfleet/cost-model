# Fix: Week Parsing di Monitoring Note

## Masalah
Error terjadi saat menyimpan note monitoring:
```
Error: Terjadi kesalahan: The week field must be an integer.
```

## Root Cause
Fungsi `showNote` mengirim field `week` sebagai string dengan format "W1", "W2", dll, sedangkan backend validation mengharapkan integer.

## Solusi

### 1. **Parsing Week ke Integer**
```javascript
// Sebelum (SALAH)
week = idParts[existingIndex + 2]; // "W1", "W2", dll

// Sesudah (BENAR)
week = parseInt(idParts[existingIndex + 2].replace('W', '')); // 1, 2, dll
```

### 2. **Parsing Year ke Integer**
```javascript
// Sebelum (SALAH)
year = idParts[existingIndex + 1]; // "1", "2", dll

// Sesudah (BENAR)
year = parseInt(idParts[existingIndex + 1]); // 1, 2, dll
```

### 3. **Debugging Logs**
Menambahkan console logs untuk debugging:
```javascript
console.log('DEBUG - Parsed ID:', {
    id: id,
    isExisting: isExisting,
    component: component,
    year: year,
    week: week,
    unitPoliceNumber: unitPoliceNumber
});

console.log('DEBUG - Saving note with data:', {
    unitPoliceNumber: unitPoliceNumber,
    year: year,
    week: week,
    component: component,
    note: note
});
```

## ID Format yang Didukung

### 1. **Regular Monitoring**
- Format: `Component_year_week`
- Contoh: `Service_Berkala/PM_1_W1`
- Parsing:
  - Component: `Service_Berkala/PM`
  - Year: `1` (integer)
  - Week: `1` (integer, dari "W1")

### 2. **Existing Monitoring**
- Format: `Component_existing_year_week`
- Contoh: `Service_Berkala/PM_existing_1_W1`
- Parsing:
  - Component: `Service_Berkala/PM`
  - Year: `1` (integer)
  - Week: `1` (integer, dari "W1")

## Backend Validation
```php
$request->validate([
    'unit_police_number' => 'required|string|max:255',
    'year' => 'required|integer|min:1',
    'week' => 'required|integer|min:1|max:52', // Harus integer
    'component' => 'required|string|max:255',
    'note' => 'nullable|string|max:1000',
]);
```

## Testing

### Test Case 1: Regular Monitoring Note
1. Klik "Note" pada field `Service_Berkala/PM_1_W1`
2. **Expected Result**: 
   - Parsed: component="Service_Berkala/PM", year=1, week=1
   - Note tersimpan tanpa error

### Test Case 2: Existing Monitoring Note
1. Klik "Note" pada field `Service_Berkala/PM_existing_1_W1`
2. **Expected Result**:
   - Parsed: component="Service_Berkala/PM", year=1, week=1
   - Note tersimpan tanpa error

### Test Case 3: Week 52
1. Klik "Note" pada field dengan week 52
2. **Expected Result**:
   - Parsed: week=52 (integer)
   - Note tersimpan tanpa error

## File yang Dimodifikasi

### Frontend
1. `resources/views/index.blade.php`
   - Memperbaiki parsing week dari "W1" ke 1
   - Memperbaiki parsing year dari string ke integer
   - Menambahkan debugging logs

## Status
✅ **SELESAI** - Week parsing fix telah diimplementasikan 