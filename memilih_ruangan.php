<?php
session_start();

$ruangan = [
    ["id"=>1,"nama"=>"RUANG A","kapasitas"=>40,"fasilitas"=>"AC, Proyektor, Whiteboard, Stop Kontak Lengkap","gambar"=>"img/ruang_a.jpg"],
    ["id"=>2,"nama"=>"RUANG B","kapasitas"=>40,"fasilitas"=>"AC, Smartboard, Stop Kontak Lengkap","gambar"=>"img/ruang_b.jpg"],
    ["id"=>3,"nama"=>"RUANG C","kapasitas"=>40,"fasilitas"=>"AC, TV, Whiteboard","gambar"=>"img/ruang_c.jpg"],
    ["id"=>4,"nama"=>"LAB A","kapasitas"=>45,"fasilitas"=>"Komputer Lengkap, AC, Smartboard, Internet Stabil","gambar"=>"img/lab_a.jpg"],
    ["id"=>5,"nama"=>"LAB B","kapasitas"=>45,"fasilitas"=>"Komputer Lengkap, AC, Smartboard, Internet Stabil, Audio","gambar"=>"img/lab_b.jpg"],
    ["id"=>6,"nama"=>"SMART CLASSROOM","kapasitas"=>100,"fasilitas"=>"Smart TV, Komputer, Kamera, Mic Wireless, AC, Stop Kontak Lengkap","gambar"=>"img/smartclass.jpg"],
];

if(isset($_POST['lanjutkan'])){
    $_SESSION['ruangan'] = json_decode($_POST['data_ruangan'], true);
    header("Location: proses_booking.php");
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

/* CARD RUANGAN */
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

/* DETAIL CARD */
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

<!-- HEADER -->
<div class="header">
    <img src="img/logo.png">
    <h2>RuangKita</h2>
    <p>Sistem Booking Ruangan Modern</p>
</div>

<!-- DETAIL CARD -->
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
                    Lanjutkan →
                </button>
            </form>
        </div>
    </div>

</div>

<!-- LIST RUANGAN -->
<div class="row">
<?php foreach ($ruangan as $r): ?>
<div class="col-md-4 mb-4">

<div class="card room-card shadow-sm"
     onclick='pilihRuangan(this, <?= json_encode($r) ?>)'>

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