# Debugging Monitoring Auto-Save

## 🔍 Masalah yang Ditemukan

User melaporkan bahwa data monitoring tidak tersimpan ke database meskipun auto-save sudah diimplementasikan.

## 🛠️ Langkah Debugging yang Dilakukan

### 1. **Verifikasi Backend API**
- ✅ API endpoint `/api/cost-model/upsert-monitoring` berfungsi dengan baik
- ✅ Tabel `cost_model_monitoring` sudah ada dan dapat menyimpan data
- ✅ Model `CostModelMonitoring` sudah benar
- ✅ Route sudah terdaftar dengan benar

### 2. **Test API Manual**
```bash
curl -X POST http://localhost:8000/api/cost-model/upsert-monitoring \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: test" \
  -d '{"unit_police_number":"B 1234 AB","year":1,"week":1,"component":"Service_Berkala/PM","value":"1000000","note":"test"}'
```

**Hasil**: ✅ Berhasil menyimpan data ke database

### 3. **Debugging Frontend**

#### **A. Debugging Event Listener Setup**
```javascript
// Setup auto-save untuk monitoring fields
function setupMonitoringAutoSave() {
    // Monitoring fields - cari semua input yang memiliki pattern monitoring
    const monitoringInputs = document.querySelectorAll('input[id*="_W"]');
    console.log('DEBUG - Found monitoring inputs:', monitoringInputs.length);
    monitoringInputs.forEach(input => {
        const id = input.id;
        console.log('DEBUG - Setting up monitoring input:', id);
        // ... event listener setup
    });
}
```

#### **B. Debugging Auto-Save Logic**
```javascript
} else {
    // Untuk monitoring table fields
    const year = parseInt(document.getElementById('yearToMonitor')?.value || '1');
    const parts = inputId.split('_');
    const week = parts[parts.length - 1]; // Ambil week dari ID
    
    console.log('DEBUG - Input ID:', inputId);
    console.log('DEBUG - Parts:', parts);
    console.log('DEBUG - Week:', week);
    console.log('DEBUG - Year:', year);
    console.log('DEBUG - Input Value:', input.value);
    
    // ... component parsing logic
}
```

#### **C. Debugging Existing Monitoring**
```javascript
} else if (fieldType === 'existing_monitoring') {
    // Untuk existing monitoring fields
    const year = parseInt(document.getElementById('existingYearToMonitor')?.value || '1');
    const parts = inputId.split('_');
    const week = parts[parts.length - 1]; // Ambil week dari ID
    
    console.log('DEBUG - Existing Input ID:', inputId);
    console.log('DEBUG - Existing Parts:', parts);
    console.log('DEBUG - Existing Week:', week);
    console.log('DEBUG - Existing Year:', year);
    console.log('DEBUG - Existing Input Value:', input.value);
    
    // ... component parsing logic
}
```

## 🔍 Kemungkinan Penyebab Masalah

### 1. **Event Listener Tidak Terpasang**
- Input monitoring table dibuat secara dinamis
- Event listener mungkin tidak terpasang pada input yang baru dibuat
- Timing issue saat setup event listener

### 2. **Component ID Parsing Error**
- Format ID input mungkin tidak sesuai dengan yang diharapkan
- Parsing logic mungkin salah untuk component name
- Week extraction mungkin tidak benar

### 3. **CSRF Token Issue**
- CSRF token mungkin tidak tersedia atau salah
- Request mungkin ditolak oleh Laravel CSRF protection

### 4. **Network/API Error**
- Request mungkin gagal karena network issue
- Error mungkin tidak ditampilkan dengan benar

## 🧪 Testing Steps

### **Step 1: Buka Browser Developer Tools**
1. Buka halaman monitoring
2. Buka Developer Tools (F12)
3. Pilih tab Console
4. Refresh halaman

### **Step 2: Cek Debug Output**
Cari output seperti:
```
DEBUG - Found monitoring inputs: X
DEBUG - Setting up monitoring input: Service_Berkala/PM_1_W1
DEBUG - Found subcategory inputs: Y
```

### **Step 3: Test Input Monitoring**
1. Pilih tab Monitoring
2. Pilih year (misal: 1st year)
3. Isi input di kolom Service Berkala/PM, Week 1
4. Cek console untuk debug output

### **Step 4: Cek Network Tab**
1. Buka tab Network di Developer Tools
2. Isi input monitoring
3. Cari request ke `/api/cost-model/upsert-monitoring`
4. Cek response dan status code

## 📋 Checklist Debugging

### ✅ **Backend Verification**
- [ ] API endpoint berfungsi (tested with curl)
- [ ] Database table ada dan dapat menyimpan
- [ ] Model dan migration sudah benar
- [ ] Route sudah terdaftar

### 🔍 **Frontend Debugging**
- [ ] Event listener terpasang (cek console log)
- [ ] Input ID parsing benar (cek debug output)
- [ ] API call terkirim (cek network tab)
- [ ] CSRF token tersedia
- [ ] Error handling berfungsi

### 🧪 **User Testing**
- [ ] Buka halaman monitoring
- [ ] Isi input di monitoring table
- [ ] Cek console untuk debug output
- [ ] Cek network tab untuk API calls
- [ ] Verifikasi data tersimpan di database

## 🎯 Expected Debug Output

### **Setup Phase**
```
DEBUG - Found monitoring inputs: 312
DEBUG - Setting up monitoring input: Service_Berkala/PM_1_W1
DEBUG - Setting up monitoring input: Service_Berkala/PM_1_W2
...
DEBUG - Found subcategory inputs: 8840
DEBUG - Setting up subcategory input: BBM_Fuel_Consumption_1_W1
...
```

### **Input Change Phase**
```
DEBUG - Input ID: Service_Berkala/PM_1_W1
DEBUG - Parts: ["Service", "Berkala/PM", "1", "W1"]
DEBUG - Week: W1
DEBUG - Year: 1
DEBUG - Input Value: 1000000
DEBUG - Regular Component: Service_Berkala/PM
Auto-saved monitoring data for: Service_Berkala/PM_1_W1
```

## 🚨 Troubleshooting

### **Jika Event Listener Tidak Terpasang**
- Cek apakah `setupMonitoringAutoSave()` dipanggil setelah table dibuat
- Pastikan timing yang benar untuk dynamic content

### **Jika Component Parsing Salah**
- Cek format ID input yang dihasilkan
- Sesuaikan parsing logic dengan format ID yang benar

### **Jika API Call Gagal**
- Cek CSRF token
- Cek network connectivity
- Cek Laravel logs untuk error detail

### **Jika Data Tidak Tersimpan**
- Cek database connection
- Cek model validation rules
- Cek database constraints

## 📝 Next Steps

1. **User Testing**: Minta user untuk test dengan debug output
2. **Analyze Output**: Analisis debug output untuk identifikasi masalah
3. **Fix Issues**: Perbaiki masalah berdasarkan hasil debugging
4. **Remove Debug**: Hapus debug code setelah masalah teratasi
5. **Documentation**: Update dokumentasi dengan solusi yang ditemukan 