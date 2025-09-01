# Monitoring Total Assumptions Feature

## Deskripsi
Fitur ini menambahkan baris "Total Assumptions" di halaman monitoring yang mengambil nilai dari dashboard dan membaginya dengan 52 untuk setiap minggu. Nilai ini memberikan referensi biaya asumsi per minggu berdasarkan perhitungan dashboard.

## Perubahan yang Dilakukan

### 1. Struktur Tabel Monitoring
- **Baris Total (Monitoring)**: Baris yang menampilkan total dari semua komponen monitoring
- **Baris Total (Assumptions)**: Ditambahkan baris baru setelah baris "Total (Monitoring)" di bagian bawah tabel monitoring
- **Nilai Per Minggu**: Setiap kolom W1 sampai W52 menampilkan nilai yang sama (Total Assumptions tahunan dibagi 52)
- **Kolom Total**: Menampilkan total asumsi tahunan dari dashboard

### 2. Fungsi JavaScript yang Ditambahkan

#### `updateTotalAssumptions(year)`
- Mengambil data dashboard dari database
- Menghitung Total Assumptions untuk tahun tertentu
- Membagi nilai tahunan dengan 52 untuk mendapatkan nilai mingguan
- Update semua kolom W1-W52 dengan nilai yang sama
- Update kolom Total dengan nilai tahunan

#### `updateExistingTotalAssumptions(year)`
- Sama seperti `updateTotalAssumptions()` tetapi untuk existing monitoring
- Menggunakan prefix 'existing_' untuk ID element

### 3. Styling CSS
- **`.total-assumptions-row`**: Background kuning muda untuk baris Total Assumptions
- **`.assumption-cell`**: Background kuning muda untuk cell Total Assumptions
- **Warna**: Kuning muda (#fff3cd) dengan teks coklat (#856404)

## Cara Kerja

### 1. Sumber Data
- Data diambil dari `dashboard_data.assumption_totals` yang tersimpan di database
- Array `assumption_totals` berisi total asumsi untuk 10 tahun (index 0-9)

### 2. Perhitungan
```javascript
const assumptionTotal = dashboardData.dashboard_data.assumption_totals[year - 1] || 0;
const weeklyAssumption = assumptionTotal / 52; // Divide by 52 weeks
```

### 3. Mapping Tahun
- Monitoring tahun 1 → Dashboard assumption_totals[0]
- Monitoring tahun 2 → Dashboard assumption_totals[1]
- Monitoring tahun 3 → Dashboard assumption_totals[2]
- dst...

### 4. Auto Update
- Total Assumptions terupdate otomatis saat:
  - Halaman monitoring dibuka
  - Tahun monitoring diubah
  - Unit polisi diubah
  - Data dashboard berubah

## Format Data

### ID Element untuk Total Assumptions
```
total_assumptions_[Year]_[Week]
```
Contoh: `total_assumptions_1_W1`, `total_assumptions_1_W52`

### ID Element untuk Total Column
```
total_assumptions_total_[Year]
```
Contoh: `total_assumptions_total_1`

### ID Element untuk Existing Monitoring
```
total_assumptions_existing_[Year]_[Week]
total_assumptions_total_existing_[Year]
```

## Komponen yang Dihitung dalam Total Assumptions

Berdasarkan dashboard, Total Assumptions mencakup:
- Service Berkala/PM
- Service General/GM
- BBM
- AdBlue
- Driver Cost
- Ban
- Downtime (1%)

## Tampilan

### Regular Monitoring
- Baris "Total (Monitoring)" dengan background biru muda
- Baris "Total (Assumptions)" dengan background kuning muda
- Setiap kolom W1-W52 menampilkan nilai yang sama untuk Total (Assumptions)
- Kolom Total menampilkan total tahunan

### Existing Monitoring
- Sama seperti regular monitoring
- Menggunakan prefix 'existing_' untuk membedakan

## Integrasi dengan Dashboard

### 1. Data Flow
```
Dashboard Calculate → Save to Database → Monitoring Load → Display Total Assumptions
```

### 2. Dependencies
- Memerlukan data dashboard yang sudah dihitung
- Jika tidak ada data dashboard, Total Assumptions akan menampilkan 0.00

### 3. Real-time Update
- Total Assumptions akan terupdate setiap kali dashboard dihitung ulang
- Nilai akan konsisten dengan perhitungan dashboard terbaru

## Error Handling

### 1. No Dashboard Data
- Jika tidak ada data dashboard, menampilkan 0.00
- Console log: "No dashboard data available for Total Assumptions"

### 2. API Error
- Jika gagal mengambil data dashboard, menampilkan 0.00
- Console log: "Error updating Total Assumptions: [error]"

### 3. Invalid Year
- Jika tahun tidak valid, menggunakan tahun 1 sebagai default
- Array index disesuaikan (year - 1)

## Benefits

1. **Referensi Biaya**: Memberikan referensi biaya asumsi per minggu
2. **Konsistensi Data**: Nilai konsisten dengan perhitungan dashboard
3. **Perbandingan**: Memudahkan perbandingan antara asumsi dan aktual
4. **Auto Update**: Terupdate otomatis tanpa input manual
5. **Visual Distinction**: Styling berbeda untuk membedakan dari data input

## Kompatibilitas

- Bekerja untuk monitoring regular dan existing monitoring
- Mendukung semua tahun (1-10 tahun)
- Kompatibel dengan fitur note dan auto-save yang sudah ada
- Tidak mengganggu perhitungan total monitoring yang sudah ada

## Testing

Untuk memastikan fitur berfungsi:
1. Buka halaman dashboard dan lakukan perhitungan
2. Buka halaman monitoring
3. Pilih tahun yang sama dengan dashboard
4. Verifikasi baris "Total Assumptions" muncul dengan nilai yang benar
5. Ubah tahun dan verifikasi nilai berubah sesuai
6. Test dengan existing monitoring
