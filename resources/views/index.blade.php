<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cost Model Calculator</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            line-height: 1.6;
            padding: 0;
        }
        
        /* Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .navbar-brand {
            color: #fff;
            font-size: 24px;
            font-weight: 600;
            text-decoration: none;
        }
        
        .navbar-brand i {
            margin-right: 10px;
        }
        
        .navbar-user {
            display: flex;
            align-items: center;
            color: #fff;
        }
        
        .navbar-user span {
            margin-right: 15px;
        }
        
        .navbar-user a {
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .navbar-user a:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            margin: 0 20px 20px 20px;
        }
        .tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
        }
        .tab {
            padding: 12px 24px;
            cursor: pointer;
            background: #f8f9fa;
            margin-right: 5px;
            border-radius: 8px 8px 0 0;
            font-weight: 500;
            color: #555;
            transition: all 0.3s ease;
        }
        .tab.active {
            background: #007bff;
            color: #fff;
            border-bottom: 2px solid #0056b3;
        }
        .tab-content {
            display: none;
            padding: 20px 0;
        }
        .tab-content.active {
            display: block;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }
        input, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 14px;
            color: #495057;
            background-color: #fff;
            transition: border-color 0.3s ease;
        }
        input:focus, select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }
        button {
            padding: 12px 24px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        .results {
            margin-top: 30px;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .result-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            width: 30%;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .result-card h4 {
            margin-top: 0;
            color: #333;
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .result-card p {
            margin: 8px 0;
            font-size: 14px;
            color: #555;
        }
        .maintenance-inputs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .dashboard-table-container {
            overflow-x: auto;
            max-width: 100%;
            margin-top: 20px;
        }
        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
            background: #fff;
        }
        .dashboard-table th, .dashboard-table td {
            border: 1px solid #dee2e6;
            padding: 12px 15px;
            text-align: right;
            white-space: nowrap;
            font-size: 14px;
        }
        .dashboard-table th {
            background-color: #007bff;
            color: #fff;
            font-weight: 600;
            text-align: center;
        }
        .dashboard-table td:first-child {
            text-align: left;
            font-weight: 500;
            color: #333;
        }
        .dashboard-table .assumption {
            background-color: #ffeb3b;
            font-weight: 600;
            color: #2c3e50;
        }
        .dashboard-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .dashboard-table .category {
            background-color: #28a745 !important;
            font-weight: 600;
            color: #fff;
        }
        .dashboard-table .category td:not(:first-child) {
            text-align: center;
        }
        .dashboard-table .total-row {
            background-color: #d1e7ff !important;
            font-weight: 600;
        }
        .dashboard-table .total-per-year {
            background-color: #e0f7fa !important;
            font-weight: 600;
        }
        .dashboard-table .grand-total {
            background-color: #0056b3 !important;
            color: #fff !important;
            font-weight: 600;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            margin-right: 10px;
        }
        .section.actual {
            background-color: #e6f3ff;
        }
        .section.assumption {
            background-color: #f9ebeb;
        }
        .section h2 {
            font-size: 20px;
            font-weight: 700;
            color: #0056b3;
            margin-bottom: 15px;
        }
        .section.assumption h2 {
            color: #b30000;
        }
        .subsection {
            margin-bottom: 15px;
        }
        .subsection h3 {
            font-size: 18px;
            font-weight: 600;
            color: #28a745;
            margin-bottom: 10px;
        }
        .note {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .monitoring-table-container {
            overflow-x: auto;
            max-width: 100%;
            margin-top: 20px;
        }
        .monitoring-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
            background: #fff;
        }
        .monitoring-table th, .monitoring-table td {
            border: 1px solid #dee2e6;
            padding: 12px 15px;
            text-align: right;
            white-space: nowrap;
            font-size: 14px;
        }
        .monitoring-table th {
            background-color: #007bff;
            color: #fff;
            font-weight: 600;
            text-align: center;
        }
        .monitoring-table td:first-child {
            text-align: left;
            font-weight: 500;
            color: #333;
        }
        .monitoring-table input {
            width: 100px;
            text-align: right;
        }
        .monitoring-table .note-cell {
            background-color: #e9ecef;
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }
        .monitoring-table .note-cell:hover {
            background-color: #dee2e6;
        }
        .monitoring-table .subcategory {
            padding-left: 20px;
            font-style: italic;
            color: #666;
        }
        .monitoring-table .total-row {
            background-color: #d1e7ff;
            font-weight: 600;
        }
        
        /* Auto-save notification styles */
        #auto-save-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 6px;
            color: white;
            font-weight: 500;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            animation: slideIn 0.3s ease;
        }
        
        #auto-save-notification.success {
            background-color: #28a745;
        }
        
        #auto-save-notification.error {
            background-color: #dc3545;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            border: 1px solid #888;
            width: 90%;
            max-width: 1200px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            animation: slideDown 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover,
        .close:focus {
            color: #000;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="{{ route('cost-model.index') }}" class="navbar-brand">
            <i class="fas fa-calculator"></i> Cost Model Calculator
        </a>
        <div class="navbar-user">
            <span>Selamat datang, {{ Auth::user()->name }}!</span>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Cost Model Calculator</h1>
            <div style="display: flex; gap: 10px;">
                <button id="masterPoliceUnitBtn" type="button" onclick="openPoliceUnitsModal()" style="background: #0e45cf; padding: 8px 16px; border-radius: 6px; color: white; border: none; cursor: pointer; font-size: 14px; display: none;">
                    <i class="fas fa-car"></i> Master Nomor Polisi
                </button>
            </div>
        </div>
        <div class="tabs">
            <div class="tab active" onclick="showTab('settings')">Settings</div>
            <div class="tab" onclick="showTab('expense')">Expense</div>
            <div class="tab" onclick="showTab('dashboard')">Dashboard</div>
            <div class="tab" onclick="showTab('monitoring')">Monitoring</div>
            {{-- dihide sementara --}}
            {{-- <div class="tab" onclick="showTab('existingMonitoring')">Existing Monitoring</div> --}}
        </div>
        <div id="settings" class="tab-content active">
            <h2>Settings</h2>
            <div class="section actual">
                <h2>Actual</h2>
                <div class="subsection">
                    <h3>Base Premises</h3>
                    <div class="form-group">
                        <label>Units Price (IDR)</label>
                        <input type="text" id="unitsPrice" oninput="formatInputWithSeparator(this)">
                    </div>
                    <p style="font-size: 12px; color: #666;">*exclude PPN</p>
                    <div class="form-group">
                        <label>Qty of Units (units)</label>
                        <input type="number" id="qtyUnits" min="0">
                    </div>
                    <div class="form-group">
                        <label>Net Book Value (years)</label>
                        <input type="number" id="netBookValue" min="0">
                    </div>
                </div>
                <div class="subsection">
                    <h3>Fuel</h3>
                    <div class="form-group">
                        <label>Solar (IDR/L)</label>
                        <input type="text" id="solarPrice" oninput="formatInputWithSeparator(this)">
                    </div>
                </div>
                <div class="subsection">
                    <h3>AdBlue</h3>
                    <div class="form-group">
                        <label>AdBlue Price (IDR/L)</label>
                        <input type="text" id="adBluePrice" oninput="formatInputWithSeparator(this)">
                    </div>
                </div>
            </div>
            <div class="section assumption">
                <h2>Assumption</h2>
                <div class="subsection">
                    <h3>Retase</h3>
                    <div class="form-group">
                        <label>Number of Retase (ret/day)</label>
                        <input type="number" id="retasePerDay" min="0">
                    </div>
                    <div class="form-group">
                        <label>Average Ritase / Day (km/day)</label>
                        <input type="number" id="avgRitasePerDay" min="0">
                    </div>
                </div>
                <div class="subsection">
                    <h3>Fuel Consumption</h3>
                    <div class="form-group">
                        <label>Fuel Consumption (km/L)</label>
                        <input type="number" id="fuelConsumption" min="0" step="0.01">
                    </div>
                    <p style="font-size: 12px; color: #666;">*Syarat dan ketentuan berlaku<br>Driver Habbit, Capacity Load, Road Construction</p>
                </div>
                <div class="subsection">
                    <h3>AdBlue Consumption</h3>
                    <div class="form-group">
                        <label>AdBlue (km/L)</label>
                        <input type="number" id="adBlue" min="0" step="0.01">
                    </div>
                </div>
                <div class="subsection">
                    <h3>Day Operation</h3>
                    <div class="form-group">
                        <label>Day Operation (Days in a Month)</label>
                        <input type="number" id="dayOperation" min="0">
                    </div>
                </div>
            </div>
        </div>
        <div id="expense" class="tab-content">
            <h2>Expense</h2>
            <div class="section actual">
                <h2>Actual</h2>
                <div class="subsection">
                    <h3>Base Premises</h3>
                    <div class="form-group">
                        <label>Insurance (IDR/Year)</label>
                        <input type="text" id="insuranceUnit" oninput="formatInputWithSeparator(this)">
                    </div>
                    <div class="form-group">
                        <label>First Payment (IDR)</label>
                        <input type="text" id="firstPayment" oninput="formatInputWithSeparator(this)">
                    </div>
                    <div class="form-group">
                        <label>Leasing Payment (IDR, Flat for 3 Years)</label>
                        <input type="text" id="leasingPayment" oninput="formatInputWithSeparator(this)">
                    </div>
                    <div class="form-group">
                        <label>Tax Vehicle (IDR/Year)</label>
                        <input type="text" id="vehicleTax" oninput="formatInputWithSeparator(this)">
                    </div>
                    <div class="form-group">
                        <label>KIR (IDR/Year)</label>
                        <input type="text" id="kir" oninput="formatInputWithSeparator(this)">
                    </div>
                </div>
                <div class="subsection">
                    <h3>Telematics Module</h3>
                    <div class="form-group">
                        <label>One Time Cost (IDR)</label>
                        <input type="text" id="telematicsOneTimeCost" oninput="formatInputWithSeparator(this)">
                    </div>
                    <div class="form-group">
                        <label>Recurring Cost (IDR/Month)</label>
                        <input type="text" id="telematicsRecurringCost" oninput="formatInputWithSeparator(this)">
                    </div>
                </div>
                <div class="subsection">
                    <h3>Tyre Management</h3>
                    <div class="form-group">
                        <label>Tire Price (IDR/Tyre)</label>
                        <input type="text" id="tirePrice" oninput="formatInputWithSeparator(this)">
                    </div>
                    <div class="form-group">
                        <label>Lifetime Tyre (Km)</label>
                        <input type="text" id="lifetimeTyre" oninput="formatInputWithSeparator(this)">
                    </div>
                </div>                
                <div class="subsection">
                    <h3>Preventive Maintenance/PM</h3>
                    <div class="form-group">
                        <label>Oil Price (IDR)</label>
                        <input type="text" id="oilPrice" oninput="formatInputWithSeparator(this); updatePMNotes()">
                    </div>
                    <div class="maintenance-inputs">
                        <div>
                            <label>1st Year</label>
                            <input type="text" id="pm1" oninput="formatInputWithSeparator(this); updatePMNotes()">
                            <p id="pm1-note" class="note"></p>
                        </div>
                        <div>
                            <label>2nd Year</label>
                            <input type="text" id="pm2" oninput="formatInputWithSeparator(this); updatePMNotes()">
                            <p id="pm2-note" class="note"></p>
                        </div>
                        <div>
                            <label>3rd Year</label>
                            <input type="text" id="pm3" oninput="formatInputWithSeparator(this); updatePMNotes()">
                            <p id="pm3-note" class="note"></p>
                        </div>
                        <div>
                            <label>4th Year</label>
                            <input type="text" id="pm4" oninput="formatInputWithSeparator(this); updatePMNotes()">
                            <p id="pm4-note" class="note"></p>
                        </div>
                        <div>
                            <label>5th Year</label>
                            <input type="text" id="pm5" oninput="formatInputWithSeparator(this); updatePMNotes()">
                            <p id="pm5-note" class="note"></p>
                        </div>
                        <div>
                            <label>6th Year</label>
                            <input type="text" id="pm6" oninput="formatInputWithSeparator(this); updatePMNotes()">
                            <p id="pm6-note" class="note"></p>
                        </div>
                        <div>
                            <label>7th Year</label>
                            <input type="text" id="pm7" oninput="formatInputWithSeparator(this); updatePMNotes()">
                            <p id="pm7-note" class="note"></p>
                        </div>
                        <div>
                            <label>8th Year</label>
                            <input type="text" id="pm8" oninput="formatInputWithSeparator(this); updatePMNotes()">
                            <p id="pm8-note" class="note"></p>
                        </div>
                        <div>
                            <label>9th Year</label>
                            <input type="text" id="pm9" oninput="formatInputWithSeparator(this); updatePMNotes()">
                            <p id="pm9-note" class="note"></p>
                        </div>
                        <div>
                            <label>10th Year</label>
                            <input type="text" id="pm10" oninput="formatInputWithSeparator(this); updatePMNotes()">
                            <p id="pm10-note" class="note"></p>
                        </div>
                    </div>
                </div>
                <div class="subsection">
                    <h3>General Repair/GR</h3>
                    <div class="maintenance-inputs">
                        <div><label>1st Year</label><input type="text" id="gm1" oninput="formatInputWithSeparator(this)"></div>
                        <div><label>2nd Year</label><input type="text" id="gm2" oninput="formatInputWithSeparator(this)"></div>
                        <div><label>3rd Year</label><input type="text" id="gm3" oninput="formatInputWithSeparator(this)"></div>
                        <div><label>4th Year</label><input type="text" id="gm4" oninput="formatInputWithSeparator(this)"></div>
                        <div><label>5th Year</label><input type="text" id="gm5" oninput="formatInputWithSeparator(this)"></div>
                        <div><label>6th Year</label><input type="text" id="gm6" oninput="formatInputWithSeparator(this)"></div>
                        <div><label>7th Year</label><input type="text" id="gm7" oninput="formatInputWithSeparator(this)"></div>
                        <div><label>8th Year</label><input type="text" id="gm8" oninput="formatInputWithSeparator(this)"></div>
                        <div><label>9th Year</label><input type="text" id="gm9" oninput="formatInputWithSeparator(this)"></div>
                        <div><label>10th Year</label><input type="text" id="gm10" oninput="formatInputWithSeparator(this)"></div>
                    </div>
                </div>
            </div>
            <div class="section assumption">
                <h2>Assumption</h2>
                <div class="subsection">
                    <h3>Operation</h3>
                    <div class="form-group">
                        <label>Average Toll Cost (IDR/Day)</label>
                        <input type="text" id="tollCost" oninput="formatInputWithSeparator(this)">
                    </div>
                    <div class="form-group">
                        <label>Driver per Units</label>
                        <input type="number" id="driverPerUnit" min="0">
                    </div>
                    <div class="form-group">
                        <label>Driver Cost (IDR/Ret)</label>
                        <input type="text" id="driverCost" oninput="formatInputWithSeparator(this)">
                    </div>
                </div>
                <div class="subsection">
                    <h3>Tyre Management</h3>
                    <div class="form-group">
                        <label>Tyre / Unit (Tyre)</label>
                        <input type="number" id="tyrePerUnit" min="0">
                    </div>
                </div>
                <div class="subsection">
                    <h3>Downtime</h3>
                    <div class="form-group">
                        <label>Downtime Percentage Assumption (%)</label>
                        <input type="number" id="downtime" min="0" max="100" step="0.01">
                    </div>
                </div>
            </div>
            <h3>Units Depreciation (Default Values)</h3>
            <p>1st Year (8% - 12%): 12%</p>
            <p>2nd Year (5% - 10%): 22%</p>
            <p>3rd Year (4% - 8%): 30%</p>
            <p>4th Year (4% - 6%): 36%</p>
            <p>5th Year (2% - 4%): 40%</p>
            <button id="calculateBtn" onclick="calculateTCO()">Calculate</button>
            <div id="results" class="results"></div>
        </div>
        <div id="dashboard" class="tab-content">
            <h2>Dashboard</h2>
            <div class="form-group">
                <label>Years to Display</label>
                <select id="yearsToDisplay" onchange="calculateTCO(); updateMonitoringYears(); updateExistingMonitoringYears()">
                    <option value="5">5 Years</option>
                    <option value="6">6 Years</option>
                    <option value="7">7 Years</option>
                    <option value="8">8 Years</option>
                    <option value="9">9 Years</option>
                    <option value="10">10 Years</option>
                </select>
            </div>
            <div class="dashboard-table-container">
                <table class="dashboard-table" id="dashboard-table">
                    <thead id="dashboard-table-head">
                        <tr>
                            <th>Component</th>
                            <th>Price (IDR)</th>
                            <th>1st Year</th>
                            <th>2nd Year</th>
                            <th>3rd Year</th>
                            <th>4th Year</th>
                            <th>5th Year</th>
                        </tr>
                    </thead>
                    <tbody id="dashboard-table-body"></tbody>
                </table>
            </div>
        </div>
        <div id="monitoring" class="tab-content">
            <h2>Monitoring</h2>
            <div class="form-group">
                <label>Year to Monitor</label>
                <select id="yearToMonitor" onchange="updateMonitoringTable(); loadMonitoringDataForYear(); updateMonitoringTotals(parseInt(this.value));">
                    <!-- Options will be dynamically populated -->
                </select>
            </div>
            <div class="form-group">
                <label>Nomor Polisi Unit</label>
                <select id="unitPoliceNumber" onchange="loadMonitoringDataForSelectedUnit();">
                    {{-- <option value="">Pilih Nomor Polisi Unit</option> --}}
                    <!-- Options will be dynamically populated -->
                </select>
            </div>
            <div class="monitoring-table-container">
                <table class="monitoring-table" id="monitoring-table">
                    <thead id="monitoring-table-head"></thead>
                    <tbody id="monitoring-table-body"></tbody>
                </table>
            </div>
        </div>
        <div id="existingMonitoring" class="tab-content">
            <h2>Existing Monitoring</h2>
            <div class="form-group">
                <label>Year to Monitor</label>
                <select id="existingYearToMonitor" onchange="updateExistingMonitoringTable(); loadExistingMonitoringDataForYear(); updateExistingMonitoringTotals(parseInt(this.value));">
                    <!-- Options will be dynamically populated -->
                </select>
            </div>
            <div class="form-group">
                <label>Nomor Polisi Unit</label>
                <select id="existingUnitPoliceNumber" onchange="loadExistingMonitoringDataForSelectedUnit();">
                    {{-- <option value="">Pilih Nomor Polisi Unit</option> --}}
                    <!-- Options will be dynamically populated -->
                </select>
            </div>
            <div class="monitoring-table-container">
                <table class="monitoring-table" id="existing-monitoring-table">
                    <thead id="existing-monitoring-table-head"></thead>
                    <tbody id="existing-monitoring-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Master Nomor Polisi -->
    <div id="policeUnitsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header" style="padding: 20px; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; color: #333;">
                    <i class="fas fa-car"></i> Master Nomor Polisi
                </h3>
                <span class="close" onclick="closePoliceUnitsModal()">&times;</span>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <!-- Form Tambah/Edit -->
                <div class="form-container" style="background: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                    <h4 id="formTitle">Tambah Nomor Polisi Baru</h4>
                    <form id="policeUnitForm">
                        <input type="hidden" id="editId" name="id">
                        <div style="margin-bottom: 15px;">
                            <label for="policeNumber" style="display: block; font-weight: 600; margin-bottom: 5px;">Nomor Polisi *</label>
                            <input type="text" id="policeNumber" name="police_number" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button type="submit" style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <button type="button" onclick="resetPoliceUnitForm()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabel Data -->
                <div class="table-container" style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h5 style="margin: 0;">Daftar Nomor Polisi</h5>
                            <button onclick="loadPoliceUnitsForModal()" style="background: #28a745; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                                <thead style="background-color: #007bff; color: white;">
                                    <tr>
                                        <th style="padding: 12px; text-align: left; border: 1px solid #dee2e6;">No</th>
                                        <th style="padding: 12px; text-align: left; border: 1px solid #dee2e6;">Nomor Polisi</th>
                                        <th style="padding: 12px; text-align: left; border: 1px solid #dee2e6;">Status</th>
                                        <th style="padding: 12px; text-align: left; border: 1px solid #dee2e6;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="policeUnitsTable">
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 20px;">Memuat data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="deletePoliceUnitModal" class="modal">
        <div class="modal-content" style="width: 400px; margin: 15% auto;">
            {{-- <div class="modal-header" style="padding: 20px; border-bottom: 1px solid #dee2e6;">
                <h5 style="margin: 0;">Konfirmasi Hapus</h5>
                <span class="close" onclick="closeDeleteModal()">&times;</span>
            </div> --}}
            <div class="modal-body" style="padding: 20px;">
                <p>Are you sure you want to delete a police number <strong id="deletePoliceNumber"></strong>?</p>
                <p style="color: #dc3545;">This action will delete monitoring data.</p>
            </div>
            <div class="modal-footer" style="padding: 15px 20px; border-top: 1px solid #dee2e6; text-align: right;">
                <button onclick="closeDeleteModal()" style="background: #6c757d; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin-right: 10px;">Cancel</button>
                <button onclick="confirmDeletePoliceUnit()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Delete</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/cost-model-api.js') }}"></script>
    <script>
        // Fungsi untuk format input dengan separator ribuan
        function formatInputWithSeparator(input) {
            const cursorPosition = input.selectionStart;
            let raw = input.value.replace(/[^0-9.]/g, "");
            let parts = raw.split(".");
            let integerPart = parts[0];
            let decimalPart = parts[1] || "";
            let formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            let formatted = decimalPart.length > 0 ? `${formattedInteger}.${decimalPart}` : formattedInteger;
            input.value = formatted;
            input.dataset.raw = raw;
            let newCursorPosition = formatted.length - (raw.length - cursorPosition);
            input.setSelectionRange(newCursorPosition, newCursorPosition);
            
            // Trigger auto-save jika fungsi tersedia
            if (typeof autoSaveInput === 'function') {
                autoSaveInput(input.id, 'form');
            }
        }

        function parseFormattedNumber(value) {
            return parseFloat(value.replace(/,/g, "")) || 0;
        }

        function formatNumberWithSeparator(value) {
            const number = parseFloat(value) || 0;
            if (number >= 1000) {
                const parts = number.toFixed(2).split(".");
                const integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return `${integerPart}.${parts[1]}`;
            }
            return number.toFixed(2);
        }

        function updatePMNotes() {
            const oilPrice = parseFormattedNumber(document.getElementById('oilPrice')?.value || '0');
            for (let i = 1; i <= 10; i++) {
                const pmCost = parseFormattedNumber(document.getElementById(`pm${i}`)?.value || '0');
                let adjustedCost = pmCost;
                if (oilPrice > 0) {
                    adjustedCost = i === 1 ? (pmCost - 2800000) + (oilPrice * 20 * 2) : (pmCost - 1400000) + (oilPrice * 20);
                }
                const noteElement = document.getElementById(`pm${i}-note`);
                if (noteElement) {
                    noteElement.textContent = `pm cost adjusted with oil price - ${i}th year = ${formatNumberWithSeparator(adjustedCost)}`;
                }
            }
        }

        function calculateTCO() {
            // Helper function untuk safe division
            const safeDivision = (numerator, denominator) => {
                return (denominator && denominator > 0) ? numerator / denominator : 0;
            };

            // Helper function untuk safe multiplication
            const safeMultiply = (a, b) => {
                return (a || 0) * (b || 0);
            };

            // Get global variables
            const unitsPrice = parseFormattedNumber(document.getElementById('unitsPrice')?.value || '0');
            const qtyUnits = parseFloat(document.getElementById('qtyUnits')?.value || '0');
            const netBookValue = parseFloat(document.getElementById('netBookValue')?.value || '0');
            const retasePerDay = parseFloat(document.getElementById('retasePerDay')?.value || '0');
            const avgRitasePerDay = parseFloat(document.getElementById('avgRitasePerDay')?.value || '0');
            const fuelConsumption = parseFloat(document.getElementById('fuelConsumption')?.value || '0');
            const solarPrice = parseFormattedNumber(document.getElementById('solarPrice')?.value || '0');
            const adBlue = parseFloat(document.getElementById('adBlue')?.value || '0');
            const adBluePrice = parseFormattedNumber(document.getElementById('adBluePrice')?.value || '0');
            const dayOperation = parseFloat(document.getElementById('dayOperation')?.value || '0');

            // Get expense variables
            const tollCost = parseFormattedNumber(document.getElementById('tollCost')?.value || '0');
            const driverPerUnit = parseFloat(document.getElementById('driverPerUnit')?.value || '0');
            const driverCost = parseFormattedNumber(document.getElementById('driverCost')?.value || '0');
            const tirePrice = parseFormattedNumber(document.getElementById('tirePrice')?.value || '0');
            const tyrePerUnit = parseFloat(document.getElementById('tyrePerUnit')?.value || '0');
            const lifetimeTyre = parseFormattedNumber(document.getElementById('lifetimeTyre')?.value || '0');
            const vehicleTax = parseFormattedNumber(document.getElementById('vehicleTax')?.value || '0');
            const insuranceUnit = parseFormattedNumber(document.getElementById('insuranceUnit')?.value || '0');
            const kir = parseFormattedNumber(document.getElementById('kir')?.value || '0');
            const firstPayment = parseFormattedNumber(document.getElementById('firstPayment')?.value || '0');
            const leasingPayment = parseFormattedNumber(document.getElementById('leasingPayment')?.value || '0');
            const downtimePercentage = parseFloat(document.getElementById('downtime')?.value || '0');
            const oilPrice = parseFormattedNumber(document.getElementById('oilPrice')?.value || '0');
            const telematicsOneTimeCost = parseFormattedNumber(document.getElementById('telematicsOneTimeCost')?.value || '0');
            const telematicsRecurringCost = parseFormattedNumber(document.getElementById('telematicsRecurringCost')?.value || '0');

            // Get maintenance costs and adjust PM costs
            const pmCosts = [];
            const gmCosts = [];
            for (let i = 1; i <= 10; i++) {
                const pmCost = parseFormattedNumber(document.getElementById(`pm${i}`)?.value || '0');
                let adjustedPMCost = pmCost;
                if (oilPrice > 0) {
                    adjustedPMCost = i === 1 ? (pmCost - 2800000) + safeMultiply(oilPrice, 20) * 2 : (pmCost - 1400000) + safeMultiply(oilPrice, 20);
                }
                pmCosts.push(adjustedPMCost);
                gmCosts.push(parseFormattedNumber(document.getElementById(`gm${i}`)?.value || '0'));
            }

            // Telematics Module calculations
            const telematicsCostPerMonth = telematicsRecurringCost;
            const telematicsCostFirstYear = safeMultiply(telematicsRecurringCost, 12) + telematicsOneTimeCost;
            const telematicsCostSubsequentYears = safeMultiply(telematicsRecurringCost, 12);

            // Update PM notes
            updatePMNotes();

            // Calculations
            // 1. Retase
            const avgRetPerMonth = safeMultiply(avgRitasePerDay, dayOperation);
            const avgRetPerYear = safeMultiply(avgRetPerMonth, 12);

            // 2. Fuel Consumption
            const fuelConsumptionPerRet = safeDivision(avgRitasePerDay, fuelConsumption);
            const fuelConsumptionPerMonth = safeMultiply(fuelConsumptionPerRet, dayOperation);
            const fuelConsumptionPerYear = safeMultiply(fuelConsumptionPerMonth, 12);
            const solarPerYear = safeMultiply(solarPrice, fuelConsumptionPerYear);

            // 3. AdBlue
            const adBlueConsumptionPerDay = safeMultiply(safeDivision(avgRitasePerDay, adBlue), adBluePrice);
            const adBlueConsumptionPerMonth = safeMultiply(adBlueConsumptionPerDay, dayOperation);
            const adBlueConsumptionPerYear = safeMultiply(adBlueConsumptionPerMonth, 12);

            // 4. Operation
            const driverCostPerMonth = safeMultiply(safeMultiply(driverCost, dayOperation), retasePerDay) + safeMultiply(tollCost, dayOperation);
            const driverCostPerYear = safeMultiply(driverCostPerMonth, 12);

            // 5. Tyre Management
            const costPerUnit = safeMultiply(tirePrice, tyrePerUnit);
            const idrPerKm = safeDivision(tirePrice, lifetimeTyre);
            const idrPerKmUnit = safeMultiply(idrPerKm, tyrePerUnit);
            const costDays = safeMultiply(idrPerKmUnit, avgRitasePerDay);
            const costMonth = safeMultiply(idrPerKmUnit, avgRetPerMonth);
            const costYear = safeMultiply(idrPerKmUnit, avgRetPerYear);

            // 6. Unit Payment
            const unitDownPayment = safeMultiply(unitsPrice, 0.3);
            const financing = safeMultiply(unitsPrice, 0.7);
            const leasingPaymentYearly = safeMultiply(leasingPayment, 12);

            // 7. Total Cost non Units (dynamic based on yearsToDisplay)
            const yearsToDisplay = parseInt(document.getElementById('yearsToDisplay')?.value || '5');
            const totalCostNonUnits = 
                safeMultiply(vehicleTax, yearsToDisplay) + 
                insuranceUnit + 
                safeMultiply(kir, yearsToDisplay) + 
                safeMultiply(solarPerYear, yearsToDisplay) + 
                safeMultiply(adBlueConsumptionPerYear, yearsToDisplay) + 
                safeMultiply(driverCostPerYear, yearsToDisplay) + 
                pmCosts.slice(0, yearsToDisplay).reduce((sum, cost) => sum + cost, 0) + 
                gmCosts.slice(0, yearsToDisplay).reduce((sum, cost) => sum + cost, 0) + 
                safeMultiply(costYear, yearsToDisplay) +
                telematicsCostFirstYear + safeMultiply(telematicsCostSubsequentYears, (yearsToDisplay - 1));

            // 8. Downtime Cost Estimate
            const downtimeCostEstimate = safeMultiply(totalCostNonUnits, safeDivision(downtimePercentage, 100));

            // Display results in Expense tab with individual cards
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = `
                <div class="results">
                    <div class="result-card">
                        <h4>Unit Payment</h4>
                        <p>Unit Down Payment: ${formatNumberWithSeparator(unitDownPayment)} IDR</p>
                        <p>Financing: ${formatNumberWithSeparator(financing)} IDR</p>
                    </div>
                    <div class="result-card">
                        <h4>Retase</h4>
                        <p>Average Ret per-Month: ${formatNumberWithSeparator(avgRetPerMonth)} Km/Month</p>
                        <p>Average Ret per-Year: ${formatNumberWithSeparator(avgRetPerYear)} Km/Year</p>
                    </div>
                    <div class="result-card">
                        <h4>Fuel Consumption</h4>
                        <p>Fuel Consumption/Ret: ${formatNumberWithSeparator(fuelConsumptionPerRet)} L/Day</p>
                        <p>Fuel Consumption/Month: ${formatNumberWithSeparator(fuelConsumptionPerMonth)} L/Month</p>
                        <p>Fuel Consumption/Year: ${formatNumberWithSeparator(fuelConsumptionPerYear)} L/Year</p>
                        <p>Solar/Year: ${formatNumberWithSeparator(solarPerYear)} IDR/Year</p>
                    </div>
                    <div class="result-card">
                        <h4>AdBlue</h4>
                        <p>AdBlue Consumption: ${formatNumberWithSeparator(adBlueConsumptionPerDay)} IDR/Day</p>
                        <p>AdBlue Consumption/Month: ${formatNumberWithSeparator(adBlueConsumptionPerMonth)} IDR/Month</p>
                        <p>AdBlue Consumption/Year: ${formatNumberWithSeparator(adBlueConsumptionPerYear)} IDR/Year</p>
                    </div>
                    <div class="result-card">
                        <h4>Operation</h4>
                        <p>Driver Cost: ${formatNumberWithSeparator(driverCostPerMonth)} IDR/Month</p>
                        <p>Driver Cost: ${formatNumberWithSeparator(driverCostPerYear)} IDR/Year</p>
                    </div>
                    <div class="result-card">
                        <h4>Tyre Management</h4>
                        <p>Cost/Unit: ${formatNumberWithSeparator(costPerUnit)} IDR/Unit</p>
                        <p>IDR/Km: ${formatNumberWithSeparator(idrPerKm)} IDR/Km/Tyre</p>
                        <p>IDR/KM/Unit: ${formatNumberWithSeparator(idrPerKmUnit)} IDR/Km/Unit</p>
                        <p>Cost Days: ${formatNumberWithSeparator(costDays)} IDR/Day</p>
                        <p>Cost Month: ${formatNumberWithSeparator(costMonth)} IDR/Month</p>
                        <p>Cost Year: ${formatNumberWithSeparator(costYear)} IDR/Year</p>
                    </div>
                    <div class="result-card">
                        <h4>Telematics Module</h4>
                        <p>Cost per Month: ${formatNumberWithSeparator(telematicsCostPerMonth)} IDR/Month</p>
                        <p>Cost per Year (1st Year): ${formatNumberWithSeparator(telematicsCostFirstYear)} IDR/Year</p>
                        <p>Cost per Year (2nd Year, etc.): ${formatNumberWithSeparator(telematicsCostSubsequentYears)} IDR/Year</p>
                    </div>
                    <div class="result-card">
                        <h4>Additional Calculations</h4>
                        <p>Total Cost non Units: ${formatNumberWithSeparator(totalCostNonUnits)} IDR</p>
                        <p>Downtime Cost Estimate: ${formatNumberWithSeparator(downtimeCostEstimate)} IDR</p>
                    </div>
                </div>
            `;

            // Populate Dashboard table
            const dashboardTableHead = document.getElementById('dashboard-table-head');
            const dashboardTableBody = document.getElementById('dashboard-table-body');
            const yearlyTotals = new Array(10).fill(0);

            // Dynamically generate table header based on yearsToDisplay
            const headerHTML = `
                <tr>
                    <th>Component</th>
                    <th>Price (IDR)</th>
                    <th>1st Year</th>
                    <th>2nd Year</th>
                    <th>3rd Year</th>
                    <th>4th Year</th>
                    <th>5th Year</th>
                    ${yearsToDisplay > 5 ? `<th>6th Year</th>` : ''}
                    ${yearsToDisplay > 6 ? `<th>7th Year</th>` : ''}
                    ${yearsToDisplay > 7 ? `<th>8th Year</th>` : ''}
                    ${yearsToDisplay > 8 ? `<th>9th Year</th>` : ''}
                    ${yearsToDisplay > 9 ? `<th>10th Year</th>` : ''}
                </tr>
            `;
            dashboardTableHead.innerHTML = headerHTML;

            // Telematics Module yearly costs
            const telematicsCosts = [telematicsCostFirstYear, ...new Array(9).fill(telematicsCostSubsequentYears)];

            const rows = [
                { category: 'Actual', label: 'Harga Units', assumption: unitsPrice, values: new Array(10).fill(0) },
                { category: 'Actual', label: 'Uang Muka (30%)', assumption: unitDownPayment, values: [unitDownPayment, 0, 0, 0, 0, 0, 0, 0, 0, 0] },
                { category: 'Actual', label: 'Pembiayaan (70%)', assumption: financing, values: [leasingPaymentYearly, leasingPaymentYearly, leasingPaymentYearly, 0, 0, 0, 0, 0, 0, 0] },
                { category: 'Actual', label: 'First Payment', assumption: firstPayment, values: [firstPayment, 0, 0, 0, 0, 0, 0, 0, 0, 0] },
                { category: 'Actual', label: 'Pajak & STNK', assumption: vehicleTax, values: [0, vehicleTax, vehicleTax, vehicleTax, vehicleTax, vehicleTax, vehicleTax, vehicleTax, vehicleTax, vehicleTax] },
                { category: 'Actual', label: 'Asuransi', assumption: insuranceUnit, values: [insuranceUnit, 0, 0, 0, 0, 0, 0, 0, 0, 0] },
                { category: 'Actual', label: 'KIR', assumption: kir, values: new Array(10).fill(kir) },
                { category: 'Actual', label: 'Telematics Module', assumption: 'Yearly', values: telematicsCosts },
                { category: 'Assumption', label: 'Service Berkala/PM', assumption: 'Yearly', values: pmCosts.slice(0, 10) },
                { category: 'Assumption', label: 'Service General/GM', assumption: 'Yearly', values: gmCosts.slice(0, 10) },
                { category: 'Assumption', label: 'BBM', assumption: solarPerYear, values: new Array(10).fill(solarPerYear) },
                { category: 'Assumption', label: 'AdBlue', assumption: adBlueConsumptionPerYear, values: new Array(10).fill(adBlueConsumptionPerYear) },
                { category: 'Assumption', label: 'Driver Cost', assumption: driverCostPerYear, values: new Array(10).fill(driverCostPerYear) },
                { category: 'Assumption', label: 'Ban', assumption: costYear, values: new Array(10).fill(costYear) },
                { category: 'Assumption', label: 'Downtime (1%)', assumption: `${downtimePercentage}%`, values: new Array(10).fill(downtimeCostEstimate) }
            ];

            let tableHTML = '';
            let currentCategory = '';
            let actualTotals = new Array(10).fill(0);
            let assumptionTotals = new Array(10).fill(0);

            rows.forEach(row => {
                if (row.category !== currentCategory) {
                    if (currentCategory === 'Actual' && actualTotals.some(t => t > 0)) {
                        tableHTML += '<tr class="total-row"><td>Total Actual</td><td></td>';
                        for (let i = 0; i < 10; i++) {
                            tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}>${formatNumberWithSeparator(actualTotals[i])}</td>`;
                            yearlyTotals[i] = actualTotals[i];
                        }
                        tableHTML += '</tr>';
                    } else if (currentCategory === 'Assumption' && assumptionTotals.some(t => t > 0)) {
                        tableHTML += '<tr class="total-row"><td>Total Assumptions</td><td></td>';
                        for (let i = 0; i < 10; i++) {
                            tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}>${formatNumberWithSeparator(assumptionTotals[i])}</td>`;
                            yearlyTotals[i] += assumptionTotals[i];
                        }
                        tableHTML += '</tr>';
                    }
                    if (row.category === 'Assumption') {
                        tableHTML += `<tr class="category" style="background-color: #28a745 !important;"><td>${row.category}</td><td></td>`;
                        for (let i = 0; i < 10; i++) {
                            tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}></td>`;
                        }
                        tableHTML += '</tr>';
                    } else {
                        tableHTML += `<tr class="category" style="background-color: #28a745 !important;"><td>${row.category}</td><td colspan="${yearsToDisplay + 1}"></td></tr>`;
                    }
                    currentCategory = row.category;
                    actualTotals = new Array(10).fill(0);
                    assumptionTotals = new Array(10).fill(0);
                }
                tableHTML += '<tr>';
                tableHTML += `<td>${row.label}</td>`;
                const assumptionValue = typeof row.assumption === 'number' ? formatNumberWithSeparator(row.assumption) : row.assumption;
                tableHTML += `<td class="assumption">${assumptionValue}</td>`;
                for (let i = 0; i < 10; i++) {
                    const displayValue = typeof row.values[i] === 'string' ? row.values[i] : formatNumberWithSeparator(row.values[i]);
                    tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}>${displayValue}</td>`;
                    if (typeof row.values[i] === 'number' && i < yearsToDisplay) {
                        if (row.category === 'Actual') actualTotals[i] += row.values[i];
                        else if (row.category === 'Assumption') assumptionTotals[i] += row.values[i];
                    }
                }
                tableHTML += '</tr>';
            });

            if (currentCategory === 'Actual' && actualTotals.some(t => t > 0)) {
                tableHTML += '<tr class="total-row"><td>Total Actual</td><td></td>';
                for (let i = 0; i < 10; i++) {
                    tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}>${formatNumberWithSeparator(actualTotals[i])}</td>`;
                    yearlyTotals[i] = actualTotals[i];
                }
                tableHTML += '</tr>';
            } else if (currentCategory === 'Assumption' && assumptionTotals.some(t => t > 0)) {
                tableHTML += '<tr class="total-row"><td>Total Assumptions</td><td></td>';
                for (let i = 0; i < 10; i++) {
                    tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}>${formatNumberWithSeparator(assumptionTotals[i])}</td>`;
                    yearlyTotals[i] += assumptionTotals[i];
                }
                tableHTML += '</tr>';
            }

            tableHTML += '<tr class="total-per-year"><td>Total per Year</td><td></td>';
            for (let i = 0; i < 10; i++) {
                tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}>${formatNumberWithSeparator(yearlyTotals[i])}</td>`;
            }
            tableHTML += '</tr>';

            tableHTML += '<tr><td>Monthly Cost</td><td></td>';
            for (let i = 0; i < 10; i++) {
                const monthly = yearlyTotals[i] / 12;
                tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}>${formatNumberWithSeparator(monthly)}</td>`;
            }
            tableHTML += '</tr>';

            const grandTotal = yearlyTotals.slice(0, yearsToDisplay).reduce((sum, total) => sum + total, 0);
            tableHTML += `<tr class="grand-total"><td>Grand Total</td><td></td><td colspan="${yearsToDisplay}">${formatNumberWithSeparator(grandTotal)}</td>`;
            for (let i = yearsToDisplay; i < 10; i++) {
                tableHTML += '<td style="display:none"></td>';
            }
            tableHTML += '</tr>';

            dashboardTableBody.innerHTML = tableHTML;
            // Debug: Pastikan elemen grand-total ada dan gaya diterapkan
            const grandTotalRow = document.querySelector('.grand-total');
            if (grandTotalRow) {
                grandTotalRow.style.backgroundColor = '#0056b3';
                grandTotalRow.style.color = '#fff';
            }

            // Save dashboard data to database
            saveDashboardDataToDatabase(rows, yearlyTotals, actualTotals, assumptionTotals);

            updateMonitoringTable();
            updateExistingMonitoringTable();
        }

        function updateMonitoringTable() {
            const year = parseInt(document.getElementById('yearToMonitor')?.value || '1');
            const weeks = Array.from({ length: 52 }, (_, i) => `W${i + 1}`);
            const components = [
                { label: 'Service Berkala/PM', sub: [] },
                { label: 'Service General/GM', sub: [] },
                { label: 'BBM', sub: [] },
                { label: 'AdBlue', sub: [] },
                { label: 'Driver Cost', sub: [] },
                { label: 'Ban', sub: [] },
                { label: 'Downtime (1%)', sub: [] } // Tetap ada, tapi tanpa input
            ];

            const tableHead = document.getElementById('monitoring-table-head');
            if (tableHead) {
                let headerHTML = `
                    <tr>
                        <th>Component</th>
                        ${weeks.map(w => `<th>${w}</th>`).join('')}
                    </tr>
                `;
                tableHead.innerHTML = headerHTML;
            }

            let tableHTML = '';
            components.forEach(component => {
                tableHTML += '<tr>';
                tableHTML += `<td>${component.label}</td>`;
                weeks.forEach(week => {
                    const idBase = `${component.label.replace(/ /g, '_').replace(/\(|\)/g, '')}_${year}_${week}`;
                    if (component.label === 'Downtime (1%)') {
                        // Tampilkan nilai otomatis (akan diisi oleh updateMonitoringTotals)
                        tableHTML += `<td id="${idBase}">${formatNumberWithSeparator(0)}</td>`; // Nilai awal 0
                    } else {
                        tableHTML += `
                            <td>
                                <input type="text" id="${idBase}" oninput="formatInputWithSeparator(this); updateMonitoringTotals(${year})" placeholder="0.00">
                                <span class="note-cell" onclick="showNote('${idBase}')">Note</span>
                            </td>
                        `;
                    }
                });
                tableHTML += '</tr>';
                component.sub.forEach(sub => {
                    tableHTML += '<tr>';
                    tableHTML += `<td class="subcategory">${sub}</td>`;
                    weeks.forEach(week => {
                        const id = `${component.label.replace(/ /g, '_').replace(/\(|\)/g, '')}_${sub.replace(/ /g, '_')}_${year}_${week}`;
                        tableHTML += `
                            <td>
                                <input type="text" id="${id}" oninput="formatInputWithSeparator(this); updateMonitoringTotals(${year})" placeholder="0.00">
                            </td>
                        `;
                    });
                    tableHTML += '</tr>';
                });
            });

            // Add total row for each week
            tableHTML += '<tr class="total-row"><td>Total</td>';
            weeks.forEach(week => {
                const totalId = `total_${year}_${week}`;
                tableHTML += `<td id="${totalId}">0.00</td>`;
            });
            tableHTML += '</tr>';

            const tableBody = document.getElementById('monitoring-table-body');
            if (tableBody) {
                tableBody.innerHTML = tableHTML;
                updateMonitoringTotals(year); // Initialize totals
            }
        }

        function updateMonitoringTotals(year) {
            const weeks = Array.from({ length: 52 }, (_, i) => `W${i + 1}`);
            const components = ['Service_Berkala/PM', 'Service_General/GM', 'BBM', 'AdBlue', 'Driver_Cost', 'Ban', 'Downtime_1%'];
            const downtimePercentage = parseFloat(document.getElementById('downtime')?.value || '0'); // Ambil dari Expense tab

            weeks.forEach(week => {
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

                // Hitung Downtime (1%) = Total Komponen * (Downtime Percentage / 100)
                const downtimeId = `Downtime_1%_${year}_${week}`;
                const downtimeValue = (totalComponents * (downtimePercentage / 100)) || 0;
                const downtimeCell = document.getElementById(downtimeId);
                if (downtimeCell) {
                    downtimeCell.textContent = formatNumberWithSeparator(downtimeValue);
                }

                // Hitung Total
                let total = totalComponents + downtimeValue;
                const totalId = `total_${year}_${week}`;
                const totalCell = document.getElementById(totalId);
                if (totalCell) {
                    totalCell.textContent = formatNumberWithSeparator(total);
                }
            });
        }

        function updateMonitoringYears() {
            const yearsToDisplay = parseInt(document.getElementById('yearsToDisplay')?.value || '5');
            const yearSelect = document.getElementById('yearToMonitor');
            if (yearSelect) {
                yearSelect.innerHTML = ''; // Clear existing options
                for (let i = 1; i <= yearsToDisplay; i++) {
                    const suffix = i === 1 ? 'st' : (i === 2 ? 'nd' : (i === 3 ? 'rd' : 'th'));
                    const option = document.createElement('option');
                    option.value = i;
                    option.text = `${i}${suffix} year`;
                    yearSelect.appendChild(option);
                }
                updateMonitoringTable(); // Update table with new year range
            }
        }

        function updateExistingMonitoringTable() {
            const year = parseInt(document.getElementById('existingYearToMonitor')?.value || '1');
            const weeks = Array.from({ length: 52 }, (_, i) => `W${i + 1}`);
            const components = [
                { label: 'Service Berkala/PM', sub: [] },
                { label: 'Service General/GM', sub: [] },
                { label: 'BBM', sub: [] },
                { label: 'AdBlue', sub: [] },
                { label: 'Driver Cost', sub: [] },
                { label: 'Ban', sub: [] },
                { label: 'Downtime (1%)', sub: [] } // Tetap ada, tapi tanpa input
            ];

            const tableHead = document.getElementById('existing-monitoring-table-head');
            if (tableHead) {
                let headerHTML = `
                    <tr>
                        <th>Component</th>
                        ${weeks.map(w => `<th>${w}</th>`).join('')}
                    </tr>
                `;
                tableHead.innerHTML = headerHTML;
            }

            let tableHTML = '';
            components.forEach(component => {
                tableHTML += '<tr>';
                tableHTML += `<td>${component.label}</td>`;
                weeks.forEach(week => {
                    const idBase = `${component.label.replace(/ /g, '_').replace(/\(|\)/g, '')}_existing_${year}_${week}`;
                    if (component.label === 'Downtime (1%)') {
                        // Tampilkan nilai otomatis
                        tableHTML += `<td id="${idBase}">${formatNumberWithSeparator(0)}</td>`; // Nilai awal 0
                    } else {
                        tableHTML += `
                            <td>
                                <input type="text" id="${idBase}" oninput="formatInputWithSeparator(this); updateExistingMonitoringTotals(${year})" placeholder="0.00">
                                <span class="note-cell" onclick="showNote('${idBase}')">Note</span>
                            </td>
                        `;
                    }
                });
                tableHTML += '</tr>';
                component.sub.forEach(sub => {
                    tableHTML += '<tr>';
                    tableHTML += `<td class="subcategory">${sub}</td>`;
                    weeks.forEach(week => {
                        const id = `${component.label.replace(/ /g, '_').replace(/\(|\)/g, '')}_${sub.replace(/ /g, '_')}_existing_${year}_${week}`;
                        tableHTML += `
                            <td>
                                <input type="text" id="${id}" oninput="formatInputWithSeparator(this); updateExistingMonitoringTotals(${year})" placeholder="0.00">
                            </td>
                        `;
                    });
                    tableHTML += '</tr>';
                });
            });

            // Add total row for each week
            tableHTML += '<tr class="total-row"><td>Total</td>';
            weeks.forEach(week => {
                const totalId = `total_existing_${year}_${week}`;
                tableHTML += `<td id="${totalId}">0.00</td>`;
            });
            tableHTML += '</tr>';

            const tableBody = document.getElementById('existing-monitoring-table-body');
            if (tableBody) {
                tableBody.innerHTML = tableHTML;
                updateExistingMonitoringTotals(year); // Initialize totals
            }
        }

        function updateExistingMonitoringTotals(year) {
            const weeks = Array.from({ length: 52 }, (_, i) => `W${i + 1}`);
            const components = ['Service_Berkala/PM', 'Service_General/GM', 'BBM', 'AdBlue', 'Driver_Cost', 'Ban', 'Downtime_1%'];
            const downtimePercentage = parseFloat(document.getElementById('downtime')?.value || '0'); // Ambil dari Expense tab

            weeks.forEach(week => {
                let totalComponents = 0;
                components.forEach(component => {
                    if (component !== 'Downtime_1%') {
                        const id = `${component}_existing_${year}_${week}`;
                        const input = document.getElementById(id);
                        if (input) {
                            const value = parseFormattedNumber(input.value || '0');
                            totalComponents += value;
                        }
                    }
                });

                // Hitung Downtime (1%) = Total Komponen * (Downtime Percentage / 100)
                const downtimeId = `Downtime_1%_existing_${year}_${week}`;
                const downtimeValue = (totalComponents * (downtimePercentage / 100)) || 0;
                const downtimeCell = document.getElementById(downtimeId);
                if (downtimeCell) {
                    downtimeCell.textContent = formatNumberWithSeparator(downtimeValue);
                }

                // Hitung Total
                let total = totalComponents + downtimeValue;
                const totalId = `total_existing_${year}_${week}`;
                const totalCell = document.getElementById(totalId);
                if (totalCell) {
                    totalCell.textContent = formatNumberWithSeparator(total);
                }
            });
        }

        function updateExistingMonitoringYears() {
            const yearsToDisplay = parseInt(document.getElementById('yearsToDisplay')?.value || '5');
            const yearSelect = document.getElementById('existingYearToMonitor');
            if (yearSelect) {
                yearSelect.innerHTML = ''; // Clear existing options
                for (let i = 1; i <= yearsToDisplay; i++) {
                    const suffix = i === 1 ? 'st' : (i === 2 ? 'nd' : (i === 3 ? 'rd' : 'th'));
                    const option = document.createElement('option');
                    option.value = i;
                    option.text = `${i}${suffix} year`;
                    yearSelect.appendChild(option);
                }
                updateExistingMonitoringTable(); // Update table with new year range
            }
        }

        async function showNote(id) {
            try {
                // Parse ID untuk mendapatkan informasi komponen
                const idParts = id.split('_');
                let component, year, week;
                
                // Cek apakah ini existing monitoring atau regular monitoring
                const isExisting = id.includes('_existing_');
                
                if (isExisting) {
                    // Format: Component_existing_year_week
                    const existingIndex = idParts.indexOf('existing');
                    component = idParts.slice(0, existingIndex).join('_');
                    year = parseInt(idParts[existingIndex + 1]);
                    week = parseInt(idParts[existingIndex + 2].replace('W', ''));
                } else {
                    // Format: Component_year_week
                    const lastTwoParts = idParts.slice(-2);
                    year = parseInt(lastTwoParts[0]);
                    week = parseInt(lastTwoParts[1].replace('W', ''));
                    component = idParts.slice(0, -2).join('_');
                }
                
                // Ambil unit police number
                const unitPoliceNumber = isExisting 
                    ? document.getElementById('existingUnitPoliceNumber')?.value || ''
                    : document.getElementById('unitPoliceNumber')?.value || '';
                
                console.log('DEBUG - Parsed ID:', {
                    id: id,
                    isExisting: isExisting,
                    component: component,
                    year: year,
                    week: week,
                    unitPoliceNumber: unitPoliceNumber
                });
                
                if (!unitPoliceNumber) {
                    alert('Mohon isi Nomor Polisi Unit terlebih dahulu!');
                    return;
                }
                
                // Ambil note yang sudah ada
                let existingNote = '';
                if (typeof costModelAPI !== 'undefined') {
                    try {
                        const noteData = await costModelAPI.getMonitoringNote(unitPoliceNumber, year, week, component);
                        existingNote = noteData?.note || '';
                    } catch (error) {
                        console.error('Error getting existing note:', error);
                    }
                }
                
                // Tampilkan popup untuk input note
                const input = document.getElementById(id);
                const currentValue = input ? input.value || '0.00' : 'N/A';
                const note = prompt(
                    `Enter note for ${id}\nCurrent value: ${currentValue} IDR\n\nNote:`,
                    existingNote
                );
                
                if (note !== null) { // User tidak klik Cancel
                    if (typeof costModelAPI !== 'undefined') {
                        console.log('DEBUG - Saving note with data:', {
                            unitPoliceNumber: unitPoliceNumber,
                            year: year,
                            week: week,
                            component: component,
                            note: note
                        });
                        
                        await costModelAPI.saveMonitoringNote(unitPoliceNumber, year, week, component, note);
                        alert(`Note berhasil disimpan untuk ${id}: ${note}`);
                    } else {
                        alert('Error: costModelAPI tidak tersedia');
                    }
                }
                
            } catch (error) {
                console.error('Error in showNote:', error);
                alert('Terjadi kesalahan saat menyimpan note: ' + error.message);
            }
        }

        function showTab(tabId) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            const tabElement = document.querySelector(`[onclick="showTab('${tabId}')"]`);
            const contentElement = document.getElementById(tabId);
            
            if (tabElement) tabElement.classList.add('active');
            if (contentElement) contentElement.classList.add('active');
            
            // Show/hide Master Nomor Polisi button based on active tab
            const masterPoliceUnitBtn = document.getElementById('masterPoliceUnitBtn');
            if (masterPoliceUnitBtn) {
                if (tabId === 'monitoring') {
                    masterPoliceUnitBtn.style.display = 'block';
                } else {
                    masterPoliceUnitBtn.style.display = 'none';
                }
            }
            
            // Handle different tabs appropriately
            if (tabId === 'dashboard') {
                // For dashboard, try to load from stored data first
                setTimeout(async () => {
                    try {
                        if (typeof costModelAPI !== 'undefined') {
                            const dashboardData = await costModelAPI.getDashboardData();
                            if (dashboardData && dashboardData.dashboard_data) {
                                populateDashboardFromStoredData(dashboardData.dashboard_data);
                            } else {
                                // If no stored data, run calculation
                                calculateTCO();
                            }
                        }
                    } catch (error) {
                        console.error('Error loading dashboard data:', error);
                        // Fallback to calculation
                        calculateTCO();
                    }
                }, 100);
            } else if (tabId === 'expense') {
                // For expense tab, only run calculation if there's no stored data
                setTimeout(async () => {
                    try {
                        if (typeof costModelAPI !== 'undefined') {
                            const dashboardData = await costModelAPI.getDashboardData();
                            if (!dashboardData || !dashboardData.dashboard_data) {
                                calculateTCO();
                            }
                        }
                    } catch (error) {
                        console.error('Error checking stored data:', error);
                        calculateTCO();
                    }
                }, 100);
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
        }

        // Load dashboard data from database
        async function loadDashboardData() {
            try {
                if (typeof costModelAPI !== 'undefined') {
                    const dashboardData = await costModelAPI.getDashboardData();
                    if (dashboardData && dashboardData.dashboard_data) {
                        console.log('Dashboard data loaded from database:', dashboardData.dashboard_data);
                        
                        // If we're on dashboard tab, populate the table with stored data
                        const activeTab = document.querySelector('.tab.active');
                        if (activeTab && activeTab.textContent.includes('Dashboard')) {
                            populateDashboardFromStoredData(dashboardData.dashboard_data);
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        // Populate dashboard table from stored data without calculation
        function populateDashboardFromStoredData(dashboardData) {
            try {
                if (!dashboardData || !dashboardData.rows) return;
                
                const yearsToDisplay = parseInt(document.getElementById('yearsToDisplay')?.value || '5');
                const dashboardTableHead = document.getElementById('dashboard-table-head');
                const dashboardTableBody = document.getElementById('dashboard-table-body');
                
                if (!dashboardTableHead || !dashboardTableBody) return;
                
                // Generate table header
                const headerHTML = `
                    <tr>
                        <th>Component</th>
                        <th>Price (IDR)</th>
                        <th>1st Year</th>
                        <th>2nd Year</th>
                        <th>3rd Year</th>
                        <th>4th Year</th>
                        <th>5th Year</th>
                        ${yearsToDisplay > 5 ? `<th>6th Year</th>` : ''}
                        ${yearsToDisplay > 6 ? `<th>7th Year</th>` : ''}
                        ${yearsToDisplay > 7 ? `<th>8th Year</th>` : ''}
                        ${yearsToDisplay > 8 ? `<th>9th Year</th>` : ''}
                        ${yearsToDisplay > 9 ? `<th>10th Year</th>` : ''}
                    </tr>
                `;
                dashboardTableHead.innerHTML = headerHTML;
                
                // Generate table body from stored data
                let tableHTML = '';
                let currentCategory = '';
                
                dashboardData.rows.forEach(row => {
                    if (row.category !== currentCategory) {
                        if (currentCategory === 'Actual' && dashboardData.actual_totals && dashboardData.actual_totals.some(t => t > 0)) {
                            tableHTML += '<tr class="total-row"><td>Total Actual</td><td></td>';
                            for (let i = 0; i < 10; i++) {
                                tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}>${formatNumberWithSeparator(dashboardData.actual_totals[i] || 0)}</td>`;
                            }
                            tableHTML += '</tr>';
                        } else if (currentCategory === 'Assumption' && dashboardData.assumption_totals && dashboardData.assumption_totals.some(t => t > 0)) {
                            tableHTML += '<tr class="total-row"><td>Total Assumptions</td><td></td>';
                            for (let i = 0; i < 10; i++) {
                                tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}>${formatNumberWithSeparator(dashboardData.assumption_totals[i] || 0)}</td>`;
                            }
                            tableHTML += '</tr>';
                        }
                        
                        if (row.category === 'Assumption') {
                            tableHTML += `<tr class="category" style="background-color: #28a745 !important;"><td>${row.category}</td><td></td>`;
                            for (let i = 0; i < 10; i++) {
                                tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}></td>`;
                            }
                            tableHTML += '</tr>';
                        } else {
                            tableHTML += `<tr class="category" style="background-color: #28a745 !important;"><td>${row.category}</td><td colspan="${yearsToDisplay + 1}"></td></tr>`;
                        }
                        currentCategory = row.category;
                    }
                    
                    tableHTML += '<tr>';
                    tableHTML += `<td>${row.label}</td>`;
                    const assumptionValue = typeof row.assumption === 'number' ? formatNumberWithSeparator(row.assumption) : row.assumption;
                    tableHTML += `<td class="assumption">${assumptionValue}</td>`;
                    
                    for (let i = 0; i < 10; i++) {
                        const displayValue = typeof row.values[i] === 'string' ? row.values[i] : formatNumberWithSeparator(row.values[i] || 0);
                        tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}>${displayValue}</td>`;
                    }
                    tableHTML += '</tr>';
                });
                
                // Add totals
                if (dashboardData.yearly_totals) {
                    tableHTML += '<tr class="total-per-year"><td>Total per Year</td><td></td>';
                    for (let i = 0; i < 10; i++) {
                        tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}>${formatNumberWithSeparator(dashboardData.yearly_totals[i] || 0)}</td>`;
                    }
                    tableHTML += '</tr>';
                    
                    tableHTML += '<tr><td>Monthly Cost</td><td></td>';
                    for (let i = 0; i < 10; i++) {
                        const monthly = (dashboardData.yearly_totals[i] || 0) / 12;
                        tableHTML += `<td ${i >= yearsToDisplay ? 'style="display:none"' : ''}>${formatNumberWithSeparator(monthly)}</td>`;
                    }
                    tableHTML += '</tr>';
                    
                    const grandTotal = dashboardData.grand_total || 0;
                    tableHTML += `<tr class="grand-total"><td>Grand Total</td><td></td><td colspan="${yearsToDisplay}">${formatNumberWithSeparator(grandTotal)}</td>`;
                    for (let i = yearsToDisplay; i < 10; i++) {
                        tableHTML += '<td style="display:none"></td>';
                    }
                    tableHTML += '</tr>';
                }
                
                dashboardTableBody.innerHTML = tableHTML;
                
                // Apply styling to grand total row
                const grandTotalRow = document.querySelector('.grand-total');
                if (grandTotalRow) {
                    grandTotalRow.style.backgroundColor = '#0056b3';
                    grandTotalRow.style.color = '#fff';
                }
                
                console.log('Dashboard populated from stored data');
            } catch (error) {
                console.error('Error populating dashboard from stored data:', error);
            }
        }

        // Save dashboard data to database
        async function saveDashboardDataToDatabase(rows, yearlyTotals, actualTotals, assumptionTotals) {
            try {
                if (typeof costModelAPI !== 'undefined') {
                    // Trigger calculation to save dashboard data
                    await costModelAPI.calculate();
                    console.log('Dashboard data saved to database successfully');
                    showAutoSaveNotification('Data dashboard berhasil disimpan ke database!', 'success');
                }
            } catch (error) {
                console.error('Error saving dashboard data:', error);
                showAutoSaveNotification('Gagal menyimpan data dashboard: ' + error.message, 'error');
            }
        }

        // Load monitoring data for selected year
        async function loadMonitoringDataForYear() {
            try {
                if (typeof costModelAPI !== 'undefined') {
                    const year = parseInt(document.getElementById('yearToMonitor')?.value || '1');
                    const unitPoliceNumberElement = document.getElementById('unitPoliceNumber');
                    const unitPoliceNumber = unitPoliceNumberElement?.value || '';
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

        // Load police units data untuk dropdown
        async function loadPoliceUnitsForDropdown() {
            try {
                if (typeof costModelAPI !== 'undefined') {
                    const policeUnits = await costModelAPI.getAllUnitPoliceNumbers();
                    console.log('Police units loaded for dropdown:', policeUnits);
                    
                    // Populate monitoring dropdown
                    const monitoringDropdown = document.getElementById('unitPoliceNumber');
                    if (monitoringDropdown) {
                        // Clear existing options except the first one
                        monitoringDropdown.innerHTML = '';
                        
                        policeUnits.forEach(unit => {
                            const option = document.createElement('option');
                            option.value = unit.id; // Use id as value
                            option.textContent = `${unit.police_number}`;
                            monitoringDropdown.appendChild(option);
                        });
                    }
                    
                    // Populate existing monitoring dropdown
                    const existingMonitoringDropdown = document.getElementById('existingUnitPoliceNumber');
                    if (existingMonitoringDropdown) {
                        // Clear existing options except the first one
                        existingMonitoringDropdown.innerHTML = '';
                        
                        policeUnits.forEach(unit => {
                            const option = document.createElement('option');
                            option.value = unit.id; // Use id as value
                            option.textContent = `${unit.police_number}`;
                            existingMonitoringDropdown.appendChild(option);
                        });
                    }
                }
            } catch (error) {
                console.error('Error loading police units for dropdown:', error);
            }
        }

        // Load monitoring data for selected unit
        async function loadMonitoringDataForSelectedUnit() {
            try {
                const unitPoliceNumberElement = document.getElementById('unitPoliceNumber');
                const unitPoliceNumber = unitPoliceNumberElement?.value || '';
                const year = parseInt(document.getElementById('yearToMonitor')?.value || '1');
                
                if (unitPoliceNumber) {
                    await costModelAPI.loadMonitoringData(year, unitPoliceNumber);
                    console.log(`Monitoring data loaded for unit ${unitPoliceNumber} and year ${year}`);
                    
                    // Update monitoring totals after data is loaded
                    setTimeout(() => {
                        updateMonitoringTotals(year);
                        console.log(`Monitoring totals updated for year ${year}`);
                    }, 200);
                } else {
                    // Clear monitoring table if no unit selected
                    const tableBody = document.getElementById('monitoring-table-body');
                    if (tableBody) {
                        tableBody.innerHTML = '';
                    }
                }
            } catch (error) {
                console.error('Error loading monitoring data for selected unit:', error);
            }
        }

        // Load existing monitoring data for selected unit
        async function loadExistingMonitoringDataForSelectedUnit() {
            try {
                const unitPoliceNumberElement = document.getElementById('existingUnitPoliceNumber');
                const unitPoliceNumber = unitPoliceNumberElement?.value || '';
                const year = parseInt(document.getElementById('existingYearToMonitor')?.value || '1');
                
                if (unitPoliceNumber) {
                    await costModelAPI.loadExistingMonitoringData(year, unitPoliceNumber);
                    console.log(`Existing monitoring data loaded for unit ${unitPoliceNumber} and year ${year}`);
                    
                    // Update existing monitoring totals after data is loaded
                    setTimeout(() => {
                        updateExistingMonitoringTotals(year);
                        console.log(`Existing monitoring totals updated for year ${year}`);
                    }, 200);
                } else {
                    // Clear existing monitoring table if no unit selected
                    const tableBody = document.getElementById('existing-monitoring-table-body');
                    if (tableBody) {
                        tableBody.innerHTML = '';
                    }
                }
            } catch (error) {
                console.error('Error loading existing monitoring data for selected unit:', error);
            }
        }

        // Load monitoring metadata (unit police number) for all years
        async function loadMonitoringMetadata() {
            try {
                if (typeof costModelAPI !== 'undefined') {
                    // Try to load metadata from year 1 first
                    const year = 1;
                    const unitPoliceNumberElement = document.getElementById('unitPoliceNumber');
                    const unitPoliceNumber = unitPoliceNumberElement?.value || '';
                    await costModelAPI.loadMonitoringData(year, unitPoliceNumber);
                    console.log('Monitoring metadata loaded');
                }
            } catch (error) {
                console.error('Error loading monitoring metadata:', error);
            }
        }

        // Load existing monitoring data for selected year
        async function loadExistingMonitoringDataForYear() {
            try {
                if (typeof costModelAPI !== 'undefined') {
                    const year = parseInt(document.getElementById('existingYearToMonitor')?.value || '1');
                    const unitPoliceNumberElement = document.getElementById('existingUnitPoliceNumber');
                    const unitPoliceNumber = unitPoliceNumberElement?.value || '';
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

        // Load existing monitoring metadata (unit police number) for all years
        async function loadExistingMonitoringMetadata() {
            try {
                if (typeof costModelAPI !== 'undefined') {
                    // Try to load metadata from year 1 first
                    const year = 1;
                    const unitPoliceNumberElement = document.getElementById('existingUnitPoliceNumber');
                    const unitPoliceNumber = unitPoliceNumberElement?.value || '';
                    await costModelAPI.loadExistingMonitoringData(year, unitPoliceNumber);
                    console.log('Existing monitoring metadata loaded');
                }
            } catch (error) {
                console.error('Error loading existing monitoring metadata:', error);
            }
        }

        // Initialize tables on load
        window.onload = function() {
            try {
                // Hide Master Nomor Polisi button initially (only show on monitoring tab)
                const masterPoliceUnitBtn = document.getElementById('masterPoliceUnitBtn');
                if (masterPoliceUnitBtn) {
                    masterPoliceUnitBtn.style.display = 'none';
                }
                
                // Load dashboard data from database first
                loadDashboardData();
                
                // Initialize monitoring tables without calculation
                updateMonitoringYears(); // Initialize year options
                updateExistingMonitoringYears(); // Initialize year options for existing monitoring
                updateMonitoringTable();
                updateExistingMonitoringTable();
                
                // Load police units data untuk dropdown
                setTimeout(async () => {
                    await loadPoliceUnitsForDropdown();
                    await loadMonitoringMetadata();
                    await loadExistingMonitoringMetadata();
                }, 200);
                
                // Setup auto-save untuk semua input fields yang sudah ada
                if (typeof setupAutoSave === 'function') {
                    setupAutoSave();
                }
                
                // Setup event listeners untuk input number fields
                const numberInputs = document.querySelectorAll('input[type="number"]');
                numberInputs.forEach(input => {
                    input.addEventListener('input', function() {
                        if (typeof autoSaveInput === 'function') {
                            autoSaveInput(this.id, 'form');
                        }
                    });
                });
                
                // Setup monitoring auto-save
                if (typeof setupMonitoringAutoSave === 'function') {
                    setupMonitoringAutoSave();
                }
                
                // Setup year selector listeners
                if (typeof setupYearSelectorListeners === 'function') {
                    setupYearSelectorListeners();
                }
                
                console.log('Initialization completed');
            } catch (error) {
                console.error('Error during initialization:', error);
            }
        };

        // Police Units Modal Functions
        let deletePoliceUnitId = null;

        function openPoliceUnitsModal() {
            document.getElementById('policeUnitsModal').style.display = 'block';
            loadPoliceUnitsForModal();
            // Reset form when opening modal
            resetPoliceUnitForm();
        }

        function closePoliceUnitsModal() {
            document.getElementById('policeUnitsModal').style.display = 'none';
            resetPoliceUnitForm();
        }

        function closeDeleteModal() {
            document.getElementById('deletePoliceUnitModal').style.display = 'none';
            deletePoliceUnitId = null;
        }

        // Load police units for modal
        async function loadPoliceUnitsForModal() {
            try {
                const response = await fetch('/api/cost-model/police-units');
                const result = await response.json();
                
                if (result.success) {
                    displayPoliceUnitsInModal(result.data);
                } else {
                    console.error('Error loading police units:', result.message);
                }
            } catch (error) {
                console.error('Error loading police units:', error);
                // Notifikasi error loading juga dihilangkan
            }
        }

        // Display police units in modal table
        function displayPoliceUnitsInModal(data) {
            const tbody = document.getElementById('policeUnitsTable');
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 20px;">Tidak ada data</td></tr>';
                return;
            }

            tbody.innerHTML = data.map((unit, index) => `
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 12px; border: 1px solid #dee2e6;">${index + 1}</td>
                    <td style="padding: 12px; border: 1px solid #dee2e6;"><strong>${unit.police_number}</strong></td>
                    <td style="padding: 12px; border: 1px solid #dee2e6;">
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; ${unit.is_active ? 'background-color: #28a745; color: white;' : 'background-color: #dc3545; color: white;'}">
                            ${unit.is_active ? 'Aktif' : 'Tidak Aktif'}
                        </span>
                    </td>
                    <td style="padding: 12px; border: 1px solid #dee2e6;">
                        <button onclick="editPoliceUnitInModal(${unit.id})" style="background: #ffc107; color: #212529; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deletePoliceUnitInModal(${unit.id}, '${unit.police_number}')" style="background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Handle form submission for police unit
        document.getElementById('policeUnitForm').addEventListener('submit', function(e) {
            e.preventDefault();
            savePoliceUnitInModal();
        });

        // Save police unit in modal
        async function savePoliceUnitInModal() {
            const form = document.getElementById('policeUnitForm');
            const formData = new FormData(form);
            const data = {};

            // Hanya ambil data yang diperlukan
            if (formData.get('police_number')) {
                data.police_number = formData.get('police_number');
            }
            
            if (formData.get('id')) {
                data.id = formData.get('id');
            }

            try {
                const response = await fetch('/api/cost-model/police-units', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                
                if (result.success) {
                    // Notifikasi sukses dihilangkan untuk police units
                    console.log('Police unit saved:', result.message);
                    resetPoliceUnitForm();
                    loadPoliceUnitsForModal();
                    // Reload dropdown data
                    await loadPoliceUnitsForDropdown();
                } else {
                    console.error('Error saving police unit:', result.message);
                }
            } catch (error) {
                console.error('Error saving police unit:', error);
                // Notifikasi error juga dihilangkan untuk police units
            }
        }

        // Edit police unit in modal
        async function editPoliceUnitInModal(id) {
            try {
                const response = await fetch(`/api/cost-model/police-units`);
                const result = await response.json();
                
                if (result.success) {
                    const unit = result.data.find(u => u.id === id);
                    if (unit) {
                        document.getElementById('editId').value = unit.id;
                        document.getElementById('policeNumber').value = unit.police_number;
                        
                        document.getElementById('formTitle').textContent = 'Edit Nomor Polisi';
                        document.getElementById('policeNumber').focus();
                    }
                }
            } catch (error) {
                console.error('Error loading police unit for edit:', error);
                // Notifikasi error edit juga dihilangkan
            }
        }

        // Delete police unit in modal
        function deletePoliceUnitInModal(id, policeNumber) {
            deletePoliceUnitId = id;
            document.getElementById('deletePoliceNumber').textContent = policeNumber;
            document.getElementById('deletePoliceUnitModal').style.display = 'block';
        }

        // Confirm delete police unit
        async function confirmDeletePoliceUnit() {
            if (!deletePoliceUnitId) return;

            try {
                const response = await fetch('/api/cost-model/police-units', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ id: deletePoliceUnitId })
                });

                const result = await response.json();
                
                if (result.success) {
                    // Notifikasi sukses dihilangkan untuk delete police units
                    console.log('Police unit deleted:', result.message);
                    loadPoliceUnitsForModal();
                    // Reload dropdown data
                    await loadPoliceUnitsForDropdown();
                } else {
                    console.error('Error deleting police unit:', result.message);
                }
            } catch (error) {
                console.error('Error deleting police unit:', error);
                // Notifikasi error juga dihilangkan untuk delete police units
            } finally {
                closeDeleteModal();
            }
        }

        // Reset police unit form
        function resetPoliceUnitForm() {
            document.getElementById('policeUnitForm').reset();
            document.getElementById('editId').value = '';
            document.getElementById('formTitle').textContent = 'Tambah Nomor Polisi Baru';
        }

        // Show alert function
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; background-color: ' + (type === 'success' ? '#d4edda' : '#f8d7da') + '; color: ' + (type === 'success' ? '#155724' : '#721c24') + '; padding: 12px 20px; border-radius: 4px; border: 1px solid ' + (type === 'success' ? '#c3e6cb' : '#f5c6cb') + ';';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()" style="float: right; background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const policeUnitsModal = document.getElementById('policeUnitsModal');
            const deleteModal = document.getElementById('deletePoliceUnitModal');
            
            if (event.target === policeUnitsModal) {
                closePoliceUnitsModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }

        // Add keyboard support for closing modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const policeUnitsModal = document.getElementById('policeUnitsModal');
                const deleteModal = document.getElementById('deletePoliceUnitModal');
                
                if (policeUnitsModal.style.display === 'block') {
                    closePoliceUnitsModal();
                }
                if (deleteModal.style.display === 'block') {
                    closeDeleteModal();
                }
            }
        });
    </script>
</body>
</html>