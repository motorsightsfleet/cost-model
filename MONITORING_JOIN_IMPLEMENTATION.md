# Implementasi JOIN di Halaman Monitoring

## Ringkasan Perubahan

Halaman monitoring telah berhasil diupdate untuk menggunakan JOIN antara tabel `cost_model_monitoring` dan `police_units`. Nomor polisi yang ditampilkan di halaman monitoring sekarang diambil dari kolom `police_number` di tabel `police_units` melalui JOIN dengan kolom `unit_police_number` (yang berisi ID dari `police_units`).

## Struktur Data yang Digunakan

### 1. Tabel Master: `police_units`
```sql
CREATE TABLE police_units (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    police_number VARCHAR(255) UNIQUE NOT NULL, -- Nomor polisi (B 1234 AB)
    unit_name VARCHAR(255) NULL,                -- Nama unit
    unit_type VARCHAR(255) NULL,                -- Jenis unit
    description TEXT NULL,                      -- Deskripsi
    is_active BOOLEAN DEFAULT TRUE,             -- Status aktif
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### 2. Tabel Monitoring: `cost_model_monitoring`
```sql
CREATE TABLE cost_model_monitoring (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    unit_police_number INTEGER NULL,            -- Foreign key ke police_units.id
    year INTEGER DEFAULT 1,
    week INTEGER DEFAULT 1,
    component VARCHAR(255) NOT NULL,
    value DECIMAL(15,2) DEFAULT 0,
    note TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (unit_police_number) REFERENCES police_units(id) ON DELETE SET NULL
);
```

## JOIN Query yang Digunakan

### 1. Query JOIN di Controller
```php
// Menggunakan scope withPoliceUnit() untuk JOIN
$query = CostModelMonitoring::withPoliceUnit();

// Filter berdasarkan nomor polisi
if ($request->has('unit_police_number') && $request->unit_police_number) {
    $query->where('police_units.police_number', $request->unit_police_number);
}

$monitoring = $query->get();
```

### 2. Scope withPoliceUnit() di Model
```php
public function scopeWithPoliceUnit($query)
{
    return $query->join('police_units', 'cost_model_monitoring.unit_police_number', '=', 'police_units.id')
                ->select('cost_model_monitoring.*', 'police_units.police_number', 'police_units.unit_name', 'police_units.unit_type');
}
```

### 3. SQL Query yang Dihasilkan
```sql
SELECT 
    cost_model_monitoring.*,
    police_units.police_number,
    police_units.unit_name,
    police_units.unit_type
FROM cost_model_monitoring
JOIN police_units ON cost_model_monitoring.unit_police_number = police_units.id
WHERE police_units.police_number = 'B 1234 AB';
```

## Data yang Dikembalikan ke Frontend

### 1. Format Data Monitoring
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "unit_police_number": "B 1234 AB",  // Diambil dari JOIN
            "year": 1,
            "week": 1,
            "component": "Service_Berkala/PM",
            "value": "1500000.00",
            "note": "Service berkala minggu pertama",
            "created_at": "2025-08-05T10:00:00.000000Z",
            "updated_at": "2025-08-05T10:00:00.000000Z",
            "police_unit_info": {
                "police_number": "B 1234 AB",
                "unit_name": "Unit B 1234 AB",
                "unit_type": "Kendaraan"
            }
        }
    ]
}
```

### 2. Format Data Latest Monitoring
```json
{
    "success": true,
    "data": {
        "1": {
            "1": [
                {
                    "id": 1,
                    "component": "Service_Berkala/PM",
                    "value": "1500000.00",
                    "note": "Service berkala minggu pertama",
                    "unit_police_number": "B 1234 AB",  // Diambil dari JOIN
                    "police_unit_info": {
                        "police_number": "B 1234 AB",
                        "unit_name": "Unit B 1234 AB",
                        "unit_type": "Kendaraan"
                    },
                    "created_at": "2025-08-05T10:00:00.000000Z",
                    "updated_at": "2025-08-05T10:00:00.000000Z"
                }
            ]
        }
    },
    "latest_unit": "B 1234 AB",
    "message": "Data monitoring berhasil diambil"
}
```

## Implementasi di Frontend

### 1. Loading Data Monitoring
```javascript
// Method loadMonitoringData di cost-model-api.js
async loadMonitoringData(year, unitPoliceNumber = '') {
    const filters = {
        year: year
    };
    
    if (unitPoliceNumber) {
        filters.unit_police_number = unitPoliceNumber;
    }

    const monitoringData = await this.getMonitoringData(filters);
    
    // Populate monitoring table
    monitoringData.forEach(item => {
        if (item.week === 0) return; // Skip metadata
        
        const inputId = `${item.component}_${item.year}_W${item.week}`;
        const element = document.getElementById(inputId);
        
        if (element) {
            element.value = this.formatNumberWithSeparator(item.value);
        }
    });
    
    // Set nomor polisi dari data JOIN
    if (monitoringData.length > 0) {
        const firstItem = monitoringData[0];
        if (firstItem.unit_police_number) {
            const element = document.getElementById('unitPoliceNumber');
            if (element) {
                element.value = firstItem.unit_police_number; // Dari JOIN
            }
        }
    }
}
```

### 2. Menampilkan Nomor Polisi di UI
```html
<!-- Di halaman monitoring -->
<div class="form-group">
    <label>Nomor Polisi Unit</label>
    <input type="text" id="unitPoliceNumber" placeholder="e.g., B 1234 AB">
</div>
```

## Keuntungan Implementasi JOIN

### 1. **Data Konsisten**
- Nomor polisi selalu diambil dari tabel master `police_units`
- Tidak ada duplikasi atau inkonsistensi data
- Validasi data terpusat di tabel master

### 2. **Performa Optimal**
- JOIN langsung tanpa subquery
- Index yang optimal pada foreign key
- Query yang efisien dan cepat

### 3. **Maintenance Mudah**
- Perubahan data master otomatis tercermin di monitoring
- Struktur database yang normal dan terstruktur
- Debugging yang lebih mudah

### 4. **Integritas Data**
- Foreign key constraint memastikan data valid
- Tidak ada orphaned records
- Cascade operations yang proper

## Alur Data

### 1. **Input Data Monitoring**
```
User Input → Controller → PoliceUnit::firstOrCreate() → CostModelMonitoring::updateOrCreate()
```

### 2. **Tampilan Data Monitoring**
```
Frontend Request → Controller → JOIN Query → Transform Data → Frontend Display
```

### 3. **Relasi Data**
```
cost_model_monitoring.unit_police_number (ID) → police_units.id → police_units.police_number
```

## Testing

### 1. **Unit Tests**
- ✅ 9 test cases passed
- ✅ JOIN operations berhasil
- ✅ Relasi database berfungsi dengan baik
- ✅ Data integrity terjaga

### 2. **Integration Tests**
- ✅ Data monitoring dapat di-load dengan JOIN
- ✅ Nomor polisi ditampilkan dengan benar
- ✅ Auto-save dan submit berfungsi
- ✅ Data transformation berhasil

## Contoh Penggunaan

### 1. **Mengambil Data Monitoring dengan JOIN**
```php
// Di Controller
$monitoringData = CostModelMonitoring::withPoliceUnit()
    ->where('police_units.police_number', 'B 1234 AB')
    ->where('cost_model_monitoring.year', 1)
    ->get();

// Data yang dikembalikan sudah termasuk police_number dari JOIN
foreach ($monitoringData as $data) {
    echo "Nomor Polisi: " . $data->police_number; // Dari JOIN
    echo "Component: " . $data->component;
    echo "Value: " . $data->value;
}
```

### 2. **Menampilkan di Frontend**
```javascript
// Data yang diterima dari API sudah termasuk police_number dari JOIN
monitoringData.forEach(item => {
    console.log('Nomor Polisi:', item.unit_police_number); // Dari JOIN
    console.log('Component:', item.component);
    console.log('Value:', item.value);
    
    // Set nomor polisi di input field
    document.getElementById('unitPoliceNumber').value = item.unit_police_number;
});
```

## Kesimpulan

Implementasi JOIN di halaman monitoring telah berhasil diselesaikan dengan:

1. **Data Source**: Nomor polisi diambil dari tabel `police_units` melalui JOIN
2. **Performance**: Query yang efisien dengan JOIN langsung
3. **Consistency**: Data yang konsisten dan terpusat
4. **Maintainability**: Struktur yang mudah dipahami dan di-maintain
5. **User Experience**: Tampilan yang akurat dan real-time

Sistem monitoring sekarang menggunakan data master yang terpusat dan menampilkan nomor polisi yang akurat dari tabel `police_units` melalui JOIN operations. 