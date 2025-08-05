# Cost Model API Documentation

## Overview
API ini menyediakan endpoint untuk mengelola data cost model calculator dengan fitur insert atau update (upsert) untuk semua inputan.

## Base URL
```
/api/cost-model
```

## Authentication
Semua endpoint memerlukan CSRF token yang dapat ditemukan di meta tag HTML:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

## Endpoints

### 1. Upsert All Data
**POST** `/api/cost-model/upsert-all`

Menyimpan atau mengupdate semua data settings dan expenses dalam satu request. Hanya menyimpan satu record (menggunakan truncate).

**Request Body:**
```json
{
    // Settings - Actual
    "units_price": 100000000,
    "qty_units": 10,
    "net_book_value": 5,
    "solar_price": 10000,
    "adblue_price": 15000,
    
    // Settings - Assumption
    "retase_per_day": 3,
    "avg_ritase_per_day": 150.5,
    "fuel_consumption": 8.5,
    "adblue_consumption": 1000,
    "day_operation": 25,
    
    // Expenses - Actual
    "insurance_unit": 5000000,
    "first_payment": 30000000,
    "leasing_payment": 5000000,
    "vehicle_tax": 2000000,
    "kir": 500000,
    "telematics_one_time_cost": 1000000,
    "telematics_recurring_cost": 50000,
    "tire_price": 2000000,
    "lifetime_tyre": 50000,
    "oil_price": 50000,
    
    // PM Costs (10 tahun)
    "pm_year_1": 5000000,
    "pm_year_2": 4000000,
    "pm_year_3": 3500000,
    "pm_year_4": 3000000,
    "pm_year_5": 2500000,
    "pm_year_6": 2000000,
    "pm_year_7": 1500000,
    "pm_year_8": 1000000,
    "pm_year_9": 800000,
    "pm_year_10": 500000,
    
    // GM Costs (10 tahun)
    "gm_year_1": 3000000,
    "gm_year_2": 2500000,
    "gm_year_3": 2000000,
    "gm_year_4": 1800000,
    "gm_year_5": 1500000,
    "gm_year_6": 1200000,
    "gm_year_7": 1000000,
    "gm_year_8": 800000,
    "gm_year_9": 600000,
    "gm_year_10": 400000,
    
    // Expenses - Assumption
    "toll_cost": 50000,
    "driver_per_unit": 2,
    "driver_cost": 100000,
    "tyre_per_unit": 6,
    "downtime_percentage": 1.5
}
```

**Response:**
```json
{
    "success": true,
    "message": "Data berhasil disimpan/ diperbarui",
    "data": {
        "setting": { ... },
        "expense": { ... },
        "calculation": { ... }
    }
}
```

### 2. Get Stored Data
**GET** `/api/cost-model/stored-data`

Mengambil data settings dan expenses yang tersimpan.

**Response:**
```json
{
    "success": true,
    "data": {
        "setting": { ... },
        "expense": { ... },
        "calculation": { ... }
    }
}
```

### 3. Upsert Monitoring Data
**POST** `/api/cost-model/upsert-monitoring`

Menyimpan atau mengupdate data monitoring untuk komponen tertentu.

**Request Body:**
```json
{
    "unit_police_number": "B 1234 AB",
    "year": 1,
    "week": 1,
    "component": "Service_Berkala/PM",
    "value": 500000,
    "note": "Catatan untuk monitoring"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Data monitoring berhasil disimpan/diperbarui",
    "data": { ... }
}
```

### 4. Upsert Existing Monitoring Data
**POST** `/api/cost-model/upsert-existing-monitoring`

Menyimpan atau mengupdate data existing monitoring dengan prefix 'existing_'.

**Request Body:**
```json
{
    "unit_police_number": "B 1234 AB",
    "year": 1,
    "week": 1,
    "component": "Service_Berkala/PM",
    "value": 500000,
    "note": "Catatan untuk existing monitoring"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Data existing monitoring berhasil disimpan/diperbarui",
    "data": { ... }
}
```

### 5. Get Monitoring Data
**GET** `/api/cost-model/monitoring-data`

Mengambil data monitoring berdasarkan filter.

**Query Parameters:**
- `unit_police_number` (optional): Nomor polisi unit
- `year` (optional): Tahun monitoring
- `week` (optional): Minggu (1-52)
- `component` (optional): Nama komponen

**Example:**
```
GET /api/cost-model/monitoring-data?year=1&unit_police_number=B 1234 AB
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "unit_police_number": "B 1234 AB",
            "year": 1,
            "week": 1,
            "component": "Service_Berkala/PM",
            "value": 500000,
            "note": "Catatan",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

### 6. Calculate
**POST** `/api/cost-model/calculate`

Melakukan perhitungan cost model berdasarkan data settings dan expenses yang tersimpan.

**Response:**
```json
{
    "success": true,
    "message": "Perhitungan berhasil",
    "data": {
        "id": 1,
        "setting_id": 1,
        "expense_id": 1,
        "unit_down_payment": 30000000,
        "financing": 70000000,
        "leasing_payment_yearly": 60000000,
        "avg_ret_per_month": 3750,
        "avg_ret_per_year": 45000,
        "fuel_consumption_per_ret": 17.65,
        "fuel_consumption_per_month": 441.25,
        "fuel_consumption_per_year": 5295,
        "solar_per_year": 52950000,
        "adblue_consumption_per_day": 2257.5,
        "adblue_consumption_per_month": 56437.5,
        "adblue_consumption_per_year": 677250,
        "driver_cost_per_month": 3750000,
        "driver_cost_per_year": 45000000,
        "cost_per_unit": 12000000,
        "idr_per_km": 40,
        "idr_per_km_unit": 240,
        "cost_days": 36000,
        "cost_month": 900000,
        "cost_year": 10800000,
        "telematics_cost_per_month": 50000,
        "telematics_cost_first_year": 1600000,
        "telematics_cost_subsequent_years": 600000,
        "total_cost_non_units": 1234567890,
        "downtime_cost_estimate": 18518518.35,
        "yearly_breakdown": { ... },
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

## Database Schema

### Cost Model Settings
```sql
CREATE TABLE cost_model_settings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    units_price DECIMAL(15,2) DEFAULT 0,
    qty_units INT DEFAULT 0,
    net_book_value INT DEFAULT 0,
    solar_price DECIMAL(15,2) DEFAULT 0,
    adblue_price DECIMAL(15,2) DEFAULT 0,
    retase_per_day INT DEFAULT 0,
    avg_ritase_per_day DECIMAL(10,2) DEFAULT 0,
    fuel_consumption DECIMAL(10,2) DEFAULT 0,
    adblue_consumption DECIMAL(10,2) DEFAULT 0,
    day_operation INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Cost Model Expenses
```sql
CREATE TABLE cost_model_expenses (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    insurance_unit DECIMAL(15,2) DEFAULT 0,
    first_payment DECIMAL(15,2) DEFAULT 0,
    leasing_payment DECIMAL(15,2) DEFAULT 0,
    vehicle_tax DECIMAL(15,2) DEFAULT 0,
    kir DECIMAL(15,2) DEFAULT 0,
    telematics_one_time_cost DECIMAL(15,2) DEFAULT 0,
    telematics_recurring_cost DECIMAL(15,2) DEFAULT 0,
    tire_price DECIMAL(15,2) DEFAULT 0,
    lifetime_tyre DECIMAL(15,2) DEFAULT 0,
    oil_price DECIMAL(15,2) DEFAULT 0,
    pm_year_1 DECIMAL(15,2) DEFAULT 0,
    pm_year_2 DECIMAL(15,2) DEFAULT 0,
    pm_year_3 DECIMAL(15,2) DEFAULT 0,
    pm_year_4 DECIMAL(15,2) DEFAULT 0,
    pm_year_5 DECIMAL(15,2) DEFAULT 0,
    pm_year_6 DECIMAL(15,2) DEFAULT 0,
    pm_year_7 DECIMAL(15,2) DEFAULT 0,
    pm_year_8 DECIMAL(15,2) DEFAULT 0,
    pm_year_9 DECIMAL(15,2) DEFAULT 0,
    pm_year_10 DECIMAL(15,2) DEFAULT 0,
    gm_year_1 DECIMAL(15,2) DEFAULT 0,
    gm_year_2 DECIMAL(15,2) DEFAULT 0,
    gm_year_3 DECIMAL(15,2) DEFAULT 0,
    gm_year_4 DECIMAL(15,2) DEFAULT 0,
    gm_year_5 DECIMAL(15,2) DEFAULT 0,
    gm_year_6 DECIMAL(15,2) DEFAULT 0,
    gm_year_7 DECIMAL(15,2) DEFAULT 0,
    gm_year_8 DECIMAL(15,2) DEFAULT 0,
    gm_year_9 DECIMAL(15,2) DEFAULT 0,
    gm_year_10 DECIMAL(15,2) DEFAULT 0,
    toll_cost DECIMAL(15,2) DEFAULT 0,
    driver_per_unit INT DEFAULT 0,
    driver_cost DECIMAL(15,2) DEFAULT 0,
    tyre_per_unit INT DEFAULT 0,
    downtime_percentage DECIMAL(5,2) DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Cost Model Monitoring
```sql
CREATE TABLE cost_model_monitoring (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    unit_police_number VARCHAR(255) NULL,
    year INT DEFAULT 1,
    week INT DEFAULT 1,
    component VARCHAR(255),
    value DECIMAL(15,2) DEFAULT 0,
    note TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_unit_year_week (unit_police_number, year, week),
    INDEX idx_component (component)
);
```

## JavaScript Integration

File `cost-model-api.js` menyediakan class `CostModelAPI` untuk integrasi dengan frontend:

```javascript
// Initialize API
const costModelAPI = new CostModelAPI();

// Save all data
const formData = costModelAPI.collectFormData();
await costModelAPI.upsertAllData(formData);

// Load stored data
const storedData = await costModelAPI.getStoredData();
costModelAPI.populateFormWithStoredData(storedData);

// Save monitoring data
await costModelAPI.saveMonitoringData(year, week, component, value, note);

// Load monitoring data
await costModelAPI.loadMonitoringData(year, unitPoliceNumber);
```

## Error Handling

Semua endpoint mengembalikan response dengan format:
```json
{
    "success": false,
    "message": "Pesan error yang detail"
}
```

## Validation Rules

- Semua field numeric harus >= 0
- `downtime_percentage` harus antara 0-100
- `year` harus antara 1-10
- `week` harus antara 1-52
- Field yang tidak diisi akan diset ke 0 