<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>API Test - Cost Model Calculator</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section { margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 4px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px; }
        button:hover { background: #0056b3; }
        .response { margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h1>API Test - Cost Model Calculator</h1>
        
        <div class="section">
            <h2>1. Test Settings API</h2>
            <div class="form-group">
                <label>Units Price (IDR)</label>
                <input type="number" id="testUnitsPrice" value="500000000" step="0.01">
            </div>
            <div class="form-group">
                <label>Qty of Units</label>
                <input type="number" id="testQtyUnits" value="1">
            </div>
            <div class="form-group">
                <label>Net Book Value (years)</label>
                <input type="number" id="testNetBookValue" value="5">
            </div>
            <div class="form-group">
                <label>Solar Price (IDR/L)</label>
                <input type="number" id="testSolarPrice" value="15000" step="0.01">
            </div>
            <div class="form-group">
                <label>AdBlue Price (IDR/L)</label>
                <input type="number" id="testAdbluePrice" value="25000" step="0.01">
            </div>
            <div class="form-group">
                <label>Retase per Day</label>
                <input type="number" id="testRetasePerDay" value="2">
            </div>
            <div class="form-group">
                <label>Average Ritase per Day (km)</label>
                <input type="number" id="testAvgRitasePerDay" value="150" step="0.01">
            </div>
            <div class="form-group">
                <label>Fuel Consumption (km/L)</label>
                <input type="number" id="testFuelConsumption" value="8" step="0.01">
            </div>
            <div class="form-group">
                <label>AdBlue Consumption (km/L)</label>
                <input type="number" id="testAdblueConsumption" value="100" step="0.01">
            </div>
            <div class="form-group">
                <label>Day Operation</label>
                <input type="number" id="testDayOperation" value="25">
            </div>
            <button onclick="testSettingsAPI()">Test Settings API</button>
            <div id="settingsResponse" class="response"></div>
        </div>
        
        <div class="section">
            <h2>2. Test Expenses API</h2>
            <div class="form-group">
                <label>Insurance (IDR/Year)</label>
                <input type="number" id="testInsuranceUnit" value="5000000" step="0.01">
            </div>
            <div class="form-group">
                <label>First Payment (IDR)</label>
                <input type="number" id="testFirstPayment" value="150000000" step="0.01">
            </div>
            <div class="form-group">
                <label>Leasing Payment (IDR)</label>
                <input type="number" id="testLeasingPayment" value="15000000" step="0.01">
            </div>
            <div class="form-group">
                <label>Vehicle Tax (IDR/Year)</label>
                <input type="number" id="testVehicleTax" value="2000000" step="0.01">
            </div>
            <div class="form-group">
                <label>KIR (IDR/Year)</label>
                <input type="number" id="testKir" value="500000" step="0.01">
            </div>
            <div class="form-group">
                <label>Oil Price (IDR)</label>
                <input type="number" id="testOilPrice" value="50000" step="0.01">
            </div>
            <div class="form-group">
                <label>Toll Cost (IDR/Day)</label>
                <input type="number" id="testTollCost" value="50000" step="0.01">
            </div>
            <div class="form-group">
                <label>Driver per Unit</label>
                <input type="number" id="testDriverPerUnit" value="1">
            </div>
            <div class="form-group">
                <label>Driver Cost (IDR/Ret)</label>
                <input type="number" id="testDriverCost" value="50000" step="0.01">
            </div>
            <div class="form-group">
                <label>Tyre per Unit</label>
                <input type="number" id="testTyrePerUnit" value="6">
            </div>
            <div class="form-group">
                <label>Downtime Percentage (%)</label>
                <input type="number" id="testDowntimePercentage" value="1" step="0.01" min="0" max="100">
            </div>
            <button onclick="testExpensesAPI()">Test Expenses API</button>
            <div id="expensesResponse" class="response"></div>
        </div>
        
        <div class="section">
            <h2>3. Test Calculate API</h2>
            <div class="form-group">
                <label>Setting ID</label>
                <input type="number" id="testSettingId" placeholder="Masukkan Setting ID dari response sebelumnya">
            </div>
            <div class="form-group">
                <label>Expense ID</label>
                <input type="number" id="testExpenseId" placeholder="Masukkan Expense ID dari response sebelumnya">
            </div>
            <button onclick="testCalculateAPI()">Test Calculate API</button>
            <div id="calculateResponse" class="response"></div>
        </div>
        
        <div class="section">
            <h2>4. Test Monitoring API</h2>
            <div class="form-group">
                <label>Unit Police Number</label>
                <input type="text" id="testUnitPoliceNumber" value="B 1234 AB">
            </div>
            <div class="form-group">
                <label>Year</label>
                <select id="testYear">
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                    <option value="5">5th Year</option>
                </select>
            </div>
            <div class="form-group">
                <label>Week</label>
                <select id="testWeek">
                    <option value="1">Week 1</option>
                    <option value="2">Week 2</option>
                    <option value="3">Week 3</option>
                    <option value="4">Week 4</option>
                </select>
            </div>
            <div class="form-group">
                <label>Service PM</label>
                <input type="number" id="testServicePM" value="5000000" step="0.01">
            </div>
            <div class="form-group">
                <label>Service GM</label>
                <input type="number" id="testServiceGM" value="2000000" step="0.01">
            </div>
            <div class="form-group">
                <label>Fuel Consumption</label>
                <input type="number" id="testFuelConsumptionMonitoring" value="100" step="0.01">
            </div>
            <div class="form-group">
                <label>Fuel Price</label>
                <input type="number" id="testFuelPrice" value="15000" step="0.01">
            </div>
            <div class="form-group">
                <label>Driver Cost</label>
                <input type="number" id="testDriverCostMonitoring" value="50000" step="0.01">
            </div>
            <div class="form-group">
                <label>Downtime Percentage</label>
                <input type="number" id="testDowntimePercentageMonitoring" value="1" step="0.01" min="0" max="100">
            </div>
            <button onclick="testMonitoringAPI()">Test Monitoring API</button>
            <div id="monitoringResponse" class="response"></div>
        </div>
        
        <div class="section">
            <h2>5. Test Get Data APIs</h2>
            <button onclick="testGetSettings()">Get Settings</button>
            <button onclick="testGetExpenses()">Get Expenses</button>
            <button onclick="testGetCalculations()">Get Calculations</button>
            <button onclick="testGetMonitoring()">Get Monitoring</button>
            <div id="getDataResponse" class="response"></div>
        </div>
    </div>
    
    <script>
        function showResponse(elementId, data, isSuccess = true) {
            const element = document.getElementById(elementId);
            element.className = `response ${isSuccess ? 'success' : 'error'}`;
            element.innerHTML = `<strong>Response:</strong><br><pre>${JSON.stringify(data, null, 2)}</pre>`;
        }
        
        function testSettingsAPI() {
            const data = {
                units_price: parseFloat(document.getElementById('testUnitsPrice').value) || 0,
                qty_units: parseInt(document.getElementById('testQtyUnits').value) || 0,
                net_book_value: parseInt(document.getElementById('testNetBookValue').value) || 0,
                solar_price: parseFloat(document.getElementById('testSolarPrice').value) || 0,
                adblue_price: parseFloat(document.getElementById('testAdbluePrice').value) || 0,
                retase_per_day: parseInt(document.getElementById('testRetasePerDay').value) || 0,
                avg_ritase_per_day: parseFloat(document.getElementById('testAvgRitasePerDay').value) || 0,
                fuel_consumption: parseFloat(document.getElementById('testFuelConsumption').value) || 0,
                adblue_consumption: parseFloat(document.getElementById('testAdblueConsumption').value) || 0,
                day_operation: parseInt(document.getElementById('testDayOperation').value) || 0,
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
                showResponse('settingsResponse', data, data.success);
                if (data.success && data.data) {
                    document.getElementById('testSettingId').value = data.data.id;
                }
            })
            .catch(error => {
                showResponse('settingsResponse', { error: error.message }, false);
            });
        }
        
        function testExpensesAPI() {
            const data = {
                insurance_unit: parseFloat(document.getElementById('testInsuranceUnit').value) || 0,
                first_payment: parseFloat(document.getElementById('testFirstPayment').value) || 0,
                leasing_payment: parseFloat(document.getElementById('testLeasingPayment').value) || 0,
                vehicle_tax: parseFloat(document.getElementById('testVehicleTax').value) || 0,
                kir: parseFloat(document.getElementById('testKir').value) || 0,
                oil_price: parseFloat(document.getElementById('testOilPrice').value) || 0,
                toll_cost: parseFloat(document.getElementById('testTollCost').value) || 0,
                driver_per_unit: parseInt(document.getElementById('testDriverPerUnit').value) || 0,
                driver_cost: parseFloat(document.getElementById('testDriverCost').value) || 0,
                tyre_per_unit: parseInt(document.getElementById('testTyrePerUnit').value) || 0,
                downtime_percentage: parseFloat(document.getElementById('testDowntimePercentage').value) || 0,
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
                showResponse('expensesResponse', data, data.success);
                if (data.success && data.data) {
                    document.getElementById('testExpenseId').value = data.data.id;
                }
            })
            .catch(error => {
                showResponse('expensesResponse', { error: error.message }, false);
            });
        }
        
        function testCalculateAPI() {
            const settingId = document.getElementById('testSettingId').value;
            const expenseId = document.getElementById('testExpenseId').value;
            
            if (!settingId || !expenseId) {
                showResponse('calculateResponse', { error: 'Setting ID dan Expense ID harus diisi' }, false);
                return;
            }

            const data = {
                setting_id: parseInt(settingId),
                expense_id: parseInt(expenseId)
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
                showResponse('calculateResponse', data, data.success);
            })
            .catch(error => {
                showResponse('calculateResponse', { error: error.message }, false);
            });
        }
        
        function testMonitoringAPI() {
            const data = {
                unit_police_number: document.getElementById('testUnitPoliceNumber').value,
                year: parseInt(document.getElementById('testYear').value),
                week: parseInt(document.getElementById('testWeek').value),
                service_pm: parseFloat(document.getElementById('testServicePM').value) || 0,
                service_gm: parseFloat(document.getElementById('testServiceGM').value) || 0,
                fuel_consumption: parseFloat(document.getElementById('testFuelConsumptionMonitoring').value) || 0,
                fuel_price: parseFloat(document.getElementById('testFuelPrice').value) || 0,
                driver_cost: parseFloat(document.getElementById('testDriverCostMonitoring').value) || 0,
                downtime_percentage: parseFloat(document.getElementById('testDowntimePercentageMonitoring').value) || 0,
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
                showResponse('monitoringResponse', data, data.success);
            })
            .catch(error => {
                showResponse('monitoringResponse', { error: error.message }, false);
            });
        }
        
        function testGetSettings() {
            fetch('/api/cost-model/settings')
            .then(response => response.json())
            .then(data => {
                showResponse('getDataResponse', data, data.success);
            })
            .catch(error => {
                showResponse('getDataResponse', { error: error.message }, false);
            });
        }
        
        function testGetExpenses() {
            fetch('/api/cost-model/expenses')
            .then(response => response.json())
            .then(data => {
                showResponse('getDataResponse', data, data.success);
            })
            .catch(error => {
                showResponse('getDataResponse', { error: error.message }, false);
            });
        }
        
        function testGetCalculations() {
            fetch('/api/cost-model/calculations')
            .then(response => response.json())
            .then(data => {
                showResponse('getDataResponse', data, data.success);
            })
            .catch(error => {
                showResponse('getDataResponse', { error: error.message }, false);
            });
        }
        
        function testGetMonitoring() {
            fetch('/api/cost-model/monitoring')
            .then(response => response.json())
            .then(data => {
                showResponse('getDataResponse', data, data.success);
            })
            .catch(error => {
                showResponse('getDataResponse', { error: error.message }, false);
            });
        }
    </script>
</body>
</html> 