<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$koneksi = new mysqli("localhost", "root", "", "ruangkita");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$current_page = basename($_SERVER['PHP_SELF']);
$kapasitas = isset($_GET['kapasitas']) ? $_GET['kapasitas'] : '';
$fasilitas = isset($_GET['fasilitas']) ? $_GET['fasilitas'] : '';
$query = "SELECT * FROM ruangan WHERE 1=1";

if (!empty($kapasitas)) {
    $query .= " AND kapasitas >= '$kapasitas'";
}

if ($fasilitas == 'terlengkap') {
    $query .= " AND LOWER(nama) LIKE '%smart classroom%'";
} elseif (!empty($fasilitas)) {
    $query .= " AND LOWER(fasilitas) LIKE LOWER('%$fasilitas%')";
}

$result = $koneksi->query($query);

if(!$result){
    die("Query error: " . $koneksi->error);
}

$ruangan = [];
while($row = $result->fetch_assoc()){
    $ruangan[] = $row;
}

if(isset($_POST['lanjutkan']) && !empty($_POST['data_ruangan'])){
    $_SESSION['ruangan'] = json_decode($_POST['data_ruangan'], true);

    if(isset($_SESSION['ruangan']['nama'])){
        echo "<script>alert('Ruangan dipilih: ".$_SESSION['ruangan']['nama']."'); window.location.href='proses_booking.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RuangKita</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Plus Jakarta Sans', sans-serif;
}

body{
    background:#eef4ff;
    overflow-x:hidden;
}

.dashboard-layout{
    display:flex;
    min-height:100vh;
}

.sidebar-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.35);
    z-index:998;
    opacity:0;
    visibility:hidden;
    transition:0.3s;
}

.sidebar-overlay.active{
    opacity:1;
    visibility:visible;
}

.sidebar{
    width:295px;
    background:#ffffff;
    padding:24px 18px;
    border-right:1px solid #e5e7eb;
    position:fixed;
    left:0;
    top:0;
    height:100vh;
    z-index:999;
    transition:0.3s ease;
    overflow-y:auto;
    transform:translateX(0);
}

.sidebar.active{
    transform:translateX(0);
}

.sidebar.closed{
    transform:translateX(-100%);
}

.sidebar-logo{
    display:flex;
    align-items:flex-start;
    gap:14px;
    margin-bottom:28px;
    padding-left:60px;
}

.sidebar-logo img{
    width:48px;
    height:48px;
    object-fit:contain;
    border-radius:14px;
    position:relative;
    top:-6px;
}

.sidebar-logo h2{
    font-size:20px;
    color:#111827;
    margin:0;
    margin-top:-4px;
    font-weight:800;
}

.sidebar-logo p{
    font-size:13px;
    color:#6b7280;
    margin-top:-2px;
}

.profile-box{
    background:#f8fafc;
    border:1px solid #e5e7eb;
    padding:18px;
    border-radius:20px;
    margin-bottom:30px;
}

.profile-name{
    font-size:15px;
    font-weight:700;
    color:#111827;
}

.profile-email{
    font-size:13px;
    color:#6b7280;
    margin-top:5px;
    word-break:break-word;
}

.sidebar-menu{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.sidebar-menu a{
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:14px;
    padding:15px 17px;
    border-radius:18px;
    color:#374151;
    font-weight:600;
    font-size:15px;
    transition:all 0.25s ease;
    position:relative;
    overflow:hidden;
}

.sidebar-menu a:hover{
    transform:translateX(5px);
    color:#2563eb;
}

.sidebar-menu a.active{
    background:linear-gradient(
      135deg,
      #2563eb,
      #3b82f6
    );
    color:white;
    box-shadow:
      0 12px 28px rgba(37,99,235,0.28);
}

.sidebar-menu a i{
    font-size:22px;
    min-width:22px;
}

.sidebar-bottom{
    margin-top:20px;
    padding-top:20px;
    border-top:1px solid #e5e7eb;
}

.sidebar-bottom .sidebar-menu a{
    color:#dc2626;
}

.main-content{
    width:100%;
    min-height:100vh;
    padding:20px;
    margin-left:295px;
    background:
    linear-gradient(
        180deg,
        #f8fbff,
        #eef4ff
    );
}

.sidebar-toggle{
    position:fixed;
    top:18px;
    left:18px;
    z-index:1001;
    width:52px;
    height:52px;
    border:none;
    border-radius:18px;
    background:linear-gradient(
      135deg,
      #2563eb,
      #3b82f6
    );
    color:white;
    font-size:26px;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:0.25s ease;
    box-shadow:
      0 12px 28px rgba(37,99,235,0.25);
}


.filter-box{
    background:white;
    border:1px solid #dbeafe;
    box-shadow:
    0 10px 30px rgba(37,99,235,0.08);
    padding:20px;
    border-radius:20px;
    margin-bottom:25px;
}

.filter-box input,
.filter-box select{
    border:2px solid #dbeafe;
    background:#f8fbff;
    border-radius:14px;
    padding:12px 14px;
    font-weight:600;
    color:#1e293b;
    transition:0.25s ease;
    box-shadow:none;
}

.filter-box input::placeholder{
    color:#64748b;
    font-weight:500;
}

.filter-box input:focus,
.filter-box select:focus{
    border-color:#3b82f6;
    background:white;
    box-shadow:
    0 0 0 4px rgba(59,130,246,0.12);
}

.btn-cari{
    background:
    linear-gradient(
        135deg,
        #2563eb,
        #3b82f6
    );
    color:white;
    border:none;
    border-radius:12px;
    font-weight:700;
    padding:10px 18px;
    transition:0.25s ease;
}

.btn-cari:hover{
    transform:translateY(-2px);
    box-shadow:
    0 10px 20px rgba(37,99,235,0.20);
}

.btn-custom{
    background:
    linear-gradient(
        135deg,
        #2563eb,
        #3b82f6
    ) !important;

    color:white !important;
    border:none !important;
    border-radius:14px;
    padding:12px 22px;
    font-weight:700;
    box-shadow:
    0 10px 20px rgba(37,99,235,0.20);
}

.btn-custom:hover{
    transform:translateY(-2px);
    opacity:0.96;
}

.room-card{
    border:none;
    border-radius:24px;
    overflow:hidden;
    transition:0.3s ease;
    background:white;
    box-shadow:
    0 10px 25px rgba(15,23,42,0.06);
    cursor:pointer;
}

.room-card:hover{
    transform:translateY(-6px);
    box-shadow:
    0 20px 35px rgba(37,99,235,0.15);
}

.room-card img{
    height:220px;
    object-fit:cover;
}

.room-card.active{
    border:2px solid #2563eb;
}

.detail-card{
    border:none;
    border-radius:24px;
    background:white;
    box-shadow:
    0 10px 30px rgba(37,99,235,0.08);
    display:none;
}

.btn-custom{
    background:
    linear-gradient(
        135deg,
        #2563eb,
        #3b82f6
    );
    color:white;
    border:none;
    border-radius:14px;
    padding:12px 20px;
    font-weight:700;
}

.btn-custom:hover{
    color:white;
    opacity:0.95;
}

.header h2{
    color:#1e293b;
    font-weight:800;
}

.header p{
    color:#475569;
    font-weight:500;
}

@media(max-width:900px){
    .sidebar{
        width:280px;
    }
}
</style>

</head>

<body class="memilih-ruangan-page">

<button class="sidebar-toggle"
        id="sidebarToggle"
        onclick="toggleSidebar()">
  ☰
</button>

<div class="sidebar-overlay"
     id="sidebarOverlay"
     onclick="closeSidebar()">
</div>

<div class="dashboard-layout">

<div class="sidebar" id="sidebar">

    <div class="sidebar-logo">
      <img src="img/logo.png" alt="Logo">
      <div>
        <h2>RuangKita</h2>
        <p>User Panel</
        p>
      </div>
    </div>

    <div class="profile-box">
      <div class="profile-name">
        <?= $_SESSION['nama']; ?>
      </div>

      <div class="profile-email">
        <?= $_SESSION['email']; ?>
      </div>
    </div>

    <div class="sidebar-menu">

      <a href="user_dashboard.php"
         class="<?= ($current_page == 'user_dashboard.php') ? 'active' : ''; ?>">
        <i class="ri-dashboard-3-fill"></i>
        <span>Dashboard</span>
      </a>

      <a href="memilih_ruangan.php"
         class="<?= ($current_page == 'memilih_ruangan.php') ? 'active' : ''; ?>">
        <i class="ri-calendar-schedule-fill"></i>
        <span>Booking Ruangan</span>
      </a>

      <a href="kalender_jadwal_ruangan.php"
         class="<?= ($current_page == 'kalender_jadwal_ruangan.php') ? 'active' : ''; ?>">
        <i class="ri-calendar-check-fill"></i>
        <span>Kalender</span>
      </a>

    </div>

    <div class="sidebar-bottom">
      <div class="sidebar-menu">
        <a href="logout.php">
          <i class="ri-logout-circle-r-fill"></i>
          <span>Logout</span>
        </a>
      </div>
    </div>

</div>

<div class="main-content">

<div class="container py-4">

<div class="header">
    <img src="img/logo.png">
    <h2>RuangKita</h2>
    <p>Sistem Booking Ruangan Modern</p>
</div>

<div class="filter-box">
<form method="GET" class="row g-2 align-items-end">

    <div class="col-md-5">
        <input type="number" name="kapasitas" class="form-control"
        placeholder="Minimal Kapasitas"
        value="<?= $kapasitas ?>">
    </div>

    <div class="col-md-5">
        <select name="fasilitas" class="form-control">
            <option value="">Semua Ruangan</option>
            <option value="terlengkap" <?= $fasilitas=='terlengkap'?'selected':'' ?>>Fasilitas Terlengkap</option>

            <option value="AC" <?= $fasilitas=='AC'?'selected':'' ?>>AC</option>
            <option value="TV" <?= $fasilitas=='TV'?'selected':'' ?>>TV</option>
            <option value="Smartboard" <?= $fasilitas=='Smartboard'?'selected':'' ?>>Smartboard</option>
            <option value="Proyektor" <?= $fasilitas=='Proyektor'?'selected':'' ?>>Proyektor</option>
            <option value="Whiteboard" <?= $fasilitas=='Whiteboard'?'selected':'' ?>>Whiteboard</option>
            <option value="Stop Kontak" <?= $fasilitas=='Stop Kontak'?'selected':'' ?>>Stop Kontak</option>
            <option value="Komputer" <?= $fasilitas=='Komputer'?'selected':'' ?>>Komputer</option>
            <option value="Internet" <?= $fasilitas=='Internet'?'selected':'' ?>>Internet</option>
            <option value="Audio" <?= $fasilitas=='Audio'?'selected':'' ?>>Audio</option>
            <option value="Smart TV" <?= $fasilitas=='Smart TV'?'selected':'' ?>>Smart TV</option>
            <option value="Kamera" <?= $fasilitas=='Kamera'?'selected':'' ?>>Kamera</option>
            <option value="Mic Wireless" <?= $fasilitas=='Mic Wireless'?'selected':'' ?>>Mic Wireless</option>
        </select>
    </div>

    <div class="col-md-2 text-end">
        <button class="btn-cari">Cari Ruangan</button>
    </div>

</form>
</div>

<div id="detailCard" class="card detail-card shadow p-3 mb-4">

    <div class="row align-items-center">
        <div class="col-md-4">
            <img id="d_gambar" class="img-fluid rounded">
        </div>

        <div class="col-md-8">
            <h4 id="d_nama" class="fw-bold"></h4>
            <p class="mb-1"><b>Kapasitas:</b> <span id="d_kapasitas"></span> orang</p>
            <p><b>Fasilitas:</b> <span id="d_fasilitas"></span></p>

            <form method="POST">
                <input type="hidden" name="data_ruangan" id="data_ruangan">
                <button name="lanjutkan" class="btn btn-custom">
                    Pilih Ruangan
                </button>
            </form>
        </div>
    </div>

</div>

<div class="row">
<?php if(count($ruangan) > 0): ?>
    <?php foreach ($ruangan as $r): ?>

    <div class="col-md-4 mb-4">
    <div class="card room-card shadow-sm"
         onclick='pilihRuangan(this, <?= json_encode($r, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>

        <img src="<?= $r['gambar'] ?>">
        <div class="p-3 text-center">
            <h6 class="fw-bold mb-1"><?= $r['nama'] ?></h6>
        </div>

    </div>
    </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="text-center mt-5">
        <h5 style="color:#6b7280;">Ups! Ruangan tidak tersedia.</h5>
        <p class="text-muted">Coba ubah filter kapasitas atau fasilitas</p>
    </div>

<?php endif; ?>
</div>

</div>
</div>
</div>

<script>

function pilihRuangan(el, data){

    document.querySelectorAll('.room-card').forEach(e=>e.classList.remove('active'));
    el.classList.add('active');

    document.getElementById('d_nama').innerText = data.nama;
    document.getElementById('d_kapasitas').innerText = data.kapasitas;
    document.getElementById('d_fasilitas').innerText = data.fasilitas;
    document.getElementById('d_gambar').src = data.gambar;

    document.getElementById('data_ruangan').value = JSON.stringify(data);

    let card = document.getElementById('detailCard');
    card.style.display = 'block';

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');

function toggleSidebar(){

  sidebar.classList.toggle('closed');

}

function closeSidebar(){

  sidebar.classList.add('closed');

}
</script>

</body>
</html>