<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $mode = $_POST['mode'] ?? '';

    $nama = trim($_SESSION['nama'] ?? '');
    $prodi = trim($_POST['prodi'] ?? '');
    $email = trim($_SESSION['email'] ?? '');
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

    if ($cin_date < $today || $cout_date < $today) {

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

    // CEK BENTROK JADWAL
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

    // MODE CEK
    if ($mode == "cek") {

        echo "tersedia";
        exit;
    }

    // VALIDASI FORM
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

    // ESCAPE
    $nama = mysqli_real_escape_string($conn, $nama);
    $prodi = mysqli_real_escape_string($conn, $prodi);
    $email = mysqli_real_escape_string($conn, $email);
    $angkatan = mysqli_real_escape_string($conn, $angkatan);
    $kelas = mysqli_real_escape_string($conn, $kelas);
    $keperluan = mysqli_real_escape_string($conn, $keperluan);
    $ruangan_nama = mysqli_real_escape_string($conn, $ruangan_nama);

    // INSERT BOOKING
    $insert = mysqli_query($conn, "
        INSERT INTO bookings
        (
            nama,
            prodi,
            email,
            ruangan_nama,
            checkin,
            checkout,
            created_at,
            angkatan,
            kelas,
            keperluan_booking,
            status
        )
        VALUES
        (
            '$nama',
            '$prodi',
            '$email',
            '$ruangan_nama',
            '$checkin',
            '$checkout',
            NOW(),
            '$angkatan',
            '$kelas',
            '$keperluan',
            'pending'
        )
    ");

    if ($insert) {

        echo "sukses";

    } else {

        echo mysqli_error($conn);
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.swal-booking-success {
    border-radius: 24px !important;
    padding: 24px !important;
}

.swal2-popup.swal-booking-success .swal2-icon.swal2-success {
    border-color: #22c55e;
    color: #22c55e;
}

.swal2-popup.swal-booking-success .swal2-icon.swal2-success [class^='swal2-success-line'] {
    background-color: #22c55e;
}

.swal2-popup.swal-booking-success .swal2-icon.swal2-success .swal2-success-ring {
    border-color: rgba(34, 197, 94, 0.3);
}

.swal2-timer-progress-bar {
    background: linear-gradient(90deg, #2563eb, #7c3aed) !important;
}
</style>

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
                <input type="text" id="nama" value="<?= htmlspecialchars($_SESSION['nama'] ?? ''); ?>" readonly style="background:#f1f5f9; cursor:not-allowed;">
            </div>

            <div>
                <label>Program Studi</label>
                <input type="text" id="prodi">
            </div>

            <div>
                <label>Email</label>
                <input type="email" id="email" value="<?= htmlspecialchars($_SESSION['email'] ?? ''); ?>" readonly style="background:#f1f5f9; cursor:not-allowed;">
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
        <button type="button" class="btn" style="background: #94a3b8; box-shadow: 0 8px 20px rgba(148, 163, 184, 0.25);" onclick="window.location.href='memilih_ruangan.php'">
            Kembali
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

        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Data booking tidak valid!',
            confirmButtonColor: '#3085d6'
        });
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

            msg.innerHTML = "";

            Swal.fire({
                icon: 'success',
                title: '🎉 Booking Berhasil!',
                html: `
                    <div style="text-align:center;">
                        <p style="font-size:16px; color:#475569; margin-bottom:8px;">
                            Ruangan berhasil dibooking!
                        </p>
                        <div style="
                            background: linear-gradient(135deg, #dbeafe, #ede9fe);
                            border-radius: 16px;
                            padding: 16px 20px;
                            margin: 12px 0;
                            border: 1px solid #c7d2fe;
                        ">
                            <p style="margin:0; font-size:14px; color:#6366f1; font-weight:700;">
                                📋 Status: <span style="color:#f59e0b;">Menunggu Persetujuan</span>
                            </p>
                        </div>
                        <p style="font-size:13px; color:#94a3b8; margin-top:8px;">
                            Anda akan diarahkan ke Dashboard...
                        </p>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'Ke Dashboard',
                confirmButtonColor: '#2563eb',
                timer: 5000,
                timerProgressBar: true,
                allowOutsideClick: false,
                customClass: {
                    popup: 'swal-booking-success'
                }
            }).then(() => {
                window.location.href = 'user_dashboard.php';
            });

        }
        else if(res === "kosong"){

            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Mohon lengkapi semua field sebelum booking.',
                confirmButtonColor: '#f59e0b'
            });

        }
        else{

            Swal.fire({
                icon: 'error',
                title: 'Booking Gagal',
                text: 'Terjadi kesalahan saat memproses booking. Silakan coba lagi.',
                confirmButtonColor: '#ef4444'
            });

        }

    });

}

</script>
</body>
</html>

