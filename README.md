# Cost Model Calculator

Aplikasi web untuk menghitung Total Cost of Ownership (TCO) kendaraan dengan fitur monitoring dan dashboard yang komprehensif.

## Fitur

- **Settings Management**: Pengaturan parameter dasar seperti harga unit, konsumsi BBM, dll
- **Expense Tracking**: Pelacakan biaya operasional termasuk maintenance, asuransi, pajak
- **Dashboard**: Visualisasi data dalam bentuk tabel dengan breakdown per tahun
- **Monitoring**: Sistem monitoring real-time untuk tracking biaya aktual
- **API Integration**: RESTful API untuk integrasi dengan sistem lain
- **Data Persistence**: Penyimpanan data ke database SQLite

## Teknologi

- **Backend**: Laravel 10
- **Database**: SQLite
- **Frontend**: HTML, CSS, JavaScript (Vanilla)
- **API**: RESTful API dengan JSON response

## Instalasi

### Prerequisites
- PHP 8.1 atau lebih tinggi
- Composer
- SQLite

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd cost-model-laravel
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup database**
   ```bash
   php artisan migrate:fresh
   ```

5. **Start server**
   ```bash
   php artisan serve
   ```

6. **Akses aplikasi**
   ```
   http://localhost:8000
   ```

## Struktur Database

### Tabel Settings (`cost_model_settings`)
- Parameter dasar seperti harga unit, konsumsi BBM, dll
- Dibagi menjadi 2 kategori: Actual dan Assumption

### Tabel Expenses (`cost_model_expenses`)
- Biaya operasional termasuk maintenance, asuransi, pajak
- PM (Preventive Maintenance) dan GM (General Maintenance) untuk 10 tahun
- Parameter assumption untuk perhitungan

### Tabel Calculations (`cost_model_calculations`)
- Hasil perhitungan TCO
- Breakdown per tahun
- Relasi dengan settings dan expenses

### Tabel Monitoring (`cost_model_monitoring`)
- Data monitoring real-time
- Tracking per komponen, tahun, dan minggu
- Support untuk catatan per entry

## API Endpoints

### Main Endpoints
- `POST /api/cost-model/store-all` - Simpan semua data
- `GET /api/cost-model/all-data` - Ambil semua data
- `POST /api/cost-model/monitoring-data` - Simpan data monitoring

### Individual Endpoints
- `POST /api/cost-model/settings` - Simpan settings
- `GET /api/cost-model/settings` - Ambil settings
- `POST /api/cost-model/expenses` - Simpan expenses
- `GET /api/cost-model/expenses` - Ambil expenses
- `POST /api/cost-model/calculate` - Hitung TCO
- `GET /api/cost-model/calculations` - Ambil calculations
- `POST /api/cost-model/monitoring` - Simpan monitoring
- `GET /api/cost-model/monitoring` - Ambil monitoring

## Penggunaan

### 1. Settings Tab
- Isi parameter Actual (harga unit, BBM, AdBlue)
- Isi parameter Assumption (retase, konsumsi, hari operasi)
- Data akan disimpan saat klik "Save to Database"

### 2. Expense Tab
- Isi biaya Actual (asuransi, pajak, leasing, dll)
- Isi biaya PM dan GM untuk 10 tahun
- Isi parameter Assumption (tol, driver, ban, downtime)
- Klik "Calculate" untuk menghitung TCO
- Klik "Save to Database" untuk menyimpan

### 3. Dashboard Tab
- Pilih jumlah tahun yang ingin ditampilkan (5-10 tahun)
- Tabel akan menampilkan breakdown biaya per tahun
- Total per tahun dan grand total otomatis dihitung

### 4. Monitoring Tab
- Pilih tahun yang ingin dimonitor
- Isi nomor polisi unit
- Input data aktual per minggu untuk setiap komponen
- Data dapat disimpan dengan API

## JavaScript Integration

### Include API Script
```html
<script src="/js/cost-model-api.js"></script>
```

### Save Data
```javascript
async function saveAllData() {
    try {
        const result = await costModelAPI.saveAllData();
        console.log('Data berhasil disimpan:', result);
    } catch (error) {
        console.error('Error saving data:', error);
    }
}
```

### Load Data
```javascript
async function loadDataFromDatabase() {
    try {
        await costModelAPI.loadDataToForm();
        calculateTCO();
    } catch (error) {
        console.error('Error loading data:', error);
    }
}
```

## Testing

### Manual Testing
1. Akses `http://localhost:8000` untuk aplikasi utama
2. Akses `http://localhost:8000/test-api.html` untuk test API

### API Testing
File `test_api.html` menyediakan interface untuk menguji semua endpoint API:
- Test Store All Data
- Test Get All Data  
- Test Store Monitoring Data

## Dokumentasi API

Lihat file `API_DOCUMENTATION.md` untuk dokumentasi lengkap API.

## Struktur File

```
cost-model-laravel/
├── app/
│   ├── Http/Controllers/
│   │   └── CostModelController.php
│   └── Models/
│       ├── CostModelSetting.php
│       ├── CostModelExpense.php
│       ├── CostModelCalculation.php
│       └── CostModelMonitoring.php
├── database/migrations/
│   ├── create_cost_model_settings_table.php
│   ├── create_cost_model_expenses_table.php
│   ├── create_cost_model_calculations_table.php
│   └── create_cost_model_monitoring_table.php
├── public/js/
│   └── cost-model-api.js
├── resources/views/
│   └── index.blade.php
├── routes/
│   └── web.php
├── test_api.html
├── API_DOCUMENTATION.md
└── README.md
```

## Perhitungan TCO

Aplikasi menghitung Total Cost of Ownership dengan komponen:

1. **Unit Payment**
   - Down payment (30%)
   - Financing (70%)
   - Leasing payment (3 tahun)

2. **Operational Costs**
   - Fuel consumption
   - AdBlue consumption
   - Driver cost
   - Toll cost

3. **Maintenance Costs**
   - Preventive Maintenance (PM)
   - General Maintenance (GM)
   - Tire management

4. **Fixed Costs**
   - Vehicle tax
   - Insurance
   - KIR
   - Telematics module

5. **Downtime Cost**
   - Percentage-based calculation

## Contributing

1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## License

This project is licensed under the MIT License.

## Support

Untuk pertanyaan atau dukungan, silakan buat issue di repository atau hubungi tim development.
