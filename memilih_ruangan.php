<?php
session_start();

$koneksi = new mysqli("localhost", "root", "", "ruangkita");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$result = $koneksi->query("SELECT * FROM ruangan");

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
        echo "<script>alert('Ruangan dipilih: ".$_SESSION['ruangan']['nama']."');</script>";
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

<style>
body {
    background: linear-gradient(to right, #1e3c72, #ff7e00);
    font-family: 'Segoe UI', sans-serif;
}

.header {
    text-align: center;
    color: white;
    margin-bottom: 30px;
}

.header img {
    width: 60px;
}

.header h2 {
    margin-top: 10px;
    font-weight: bold;
}

.room-card {
    cursor: pointer;
    transition: 0.3s;
    border-radius: 15px;
    overflow: hidden;
    border: none;
}

.room-card:hover {
    transform: translateY(-5px);
}

.room-card.active {
    outline: 3px solid orange;
}

.room-card img {
    height: 150px;
    object-fit: cover;
}

.detail-card {
    display: none;
    border-radius: 20px;
    animation: fadeIn 0.4s ease;
}

@keyframes fadeIn {
    from {opacity: 0; transform: translateY(-10px);}
    to {opacity: 1; transform: translateY(0);}
}

.btn-custom {
    background: linear-gradient(to right, #1e3c72, #ff7e00);
    color: white;
    border: none;
}
</style>
</head>

<body>
<div class="container py-4">

<div class="header">
    <img src="img/logo.png">
    <h2>RuangKita</h2>
    <p>Sistem Booking Ruangan Modern</p>
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