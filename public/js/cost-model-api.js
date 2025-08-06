/**
 * Cost Model Calculator API Integration
 * File untuk mengintegrasikan form HTML dengan API Laravel
 */

// Cost Model API Integration
class CostModelAPI {
    constructor() {
        this.baseURL = '/api/cost-model';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    // Helper untuk format number
    formatNumberWithSeparator(value) {
        const number = parseFloat(value) || 0;
        if (number >= 1000) {
            const parts = number.toFixed(2).split(".");
            const integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return `${integerPart}.${parts[1]}`;
        }
        return number.toFixed(2);
    }

    // Helper untuk parse formatted number
    parseFormattedNumber(value) {
        // Handle null, undefined, atau nilai non-string
        if (value === null || value === undefined) {
            return 0;
        }
        
        // Convert ke string jika bukan string
        const stringValue = String(value);
        
        // Remove commas dan parse ke float
        return parseFloat(stringValue.replace(/,/g, "")) || 0;
    }

    // Mengambil semua data yang tersimpan
    async getStoredData() {
        try {
            const response = await fetch(`${this.baseURL}/stored-data`);
            const data = await response.json();
            
            if (data.success) {
                return data.data;
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error fetching stored data:', error);
            return null;
        }
    }

    // Menyimpan atau mengupdate semua data settings dan expenses
    async upsertAllData(formData) {
        try {
            const response = await fetch(`${this.baseURL}/upsert-all`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();
            
            if (data.success) {
                console.log('Data berhasil disimpan/diperbarui:', data.message);
                return data.data;
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error upserting data:', error);
            throw error;
        }
    }

    // Menyimpan atau mengupdate data monitoring
    async upsertMonitoringData(monitoringData) {
        try {
            const response = await fetch(`${this.baseURL}/upsert-monitoring`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(monitoringData)
            });

            const data = await response.json();
            
            if (data.success) {
                console.log('Monitoring data berhasil disimpan/diperbarui:', data.message);
                return data.data;
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error upserting monitoring data:', error);
            throw error;
        }
    }

    // Menyimpan atau mengupdate data existing monitoring
    async upsertExistingMonitoringData(monitoringData) {
        try {
            const response = await fetch(`${this.baseURL}/upsert-existing-monitoring`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(monitoringData)
            });

            const data = await response.json();
            
            if (data.success) {
                console.log('Existing monitoring data berhasil disimpan/diperbarui:', data.message);
                return data.data;
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error upserting existing monitoring data:', error);
            throw error;
        }
    }

    // Mengambil data monitoring
    async getMonitoringData(filters = {}) {
        try {
            const queryParams = new URLSearchParams(filters);
            const response = await fetch(`${this.baseURL}/monitoring-data?${queryParams}`);
            const data = await response.json();
            
            if (data.success) {
                return data.data;
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error fetching monitoring data:', error);
            return [];
        }
    }

    // Melakukan perhitungan
    async calculate() {
        try {
            const response = await fetch(`${this.baseURL}/calculate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();
            
            if (data.success) {
                console.log('Perhitungan berhasil:', data.message);
                return data.data;
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error calculating:', error);
            throw error;
        }
    }

    // Mengambil data dashboard dari database
    async getDashboardData() {
        try {
            const response = await fetch(`${this.baseURL}/dashboard-data`);
            const data = await response.json();
            
            if (data.success) {
                console.log('Data dashboard berhasil diambil:', data.message);
                return data.data;
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
            return null;
        }
    }

    // Mengambil data monitoring terakhir berdasarkan nopol terakhir
    async getLatestMonitoringData() {
        try {
            const response = await fetch(`${this.baseURL}/latest-monitoring-data`);
            const data = await response.json();
            
            if (data.success) {
                console.log('Data monitoring terakhir berhasil diambil:', data.message);
                return data;
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error fetching latest monitoring data:', error);
            return null;
        }
    }

    // Mengambil daftar semua nopol
    async getAllUnitPoliceNumbers() {
        try {
            const response = await fetch(`${this.baseURL}/all-unit-police-numbers`);
            const data = await response.json();
            
            if (data.success) {
                console.log('Daftar nopol berhasil diambil:', data.message);
                return data.data;
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error fetching unit police numbers:', error);
            return [];
        }
    }

    // Menyimpan note untuk monitoring data
    async saveMonitoringNote(unitPoliceNumber, year, week, component, note) {
        try {
            const response = await fetch(`${this.baseURL}/save-monitoring-note`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    unit_police_number: unitPoliceNumber,
                    year: year,
                    week: week,
                    component: component,
                    note: note
                })
            });

            const data = await response.json();
            
            if (data.success) {
                console.log('Monitoring note berhasil disimpan:', data.message);
                return data.data;
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error saving monitoring note:', error);
            throw error;
        }
    }

    // Mengambil note untuk monitoring data
    async getMonitoringNote(unitPoliceNumber, year, week, component) {
        try {
            const params = new URLSearchParams({
                unit_police_number: unitPoliceNumber,
                year: year,
                week: week,
                component: component
            });

            const response = await fetch(`${this.baseURL}/get-monitoring-note?${params}`);
            const data = await response.json();
            
            if (data.success) {
                console.log('Monitoring note berhasil diambil:', data.message);
                return data.data;
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error getting monitoring note:', error);
            return null;
        }
    }

    // Mengumpulkan semua data dari form
    collectFormData() {
        const formData = {};

        // Settings - Actual
        formData.units_price = this.parseFormattedNumber(document.getElementById('unitsPrice')?.value || '0');
        formData.qty_units = parseFloat(document.getElementById('qtyUnits')?.value || '0');
        formData.net_book_value = parseFloat(document.getElementById('netBookValue')?.value || '0');
        formData.solar_price = this.parseFormattedNumber(document.getElementById('solarPrice')?.value || '0');
        formData.adblue_price = this.parseFormattedNumber(document.getElementById('adBluePrice')?.value || '0');

        // Settings - Assumption
        formData.retase_per_day = parseFloat(document.getElementById('retasePerDay')?.value || '0');
        formData.avg_ritase_per_day = parseFloat(document.getElementById('avgRitasePerDay')?.value || '0');
        formData.fuel_consumption = parseFloat(document.getElementById('fuelConsumption')?.value || '0');
        formData.adblue_consumption = parseFloat(document.getElementById('adBlue')?.value || '0');
        formData.day_operation = parseFloat(document.getElementById('dayOperation')?.value || '0');

        // Expenses - Actual
        formData.insurance_unit = this.parseFormattedNumber(document.getElementById('insuranceUnit')?.value || '0');
        formData.first_payment = this.parseFormattedNumber(document.getElementById('firstPayment')?.value || '0');
        formData.leasing_payment = this.parseFormattedNumber(document.getElementById('leasingPayment')?.value || '0');
        formData.vehicle_tax = this.parseFormattedNumber(document.getElementById('vehicleTax')?.value || '0');
        formData.kir = this.parseFormattedNumber(document.getElementById('kir')?.value || '0');
        formData.telematics_one_time_cost = this.parseFormattedNumber(document.getElementById('telematicsOneTimeCost')?.value || '0');
        formData.telematics_recurring_cost = this.parseFormattedNumber(document.getElementById('telematicsRecurringCost')?.value || '0');
        formData.tire_price = this.parseFormattedNumber(document.getElementById('tirePrice')?.value || '0');
        formData.lifetime_tyre = this.parseFormattedNumber(document.getElementById('lifetimeTyre')?.value || '0');
        formData.oil_price = this.parseFormattedNumber(document.getElementById('oilPrice')?.value || '0');

        // PM Costs (10 tahun)
        for (let i = 1; i <= 10; i++) {
            formData[`pm_year_${i}`] = this.parseFormattedNumber(document.getElementById(`pm${i}`)?.value || '0');
        }

        // GM Costs (10 tahun)
        for (let i = 1; i <= 10; i++) {
            formData[`gm_year_${i}`] = this.parseFormattedNumber(document.getElementById(`gm${i}`)?.value || '0');
        }

        // Expenses - Assumption
        formData.toll_cost = this.parseFormattedNumber(document.getElementById('tollCost')?.value || '0');
        formData.driver_per_unit = parseFloat(document.getElementById('driverPerUnit')?.value || '0');
        formData.driver_cost = this.parseFormattedNumber(document.getElementById('driverCost')?.value || '0');
        formData.tyre_per_unit = parseFloat(document.getElementById('tyrePerUnit')?.value || '0');
        formData.downtime_percentage = parseFloat(document.getElementById('downtime')?.value || '0');

        return formData;
    }

    // Mengisi form dengan data yang tersimpan
    populateFormWithStoredData(data) {
        if (!data) return;

        const { setting, expense } = data;

        if (setting) {
            // Settings - Actual
            if (document.getElementById('unitsPrice')) {
                document.getElementById('unitsPrice').value = this.formatNumberWithSeparator(setting.units_price);
            }
            if (document.getElementById('qtyUnits')) {
                document.getElementById('qtyUnits').value = setting.qty_units;
            }
            if (document.getElementById('netBookValue')) {
                document.getElementById('netBookValue').value = setting.net_book_value;
            }
            if (document.getElementById('solarPrice')) {
                document.getElementById('solarPrice').value = this.formatNumberWithSeparator(setting.solar_price);
            }
            if (document.getElementById('adBluePrice')) {
                document.getElementById('adBluePrice').value = this.formatNumberWithSeparator(setting.adblue_price);
            }

            // Settings - Assumption
            if (document.getElementById('retasePerDay')) {
                document.getElementById('retasePerDay').value = setting.retase_per_day;
            }
            if (document.getElementById('avgRitasePerDay')) {
                document.getElementById('avgRitasePerDay').value = setting.avg_ritase_per_day;
            }
            if (document.getElementById('fuelConsumption')) {
                document.getElementById('fuelConsumption').value = setting.fuel_consumption;
            }
            if (document.getElementById('adBlue')) {
                document.getElementById('adBlue').value = setting.adblue_consumption;
            }
            if (document.getElementById('dayOperation')) {
                document.getElementById('dayOperation').value = setting.day_operation;
            }
        }

        if (expense) {
            // Expenses - Actual
            if (document.getElementById('insuranceUnit')) {
                document.getElementById('insuranceUnit').value = this.formatNumberWithSeparator(expense.insurance_unit);
            }
            if (document.getElementById('firstPayment')) {
                document.getElementById('firstPayment').value = this.formatNumberWithSeparator(expense.first_payment);
            }
            if (document.getElementById('leasingPayment')) {
                document.getElementById('leasingPayment').value = this.formatNumberWithSeparator(expense.leasing_payment);
            }
            if (document.getElementById('vehicleTax')) {
                document.getElementById('vehicleTax').value = this.formatNumberWithSeparator(expense.vehicle_tax);
            }
            if (document.getElementById('kir')) {
                document.getElementById('kir').value = this.formatNumberWithSeparator(expense.kir);
            }
            if (document.getElementById('telematicsOneTimeCost')) {
                document.getElementById('telematicsOneTimeCost').value = this.formatNumberWithSeparator(expense.telematics_one_time_cost);
            }
            if (document.getElementById('telematicsRecurringCost')) {
                document.getElementById('telematicsRecurringCost').value = this.formatNumberWithSeparator(expense.telematics_recurring_cost);
            }
            if (document.getElementById('tirePrice')) {
                document.getElementById('tirePrice').value = this.formatNumberWithSeparator(expense.tire_price);
            }
            if (document.getElementById('lifetimeTyre')) {
                document.getElementById('lifetimeTyre').value = this.formatNumberWithSeparator(expense.lifetime_tyre);
            }
            if (document.getElementById('oilPrice')) {
                document.getElementById('oilPrice').value = this.formatNumberWithSeparator(expense.oil_price);
            }

            // PM Costs (10 tahun)
            for (let i = 1; i <= 10; i++) {
                const element = document.getElementById(`pm${i}`);
                if (element) {
                    element.value = this.formatNumberWithSeparator(expense[`pm_year_${i}`]);
                }
            }

            // GM Costs (10 tahun)
            for (let i = 1; i <= 10; i++) {
                const element = document.getElementById(`gm${i}`);
                if (element) {
                    element.value = this.formatNumberWithSeparator(expense[`gm_year_${i}`]);
                }
            }

            // Expenses - Assumption
            if (document.getElementById('tollCost')) {
                document.getElementById('tollCost').value = this.formatNumberWithSeparator(expense.toll_cost);
            }
            if (document.getElementById('driverPerUnit')) {
                document.getElementById('driverPerUnit').value = expense.driver_per_unit;
            }
            if (document.getElementById('driverCost')) {
                document.getElementById('driverCost').value = this.formatNumberWithSeparator(expense.driver_cost);
            }
            if (document.getElementById('tyrePerUnit')) {
                document.getElementById('tyrePerUnit').value = expense.tyre_per_unit;
            }
            if (document.getElementById('downtime')) {
                document.getElementById('downtime').value = expense.downtime_percentage;
            }
        }
    }

    // Menyimpan data monitoring dari tabel
    async saveMonitoringData(year, week, component, value, note = '') {
        const unitPoliceNumberElement = document.getElementById('unitPoliceNumber');
        const unitPoliceNumber = unitPoliceNumberElement?.value || '';
        
        const monitoringData = {
            unit_police_number: unitPoliceNumber, // This is now the id
            year: year,
            week: week,
            component: component,
            value: this.parseFormattedNumber(value),
            note: note
        };

        console.log('DEBUG - Saving monitoring data:', monitoringData);

        try {
            await this.upsertMonitoringData(monitoringData);
        } catch (error) {
            console.error('Error saving monitoring data:', error);
        }
    }

    // Menyimpan data existing monitoring dari tabel
    async saveExistingMonitoringData(year, week, component, value, note = '') {
        const unitPoliceNumberElement = document.getElementById('existingUnitPoliceNumber');
        const unitPoliceNumber = unitPoliceNumberElement?.value || '';
        
        const monitoringData = {
            unit_police_number: unitPoliceNumber, // This is now the id
            year: year,
            week: week,
            component: component,
            value: this.parseFormattedNumber(value),
            note: note
        };

        console.log('DEBUG - Saving existing monitoring data:', monitoringData);

        try {
            await this.upsertExistingMonitoringData(monitoringData);
        } catch (error) {
            console.error('Error saving existing monitoring data:', error);
        }
    }

    // Load monitoring data untuk tahun tertentu
    async loadMonitoringData(year, unitPoliceNumber = '') {
        const filters = {
            year: year
        };
        
        if (unitPoliceNumber) {
            // If unitPoliceNumber is an id (integer), we need to get the police_number first
            if (typeof unitPoliceNumber === 'string' && unitPoliceNumber.match(/^\d+$/)) {
                // It's an id, we need to get the police_number from the dropdown
                const dropdown = document.getElementById('unitPoliceNumber');
                if (dropdown) {
                    const selectedOption = dropdown.options[dropdown.selectedIndex];
                    if (selectedOption) {
                        filters.unit_police_number = selectedOption.textContent; // Use the text content (police_number)
                    }
                }
            } else {
                filters.unit_police_number = unitPoliceNumber;
            }
        }

        const monitoringData = await this.getMonitoringData(filters);
        
        console.log('DEBUG - Loading monitoring data:', monitoringData);
        
        // Define all possible components
        const components = [
            'Service_Berkala/PM', 
            'Service_General/GM', 
            'BBM', 
            'AdBlue', 
            'Driver_Cost', 
            'Ban'
        ];
        
        const weeks = Array.from({ length: 52 }, (_, i) => `W${i + 1}`);
        
        // If no data found, initialize all components with 0 values
        if (!monitoringData || monitoringData.length === 0) {
            console.log('DEBUG - No monitoring data found, initializing with 0 values');
            components.forEach(component => {
                weeks.forEach(week => {
                    const inputId = `${component}_${year}_${week}`;
                    const element = document.getElementById(inputId);
                    if (element) {
                        element.value = '0.00';
                        console.log('DEBUG - Initialized element:', inputId, 'with value: 0.00');
                    }
                });
            });
        } else {
            // Populate monitoring table with existing data
            monitoringData.forEach(item => {
                // Skip metadata items (week = 0)
                if (item.week === 0) {
                    return;
                }
                
                // Construct the correct input ID based on component and week
                const inputId = `${item.component}_${item.year}_W${item.week}`;
                const element = document.getElementById(inputId);
                
                console.log('DEBUG - Looking for element with ID:', inputId);
                
                if (element) {
                    element.value = this.formatNumberWithSeparator(item.value);
                    console.log('DEBUG - Populated element:', inputId, 'with value:', item.value);
                } else {
                    console.log('DEBUG - Element not found:', inputId);
                }
            });
            
            // Fill missing components with 0 values
            components.forEach(component => {
                weeks.forEach(week => {
                    const inputId = `${component}_${year}_${week}`;
                    const element = document.getElementById(inputId);
                    if (element && !element.value) {
                        element.value = '0.00';
                        console.log('DEBUG - Filled missing element:', inputId, 'with value: 0.00');
                    }
                });
            });
        }
        
        // Load metadata
        const metadataItems = monitoringData ? monitoringData.filter(item => item.week === 0) : [];
        console.log('DEBUG - Metadata items found:', metadataItems);
        
        metadataItems.forEach(item => {
            console.log('DEBUG - Processing metadata item:', item);
            
            if (item.component === 'unit_police_number') {
                const element = document.getElementById('unitPoliceNumber');
                if (element && element.tagName === 'SELECT') {
                    // For dropdown, we need to find the option with matching police_number
                    const options = element.options;
                    for (let i = 0; i < options.length; i++) {
                        if (options[i].textContent === item.value) {
                            element.value = options[i].value; // Set to id
                            console.log('DEBUG - Set unitPoliceNumber dropdown to id:', options[i].value);
                            break;
                        }
                    }
                } else if (element && element.tagName === 'INPUT') {
                    // For input field (backward compatibility)
                    element.value = item.value;
                    console.log('DEBUG - Set unitPoliceNumber input to:', item.value);
                } else {
                    console.log('DEBUG - unitPoliceNumber element not found');
                }
            } else if (item.component === 'selected_year') {
                const element = document.getElementById('yearToMonitor');
                if (element) {
                    element.value = item.value;
                    console.log('DEBUG - Set yearToMonitor to:', item.value);
                } else {
                    console.log('DEBUG - yearToMonitor element not found');
                }
            }
        });
        
        // If no metadata found, try to load from any monitoring data
        if (metadataItems.length === 0 && monitoringData && monitoringData.length > 0) {
            const firstItem = monitoringData[0];
            if (firstItem.unit_police_number) {
                const element = document.getElementById('unitPoliceNumber');
                if (element && element.tagName === 'SELECT') {
                    // For dropdown, find the option with matching police_number
                    const options = element.options;
                    for (let i = 0; i < options.length; i++) {
                        if (options[i].textContent === firstItem.unit_police_number) {
                            element.value = options[i].value; // Set to id
                            console.log('DEBUG - Set unitPoliceNumber dropdown to id:', options[i].value);
                            break;
                        }
                    }
                } else if (element && element.tagName === 'INPUT') {
                    element.value = firstItem.unit_police_number;
                    console.log('DEBUG - Set unitPoliceNumber input to:', firstItem.unit_police_number);
                }
            }
        }
    }

    // Load existing monitoring data untuk tahun tertentu
    async loadExistingMonitoringData(year, unitPoliceNumber = '') {
        const filters = {
            year: year
        };
        
        if (unitPoliceNumber) {
            // If unitPoliceNumber is an id (integer), we need to get the police_number first
            if (typeof unitPoliceNumber === 'string' && unitPoliceNumber.match(/^\d+$/)) {
                // It's an id, we need to get the police_number from the dropdown
                const dropdown = document.getElementById('existingUnitPoliceNumber');
                if (dropdown) {
                    const selectedOption = dropdown.options[dropdown.selectedIndex];
                    if (selectedOption) {
                        filters.unit_police_number = selectedOption.textContent; // Use the text content (police_number)
                    }
                }
            } else {
                filters.unit_police_number = unitPoliceNumber;
            }
        }

        const monitoringData = await this.getMonitoringData(filters);
        
        console.log('DEBUG - Loading existing monitoring data:', monitoringData);
        
        // Define all possible components for existing monitoring
        const components = [
            'Service_Berkala/PM', 
            'Service_General/GM', 
            'BBM', 
            'AdBlue', 
            'Driver_Cost', 
            'Ban'
        ];
        
        const weeks = Array.from({ length: 52 }, (_, i) => `W${i + 1}`);
        
        // If no data found, initialize all components with 0 values
        if (!monitoringData || monitoringData.length === 0) {
            console.log('DEBUG - No existing monitoring data found, initializing with 0 values');
            components.forEach(component => {
                weeks.forEach(week => {
                    const inputId = `${component}_existing_${year}_${week}`;
                    const element = document.getElementById(inputId);
                    if (element) {
                        element.value = '0.00';
                        console.log('DEBUG - Initialized existing element:', inputId, 'with value: 0.00');
                    }
                });
            });
        } else {
            // Populate existing monitoring table with existing data
            monitoringData.forEach(item => {
                // Skip metadata items (week = 0)
                if (item.week === 0) {
                    return;
                }
                
                // Only process existing monitoring items
                if (item.component.startsWith('existing_')) {
                    // Remove 'existing_' prefix for ID construction
                    const componentWithoutPrefix = item.component.replace('existing_', '');
                    const inputId = `${componentWithoutPrefix}_existing_${item.year}_W${item.week}`;
                    const element = document.getElementById(inputId);
                    
                    console.log('DEBUG - Looking for existing element with ID:', inputId);
                    
                    if (element) {
                        element.value = this.formatNumberWithSeparator(item.value);
                        console.log('DEBUG - Populated existing element:', inputId, 'with value:', item.value);
                    } else {
                        console.log('DEBUG - Existing element not found:', inputId);
                    }
                } else {
                    // Process regular monitoring items (non-existing)
                    const inputId = `${item.component}_${item.year}_W${item.week}`;
                    const element = document.getElementById(inputId);
                    
                    console.log('DEBUG - Looking for regular element with ID:', inputId);
                    
                    if (element) {
                        element.value = this.formatNumberWithSeparator(item.value);
                        console.log('DEBUG - Populated regular element:', inputId, 'with value:', item.value);
                    } else {
                        console.log('DEBUG - Regular element not found:', inputId);
                    }
                }
            });
            
            // Fill missing components with 0 values
            components.forEach(component => {
                weeks.forEach(week => {
                    const inputId = `${component}_existing_${year}_${week}`;
                    const element = document.getElementById(inputId);
                    if (element && !element.value) {
                        element.value = '0.00';
                        console.log('DEBUG - Filled missing existing element:', inputId, 'with value: 0.00');
                    }
                });
            });
        }
    }
}

// Initialize API
const costModelAPI = new CostModelAPI();

// Auto-save function untuk setiap input field
function autoSaveInput(inputId, fieldType = 'form') {
    const input = document.getElementById(inputId);
    if (!input) return;

    // Untuk monitoring fields, tidak ada auto-save
    if (fieldType === 'monitoring' || fieldType === 'existing_monitoring') {
        console.log(`${fieldType} field ${inputId} changed, but auto-save is disabled. Use submit button to save.`);
        return;
    }

    // Debounce function untuk menghindari terlalu banyak request (hanya untuk form fields)
    if (input.autoSaveTimeout) {
        clearTimeout(input.autoSaveTimeout);
    }

    input.autoSaveTimeout = setTimeout(async () => {
        try {
            if (fieldType === 'form') {
                // Untuk form fields (settings dan expenses)
                const formData = costModelAPI.collectFormData();
                await costModelAPI.upsertAllData(formData);
                console.log(`Auto-saved form data for field: ${inputId}`);
                // Notifikasi auto-save dihilangkan
            }
        } catch (error) {
            console.error(`Error auto-saving ${inputId}:`, error);
            // Notifikasi error auto-save juga dihilangkan
        }
    }, 1000); // Delay 1 detik sebelum save
}

// Function untuk menampilkan notifikasi auto-save (disabled)
function showAutoSaveNotification(message, type = 'success') {
    // Notifikasi auto-save dihilangkan - hanya console log
    console.log(`Auto-save notification (disabled): ${message} [${type}]`);
}

// Function untuk menampilkan notifikasi monitoring
function showMonitoringNotification(message, type = 'success') {
    // Hapus notifikasi yang sudah ada
    const existingNotification = document.getElementById('monitoring-notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Buat notifikasi baru
    const notification = document.createElement('div');
    notification.id = 'monitoring-notification';
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        padding: 15px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: white;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        background-color: ${type === 'success' ? '#28a745' : type === 'warning' ? '#ffc107' : '#dc3545'};
    `;
    notification.textContent = message;

    // Tambahkan ke body
    document.body.appendChild(notification);

    // Animasi masuk
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Hapus notifikasi setelah 4 detik
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 4000);
}

// Setup auto-save untuk semua input fields
function setupAutoSave() {
    // Form fields (settings dan expenses)
    const formFields = [
        'unitsPrice', 'qtyUnits', 'netBookValue', 'solarPrice', 'adBluePrice',
        'retasePerDay', 'avgRitasePerDay', 'fuelConsumption', 'adBlue', 'dayOperation',
        'insuranceUnit', 'firstPayment', 'leasingPayment', 'vehicleTax', 'kir',
        'telematicsOneTimeCost', 'telematicsRecurringCost', 'tirePrice', 'lifetimeTyre', 'oilPrice',
        'tollCost', 'driverPerUnit', 'driverCost', 'tyrePerUnit', 'downtime'
    ];

    // PM dan GM fields
    for (let i = 1; i <= 10; i++) {
        formFields.push(`pm${i}`, `gm${i}`);
    }

    // Monitoring input fields
    const monitoringFields = [
        'unitPoliceNumber', 'existingUnitPoliceNumber'
    ];

    // Monitoring select fields
    const monitoringSelectFields = [
        'yearToMonitor', 'existingYearToMonitor'
    ];

    // Setup event listeners untuk form fields
    formFields.forEach(fieldId => {
        const element = document.getElementById(fieldId);
        if (element) {
            element.addEventListener('input', () => autoSaveInput(fieldId, 'form'));
            element.addEventListener('change', () => autoSaveInput(fieldId, 'form'));
        }
    });

    // Monitoring fields tidak lagi menggunakan auto-save, menggunakan tombol submit manual
    // Event listeners untuk monitoring fields dihapus untuk menghindari auto-save

    // Setup auto-save untuk monitoring table fields (akan diupdate ketika tabel dibuat)
    setupMonitoringAutoSave();
}

// Setup monitoring fields untuk manual submit (tanpa auto-save)
function setupMonitoringAutoSave() {
    // Monitoring fields - cari semua input yang memiliki pattern monitoring
    const monitoringInputs = document.querySelectorAll('input[id*="_W"]');
    console.log('DEBUG - Found monitoring inputs:', monitoringInputs.length);
    
    // Subcategory monitoring fields - cari semua input yang memiliki pattern subcategory
    const subcategoryInputs = document.querySelectorAll('input[id*="_Fuel_Consumption_"], input[id*="_FC_/_Ritase_"], input[id*="_Solar_Price_"], input[id*="_AdBlue_Consumption_"], input[id*="_AdBlue_Price_"], input[id*="_AdBlue_Consumption_/_Day_"], input[id*="_Day_operation_"], input[id*="_Average_Toll_Cost_"], input[id*="_Driver_"], input[id*="_Driver_Cost_/_Ritase_"], input[id*="_Tire_Price_"], input[id*="_Tyre_/_Unit_"], input[id*="_Cost_/_Unit_"], input[id*="_Lifetime_Tyre_"], input[id*="_IDR_/_Km_"], input[id*="_IDR_/_Km_/_Unit_"], input[id*="_Cost_Days_"]');
    console.log('DEBUG - Found subcategory inputs:', subcategoryInputs.length);
    
    // Tambahkan tombol submit untuk monitoring
    addMonitoringSubmitButtons();
}

// Fungsi untuk menambahkan tombol submit di monitoring
function addMonitoringSubmitButtons() {
    // Cari container monitoring
    const monitoringContainer = document.querySelector('.monitoring-section') || 
                               document.querySelector('[id*="monitoring"]') || 
                               document.querySelector('.container');
    
    if (!monitoringContainer) {
        console.log('Monitoring container not found');
        return;
    }
    
    // Cek apakah tombol sudah ada
    if (document.getElementById('monitoring-submit-btn')) {
        return;
    }
    
    // Buat tombol submit untuk regular monitoring
    const submitButton = document.createElement('button');
    submitButton.id = 'monitoring-submit-btn';
    submitButton.textContent = 'Simpan Data Monitoring';
    submitButton.className = 'btn btn-primary monitoring-submit-btn';
    submitButton.style.cssText = `
        margin: 20px 0;
        padding: 12px 24px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    `;
    
    // Tambahkan event listener
    submitButton.addEventListener('click', async () => {
        await submitAllMonitoringData();
    });
    
    // Tambahkan hover effect
    submitButton.addEventListener('mouseenter', () => {
        submitButton.style.backgroundColor = '#0056b3';
    });
    
    submitButton.addEventListener('mouseleave', () => {
        submitButton.style.backgroundColor = '#007bff';
    });
    
    // Cari tempat yang tepat untuk menambahkan tombol
    const monitoringTable = document.querySelector('#monitoringTable') || 
                           document.querySelector('table[id*="monitoring"]');
    
    if (monitoringTable) {
        // Tambahkan tombol setelah tabel monitoring
        monitoringTable.parentNode.insertBefore(submitButton, monitoringTable.nextSibling);
    } else {
        // Tambahkan di akhir container
        monitoringContainer.appendChild(submitButton);
    }
    
    // Buat tombol submit untuk existing monitoring
    const existingSubmitButton = document.createElement('button');
    existingSubmitButton.id = 'existing-monitoring-submit-btn';
    existingSubmitButton.textContent = 'Simpan Data Existing Monitoring';
    existingSubmitButton.className = 'btn btn-success existing-monitoring-submit-btn';
    existingSubmitButton.style.cssText = `
        margin: 20px 0;
        padding: 12px 24px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    `;
    
    // Tambahkan event listener
    existingSubmitButton.addEventListener('click', async () => {
        await submitAllExistingMonitoringData();
    });
    
    // Tambahkan hover effect
    existingSubmitButton.addEventListener('mouseenter', () => {
        existingSubmitButton.style.backgroundColor = '#1e7e34';
    });
    
    existingSubmitButton.addEventListener('mouseleave', () => {
        existingSubmitButton.style.backgroundColor = '#28a745';
    });
    
    // Cari tempat yang tepat untuk menambahkan tombol existing
    const existingMonitoringTable = document.querySelector('#existingMonitoringTable') || 
                                   document.querySelector('table[id*="existing"]');
    
    if (existingMonitoringTable) {
        // Tambahkan tombol setelah tabel existing monitoring
        existingMonitoringTable.parentNode.insertBefore(existingSubmitButton, existingMonitoringTable.nextSibling);
    } else {
        // Tambahkan di akhir container
        monitoringContainer.appendChild(existingSubmitButton);
    }
    
    console.log('Monitoring submit buttons added');
}

// Fungsi untuk menyimpan semua data monitoring sekaligus
async function submitAllMonitoringData() {
    try {
        const submitButton = document.getElementById('monitoring-submit-btn');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';
        }
        
        const year = parseInt(document.getElementById('yearToMonitor')?.value || '1');
        const unitPoliceNumber = document.getElementById('unitPoliceNumber')?.value || '';
        
        console.log('Submitting all monitoring data for year:', year, 'unit:', unitPoliceNumber);
        
        // Kumpulkan semua data monitoring
        const monitoringData = [];
        
        // Regular monitoring fields (week 1-52)
        const weeks = Array.from({ length: 52 }, (_, i) => `W${i + 1}`);
        const components = ['Service_Berkala/PM', 'Service_General/GM', 'BBM', 'AdBlue', 'Driver_Cost', 'Ban'];
        
        console.log('DEBUG - Looking for monitoring fields with year:', year);
        console.log('DEBUG - Components to check:', components);
        
        for (const week of weeks) {
            for (const component of components) {
                const id = `${component}_${year}_${week}`;
                const element = document.getElementById(id);
                console.log('DEBUG - Checking element ID:', id, 'Found:', !!element, 'Value:', element?.value);
                
                if (element && element.value && element.value.trim() !== '') {
                    const weekNumber = parseInt(week.replace('W', ''));
                    const dataToSave = {
                        unit_police_number: unitPoliceNumber,
                        year: year,
                        week: weekNumber,
                        component: component,
                        value: costModelAPI.parseFormattedNumber(element.value),
                        note: ''
                    };
                    monitoringData.push(dataToSave);
                    console.log('DEBUG - Added monitoring data:', dataToSave);
                }
            }
        }
        
        // Subcategory monitoring fields
        const subcategoryPatterns = [
            '_Fuel_Consumption_', '_FC_/_Ritase_', '_Solar_Price_', 
            '_AdBlue_Consumption_', '_AdBlue_Price_', '_AdBlue_Consumption_/_Day_',
            '_Day_operation_', '_Average_Toll_Cost_', '_Driver_', 
            '_Driver_Cost_/_Ritase_', '_Tire_Price_', '_Tyre_/_Unit_',
            '_Cost_/_Unit_', '_Lifetime_Tyre_', '_IDR_/_Km_',
            '_IDR_/_Km_/_Unit_', '_Cost_Days_'
        ];
        
        console.log('DEBUG - Looking for subcategory fields with patterns:', subcategoryPatterns);
        
        for (const week of weeks) {
            for (const pattern of subcategoryPatterns) {
                const selector = `input[id*="${pattern}"][id*="${year}"][id*="${week}"]`;
                const elements = document.querySelectorAll(selector);
                console.log('DEBUG - Subcategory selector:', selector, 'Found elements:', elements.length);
                
                elements.forEach(element => {
                    console.log('DEBUG - Subcategory element ID:', element.id, 'Value:', element.value);
                    if (element.value && element.value.trim() !== '') {
                        const weekNumber = parseInt(week.replace('W', ''));
                        const component = element.id.split('_').slice(0, -2).join('_'); // Ambil component dari ID
                        
                        const dataToSave = {
                            unit_police_number: unitPoliceNumber,
                            year: year,
                            week: weekNumber,
                            component: component,
                            value: costModelAPI.parseFormattedNumber(element.value),
                            note: ''
                        };
                        monitoringData.push(dataToSave);
                        console.log('DEBUG - Added subcategory monitoring data:', dataToSave);
                    }
                });
            }
        }
        
        // Simpan semua data
        if (monitoringData.length > 0) {
            console.log('Saving', monitoringData.length, 'monitoring records');
            
            // Simpan satu per satu untuk memastikan semua tersimpan
            for (const data of monitoringData) {
                await costModelAPI.saveMonitoringData(
                    data.year, 
                    data.week, 
                    data.component, 
                    data.value, 
                    data.note
                );
            }
            
            showMonitoringNotification(`Berhasil menyimpan ${monitoringData.length} data monitoring!`, 'success');
            
            // Data monitoring terakhir tidak lagi ditampilkan di UI
            // await loadAndDisplayLatestMonitoringData();
        } else {
            showMonitoringNotification('Tidak ada data monitoring untuk disimpan', 'warning');
        }
        
    } catch (error) {
        console.error('Error submitting monitoring data:', error);
        showMonitoringNotification('Gagal menyimpan data monitoring: ' + error.message, 'error');
    } finally {
        const submitButton = document.getElementById('monitoring-submit-btn');
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Simpan Data Monitoring';
        }
    }
}

// Fungsi untuk menyimpan semua data existing monitoring sekaligus
async function submitAllExistingMonitoringData() {
    try {
        const submitButton = document.getElementById('existing-monitoring-submit-btn');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';
        }
        
        const year = parseInt(document.getElementById('existingYearToMonitor')?.value || '1');
        const unitPoliceNumber = document.getElementById('existingUnitPoliceNumber')?.value || '';
        
        console.log('Submitting all existing monitoring data for year:', year, 'unit:', unitPoliceNumber);
        
        // Kumpulkan semua data existing monitoring
        const monitoringData = [];
        
        // Existing monitoring fields (week 1-52)
        const weeks = Array.from({ length: 52 }, (_, i) => `W${i + 1}`);
        const components = ['Service_Berkala/PM', 'Service_General/GM', 'BBM', 'AdBlue', 'Driver_Cost', 'Ban'];
        
        for (const week of weeks) {
            for (const component of components) {
                const id = `${component}_existing_${year}_${week}`;
                const element = document.getElementById(id);
                if (element && element.value && element.value.trim() !== '') {
                    const weekNumber = parseInt(week.replace('W', ''));
                    monitoringData.push({
                        unit_police_number: unitPoliceNumber,
                        year: year,
                        week: weekNumber,
                        component: component,
                        value: costModelAPI.parseFormattedNumber(element.value),
                        note: ''
                    });
                }
            }
        }
        
        // Existing subcategory monitoring fields
        const subcategoryPatterns = [
            '_Fuel_Consumption_', '_FC_/_Ritase_', '_Solar_Price_', 
            '_AdBlue_Consumption_', '_AdBlue_Price_', '_AdBlue_Consumption_/_Day_',
            '_Day_operation_', '_Average_Toll_Cost_', '_Driver_', 
            '_Driver_Cost_/_Ritase_', '_Tire_Price_', '_Tyre_/_Unit_',
            '_Cost_/_Unit_', '_Lifetime_Tyre_', '_IDR_/_Km_',
            '_IDR_/_Km_/_Unit_', '_Cost_Days_'
        ];
        
        for (const week of weeks) {
            for (const pattern of subcategoryPatterns) {
                const selector = `input[id*="${pattern}"][id*="existing"][id*="${year}"][id*="${week}"]`;
                const elements = document.querySelectorAll(selector);
                
                elements.forEach(element => {
                    if (element.value && element.value.trim() !== '') {
                        const weekNumber = parseInt(week.replace('W', ''));
                        const component = element.id.split('_').slice(0, -3).join('_'); // Ambil component dari ID (exclude 'existing', year, week)
                        
                        monitoringData.push({
                            unit_police_number: unitPoliceNumber,
                            year: year,
                            week: weekNumber,
                            component: component,
                            value: costModelAPI.parseFormattedNumber(element.value),
                            note: ''
                        });
                    }
                });
            }
        }
        
        // Simpan semua data
        if (monitoringData.length > 0) {
            console.log('Saving', monitoringData.length, 'existing monitoring records');
            
            // Simpan satu per satu untuk memastikan semua tersimpan
            for (const data of monitoringData) {
                await costModelAPI.saveExistingMonitoringData(
                    data.year, 
                    data.week, 
                    data.component, 
                    data.value, 
                    data.note
                );
            }
            
            showMonitoringNotification(`Berhasil menyimpan ${monitoringData.length} data existing monitoring!`, 'success');
            
            // Data monitoring terakhir tidak lagi ditampilkan di UI
            // await loadAndDisplayLatestMonitoringData();
        } else {
            showMonitoringNotification('Tidak ada data existing monitoring untuk disimpan', 'warning');
        }
        
    } catch (error) {
        console.error('Error submitting existing monitoring data:', error);
        showMonitoringNotification('Gagal menyimpan data existing monitoring: ' + error.message, 'error');
    } finally {
        const submitButton = document.getElementById('existing-monitoring-submit-btn');
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Simpan Data Existing Monitoring';
        }
        }
    }

// Override calculateTCO function to save data
const originalCalculateTCO = window.calculateTCO;
window.calculateTCO = async function() {
    try {
        // Collect form data
        const formData = costModelAPI.collectFormData();
        
        // Save to API
        await costModelAPI.upsertAllData(formData);
        
        // Call original function
        if (originalCalculateTCO) {
            originalCalculateTCO();
        }
        
        // Show success message
        // alert('Data berhasil disimpan dan perhitungan selesai!');
    } catch (error) {
        // console.error('Error saving data:', error);
        // alert('Terjadi kesalahan saat menyimpan data: ' + error.message);
    }
};

// Override monitoring functions untuk setup tombol submit (tanpa auto-save)
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

// Fungsi untuk memaksa perhitungan ulang total dan downtime
function forceRecalculateMonitoringTotals(year) {
    try {
        console.log('DEBUG - forceRecalculateMonitoringTotals called for year:', year);
        
        // Cari dan trigger perhitungan total untuk setiap komponen
        const components = ['Service_Berkala/PM', 'Service_General/GM', 'BBM', 'AdBlue', 'Driver_Cost', 'Ban'];
        
        components.forEach(component => {
            // Trigger perhitungan total untuk setiap komponen
            const totalElement = document.getElementById(`${component}_Total_${year}`);
            if (totalElement) {
                console.log('DEBUG - Found total element for', component, ':', totalElement.id);
                // Trigger input event untuk memaksa perhitungan
                const event = new Event('input', { bubbles: true });
                totalElement.dispatchEvent(event);
            } else {
                console.log('DEBUG - Total element not found for', component, 'year', year);
            }
        });
        
        // Trigger perhitungan downtime
        const downtimeElement = document.getElementById(`Downtime_${year}`);
        if (downtimeElement) {
            console.log('DEBUG - Found downtime element:', downtimeElement.id);
            const event = new Event('input', { bubbles: true });
            downtimeElement.dispatchEvent(event);
        } else {
            console.log('DEBUG - Downtime element not found for year', year);
        }
        
        // Trigger perhitungan grand total
        const grandTotalElement = document.getElementById(`Grand_Total_${year}`);
        if (grandTotalElement) {
            console.log('DEBUG - Found grand total element:', grandTotalElement.id);
            const event = new Event('input', { bubbles: true });
            grandTotalElement.dispatchEvent(event);
        } else {
            console.log('DEBUG - Grand total element not found for year', year);
        }
        
            console.log('DEBUG - Force recalculation completed');
    
} catch (error) {
    console.error('DEBUG - Error in forceRecalculateMonitoringTotals:', error);
}
}

// Setup event listener untuk year selector monitoring
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
    } else {
        console.log('DEBUG - yearToMonitor element not found');
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
    } else {
        console.log('DEBUG - existingYearToMonitor element not found');
    }
}

const originalUpdateExistingMonitoringTotals = window.updateExistingMonitoringTotals;
window.updateExistingMonitoringTotals = async function(year) {
    // Call original function first
    if (originalUpdateExistingMonitoringTotals) {
        originalUpdateExistingMonitoringTotals(year);
    }
    
    // Setup tombol submit setelah tabel dibuat
    setTimeout(() => {
        addMonitoringSubmitButtons();
    }, 100);
};

// Override updateMonitoringTable untuk setup tombol submit
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

// Override updateExistingMonitoringTable untuk setup tombol submit
const originalUpdateExistingMonitoringTable = window.updateExistingMonitoringTable;
window.updateExistingMonitoringTable = async function() {
    // Call original function first
    if (originalUpdateExistingMonitoringTable) {
        originalUpdateExistingMonitoringTable();
    }
    
    // Setup tombol submit untuk existing monitoring fields yang baru dibuat
    setTimeout(() => {
        setupMonitoringAutoSave();
    }, 100);
    
    // Load existing monitoring data
    const year = parseInt(document.getElementById('existingYearToMonitor')?.value || '1');
    const unitPoliceNumber = document.getElementById('existingUnitPoliceNumber')?.value || '';
    
    await costModelAPI.loadExistingMonitoringData(year, unitPoliceNumber);
};

// Load stored data when page loads
document.addEventListener('DOMContentLoaded', async function() {
    try {
        const storedData = await costModelAPI.getStoredData();
        if (storedData) {
            costModelAPI.populateFormWithStoredData(storedData);
        }
        
            // Setup auto-save setelah data dimuat
    setupAutoSave();
    
    // Setup event listener untuk year selector monitoring
    setupYearSelectorListeners();
    
    // Data monitoring terakhir tidak lagi ditampilkan di UI
    // await loadAndDisplayLatestMonitoringData();
    } catch (error) {
        console.error('Error loading stored data:', error);
    }
});

// Fungsi untuk memuat dan menampilkan data monitoring terakhir
async function loadAndDisplayLatestMonitoringData() {
    try {
        const latestData = await costModelAPI.getLatestMonitoringData();
        
        if (latestData && latestData.data && Object.keys(latestData.data).length > 0) {
            displayLatestMonitoringData(latestData);
        } else {
            console.log('Tidak ada data monitoring terakhir yang ditemukan');
        }
    } catch (error) {
        console.error('Error loading latest monitoring data:', error);
    }
}

// Fungsi untuk menampilkan data monitoring terakhir (DISABLED - Tabel disembunyikan)
function displayLatestMonitoringData(latestData) {
    // Tabel Data Monitoring Terakhir disembunyikan dari UI
    // Data masih diambil dan diproses di background untuk keperluan lain
    console.log('Data monitoring terakhir diambil:', latestData);
    
    // Hapus container jika ada
    const container = document.getElementById('latest-monitoring-container');
    if (container) {
        container.remove();
    }
    
    console.log('Tabel Data Monitoring Terakhir disembunyikan dari UI');
} 