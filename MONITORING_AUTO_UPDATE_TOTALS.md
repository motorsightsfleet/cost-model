# Auto Trigger updateMonitoringTotals di Halaman Monitoring

## Deskripsi
Sistem monitoring sekarang akan otomatis memanggil `updateMonitoringTotals()` dan `updateExistingMonitoringTotals()` pada berbagai event untuk memastikan perhitungan total dan downtime selalu up-to-date.

## Trigger Points

### 1. Saat Tab Monitoring Dibuka
```javascript
} else if (tabId === 'monitoring') {
    // For monitoring tab, load monitoring data
    setTimeout(async () => {
        try {
            if (typeof costModelAPI !== 'undefined') {
                const year = parseInt(document.getElementById('yearToMonitor')?.value || '1');
                const unitPoliceNumber = document.getElementById('unitPoliceNumber')?.value || '';
                await costModelAPI.loadMonitoringData(year, unitPoliceNumber);
                console.log('Monitoring data loaded successfully');
                
                // Update monitoring totals after data is loaded
                setTimeout(() => {
                    updateMonitoringTotals(year);
                    console.log('Monitoring totals updated after tab load');
                }, 200);
            }
        } catch (error) {
            console.error('Error loading monitoring data:', error);
        }
    }, 100);
}
```
- **Timing**: 200ms setelah data monitoring dimuat
- **Tujuan**: Memastikan total dan downtime terhitung saat pertama kali membuka tab monitoring

### 2. Saat Tab Existing Monitoring Dibuka
```javascript
} else if (tabId === 'existingMonitoring') {
    // For existing monitoring tab, load existing monitoring data
    setTimeout(async () => {
        try {
            if (typeof costModelAPI !== 'undefined') {
                const year = parseInt(document.getElementById('existingYearToMonitor')?.value || '1');
                const unitPoliceNumber = document.getElementById('existingUnitPoliceNumber')?.value || '';
                await costModelAPI.loadExistingMonitoringData(year, unitPoliceNumber);
                console.log('Existing monitoring data loaded successfully');
                
                // Update existing monitoring totals after data is loaded
                setTimeout(() => {
                    updateExistingMonitoringTotals(year);
                    console.log('Existing monitoring totals updated after tab load');
                }, 200);
            }
        } catch (error) {
            console.error('Error loading existing monitoring data:', error);
        }
    }, 100);
}
```
- **Timing**: 200ms setelah data existing monitoring dimuat
- **Tujuan**: Memastikan total dan downtime terhitung saat pertama kali membuka tab existing monitoring

### 3. Saat Tahun Diubah (Regular Monitoring)
```html
<select id="yearToMonitor" onchange="updateMonitoringTable(); loadMonitoringDataForYear(); updateMonitoringTotals(parseInt(this.value));">
```
- **Trigger**: Event `onchange` pada dropdown year
- **Tujuan**: Memastikan total dan downtime terhitung ulang setelah tahun diubah

### 4. Saat Tahun Diubah (Existing Monitoring)
```html
<select id="existingYearToMonitor" onchange="updateExistingMonitoringTable(); loadExistingMonitoringDataForYear(); updateExistingMonitoringTotals(parseInt(this.value));">
```
- **Trigger**: Event `onchange` pada dropdown existing year
- **Tujuan**: Memastikan total dan downtime terhitung ulang setelah existing year diubah

### 5. Saat Data Monitoring Dimuat untuk Tahun Tertentu
```javascript
async function loadMonitoringDataForYear() {
    try {
        if (typeof costModelAPI !== 'undefined') {
            const year = parseInt(document.getElementById('yearToMonitor')?.value || '1');
            const unitPoliceNumber = document.getElementById('unitPoliceNumber')?.value || '';
            await costModelAPI.loadMonitoringData(year, unitPoliceNumber);
            console.log(`Monitoring data loaded for year ${year}`);
            
            // Update monitoring totals after data is loaded
            setTimeout(() => {
                updateMonitoringTotals(year);
                console.log(`Monitoring totals updated for year ${year}`);
            }, 200);
        }
    } catch (error) {
        console.error('Error loading monitoring data for year:', error);
    }
}
```
- **Timing**: 200ms setelah data dimuat
- **Tujuan**: Memastikan total dan downtime terhitung setelah data monitoring dimuat

### 6. Saat Data Existing Monitoring Dimuat untuk Tahun Tertentu
```javascript
async function loadExistingMonitoringDataForYear() {
    try {
        if (typeof costModelAPI !== 'undefined') {
            const year = parseInt(document.getElementById('existingYearToMonitor')?.value || '1');
            const unitPoliceNumber = document.getElementById('existingUnitPoliceNumber')?.value || '';
            await costModelAPI.loadExistingMonitoringData(year, unitPoliceNumber);
            console.log(`Existing monitoring data loaded for year ${year}`);
            
            // Update existing monitoring totals after data is loaded
            setTimeout(() => {
                updateExistingMonitoringTotals(year);
                console.log(`Existing monitoring totals updated for year ${year}`);
            }, 200);
        }
    } catch (error) {
        console.error('Error loading existing monitoring data for year:', error);
    }
}
```
- **Timing**: 200ms setelah data dimuat
- **Tujuan**: Memastikan total dan downtime terhitung setelah data existing monitoring dimuat

### 7. Saat Table Update (Built-in)
```javascript
function updateMonitoringTable() {
    // ... table generation code ...
    
    const tableBody = document.getElementById('monitoring-table-body');
    if (tableBody) {
        tableBody.innerHTML = tableHTML;
        updateMonitoringTotals(year); // Initialize totals
    }
}
```
- **Trigger**: Saat tabel monitoring diperbarui
- **Tujuan**: Inisialisasi total dan downtime saat tabel dibuat

### 8. Saat Existing Table Update (Built-in)
```javascript
function updateExistingMonitoringTable() {
    // ... table generation code ...
    
    const tableBody = document.getElementById('existing-monitoring-table-body');
    if (tableBody) {
        tableBody.innerHTML = tableHTML;
        updateExistingMonitoringTotals(year); // Initialize totals
    }
}
```
- **Trigger**: Saat tabel existing monitoring diperbarui
- **Tujuan**: Inisialisasi total dan downtime saat tabel existing dibuat

### 9. Saat Input Berubah (Built-in)
```html
<input type="text" id="${idBase}" oninput="formatInputWithSeparator(this); updateMonitoringTotals(${year})" placeholder="0.00">
```
- **Trigger**: Event `oninput` pada setiap input field
- **Tujuan**: Memperbarui total dan downtime secara real-time saat user mengetik

## Fungsi updateMonitoringTotals

### Perhitungan yang Dilakukan:

#### 1. **Total Komponen**
```javascript
let totalComponents = 0;
components.forEach(component => {
    if (component !== 'Downtime_1%') {
        const id = `${component}_${year}_${week}`;
        const input = document.getElementById(id);
        if (input) {
            const value = parseFormattedNumber(input.value || '0');
            totalComponents += value;
        }
    }
});
```

#### 2. **Downtime (1%)**
```javascript
const downtimePercentage = parseFloat(document.getElementById('downtime')?.value || '0');
const downtimeValue = (totalComponents * (downtimePercentage / 100)) || 0;
```

#### 3. **Total Akhir**
```javascript
let total = totalComponents + downtimeValue;
```

### Komponen yang Dihitung:
- Service Berkala/PM
- Service General/GM
- BBM
- AdBlue
- Driver Cost
- Ban
- Downtime (1%) - dihitung otomatis

## Timing Strategy

### 1. Cascading Timeouts
- **100ms**: Tab load data loading
- **200ms**: Update totals after data load
- **Immediate**: Year change trigger
- **Immediate**: Input change trigger

### 2. Safety Delays
- Menggunakan `setTimeout` untuk memastikan DOM sudah siap
- Delay 200ms setelah data dimuat untuk memastikan semua element sudah ter-render

## Keuntungan

### 1. Real-time Updates
- Total dan downtime selalu terhitung dengan data terbaru
- Tidak perlu manual refresh atau reload
- Konsistensi data monitoring

### 2. User Experience
- User tidak perlu mengingat untuk klik tombol calculate
- Perhitungan otomatis saat ada perubahan
- Interface yang lebih responsif

### 3. Data Integrity
- Memastikan total dan downtime selalu sinkron dengan input
- Menghindari perhitungan yang tidak konsisten
- Data yang selalu akurat

## Debugging

### 1. Console Logs
Setiap trigger point memiliki console log:
```javascript
console.log('Monitoring totals updated after tab load');
console.log(`Monitoring totals updated for year ${year}`);
```

### 2. Timing Information
Timeout yang berbeda untuk setiap event memudahkan debugging:
- 100ms: Tab load data loading
- 200ms: Update totals after data load
- Immediate: Year change dan input change

## Testing

### Test Case 1: Tab Load
1. Buka tab monitoring
2. **Expected Result**: Total dan downtime terhitung otomatis setelah 200ms

### Test Case 2: Year Change
1. Ubah tahun di dropdown
2. **Expected Result**: Total dan downtime terhitung ulang secara otomatis

### Test Case 3: Input Change
1. Ubah nilai di input field
2. **Expected Result**: Total dan downtime terhitung ulang secara real-time

### Test Case 4: Console Logs
1. Buka Developer Tools (F12)
2. Lihat console logs
3. **Expected Result**: Debug logs menunjukkan auto update totals

## File yang Dimodifikasi

### Frontend
1. `resources/views/index.blade.php`
   - Menambahkan `updateMonitoringTotals()` di event handler year selector
   - Menambahkan `updateExistingMonitoringTotals()` di event handler existing year selector
   - Menambahkan auto trigger di fungsi `showTab()` untuk tab monitoring
   - Menambahkan auto trigger di fungsi `loadMonitoringDataForYear()`
   - Menambahkan auto trigger di fungsi `loadExistingMonitoringDataForYear()`

## Status
✅ **SELESAI** - Auto trigger updateMonitoringTotals telah diimplementasikan di semua trigger points 