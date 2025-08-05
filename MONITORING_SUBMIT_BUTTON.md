# Perubahan Monitoring: Auto-Save ke Tombol Submit

## Deskripsi
Sistem monitoring telah diubah dari auto-save (onchange) menjadi manual submit dengan tombol. User sekarang dapat mengisi semua data monitoring terlebih dahulu, kemudian menyimpannya dengan menekan tombol submit.

## Perubahan yang Dilakukan

### 1. Menghapus Auto-Save
- Dihapus event listener `input` dan `change` pada field monitoring
- Field monitoring tidak lagi otomatis tersimpan saat diubah
- User dapat mengisi data tanpa khawatir data tersimpan sebelum selesai

### 2. Menambahkan Tombol Submit
- **Tombol "Simpan Data Monitoring"** - untuk regular monitoring
- **Tombol "Simpan Data Existing Monitoring"** - untuk existing monitoring
- Tombol muncul otomatis setelah tabel monitoring dibuat
- Styling yang menarik dengan hover effect

### 3. Fungsi Submit Manual
- `submitAllMonitoringData()` - Menyimpan semua data regular monitoring
- `submitAllExistingMonitoringData()` - Menyimpan semua data existing monitoring
- Mengumpulkan semua data yang diisi (tidak kosong)
- Menyimpan data satu per satu untuk memastikan semua tersimpan
- Menampilkan notifikasi berhasil/gagal

## Cara Kerja

### 1. Setup Tombol
```javascript
function addMonitoringSubmitButtons() {
    // Cari container monitoring
    const monitoringContainer = document.querySelector('.monitoring-section') || 
                               document.querySelector('[id*="monitoring"]') || 
                               document.querySelector('.container');
    
    // Buat tombol submit untuk regular monitoring
    const submitButton = document.createElement('button');
    submitButton.id = 'monitoring-submit-btn';
    submitButton.textContent = 'Simpan Data Monitoring';
    // ... styling dan event listener
}
```

### 2. Pengumpulan Data
```javascript
async function submitAllMonitoringData() {
    // Kumpulkan semua data monitoring
    const monitoringData = [];
    
    // Regular monitoring fields (week 1-52)
    const weeks = Array.from({ length: 52 }, (_, i) => `W${i + 1}`);
    const components = ['Service_Berkala/PM', 'Service_General/GM', 'BBM', 'AdBlue', 'Driver_Cost', 'Ban'];
    
    for (const week of weeks) {
        for (const component of components) {
            const id = `${component}_${year}_${week}`;
            const element = document.getElementById(id);
            if (element && element.value && element.value.trim() !== '') {
                // Tambahkan ke array data
                monitoringData.push({...});
            }
        }
    }
    
    // Simpan semua data
    for (const data of monitoringData) {
        await costModelAPI.saveMonitoringData(...);
    }
}
```

### 3. Feedback User
- Tombol berubah menjadi "Menyimpan..." saat proses berlangsung
- Notifikasi berhasil dengan jumlah data yang disimpan
- Notifikasi warning jika tidak ada data untuk disimpan
- Notifikasi error jika terjadi kesalahan

## Keuntungan

### 1. User Experience
- User dapat mengisi data dengan tenang tanpa khawatir data tersimpan sebelum selesai
- Kontrol penuh atas kapan data disimpan
- Feedback yang jelas tentang status penyimpanan

### 2. Performa
- Mengurangi jumlah request ke server
- Satu kali submit untuk semua data
- Tidak ada request berlebihan saat user mengetik

### 3. Data Integrity
- Semua data disimpan sekaligus
- Tidak ada data yang terlewat
- Konsistensi data lebih terjamin

## Styling Tombol

### Regular Monitoring Button
- Background: #007bff (Bootstrap primary)
- Hover: #0056b3
- Padding: 12px 24px
- Border-radius: 6px
- Font-weight: bold
- Box-shadow: 0 2px 4px rgba(0,0,0,0.1)

### Existing Monitoring Button
- Background: #28a745 (Bootstrap success)
- Hover: #1e7e34
- Styling sama dengan regular button

## Posisi Tombol
- Tombol muncul setelah tabel monitoring
- Jika tabel tidak ditemukan, tombol ditambahkan di akhir container
- Tombol hanya ditambahkan sekali (tidak duplikat)

## Testing

### Test Case 1: Regular Monitoring
1. Buka halaman monitoring
2. Isi beberapa data di week 1 dan 2
3. Klik tombol "Simpan Data Monitoring"
4. **Expected Result**: Data tersimpan, notifikasi berhasil muncul

### Test Case 2: Existing Monitoring
1. Buka halaman existing monitoring
2. Isi beberapa data di week 1 dan 2
3. Klik tombol "Simpan Data Existing Monitoring"
4. **Expected Result**: Data tersimpan, notifikasi berhasil muncul

### Test Case 3: No Data
1. Buka halaman monitoring
2. Jangan isi data apapun
3. Klik tombol submit
4. **Expected Result**: Notifikasi warning "Tidak ada data untuk disimpan"

### Test Case 4: Partial Data
1. Isi data hanya di week 1
2. Klik tombol submit
3. **Expected Result**: Hanya data week 1 yang tersimpan

## File yang Dimodifikasi

### Frontend
1. `public/js/cost-model-api.js`
   - Menghapus auto-save dari `setupMonitoringAutoSave()`
   - Menambahkan fungsi `addMonitoringSubmitButtons()`
   - Menambahkan fungsi `submitAllMonitoringData()`
   - Menambahkan fungsi `submitAllExistingMonitoringData()`
   - Memodifikasi override functions untuk setup tombol

## Status
✅ **SELESAI** - Monitoring sekarang menggunakan tombol submit manual 