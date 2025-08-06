<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Master Nomor Polisi - Cost Model</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
        
        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .btn-action {
            margin: 0 2px;
        }
        .status-active {
            color: #28a745;
        }
        .status-inactive {
            color: #dc3545;
        }
        .form-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-light">
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

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-car"></i> Master Nomor Polisi</h2>
                    <div>
                        <a href="{{ route('cost-model.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-home"></i> Beranda
                        </a>
                        <a href="{{ route('cost-model.index') }}" class="btn btn-primary">
                            <i class="fas fa-calculator"></i> Cost Model
                        </a>
                    </div>
                </div>

                <!-- Form Tambah/Edit -->
                <div class="form-container">
                    <h4 id="formTitle">Tambah Nomor Polisi Baru</h4>
                    <form id="policeUnitForm">
                        <input type="hidden" id="editId" name="id">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="policeNumber" class="form-label">Nomor Polisi *</label>
                                    <input type="text" class="form-control" id="policeNumber" name="police_number" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="unitName" class="form-label">Nama Unit</label>
                                    <input type="text" class="form-control" id="unitName" name="unit_name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="unitType" class="form-label">Jenis Unit</label>
                                    <select class="form-control" id="unitType" name="unit_type">
                                        <option value="">Pilih Jenis</option>
                                        <option value="Kendaraan">Kendaraan</option>
                                        <option value="Motor">Motor</option>
                                        <option value="Truk">Truk</option>
                                        <option value="Bus">Bus</option>
                                        <option value="Ambulans">Ambulans</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="isActive" class="form-label">Status</label>
                                    <select class="form-control" id="isActive" name="is_active">
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabel Data -->
                <div class="table-container">
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Daftar Nomor Polisi</h5>
                            <button class="btn btn-success" onclick="loadPoliceUnits()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Polisi</th>
                                        <th>Nama Unit</th>
                                        <th>Jenis Unit</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="policeUnitsTable">
                                    <tr>
                                        <td colspan="7" class="text-center">Memuat data...</td>
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
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus nomor polisi <strong id="deletePoliceNumber"></strong>?</p>
                    <p class="text-danger">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let deleteId = null;

        // Load data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            loadPoliceUnits();
        });

        // Handle form submission
        document.getElementById('policeUnitForm').addEventListener('submit', function(e) {
            e.preventDefault();
            savePoliceUnit();
        });

        // Load semua data police units
        async function loadPoliceUnits() {
            try {
                const response = await fetch('/api/cost-model/police-units');
                const result = await response.json();
                
                if (result.success) {
                    displayPoliceUnits(result.data);
                } else {
                    showAlert('Error: ' + result.message, 'danger');
                }
            } catch (error) {
                console.error('Error loading police units:', error);
                showAlert('Terjadi kesalahan saat memuat data', 'danger');
            }
        }

        // Display data dalam tabel
        function displayPoliceUnits(data) {
            const tbody = document.getElementById('policeUnitsTable');
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>';
                return;
            }

            tbody.innerHTML = data.map((unit, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${unit.police_number}</strong></td>
                    <td>${unit.unit_name || '-'}</td>
                    <td>${unit.unit_type || '-'}</td>
                    <td>${unit.description || '-'}</td>
                    <td>
                        <span class="badge ${unit.is_active ? 'bg-success' : 'bg-danger'}">
                            ${unit.is_active ? 'Aktif' : 'Tidak Aktif'}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-action" onclick="editPoliceUnit(${unit.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-action" onclick="deletePoliceUnit(${unit.id}, '${unit.police_number}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Save police unit
        async function savePoliceUnit() {
            const form = document.getElementById('policeUnitForm');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            
            // Convert is_active to boolean
            data.is_active = data.is_active === '1';

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
                    showAlert(result.message, 'success');
                    resetForm();
                    loadPoliceUnits();
                } else {
                    showAlert('Error: ' + result.message, 'danger');
                }
            } catch (error) {
                console.error('Error saving police unit:', error);
                showAlert('Terjadi kesalahan saat menyimpan data', 'danger');
            }
        }

        // Edit police unit
        async function editPoliceUnit(id) {
            try {
                const response = await fetch(`/api/cost-model/police-units`);
                const result = await response.json();
                
                if (result.success) {
                    const unit = result.data.find(u => u.id === id);
                    if (unit) {
                        document.getElementById('editId').value = unit.id;
                        document.getElementById('policeNumber').value = unit.police_number;
                        document.getElementById('unitName').value = unit.unit_name || '';
                        document.getElementById('unitType').value = unit.unit_type || '';
                        document.getElementById('description').value = unit.description || '';
                        document.getElementById('isActive').value = unit.is_active ? '1' : '0';
                        
                        document.getElementById('formTitle').textContent = 'Edit Nomor Polisi';
                        document.getElementById('policeNumber').focus();
                    }
                }
            } catch (error) {
                console.error('Error loading police unit for edit:', error);
                showAlert('Terjadi kesalahan saat memuat data untuk edit', 'danger');
            }
        }

        // Delete police unit
        function deletePoliceUnit(id, policeNumber) {
            deleteId = id;
            document.getElementById('deletePoliceNumber').textContent = policeNumber;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Confirm delete
        async function confirmDelete() {
            if (!deleteId) return;

            try {
                const response = await fetch('/api/cost-model/police-units', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ id: deleteId })
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert(result.message, 'success');
                    loadPoliceUnits();
                } else {
                    showAlert('Error: ' + result.message, 'danger');
                }
            } catch (error) {
                console.error('Error deleting police unit:', error);
                showAlert('Terjadi kesalahan saat menghapus data', 'danger');
            } finally {
                deleteId = null;
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
            }
        }

        // Reset form
        function resetForm() {
            document.getElementById('policeUnitForm').reset();
            document.getElementById('editId').value = '';
            document.getElementById('formTitle').textContent = 'Tambah Nomor Polisi Baru';
        }

        // Show alert
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html> 