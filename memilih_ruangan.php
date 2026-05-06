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

// pilih ruangan
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
<link rel="stylesheet" href="style.css">

<style>
.filter-box {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(12px);
    padding: 20px;
    border-radius: 16px;
    margin-bottom: 25px;
    border: 1px solid rgba(255,255,255,0.2);
}

.filter-box input,
.filter-box select {
    border-radius: 10px;
    border: none;
    padding: 10px;
}

.btn-cari {
    background: linear-gradient(135deg, #4f46e5, #f97316);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
}

.btn-cari:hover {
    opacity: 0.9;
}
</style>

</head>

<body class="memilih-ruangan-page">
<div class="container py-4">

<div class="header">
    <img src="img/logo.png">
    <h2>RuangKita</h2>
    <p>Sistem Booking Ruangan Modern</p>
</div>

<div class="filter-box">
<form method="GET" class="row g-2">

    <div class="col-md-4">
        <input type="number" name="kapasitas" class="form-control"
        placeholder="Minimal Kapasitas"
        value="<?= $kapasitas ?>">
    </div>

    <div class="col-md-4">
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

    <div class="col-md-4 d-grid">
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
</script>

</body>
</html>