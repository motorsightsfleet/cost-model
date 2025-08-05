<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cost Model Calculator - Laravel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px; }
        button:hover { background: #0056b3; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .section { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 4px; }
        .tabs { display: flex; margin-bottom: 20px; }
        .tab { padding: 10px 20px; cursor: pointer; border: 1px solid #ddd; background: #f8f9fa; }
        .tab.active { background: #007bff; color: white; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cost Model Calculator - Laravel</h1>
        
        <div id="alerts"></div>
        
        <div class="tabs">
            <div class="tab active" onclick="showTab('settings')">Settings</div>
            <div class="tab" onclick="showTab('expense')">Expense</div>
            <div class="tab" onclick="showTab('calculate')">Calculate</div>
            <div class="tab" onclick="showTab('monitoring')">Monitoring</div>
        </div>
        
        <div id="settings" class="tab-content active">
            <h2>Settings</h2>
            <div class="section">
                <h3>Actual Settings</h3>
                <div class="form-group">
                    <label>Units Price (IDR)</label>
                    <input type="number" id="unitsPrice" step="0.01">
                </div>
                <div class="form-group">
                    <label>Qty of Units</label>
                    <input type="number" id="qtyUnits">
                </div>
                <div class="form-group">
                    <label>Net Book Value (years)</label>
                    <input type="number" id="netBookValue">
                </div>
                <div class="form-group">
                    <label>Solar Price (IDR/L)</label>
                    <input type="number" id="solarPrice" step="0.01">
                </div>
                <div class="form-group">
                    <label>AdBlue Price (IDR/L)</label>
                    <input type="number" id="adbluePrice" step="0.01">
                </div>
            </div>
            
            <div class="section">
                <h3>Assumption Settings</h3>
                <div class="form-group">
                    <label>Retase per Day</label>
                    <input type="number" id="retasePerDay">
                </div>
                <div class="form-group">
                    <label>Average Ritase per Day (km)</label>
                    <input type="number" id="avgRitasePerDay" step="0.01">
                </div>
                <div class="form-group">
                    <label>Fuel Consumption (km/L)</label>
                    <input type="number" id="fuelConsumption" step="0.01">
                </div>
                <div class="form-group">
                    <label>AdBlue Consumption (km/L)</label>
                    <input type="number" id="adblueConsumption" step="0.01">
                </div>
                <div class="form-group">
                    <label>Day Operation</label>
                    <input type="number" id="dayOperation">
                </div>
            </div>
            
            <button onclick="saveSettings()">Save Settings</button>
        </div>
        
        <div id="expense" class="tab-content">
            <h2>Expense</h2>
            <div class="section">
                <h3>Actual Expenses</h3>
                <div class="form-group">
                    <label>Insurance (IDR/Year)</label>
                    <input type="number" id="insuranceUnit" step="0.01">
                </div>
                <div class="form-group">
                    <label>First Payment (IDR)</label>
                    <input type="number" id="firstPayment" step="0.01">
                </div>
                <div class="form-group">
                    <label>Leasing Payment (IDR)</label>
                    <input type="number" id="leasingPayment" step="0.01">
                </div>
                <div class="form-group">
                    <label>Vehicle Tax (IDR/Year)</label>
                    <input type="number" id="vehicleTax" step="0.01">
                </div>
                <div class="form-group">
                    <label>KIR (IDR/Year)</label>
                    <input type="number" id="kir" step="0.01">
                </div>
                <div class="form-group">
                    <label>Oil Price (IDR)</label>
                    <input type="number" id="oilPrice" step="0.01">
                </div>
            </div>
            
            <div class="section">
                <h3>Assumption Expenses</h3>
                <div class="form-group">
                    <label>Toll Cost (IDR/Day)</label>
                    <input type="number" id="tollCost" step="0.01">
                </div>
                <div class="form-group">
                    <label>Driver per Unit</label>
                    <input type="number" id="driverPerUnit">
                </div>
                <div class="form-group">
                    <label>Driver Cost (IDR/Ret)</label>
                    <input type="number" id="driverCost" step="0.01">
                </div>
                <div class="form-group">
                    <label>Tyre per Unit</label>
                    <input type="number" id="tyrePerUnit">
                </div>
                <div class="form-group">
                    <label>Downtime Percentage (%)</label>
                    <input type="number" id="downtimePercentage" step="0.01" min="0" max="100">
                </div>
            </div>
            
            <button onclick="saveExpenses()">Save Expenses</button>
        </div>
        
        <div id="calculate" class="tab-content">
            <h2>Calculate TCO</h2>
            <button onclick="calculateTCO()">Calculate</button>
            <div id="results"></div>
        </div>
        
        <div id="monitoring" class="tab-content">
            <h2>Monitoring</h2>
            <div class="form-group">
                <label>Unit Police Number</label>
                <input type="text" id="unitPoliceNumber" placeholder="e.g., B 1234 AB">
            </div>
            <div class="form-group">
                <label>Year</label>
                <select id="yearToMonitor">
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                    <option value="5">5th Year</option>
                </select>
            </div>
            <div class="form-group">
                <label>Week</label>
                <select id="weekToMonitor">
                    @for($i = 1; $i <= 52; $i++)
                        <option value="{{ $i }}">Week {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label>Service PM</label>
                <input type="number" id="servicePM" step="0.01">
            </div>
            <div class="form-group">
                <label>Service GM</label>
                <input type="number" id="serviceGM" step="0.01">
            </div>
            <div class="form-group">
                <label>Fuel Consumption</label>
                <input type="number" id="fuelConsumptionMonitoring" step="0.01">
            </div>
            <div class="form-group">
                <label>Fuel Price</label>
                <input type="number" id="fuelPrice" step="0.01">
            </div>
            <div class="form-group">
                <label>Driver Cost</label>
                <input type="number" id="driverCostMonitoring" step="0.01">
            </div>
            <div class="form-group">
                <label>Downtime Percentage</label>
                <input type="number" id="downtimePercentageMonitoring" step="0.01" min="0" max="100">
            </div>
            
            <button onclick="saveMonitoring()">Save Monitoring Data</button>
        </div>
    </div>
    
    <script>
        function showAlert(message, type = 'info') {
            const alertsDiv = document.getElementById('alerts');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;
            alertsDiv.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        function saveSettings() {
            const data = {
                units_price: parseFloat(document.getElementById('unitsPrice').value) || 0,
                qty_units: parseInt(document.getElementById('qtyUnits').value) || 0,
                net_book_value: parseInt(document.getElementById('netBookValue').value) || 0,
                solar_price: parseFloat(document.getElementById('solarPrice').value) || 0,
                adblue_price: parseFloat(document.getElementById('adbluePrice').value) || 0,
                retase_per_day: parseInt(document.getElementById('retasePerDay').value) || 0,
                avg_ritase_per_day: parseFloat(document.getElementById('avgRitasePerDay').value) || 0,
                fuel_consumption: parseFloat(document.getElementById('fuelConsumption').value) || 0,
                adblue_consumption: parseFloat(document.getElementById('adblueConsumption').value) || 0,
                day_operation: parseInt(document.getElementById('dayOperation').value) || 0,
            };

            fetch('/api/cost-model/settings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Settings berhasil disimpan!', 'success');
                    localStorage.setItem('setting_id', data.data.id);
                } else {
                    showAlert('Gagal menyimpan settings!', 'danger');
                }
            })
            .catch(error => {
                showAlert('Error: ' + error.message, 'danger');
            });
        }

        function saveExpenses() {
            const data = {
                insurance_unit: parseFloat(document.getElementById('insuranceUnit').value) || 0,
                first_payment: parseFloat(document.getElementById('firstPayment').value) || 0,
                leasing_payment: parseFloat(document.getElementById('leasingPayment').value) || 0,
                vehicle_tax: parseFloat(document.getElementById('vehicleTax').value) || 0,
                kir: parseFloat(document.getElementById('kir').value) || 0,
                oil_price: parseFloat(document.getElementById('oilPrice').value) || 0,
                toll_cost: parseFloat(document.getElementById('tollCost').value) || 0,
                driver_per_unit: parseInt(document.getElementById('driverPerUnit').value) || 0,
                driver_cost: parseFloat(document.getElementById('driverCost').value) || 0,
                tyre_per_unit: parseInt(document.getElementById('tyrePerUnit').value) || 0,
                downtime_percentage: parseFloat(document.getElementById('downtimePercentage').value) || 0,
                // Default values untuk field yang diperlukan
                telematics_one_time_cost: 0,
                telematics_recurring_cost: 0,
                tire_price: 0,
                lifetime_tyre: 0,
                pm_year_1: 0, pm_year_2: 0, pm_year_3: 0, pm_year_4: 0, pm_year_5: 0,
                pm_year_6: 0, pm_year_7: 0, pm_year_8: 0, pm_year_9: 0, pm_year_10: 0,
                gm_year_1: 0, gm_year_2: 0, gm_year_3: 0, gm_year_4: 0, gm_year_5: 0,
                gm_year_6: 0, gm_year_7: 0, gm_year_8: 0, gm_year_9: 0, gm_year_10: 0,
            };

            fetch('/api/cost-model/expenses', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Expenses berhasil disimpan!', 'success');
                    localStorage.setItem('expense_id', data.data.id);
                } else {
                    showAlert('Gagal menyimpan expenses!', 'danger');
                }
            })
            .catch(error => {
                showAlert('Error: ' + error.message, 'danger');
            });
        }

        function calculateTCO() {
            const settingId = localStorage.getItem('setting_id');
            const expenseId = localStorage.getItem('expense_id');

            if (!settingId || !expenseId) {
                showAlert('Harap simpan settings dan expenses terlebih dahulu!', 'danger');
                return;
            }

            const data = {
                setting_id: settingId,
                expense_id: expenseId
            };

            fetch('/api/cost-model/calculate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Perhitungan berhasil!', 'success');
                    displayResults(data.data);
                } else {
                    showAlert('Gagal melakukan perhitungan!', 'danger');
                }
            })
            .catch(error => {
                showAlert('Error: ' + error.message, 'danger');
            });
        }

        function displayResults(calculation) {
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = `
                <div class="section">
                    <h3>Hasil Perhitungan</h3>
                    <p><strong>Unit Down Payment:</strong> ${calculation.unit_down_payment?.toLocaleString('id-ID')} IDR</p>
                    <p><strong>Financing:</strong> ${calculation.financing?.toLocaleString('id-ID')} IDR</p>
                    <p><strong>Average Ret per Month:</strong> ${calculation.avg_ret_per_month?.toLocaleString('id-ID')} Km</p>
                    <p><strong>Average Ret per Year:</strong> ${calculation.avg_ret_per_year?.toLocaleString('id-ID')} Km</p>
                    <p><strong>Solar per Year:</strong> ${calculation.solar_per_year?.toLocaleString('id-ID')} IDR</p>
                    <p><strong>Driver Cost per Year:</strong> ${calculation.driver_cost_per_year?.toLocaleString('id-ID')} IDR</p>
                    <p><strong>Total Cost non Units:</strong> ${calculation.total_cost_non_units?.toLocaleString('id-ID')} IDR</p>
                    <p><strong>Downtime Cost Estimate:</strong> ${calculation.downtime_cost_estimate?.toLocaleString('id-ID')} IDR</p>
                </div>
            `;
        }

        function saveMonitoring() {
            const data = {
                unit_police_number: document.getElementById('unitPoliceNumber').value,
                year: parseInt(document.getElementById('yearToMonitor').value),
                week: parseInt(document.getElementById('weekToMonitor').value),
                service_pm: parseFloat(document.getElementById('servicePM').value) || 0,
                service_gm: parseFloat(document.getElementById('serviceGM').value) || 0,
                fuel_consumption: parseFloat(document.getElementById('fuelConsumptionMonitoring').value) || 0,
                fuel_price: parseFloat(document.getElementById('fuelPrice').value) || 0,
                driver_cost: parseFloat(document.getElementById('driverCostMonitoring').value) || 0,
                downtime_percentage: parseFloat(document.getElementById('downtimePercentageMonitoring').value) || 0,
            };

            fetch('/api/cost-model/monitoring', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Data monitoring berhasil disimpan!', 'success');
                } else {
                    showAlert('Gagal menyimpan data monitoring!', 'danger');
                }
            })
            .catch(error => {
                showAlert('Error: ' + error.message, 'danger');
            });
        }

        function showTab(tabId) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            document.querySelector(`[onclick="showTab('${tabId}')"]`).classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }
    </script>
</body>
</html> 