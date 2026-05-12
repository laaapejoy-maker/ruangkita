<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $mode = $_POST['mode'] ?? '';

    $nama = trim($_POST['nama'] ?? '');
    $prodi = trim($_POST['prodi'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $angkatan = trim($_POST['angkatan'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    $keperluan = trim($_POST['keperluan_booking'] ?? '');

    $cin_date = $_POST['cin_date'] ?? '';
    $cin_time = $_POST['cin_time'] ?? '';
    $cout_date = $_POST['cout_date'] ?? '';
    $cout_time = $_POST['cout_time'] ?? '';

    if (
        empty($cin_date) ||
        empty($cin_time) ||
        empty($cout_date) ||
        empty($cout_time)
    ) {
        echo "kosong";
        exit;
    }

    $checkin = date("Y-m-d H:i:s", strtotime($cin_date . ' ' . $cin_time));
    $checkout = date("Y-m-d H:i:s", strtotime($cout_date . ' ' . $cout_time));

    $today = date("Y-m-d");

    if ($cin_date <= $today || $cout_date <= $today) {

        echo "tanggal_tidak_valid";
        exit;
    }

    $ruangan_nama = $_SESSION['ruangan']['nama'] ?? '';

    if (empty($ruangan_nama)) {
        echo "ruang_kosong";
        exit;
    }

    if (strtotime($checkout) <= strtotime($checkin)) {

        echo "waktu_salah";
        exit;
    }

    
    $query = mysqli_query($conn, "
        SELECT id FROM bookings
        WHERE ruangan_nama = '$ruangan_nama'
        AND (
            checkin < '$checkout'
            AND
            checkout > '$checkin'
        )
    ");

    if (!$query) {

        echo "query_error";
        exit;
    }

    if (mysqli_num_rows($query) > 0) {

        echo "bentrok";
        exit;
    }

    if ($mode == "cek") {

        echo "tersedia";
        exit;
    }

    if (
        empty($nama) ||
        empty($prodi) ||
        empty($email) ||
        empty($angkatan) ||
        empty($kelas) ||
        empty($keperluan)
    ) {

        echo "kosong";
        exit;
    }

    $nama = mysqli_real_escape_string($conn, $nama);
    $prodi = mysqli_real_escape_string($conn, $prodi);
    $email = mysqli_real_escape_string($conn, $email);
    $angkatan = mysqli_real_escape_string($conn, $angkatan);
    $kelas = mysqli_real_escape_string($conn, $kelas);
    $keperluan = mysqli_real_escape_string($conn, $keperluan);

    $insert = mysqli_query($conn, "
        INSERT INTO bookings
        (
            nama,
            prodi,
            email,
            ruangan_nama,
            angkatan,
            kelas,
            keperluan_booking,
            checkin,
            checkout
        )
        VALUES
        (
            '$nama',
            '$prodi',
            '$email',
            '$ruangan_nama',
            '$angkatan',
            '$kelas',
            '$keperluan',
            '$checkin',
            '$checkout'
        )
    ");

    if ($insert) {

        echo "sukses";

    } else {

        echo "gagal";
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

    <div class="left">
        <img src="IMG/LOGO.PNG">
        <h1>RuangKita</h1>
        <p>Sistem Booking Ruangan Kampus Modern</p>
    </div>

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

        <button class="btn" onclick="booking()" id="btnBooking">
            Booking Sekarang
        </button>

        <div class="msg" id="msg"></div>

    </div>

</div>

<script>

let bentrok = false;

window.onload = function(){

    let today = new Date();

    today.setDate(today.getDate() + 1);

    let yyyy = today.getFullYear();
    let mm = String(today.getMonth() + 1).padStart(2, '0');
    let dd = String(today.getDate()).padStart(2, '0');

    let minDate = yyyy + '-' + mm + '-' + dd;

    cin_date.min = minDate;
    cout_date.min = minDate;
}

function cekJadwal(){

    if(
        !cin_date.value ||
        !cin_time.value ||
        !cout_date.value ||
        !cout_time.value
    ){
        return;
    }

    let fd = new FormData();

    fd.append("mode","cek");

    fd.append("cin_date",cin_date.value);
    fd.append("cin_time",cin_time.value);
    fd.append("cout_date",cout_date.value);
    fd.append("cout_time",cout_time.value);

    fetch("proses_booking.php",{
        method:"POST",
        body:fd
    })

    .then(r => r.text())

    .then(res => {

        let msg = document.getElementById("msg");
        let btn = document.getElementById("btnBooking");

        if(res === "bentrok"){

            msg.innerHTML = "❌ Jadwal bentrok";
            msg.style.color = "red";

            bentrok = true;
            btn.disabled = true;

        }
        else if(res === "waktu_salah"){

            msg.innerHTML = "❌ Check-out harus setelah check-in";
            msg.style.color = "red";

            bentrok = true;
            btn.disabled = true;

        }
        else if(res === "tanggal_tidak_valid"){

            msg.innerHTML = "❌ Booking tidak boleh hari ini atau tanggal yang sudah lewat";
            msg.style.color = "red";

            bentrok = true;
            btn.disabled = true;

        }
        else if(res === "tersedia"){

            msg.innerHTML = "✅ Jadwal tersedia";
            msg.style.color = "green";

            bentrok = false;
            btn.disabled = false;

        }

    });

}

function booking(){

    if(bentrok === true){

        alert("Data booking tidak valid!");
        return;
    }

    let fd = new FormData();

    fd.append("mode","booking");

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

    fetch("proses_booking.php",{
        method:"POST",
        body:fd
    })

    .then(r => r.text())

    .then(res => {

        let msg = document.getElementById("msg");

        if(res === "sukses"){

            msg.innerHTML = "✔ Booking berhasil";
            msg.style.color = "#ff7a00";

        }
        else if(res === "bentrok"){

            msg.innerHTML = "❌ Jadwal bentrok";
            msg.style.color = "red";

        }
        else if(res === "waktu_salah"){

            msg.innerHTML = "❌ Check-out harus setelah check-in";
            msg.style.color = "red";

        }
        else if(res === "tanggal_tidak_valid"){

            msg.innerHTML = "❌ Tidak bisa booking di hari ini / tanggal lewat";
            msg.style.color = "red";

        }
        else if(res === "kosong"){

            msg.innerHTML = "❌ Semua data wajib diisi";
            msg.style.color = "red";

        }
        else{

            msg.innerHTML = "❌ Gagal booking";
            msg.style.color = "red";

        }

    });

}

</script>

</body>
</html>