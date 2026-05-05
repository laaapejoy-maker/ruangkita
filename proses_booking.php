<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nama = $_POST['nama'] ?? '';
    $prodi = $_POST['prodi'] ?? '';
    $email = $_POST['email'] ?? '';
    $angkatan = $_POST['angkatan'] ?? '';
    $kelas = $_POST['kelas'] ?? '';
    $keperluan = $_POST['keperluan_booking'] ?? '';

    $cin_date = $_POST['cin_date'] ?? '';
    $cin_time = $_POST['cin_time'] ?? '';
    $cout_date = $_POST['cout_date'] ?? '';
    $cout_time = $_POST['cout_time'] ?? '';

    if(!$nama || !$prodi || !$email || !$angkatan || !$kelas || !$keperluan || !$cin_date || !$cin_time || !$cout_date || !$cout_time){
        echo "kosong"; exit;
    }

    $checkin = $cin_date . " " . $cin_time;
    $checkout = $cout_date . " " . $cout_time;
    
    $ruangan_nama = $_SESSION['ruangan']['nama'] ?? '';

    if(!$ruangan_nama) {
        echo "ruang_kosong"; exit;
    }

    $cek = mysqli_query($conn,"SELECT * FROM bookings 
        WHERE ruangan_nama='$ruangan_nama' AND ('$checkin' < checkout) 
        AND ('$checkout' > checkin)");

    if(mysqli_num_rows($cek) > 0){
        echo "bentrok";
    } else {
        mysqli_query($conn,"INSERT INTO bookings
        (nama,prodi,email,ruangan_nama,angkatan,kelas,keperluan_booking,checkin,checkout)
        VALUES
        ('$nama','$prodi','$email','$ruangan_nama','$angkatan','$kelas','$keperluan','$checkin','$checkout')");
        
        echo "sukses";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>RuangKita</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="style.css">
</head>

<body class="booking-page">

<div class="main">

    <!-- LEFT -->
    <div class="left">
        <img src="IMG/LOGO.PNG">
        <h1>RuangKita</h1>
        <p>Sistem Booking Ruangan Kampus Modern</p>
    </div>

    <!-- RIGHT -->
    <div class="right">

        <h2>Form Booking Ruangan</h2>

        <div class="grid">

            <div class="full">
                <label>Nama Lengkap</label>
                <input type="text" id="nama">
            </div>

            <div>
                <label>Program Studi</label>
                <input type="text" id="prodi">
            </div>

            <div>
                <label>Email</label>
                <input type="email" id="email">
            </div>

            <div>
                <label>Angkatan</label>
                <input type="text" id="angkatan">
            </div>

            <div>
                <label>Kelas</label>
                <input type="text" id="kelas">
            </div>

            <div class="full">
                <label>Keperluan Booking</label>
                <textarea id="keperluan_booking"></textarea>
            </div>

            <div>
                <label>Check-In (Tanggal)</label>
                <input type="date" id="cin_date" onchange="cekJadwal()">
            </div>

            <div>
                <label>Check-In (Waktu)</label>
                <input type="time" id="cin_time" onchange="cekJadwal()">
            </div>

            <div>
                <label>Check-Out (Tanggal)</label>
                <input type="date" id="cout_date" onchange="cekJadwal()">
            </div>

            <div>
                <label>Check-Out (Waktu)</label>
                <input type="time" id="cout_time" onchange="cekJadwal()">
            </div>

        </div>

        <button class="btn" onclick="booking()" id="btnBooking">Booking Sekarang</button>
        <div class="msg" id="msg"></div>

    </div>

</div>

<script>
let bentrok = false;

function cekJadwal(){

    let fd = new FormData();
    fd.append("nama","cek");
    fd.append("prodi","cek");
    fd.append("email","cek");
    fd.append("angkatan","cek");
    fd.append("kelas","cek");
    fd.append("keperluan_booking","cek");

    fd.append("cin_date",cin_date.value);
    fd.append("cin_time",cin_time.value);
    fd.append("cout_date",cout_date.value);
    fd.append("cout_time",cout_time.value);

    fetch("proses_booking.php",{method:"POST",body:fd})
    .then(r=>r.text())
    .then(res=>{
        let msg = document.getElementById("msg");
        let btn = document.getElementById("btnBooking");

        if(res==="bentrok"){
            msg.innerHTML="❌ Jadwal bentrok";
            msg.style.color="red";
            btn.disabled=true;
            bentrok=true;
        }else{
            msg.innerHTML="✅ Jadwal tersedia";
            msg.style.color="green";
            btn.disabled=false;
            bentrok=false;
        }
    });
}

function booking(){

    if(bentrok){ alert("Jadwal bentrok!"); return; }

    let fd = new FormData();

    fd.append("nama",nama.value);
    fd.append("prodi",prodi.value);
    fd.append("email",email.value);
    fd.append("angkatan",angkatan.value);
    fd.append("kelas",kelas.value);
    fd.append("keperluan_booking",keperluan_booking.value);

    fd.append("cin_date",cin_date.value);
    fd.append("cin_time",cin_time.value);
    fd.append("cout_date",cout_date.value);
    fd.append("cout_time",cout_time.value);

    fetch("proses_booking.php",{method:"POST",body:fd})
    .then(r=>r.text())
    .then(res=>{
        let msg = document.getElementById("msg");

        if(res==="sukses"){
            msg.innerHTML="✔ Booking berhasil";
            msg.style.color="#ff7a00";
        }else{
            msg.innerHTML="❌ Gagal booking";
            msg.style.color="red";
        }
    });
}
</script>

</body>
</html>