# Monitoring Total Column Feature

## Deskripsi
Fitur ini menambahkan kolom "Total" dan row "Total Keseluruhan" pada halaman monitoring untuk memberikan ringkasan total biaya per komponen dan total keseluruhan.

## Perubahan yang Dilakukan

### 1. Struktur Tabel
- **Kolom Total**: Ditambahkan kolom baru setelah kolom W52 yang menampilkan total biaya untuk setiap komponen
- **Row Total Keseluruhan**: Ditambahkan row baru di bagian bawah yang menampilkan total keseluruhan dari semua komponen

### 2. Fungsi JavaScript yang Dimodifikasi

#### `updateMonitoringTable()`
- Menambahkan header "Total" dengan styling khusus
- Menambahkan cell total untuk setiap komponen
- Menambahkan cell total untuk setiap subcategory
- Menambahkan cell grand total di row total

#### `updateMonitoringTotals()`
- Menghitung total per komponen untuk semua minggu (W1-W52)
- Menghitung total keseluruhan dari semua komponen
- Update nilai di kolom Total dan cell grand total

#### `updateExistingMonitoringTable()`
- Sama seperti `updateMonitoringTable()` tetapi untuk existing monitoring
- Menambahkan kolom Total dan row total keseluruhan

#### `updateExistingMonitoringTotals()`
- Sama seperti `updateMonitoringTotals()` tetapi untuk existing monitoring

### 3. Styling CSS
- **`.total-column`**: Background biru muda untuk kolom total
- **`.grand-total-cell`**: Background biru tua dengan teks putih untuk cell grand total
- **`th.total-column`**: Header kolom Total dengan background biru tua

## Format Data

### ID Element untuk Total Komponen
```
component_total_[ComponentName]_[Year]
```
Contoh: `component_total_Service_Berkala/PM_1`

### ID Element untuk Grand Total
```
grand_total_[Year]
```
Contoh: `grand_total_1`

### ID Element untuk Existing Monitoring
```
component_total_[ComponentName]_existing_[Year]
grand_total_existing_[Year]
```

## Cara Kerja

1. **Perhitungan Total Komponen**: Setiap kali ada perubahan input, fungsi `updateMonitoringTotals()` akan:
   - Menghitung total untuk setiap komponen dari W1 sampai W52
   - Menampilkan hasil di kolom Total

2. **Perhitungan Grand Total**: 
   - Menjumlahkan semua total komponen
   - Menampilkan hasil di cell grand total

3. **Auto Update**: 
   - Total akan otomatis terupdate setiap kali ada perubahan input
   - Downtime (1%) juga dihitung otomatis berdasarkan persentase yang diset

## Komponen yang Dihitung
- Service Berkala/PM
- Service General/GM
- BBM
- AdBlue
- Driver Cost
- Ban
- Downtime (1%)

## Tampilan
- Kolom Total memiliki background biru muda untuk membedakan dari kolom mingguan
- Cell grand total memiliki background biru tua dengan teks putih untuk menonjolkan total keseluruhan
- Header kolom Total juga memiliki styling khusus

## Kompatibilitas
- Fitur ini bekerja untuk monitoring regular dan existing monitoring
- Mendukung semua tahun (1-5 tahun)
- Kompatibel dengan fitur note dan auto-save yang sudah ada
