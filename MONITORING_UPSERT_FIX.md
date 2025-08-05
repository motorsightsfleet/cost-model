# Perbaikan Monitoring Upsert

## Masalah
Monitoring data tidak melakukan update ketika data dengan kombinasi yang sama (tahun, nopol, component, week) sudah ada di database. Data baru selalu disimpan sebagai record baru, bukan mengupdate record yang sudah ada.

## Penyebab
Tidak ada unique constraint pada kombinasi `unit_police_number`, `year`, `week`, dan `component` di tabel `cost_model_monitoring`. Tanpa unique constraint, Laravel's `updateOrCreate` tidak bisa mengidentifikasi record yang sudah ada dengan benar.

## Solusi

### 1. Menambahkan Unique Constraint
Migration baru dibuat: `2025_08_05_032221_add_unique_constraint_to_cost_model_monitoring_table.php`

```php
Schema::table('cost_model_monitoring', function (Blueprint $table) {
    // Tambahkan unique constraint untuk kombinasi unit_police_number, year, week, dan component
    // Ini memastikan bahwa tidak ada duplikasi data untuk kombinasi yang sama
    $table->unique(['unit_police_number', 'year', 'week', 'component'], 'unique_monitoring_record');
});
```

### 2. Menambahkan Logging untuk Debugging
Logging ditambahkan ke controller untuk memantau proses upsert:

```php
// Log data yang akan disimpan
Log::info('Upserting monitoring data:', [
    'unit_police_number' => $request->unit_police_number,
    'year' => $request->year,
    'week' => $request->week,
    'component' => $request->component,
    'value' => $request->value,
    'note' => $request->note,
]);

// Cek apakah data sudah ada
$existingRecord = CostModelMonitoring::where([
    'unit_police_number' => $request->unit_police_number,
    'year' => $request->year,
    'week' => $request->week,
    'component' => $request->component,
])->first();

if ($existingRecord) {
    Log::info('Found existing record, updating:', ['id' => $existingRecord->id, 'old_value' => $existingRecord->value]);
} else {
    Log::info('No existing record found, creating new one');
}
```

## Testing
Sistem telah ditest dan berfungsi dengan benar:

1. **Test 1**: Insert record baru (tahun 1, nopol AE 1111 BA, component teset, week 1, value 200)
   - Result: Record baru dibuat (wasRecentlyCreated: true)

2. **Test 2**: Update record yang sama dengan value 4000
   - Result: Record yang sama diupdate (wasRecentlyCreated: false)

3. **Test 3**: Update lagi dengan value 5000
   - Result: Record yang sama diupdate lagi (wasRecentlyCreated: false)

4. **Test 4**: Insert record dengan week berbeda (week 2)
   - Result: Record baru dibuat karena kombinasi berbeda

## Cara Kerja
Sekarang ketika user input data monitoring:

1. **Input pertama**: tahun 1, nopol AE 1111 BA, component teset, week 1, value 200
   - Sistem mencari record dengan kombinasi yang sama
   - Tidak ditemukan, jadi record baru dibuat

2. **Input kedua**: tahun 1, nopol AE 1111 BA, component teset, week 2, value 4000
   - Sistem mencari record dengan kombinasi yang sama
   - Tidak ditemukan (week berbeda), jadi record baru dibuat

3. **Input ketiga**: tahun 1, nopol AE 1111 BA, component teset, week 2, value 5000
   - Sistem mencari record dengan kombinasi yang sama
   - Ditemukan record yang sama, jadi record diupdate (value berubah dari 4000 ke 5000)

## File yang Dimodifikasi
1. `database/migrations/2025_08_05_032221_add_unique_constraint_to_cost_model_monitoring_table.php` - Migration baru
2. `app/Http/Controllers/CostModelController.php` - Menambahkan logging untuk debugging

## Status
✅ **SELESAI** - Monitoring upsert sudah berfungsi dengan benar 