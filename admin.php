<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RuangKita</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#2563EB',
                        secondary: '#F97316',
                        background: '#F3F4F6',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F3F4F6; }
        .section { display: none; animation: fadeIn 0.4s ease-in-out; }
        .section.active { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .sidebar-menu.active {
            background-color: #EFF6FF;
            color: #2563EB;
            border-right: 4px solid #2563EB;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-gray-800">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-xl flex flex-col transition-all duration-300 z-20 hidden md:flex" id="sidebar">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-building"></i> RuangKita
            </h2>
            <button class="md:hidden text-gray-500" onclick="toggleSidebar()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="flex-1 py-4 overflow-y-auto">
            <nav class="space-y-1">
                <a href="#" onclick="showSection('dashboard', this)" class="sidebar-menu active flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary transition-colors">
                    <i class="fa-solid fa-chart-pie w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="#" onclick="showSection('ruang', this)" class="sidebar-menu flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary transition-colors">
                    <i class="fa-solid fa-door-open w-5"></i>
                    <span class="font-medium">Data Ruang</span>
                </a>
                <a href="#" onclick="showSection('booking', this)" class="sidebar-menu flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary transition-colors">
                    <i class="fa-solid fa-bell w-5"></i>
                    <span class="font-medium">Booking Masuk</span>
                </a>
                <a href="#" onclick="showSection('riwayat', this)" class="sidebar-menu flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary transition-colors">
                    <i class="fa-solid fa-clock-rotate-left w-5"></i>
                    <span class="font-medium">Riwayat Booking</span>
                </a>
            </nav>
        </div>
        <div class="p-4 border-t border-gray-100">
            <a href="logout.php" class="flex items-center gap-3 px-6 py-3 text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                <i class="fa-solid fa-arrow-right-from-bracket w-5"></i>
                <span class="font-medium">Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- Topbar -->
        <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between z-10">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-gray-500 hover:text-primary" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h1 id="topbar-title" class="text-xl font-semibold text-gray-800">Dashboard Overview</h1>
            </div>
            <div class="flex items-center gap-5">
                <button class="text-gray-400 hover:text-secondary relative">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-secondary rounded-full border-2 border-white"></span>
                </button>
                <div class="flex items-center gap-3 border-l pl-5 border-gray-200">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold shadow-md">
                        <?= substr($_SESSION['nama'] ?? 'A', 0, 1) ?>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-gray-700"><?= $_SESSION['nama'] ?? 'Admin' ?></p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto p-8 bg-gray-50">
            
            <!-- SECTION: Dashboard -->
            <div id="dashboard" class="section active">
                <!-- Stat Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Ruangan -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow group flex items-center gap-5">
                        <div class="w-14 h-14 rounded-full bg-blue-50 text-primary flex items-center justify-center text-2xl group-hover:bg-primary group-hover:text-white transition-colors">
                            <i class="fa-solid fa-door-closed"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Ruangan</p>
                            <h3 class="text-2xl font-bold text-gray-800" id="stat-ruangan">0</h3>
                        </div>
                    </div>
                    <!-- Booking Hari Ini -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow group flex items-center gap-5">
                        <div class="w-14 h-14 rounded-full bg-orange-50 text-secondary flex items-center justify-center text-2xl group-hover:bg-secondary group-hover:text-white transition-colors">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Booking Hari Ini</p>
                            <h3 class="text-2xl font-bold text-gray-800" id="stat-hari-ini">0</h3>
                        </div>
                    </div>
                    <!-- Pending -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow group flex items-center gap-5">
                        <div class="w-14 h-14 rounded-full bg-yellow-50 text-yellow-500 flex items-center justify-center text-2xl group-hover:bg-yellow-500 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Booking Pending</p>
                            <h3 class="text-2xl font-bold text-gray-800" id="stat-pending">0</h3>
                        </div>
                    </div>
                    <!-- Disetujui -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow group flex items-center gap-5">
                        <div class="w-14 h-14 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-2xl group-hover:bg-green-500 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Booking Disetujui</p>
                            <h3 class="text-2xl font-bold text-gray-800" id="stat-disetujui">0</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Shortcut Aksi</h3>
                    <div class="flex gap-4">
                        <button onclick="document.querySelectorAll('.sidebar-menu')[2].click()" class="bg-primary hover:bg-blue-700 text-white px-6 py-2 rounded-xl font-medium transition-colors shadow-sm">
                            Lihat Booking Masuk
                        </button>
                        <button onclick="document.querySelectorAll('.sidebar-menu')[1].click()" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2 rounded-xl font-medium transition-colors">
                            Kelola Ruangan
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION: Data Ruang -->
            <div id="ruang" class="section">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Manajemen Ruangan</h2>
                        <button onclick="alert('Fitur Tambah Ruang diaktifkan nanti')" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Tambah Ruang
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider">
                                    <th class="py-4 px-6 font-medium">ID</th>
                                    <th class="py-4 px-6 font-medium">Nama Ruangan</th>
                                    <th class="py-4 px-6 font-medium">Kapasitas</th>
                                    <th class="py-4 px-6 font-medium">Fasilitas</th>
                                    <th class="py-4 px-6 font-medium text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-ruangan" class="divide-y divide-gray-100 text-gray-700 text-sm">
                                <!-- Data ruangan dimuat via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECTION: Booking Masuk (Pending) -->
            <div id="booking" class="section">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800">Pengajuan Menunggu Persetujuan</h2>
                        <p class="text-sm text-gray-500">Tinjau dan proses booking yang baru masuk.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider">
                                    <th class="py-4 px-6 font-medium">Peminjam</th>
                                    <th class="py-4 px-6 font-medium">Ruang & Keperluan</th>
                                    <th class="py-4 px-6 font-medium">Waktu</th>
                                    <th class="py-4 px-6 font-medium">Status</th>
                                    <th class="py-4 px-6 font-medium text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-pending" class="divide-y divide-gray-100 text-gray-700 text-sm">
                                <!-- Data pending dimuat via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECTION: Riwayat Booking -->
            <div id="riwayat" class="section">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h2 class="text-lg font-semibold text-gray-800">Riwayat Keseluruhan</h2>
                        <div class="relative">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" placeholder="Cari peminjam..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent w-full md:w-64">
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider">
                                    <th class="py-4 px-6 font-medium">Peminjam</th>
                                    <th class="py-4 px-6 font-medium">Ruang</th>
                                    <th class="py-4 px-6 font-medium">Waktu</th>
                                    <th class="py-4 px-6 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody id="table-riwayat" class="divide-y divide-gray-100 text-gray-700 text-sm">
                                <!-- Data riwayat dimuat via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Overlay Sidebar Mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden md:hidden" onclick="toggleSidebar()"></div>

    <!-- SCRIPT LOGIC -->
    <script>
        const titles = {
            'dashboard': 'Dashboard Overview',
            'ruang': 'Manajemen Ruang',
            'booking': 'Booking Masuk',
            'riwayat': 'Riwayat Booking'
        };

        function showSection(id, element) {
            // Sembunyikan semua section
            document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
            // Tampilkan section terpilih
            document.getElementById(id).classList.add('active');
            
            // Update Title Topbar
            document.getElementById('topbar-title').innerText = titles[id];

            // Update state sidebar menu
            document.querySelectorAll('.sidebar-menu').forEach(menu => menu.classList.remove('active'));
            if(element) element.classList.add('active');

            // Load data sesuai section
            if (id === 'dashboard') loadStats();
            if (id === 'ruang') loadRooms();
            if (id === 'booking') loadPendingBookings();
            if (id === 'riwayat') loadAllBookings();

            // Tutup sidebar jika di mobile
            if (window.innerWidth < 768) {
                toggleSidebar();
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('absolute', 'h-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('absolute', 'h-full');
                overlay.classList.add('hidden');
            }
        }

        // --- Fetch Data API ---

        async function loadStats() {
            try {
                let res = await fetch('admin_action.php?action=get_stats');
                let json = await res.json();
                if (json.status === 'success') {
                    document.getElementById('stat-ruangan').innerText = json.data.total_ruangan;
                    document.getElementById('stat-hari-ini').innerText = json.data.booking_hari_ini;
                    document.getElementById('stat-pending').innerText = json.data.pending;
                    document.getElementById('stat-disetujui').innerText = json.data.disetujui;
                }
            } catch (e) { console.error(e); }
        }

        async function loadRooms() {
            try {
                let res = await fetch('admin_action.php?action=get_rooms');
                let json = await res.json();
                let html = '';
                if (json.status === 'success') {
                    json.data.forEach(r => {
                        html += `
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 text-gray-500">#${r.id}</td>
                                <td class="py-4 px-6 font-medium text-gray-800">${r.nama}</td>
                                <td class="py-4 px-6">${r.kapasitas} Orang</td>
                                <td class="py-4 px-6 text-xs text-gray-500 truncate max-w-xs">${r.fasilitas}</td>
                                <td class="py-4 px-6 text-right">
                                    <button class="text-blue-500 hover:text-blue-700 mr-3"><i class="fa-solid fa-pen"></i></button>
                                    <button class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        `;
                    });
                    document.getElementById('table-ruangan').innerHTML = html;
                }
            } catch (e) { console.error(e); }
        }

        function getStatusBadge(status) {
            if (status === 'pending') return `<span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Pending</span>`;
            if (status === 'disetujui') return `<span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Disetujui</span>`;
            if (status === 'ditolak') return `<span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Ditolak</span>`;
            return '';
        }

        async function loadPendingBookings() {
            try {
                let res = await fetch('admin_action.php?action=get_bookings&type=pending');
                let json = await res.json();
                let html = '';
                if (json.status === 'success' && json.data.length > 0) {
                    json.data.forEach(b => {
                        html += `
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 font-medium text-gray-800">${b.nama}</td>
                                <td class="py-4 px-6">
                                    <div class="font-medium">${b.ruangan_nama || '-'}</div>
                                    <div class="text-xs text-gray-500">${b.keperluan_booking || '-'}</div>
                                </td>
                                <td class="py-4 px-6 text-sm">
                                    <div class="text-gray-800">${b.checkin}</div>
                                    <div class="text-gray-500 text-xs">s/d ${b.checkout}</div>
                                </td>
                                <td class="py-4 px-6">${getStatusBadge(b.status)}</td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button onclick="updateStatus(${b.id}, 'disetujui')" class="bg-primary hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors shadow-sm">Approve</button>
                                        <button onclick="updateStatus(${b.id}, 'ditolak')" class="bg-secondary hover:bg-orange-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors shadow-sm">Reject</button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = `<tr><td colspan="5" class="py-8 text-center text-gray-500">Tidak ada booking yang pending.</td></tr>`;
                }
                document.getElementById('table-pending').innerHTML = html;
            } catch (e) { console.error(e); }
        }

        async function loadAllBookings() {
            try {
                let res = await fetch('admin_action.php?action=get_bookings&type=all');
                let json = await res.json();
                let html = '';
                if (json.status === 'success') {
                    json.data.forEach(b => {
                        html += `
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 font-medium text-gray-800">${b.nama}</td>
                                <td class="py-4 px-6">${b.ruangan_nama || '-'}</td>
                                <td class="py-4 px-6 text-sm">
                                    <div class="text-gray-800">${b.checkin}</div>
                                    <div class="text-gray-500 text-xs">s/d ${b.checkout}</div>
                                </td>
                                <td class="py-4 px-6">${getStatusBadge(b.status)}</td>
                            </tr>
                        `;
                    });
                    document.getElementById('table-riwayat').innerHTML = html;
                }
            } catch (e) { console.error(e); }
        }

        async function updateStatus(id, status) {
            if(!confirm('Apakah Anda yakin mengubah status menjadi ' + status + '?')) return;
            try {
                let formData = new FormData();
                formData.append('action', 'update_status');
                formData.append('id', id);
                formData.append('status', status);

                let res = await fetch('admin_action.php', { method: 'POST', body: formData });
                let json = await res.json();
                if (json.status === 'success') {
                    loadPendingBookings();
                    loadStats();
                } else {
                    alert('Gagal: ' + json.message);
                }
            } catch (e) { console.error(e); }
        }

        // Init load
        loadStats();
    </script>
</body>
</html>
