# Struktur Project Cost Model Calculator Laravel

```
cost-model-laravel/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── CostModelController.php          # Controller utama untuk Cost Model
│   └── Models/
│       ├── CostModelSetting.php                 # Model untuk settings
│       ├── CostModelExpense.php                 # Model untuk expenses
│       ├── CostModelCalculation.php             # Model untuk calculations
│       └── CostModelMonitoring.php              # Model untuk monitoring
├── database/
│   └── migrations/
│       ├── 2025_08_04_022420_create_cost_model_settings_table.php
│       ├── 2025_08_04_022424_create_cost_model_expenses_table.php
│       ├── 2025_08_04_022427_create_cost_model_calculations_table.php
│       └── 2025_08_04_022432_create_cost_model_monitoring_table.php
├── resources/
│   └── views/
│       ├── cost-model/
│       │   └── index.blade.php                  # Halaman utama Cost Model Calculator
│       └── test-api.blade.php                   # Halaman test API
├── routes/
│   └── web.php                                  # Route definitions
├── .env                                         # Environment configuration
├── README.md                                    # Dokumentasi utama
├── PROJECT_STRUCTURE.md                         # File ini
└── test_api.html                                # File test API standalone
```

## Penjelasan Komponen

### 1. Models (app/Models/)
- **CostModelSetting**: Menangani data pengaturan dasar seperti harga unit, konsumsi BBM, dll
- **CostModelExpense**: Menangani data biaya-biaya seperti asuransi, pajak, maintenance
- **CostModelCalculation**: Menyimpan hasil perhitungan TCO
- **CostModelMonitoring**: Menyimpan data monitoring per minggu

### 2. Controller (app/Http/Controllers/)
- **CostModelController**: Controller utama yang menangani semua operasi CRUD dan perhitungan

### 3. Migrations (database/migrations/)
- **create_cost_model_settings_table**: Tabel untuk menyimpan settings
- **create_cost_model_expenses_table**: Tabel untuk menyimpan expenses
- **create_cost_model_calculations_table**: Tabel untuk menyimpan hasil perhitungan
- **create_cost_model_monitoring_table**: Tabel untuk menyimpan data monitoring

### 4. Views (resources/views/)
- **cost-model/index.blade.php**: Interface utama untuk Cost Model Calculator
- **test-api.blade.php**: Interface untuk testing API endpoints

### 5. Routes (routes/web.php)
- Route untuk halaman utama: `/cost-model`
- Route untuk test API: `/test-api`
- API endpoints: `/api/cost-model/*`

## Database Schema

### cost_model_settings
```sql
- id (primary key)
- units_price (decimal)
- qty_units (integer)
- net_book_value (integer)
- solar_price (decimal)
- adblue_price (decimal)
- retase_per_day (integer)
- avg_ritase_per_day (decimal)
- fuel_consumption (decimal)
- adblue_consumption (decimal)
- day_operation (integer)
- created_at, updated_at
```

### cost_model_expenses
```sql
- id (primary key)
- insurance_unit (decimal)
- first_payment (decimal)
- leasing_payment (decimal)
- vehicle_tax (decimal)
- kir (decimal)
- oil_price (decimal)
- toll_cost (decimal)
- driver_per_unit (integer)
- driver_cost (decimal)
- tyre_per_unit (integer)
- downtime_percentage (decimal)
- pm_year_1 sampai pm_year_10 (decimal)
- gm_year_1 sampai gm_year_10 (decimal)
- created_at, updated_at
```

### cost_model_calculations
```sql
- id (primary key)
- setting_id (foreign key)
- expense_id (foreign key)
- unit_down_payment (decimal)
- financing (decimal)
- avg_ret_per_month (decimal)
- avg_ret_per_year (decimal)
- solar_per_year (decimal)
- driver_cost_per_year (decimal)
- total_cost_non_units (decimal)
- downtime_cost_estimate (decimal)
- yearly_breakdown (json)
- created_at, updated_at
```

### cost_model_monitoring
```sql
- id (primary key)
- unit_police_number (string)
- year (integer)
- week (integer)
- service_pm (decimal)
- service_gm (decimal)
- fuel_consumption (decimal)
- fuel_price (decimal)
- driver_cost (decimal)
- downtime_percentage (decimal)
- created_at, updated_at
```

## API Endpoints

### Settings
- `POST /api/cost-model/settings` - Simpan settings
- `GET /api/cost-model/settings` - Ambil data settings

### Expenses
- `POST /api/cost-model/expenses` - Simpan expenses
- `GET /api/cost-model/expenses` - Ambil data expenses

### Calculations
- `POST /api/cost-model/calculate` - Hitung TCO
- `GET /api/cost-model/calculations` - Ambil data calculations

### Monitoring
- `POST /api/cost-model/monitoring` - Simpan data monitoring
- `GET /api/cost-model/monitoring` - Ambil data monitoring

## Cara Menjalankan

1. **Setup environment**
```bash
cp .env.example .env
# Edit .env dengan konfigurasi database PostgreSQL
```

2. **Install dependencies**
```bash
composer install
```

3. **Generate key**
```bash
php artisan key:generate
```

4. **Jalankan migration**
```bash
php artisan migrate
```

5. **Jalankan server**
```bash
php artisan serve
```

6. **Akses aplikasi**
- Main app: `http://localhost:8000/cost-model`
- Test API: `http://localhost:8000/test-api`

## Fitur Utama

1. **Settings Management**: Input dan simpan pengaturan dasar
2. **Expense Management**: Input dan simpan data biaya-biaya
3. **TCO Calculation**: Perhitungan Total Cost of Ownership
4. **Monitoring**: Sistem monitoring per minggu
5. **API Testing**: Interface untuk testing API endpoints

## Teknologi yang Digunakan

- **Backend**: Laravel 12.x
- **Database**: PostgreSQL
- **Frontend**: HTML, CSS, JavaScript (Vanilla)
- **API**: RESTful API dengan JSON response 