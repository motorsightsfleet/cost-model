# Perbaikan Perhitungan Total dan Downtime di Monitoring

## Masalah yang Ditemukan
Total dan Downtime tidak auto terhitung ketika:
1. Halaman di refresh
2. Data tahun diubah
3. Tabel monitoring di-update

## Penyebab
1. Fungsi perhitungan total dan Downtime tidak dipanggil dengan benar
2. Timing issue - perhitungan dipanggil sebelum data dimuat
3. Event listener untuk year selector tidak terpasang dengan benar

## Solusi yang Diterapkan

### 1. Menambahkan Debugging Logs
```javascript
console.log('DEBUG - updateMonitoringTotals called with year:', year);
console.log('DEBUG - updateMonitoringTable called');
console.log('DEBUG - Force recalculation of totals and downtime');
```

### 2. Force Recalculation Function
```javascript
function forceRecalculateMonitoringTotals(year) {
    try {
        console.log('DEBUG - forceRecalculateMonitoringTotals called for year:', year);
        
        // Cari dan trigger perhitungan total untuk setiap komponen
        const components = ['Service_Berkala/PM', 'Service_General/GM', 'BBM', 'AdBlue', 'Driver_Cost', 'Ban'];
        
        components.forEach(component => {
            const totalElement = document.getElementById(`${component}_Total_${year}`);
            if (totalElement) {
                console.log('DEBUG - Found total element for', component, ':', totalElement.id);
                // Trigger input event untuk memaksa perhitungan
                const event = new Event('input', { bubbles: true });
                totalElement.dispatchEvent(event);
            }
        });
        
        // Trigger perhitungan downtime
        const downtimeElement = document.getElementById(`Downtime_${year}`);
        if (downtimeElement) {
            console.log('DEBUG - Found downtime element:', downtimeElement.id);
            const event = new Event('input', { bubbles: true });
            downtimeElement.dispatchEvent(event);
        }
        
        // Trigger perhitungan grand total
        const grandTotalElement = document.getElementById(`Grand_Total_${year}`);
        if (grandTotalElement) {
            console.log('DEBUG - Found grand total element:', grandTotalElement.id);
            const event = new Event('input', { bubbles: true });
            grandTotalElement.dispatchEvent(event);
        }
        
        console.log('DEBUG - Force recalculation completed');
        
    } catch (error) {
        console.error('DEBUG - Error in forceRecalculateMonitoringTotals:', error);
    }
}
```

### 3. Enhanced updateMonitoringTotals Override
```javascript
const originalUpdateMonitoringTotals = window.updateMonitoringTotals;
window.updateMonitoringTotals = async function(year) {
    console.log('DEBUG - updateMonitoringTotals called with year:', year);
    
    // Call original function first
    if (originalUpdateMonitoringTotals) {
        console.log('DEBUG - Calling original updateMonitoringTotals');
        originalUpdateMonitoringTotals(year);
    } else {
        console.log('DEBUG - Original updateMonitoringTotals not found');
    }
    
    // Setup tombol submit setelah tabel dibuat
    setTimeout(() => {
        addMonitoringSubmitButtons();
    }, 100);
    
    // Force recalculation of totals and downtime
    setTimeout(() => {
        console.log('DEBUG - Forcing recalculation of totals and downtime');
        forceRecalculateMonitoringTotals(year);
    }, 200);
};
```

### 4. Enhanced updateMonitoringTable Override
```javascript
const originalUpdateMonitoringTable = window.updateMonitoringTable;
window.updateMonitoringTable = async function() {
    console.log('DEBUG - updateMonitoringTable called');
    
    // Call original function first
    if (originalUpdateMonitoringTable) {
        console.log('DEBUG - Calling original updateMonitoringTable');
        originalUpdateMonitoringTable();
    } else {
        console.log('DEBUG - Original updateMonitoringTable not found');
    }
    
    // Setup tombol submit untuk monitoring fields yang baru dibuat
    setTimeout(() => {
        setupMonitoringAutoSave();
    }, 100);
    
    // Load monitoring data
    const year = parseInt(document.getElementById('yearToMonitor')?.value || '1');
    const unitPoliceNumber = document.getElementById('unitPoliceNumber')?.value || '';
    
    console.log('DEBUG - Loading monitoring data for year:', year, 'unit:', unitPoliceNumber);
    await costModelAPI.loadMonitoringData(year, unitPoliceNumber);
    
    // Force recalculation after data is loaded
    setTimeout(() => {
        console.log('DEBUG - Force recalculation after table update');
        forceRecalculateMonitoringTotals(year);
    }, 300);
};
```

### 5. Year Selector Event Listeners
```javascript
function setupYearSelectorListeners() {
    console.log('DEBUG - Setting up year selector listeners');
    
    // Monitor untuk year selector monitoring
    const yearToMonitorElement = document.getElementById('yearToMonitor');
    if (yearToMonitorElement) {
        console.log('DEBUG - Found yearToMonitor element');
        yearToMonitorElement.addEventListener('change', function() {
            const year = parseInt(this.value || '1');
            console.log('DEBUG - Year changed to:', year);
            
            // Force recalculation after year change
            setTimeout(() => {
                console.log('DEBUG - Force recalculation after year change');
                forceRecalculateMonitoringTotals(year);
            }, 500);
        });
    }
    
    // Monitor untuk existing year selector monitoring
    const existingYearToMonitorElement = document.getElementById('existingYearToMonitor');
    if (existingYearToMonitorElement) {
        console.log('DEBUG - Found existingYearToMonitor element');
        existingYearToMonitorElement.addEventListener('change', function() {
            const year = parseInt(this.value || '1');
            console.log('DEBUG - Existing year changed to:', year);
            
            // Force recalculation after year change
            setTimeout(() => {
                console.log('DEBUG - Force recalculation after existing year change');
                forceRecalculateMonitoringTotals(year);
            }, 500);
        });
    }
}
```

## Cara Kerja

### 1. Saat Halaman Load
- `setupYearSelectorListeners()` dipanggil
- Event listener untuk year selector terpasang
- Perhitungan total dan Downtime siap di-trigger

### 2. Saat Tahun Diubah
- Event listener `change` ter-trigger
- `forceRecalculateMonitoringTotals(year)` dipanggil
- Semua total dan Downtime dihitung ulang

### 3. Saat Tabel Di-update
- `updateMonitoringTable()` dipanggil
- Data monitoring dimuat
- `forceRecalculateMonitoringTotals(year)` dipanggil setelah data dimuat

### 4. Saat Totals Di-update
- `updateMonitoringTotals()` dipanggil
- Fungsi original dijalankan
- `forceRecalculateMonitoringTotals(year)` dipanggil sebagai backup

## Timing Strategy

### 1. Multiple Timeouts
- 100ms: Setup tombol submit
- 200ms: Force recalculation setelah totals update
- 300ms: Force recalculation setelah table update
- 500ms: Force recalculation setelah year change

### 2. Event Triggering
- Menggunakan `new Event('input', { bubbles: true })`
- Event bubbling memastikan semua handler ter-trigger
- Compatible dengan sistem perhitungan yang ada

## Testing

### Test Case 1: Page Refresh
1. Refresh halaman monitoring
2. **Expected Result**: Total dan Downtime terhitung otomatis

### Test Case 2: Year Change
1. Ubah tahun di dropdown
2. **Expected Result**: Total dan Downtime terhitung ulang

### Test Case 3: Table Update
1. Trigger update tabel monitoring
2. **Expected Result**: Total dan Downtime terhitung setelah data dimuat

### Test Case 4: Console Logs
1. Buka Developer Tools (F12)
2. Lihat console logs
3. **Expected Result**: Debug logs menunjukkan proses perhitungan

## File yang Dimodifikasi

### Frontend
1. `public/js/cost-model-api.js`
   - Menambahkan fungsi `forceRecalculateMonitoringTotals()`
   - Menambahkan fungsi `setupYearSelectorListeners()`
   - Enhanced override functions dengan debugging dan force recalculation
   - Menambahkan event listeners untuk year selector

## Status
🔧 **IN PROGRESS** - Perbaikan perhitungan total dan Downtime sedang diimplementasikan 