<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Cost Model Calculator</title>
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
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .welcome-section {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .welcome-section h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .welcome-section p {
            color: #666;
            font-size: 16px;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .dashboard-card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .dashboard-card i {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 20px;
        }
        
        .dashboard-card h3 {
            color: #333;
            font-size: 20px;
            margin-bottom: 15px;
        }
        
        .dashboard-card p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .dashboard-card a {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .dashboard-card a:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .stats-section {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .stats-section h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .stat-item .stat-number {
            font-size: 32px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-item .stat-label {
            color: #666;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }
            
            .navbar-user {
                flex-direction: column;
                gap: 10px;
            }
            
            .container {
                padding: 0 15px;
                margin: 20px auto;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="navbar-brand">
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
        <div class="welcome-section">
            <h1>Selamat Datang di Dashboard</h1>
            <p>Kelola data cost model Anda dengan mudah dan efisien</p>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <i class="fas fa-cogs"></i>
                <h3>Pengaturan</h3>
                <p>Kelola pengaturan dasar dan asumsi untuk perhitungan cost model</p>
                <a href="{{ route('cost-model.index') }}">Akses Pengaturan</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-chart-line"></i>
                <h3>Monitoring</h3>
                <p>Pantau dan kelola data monitoring cost model secara real-time</p>
                <a href="{{ route('cost-model.index') }}#monitoring">Akses Monitoring</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-users"></i>
                <h3>Unit Polisi</h3>
                <p>Kelola data master unit polisi dan nomor kendaraan</p>
                <a href="{{ route('police-units.index') }}">Akses Unit Polisi</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-calculator"></i>
                <h3>Perhitungan</h3>
                <p>Lakukan perhitungan cost model berdasarkan data yang tersedia</p>
                <a href="{{ route('cost-model.index') }}#calculation">Akses Perhitungan</a>
            </div>
        </div>

        <div class="stats-section">
            <h2>Statistik Data</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" id="total-settings">0</div>
                    <div class="stat-label">Total Pengaturan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="total-expenses">0</div>
                    <div class="stat-label">Total Biaya</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="total-monitoring">0</div>
                    <div class="stat-label">Total Monitoring</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="total-units">0</div>
                    <div class="stat-label">Total Unit Polisi</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load statistics
        document.addEventListener('DOMContentLoaded', function() {
            loadStatistics();
        });

        function loadStatistics() {
            // Load settings count
            fetch('/api/cost-model/stored-data')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-settings').textContent = data.settings ? 1 : 0;
                    document.getElementById('total-expenses').textContent = data.expenses ? 1 : 0;
                })
                .catch(error => {
                    console.error('Error loading settings:', error);
                });

            // Load monitoring count
            fetch('/api/cost-model/monitoring-data')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-monitoring').textContent = data.data ? data.data.length : 0;
                })
                .catch(error => {
                    console.error('Error loading monitoring:', error);
                });

            // Load police units count
            fetch('/api/cost-model/police-units')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-units').textContent = data.data ? data.data.length : 0;
                })
                .catch(error => {
                    console.error('Error loading police units:', error);
                });
        }
    </script>
</body>
</html> 