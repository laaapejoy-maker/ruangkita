<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

include "koneksi.php";

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';


function uploadGambar($inputName){

    $namaFile = $_FILES[$inputName]['name'];
    $tmpName  = $_FILES[$inputName]['tmp_name'];

    if($namaFile == ""){
        return "";
    }

    $folder = "img/";

    $namaBaru = time() . "_" . $namaFile;

    move_uploaded_file($tmpName, $folder . $namaBaru);

    return $folder . $namaBaru;
}



if(isset($_POST['tambah_ruangan'])){

    $nama       = $_POST['nama'];
    $kapasitas  = $_POST['kapasitas'];
    $fasilitas  = $_POST['fasilitas'];

    $gambar = uploadGambar('gambar');

    mysqli_query($conn,"
        INSERT INTO ruangan
        (nama, kapasitas, fasilitas, gambar)
        VALUES
        ('$nama','$kapasitas','$fasilitas','$gambar')
    ");

    header("Location: admin_dashboard.php?page=ruangan");
    exit;
}



if(isset($_POST['update_ruangan'])){

    $id         = $_POST['id'];
    $nama       = $_POST['nama'];
    $kapasitas  = $_POST['kapasitas'];
    $fasilitas  = $_POST['fasilitas'];

    $gambarLama = $_POST['gambar_lama'];

    if($_FILES['gambar']['name'] != ""){

        $gambar = uploadGambar('gambar');

    } else {

        $gambar = $gambarLama;
    }

    mysqli_query($conn,"
        UPDATE ruangan
        SET
        nama='$nama',
        kapasitas='$kapasitas',
        fasilitas='$fasilitas',
        gambar='$gambar'
        WHERE id='$id'
    ");

    header("Location: admin_dashboard.php?page=ruangan");
    exit;
}



if(isset($_GET['hapus_ruangan'])){

    $id = $_GET['hapus_ruangan'];

    mysqli_query($conn,"
        DELETE FROM ruangan
        WHERE id='$id'
    ");

    header("Location: admin_dashboard.php?page=ruangan");
    exit;
}



if(isset($_GET['aksi']) && isset($_GET['id'])){

    $id = $_GET['id'];
    $aksi = $_GET['aksi'];

    if($aksi == "setuju"){

        mysqli_query($conn,"
            UPDATE bookings
            SET status='disetujui'
            WHERE id='$id'
        ");
    }

    if($aksi == "tolak"){

        mysqli_query($conn,"
            UPDATE bookings
            SET status='ditolak'
            WHERE id='$id'
        ");
    }

    header("Location: admin_dashboard.php?page=booking");
    exit;
}


$top_ruangan = mysqli_query($conn,"
    SELECT ruangan_nama, COUNT(*) as jumlah
    FROM bookings
    GROUP BY ruangan_nama
    ORDER BY jumlah DESC
    LIMIT 5
");

$booking_pending = mysqli_query($conn,"
    SELECT *
    FROM bookings
    WHERE status='pending'
    ORDER BY created_at DESC
");

$riwayat = mysqli_query($conn,"
    SELECT *
    FROM bookings
    ORDER BY created_at DESC
");

$data_ruangan = mysqli_query($conn,"
    SELECT *
    FROM ruangan
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Plus Jakarta Sans', sans-serif;
}

body{
    background:
    linear-gradient(to bottom right,#f8fbff,#eef4ff);
}



.dashboard-layout{
    display:flex;
    min-height:100vh;
}


.sidebar{
    width:280px;
    background:white;
    border-right:1px solid #edf2f7;
    position:fixed;
    height:100vh;
    padding:24px;
    box-shadow:0 0 30px rgba(0,0,0,0.03);
}

.sidebar-logo{
    display:flex;
    align-items:center;
    gap:14px;
    margin-bottom:35px;
}

.sidebar-logo img{
    width:50px;
}

.sidebar-logo h2{
    font-size:23px;
    color:#111827;
}

.sidebar-menu{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.sidebar-menu a{
    text-decoration:none;
    color:#374151;
    padding:15px 18px;
    border-radius:18px;
    display:flex;
    align-items:center;
    gap:12px;
    font-weight:700;
    transition:0.3s;
}

.sidebar-menu a:hover{
    background:#eff6ff;
    color:#2563eb;
}

.sidebar-menu a.active{
    background:linear-gradient(135deg,#2563eb,#60a5fa);
    color:white;
    box-shadow:0 10px 20px rgba(37,99,235,0.25);
}

.logout{
    margin-top:35px;
}

.logout a{
    text-decoration:none;
    color:#ef4444;
    font-weight:700;
}



.main-content{
    margin-left:280px;
    width:calc(100% - 280px);
}

.topbar{
    background:white;
    padding:24px 40px;
    border-bottom:1px solid #edf2f7;
}

.topbar h1{
    font-size:30px;
    color:#111827;
}

.content{
    padding:35px;
}



.dashboard-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:24px;
}

.dashboard-card{
    background:white;
    border-radius:30px;
    padding:25px;
    box-shadow:0 10px 35px rgba(0,0,0,0.05);
    border:1px solid #f1f5f9;
}

.dashboard-card h3{
    font-size:20px;
    margin-bottom:22px;
    color:#111827;
}



.room-item{
    display:flex;
    align-items:center;
    gap:15px;
    padding:14px;
    background:linear-gradient(to right,#f8fbff,#eef5ff);
    border-radius:20px;
    margin-bottom:14px;
}

.room-image{
    width:60px;
    height:60px;
    border-radius:16px;
    background:linear-gradient(135deg,#2563eb,#60a5fa);
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:24px;
}

.room-info{
    flex:1;
}

.room-info h4{
    font-size:15px;
    margin-bottom:4px;
}

.room-info p{
    font-size:12px;
    color:#64748b;
}

.booking-count{
    background:#2563eb;
    color:white;
    padding:8px 13px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}



.pending-modern{
    position:relative;
    overflow:hidden;
    background:white;
    border-radius:22px;
    padding:16px;
    margin-bottom:16px;
    border:1px solid #edf2f7;
    box-shadow:0 8px 20px rgba(0,0,0,0.04);
}

.pending-modern::before{
    content:'';
    position:absolute;
    top:0;
    left:0;
    width:5px;
    height:100%;
    background:linear-gradient(#f59e0b,#fb923c);
}

.pending-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:12px;
}

.pending-user{
    display:flex;
    align-items:center;
    gap:12px;
}

.pending-avatar{
    width:50px;
    height:50px;
    border-radius:15px;
    background:linear-gradient(135deg,#f59e0b,#fb923c);
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:20px;
}

.pending-name{
    font-size:14px;
    font-weight:700;
}

.pending-room{
    font-size:12px;
    color:#64748b;
}

.pending-badge{
    background:#fff7ed;
    color:#ea580c;
    padding:7px 12px;
    border-radius:999px;
    font-size:11px;
    font-weight:700;
}

.pending-date{
    display:flex;
    gap:10px;
    margin-top:10px;
}

.pending-date-box{
    flex:1;
    background:#f8fafc;
    border-radius:14px;
    padding:10px;
}

.pending-date-box small{
    display:block;
    color:#94a3b8;
    margin-bottom:4px;
    font-size:11px;
}

.pending-date-box p{
    font-size:12px;
    font-weight:700;
}



.table-box{
    background:white;
    border-radius:28px;
    overflow:hidden;
    box-shadow:0 10px 35px rgba(0,0,0,0.05);
}

.table-header{
    padding:24px;
    border-bottom:1px solid #edf2f7;
    text-align:center;
}

.table-header h2{
    font-size:24px;
    color:#111827;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#f8fbff;
    padding:16px;
    text-align:left;
    color:#64748b;
    font-size:14px;
}

td{
    padding:16px;
    border-bottom:1px solid #f1f5f9;
    font-size:14px;
}

.status{
    padding:7px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.pending{
    background:#fff7ed;
    color:#ea580c;
}

.disetujui{
    background:#dcfce7;
    color:#166534;
}

.ditolak{
    background:#fee2e2;
    color:#991b1b;
}



.btn-action{
    padding:9px 14px;
    border-radius:12px;
    text-decoration:none;
    font-size:12px;
    font-weight:700;
    display:inline-flex;
    align-items:center;
    gap:6px;
}

.btn-approve{
    background:#fff7ed;
    color:#ea580c;
    border:1px solid #fdba74;
}

.btn-reject{
    background:white;
    color:#ef4444;
    border:1px solid #fecaca;
}



.room-top-action{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:24px;
}

.btn-tambah{
    background:linear-gradient(135deg,#2563eb,#60a5fa);
    color:white;
    padding:11px 16px;
    border-radius:14px;
    text-decoration:none;
    font-size:13px;
    font-weight:700;
}

.form-ruangan{
    background:white;
    padding:24px;
    border-radius:28px;
    margin-bottom:25px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}

.input-group{
    display:flex;
    flex-direction:column;
}

.input-group label{
    margin-bottom:8px;
    font-size:13px;
    font-weight:700;
}

.input-group input,
.input-group textarea{
    border:1px solid #dbeafe;
    border-radius:15px;
    padding:12px;
    outline:none;
    font-size:14px;
}

.input-group textarea{
    height:100px;
    resize:none;
}

.btn-submit{
    margin-top:18px;
    border:none;
    background:linear-gradient(135deg,#2563eb,#60a5fa);
    color:white;
    padding:12px 18px;
    border-radius:14px;
    font-weight:700;
    cursor:pointer;
}

.ruangan-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(240px,1fr));
    gap:22px;
}

.ruangan-card{
    background:white;
    border-radius:24px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
    transition:0.3s;
}

.ruangan-card:hover{
    transform:translateY(-4px);
}

.ruangan-card img{
    width:100%;
    height:160px;
    object-fit:cover;
}

.ruangan-body{
    padding:18px;
}

.ruangan-body h3{
    font-size:18px;
    margin-bottom:10px;
}

.ruangan-kapasitas{
    display:inline-block;
    background:#eff6ff;
    color:#2563eb;
    padding:6px 11px;
    border-radius:999px;
    font-size:11px;
    font-weight:700;
    margin-bottom:12px;
}

.ruangan-fasilitas{
    font-size:12px;
    color:#64748b;
    line-height:1.7;
    min-height:60px;
}

.ruangan-action{
    display:flex;
    gap:8px;
    margin-top:14px;
}

.btn-edit{
    flex:1;
    background:#eff6ff;
    color:#2563eb;
    text-decoration:none;
    text-align:center;
    padding:8px;
    border-radius:11px;
    font-size:11px;
    font-weight:700;
}

.btn-hapus{
    flex:1;
    background:#fff1f2;
    color:#e11d48;
    text-decoration:none;
    text-align:center;
    padding:8px;
    border-radius:11px;
    font-size:11px;
    font-weight:700;
}

@media(max-width:900px){

    .sidebar{
        width:100%;
        position:relative;
        height:auto;
    }

    .main-content{
        margin-left:0;
        width:100%;
    }

    .dashboard-grid{
        grid-template-columns:1fr;
    }

    .form-grid{
        grid-template-columns:1fr;
    }

}

</style>

</head>

<body>

<div class="dashboard-layout">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="sidebar-logo">

            <img src="img/logo.png">

            <div>
                <h2>RuangKita</h2>
                <small>Admin Panel</small>
            </div>

        </div>

        <div class="sidebar-menu">

            <a href="admin_dashboard.php?page=dashboard"
               class="<?= ($page == 'dashboard') ? 'active' : ''; ?>">

                <i class="ri-dashboard-fill"></i>
                Dashboard

            </a>

            <a href="admin_dashboard.php?page=ruangan"
               class="<?= ($page == 'ruangan') ? 'active' : ''; ?>">

                <i class="ri-building-2-fill"></i>
                Data Ruangan

            </a>

            <a href="admin_dashboard.php?page=booking"
               class="<?= ($page == 'booking') ? 'active' : ''; ?>">

                <i class="ri-calendar-check-fill"></i>
                Booking Masuk

            </a>

            <a href="admin_dashboard.php?page=riwayat"
               class="<?= ($page == 'riwayat') ? 'active' : ''; ?>">

                <i class="ri-history-fill"></i>
                Riwayat Booking

            </a>

        </div>

        <div class="logout">

            <a href="logout.php">
                <i class="ri-logout-circle-r-line"></i>
                Logout
            </a>

        </div>

    </div>


    <!-- MAIN -->
    <div class="main-content">

        <div class="topbar">

            <h1>

                <?php

                if($page == "dashboard"){
                    echo "Dashboard";
                }

                else if($page == "booking"){
                    echo "Booking Masuk";
                }

                else if($page == "ruangan"){
                    echo "Data Ruangan";
                }

                else{
                    echo "Riwayat Booking";
                }

                ?>

            </h1>

        </div>

        <div class="content">

            <!-- DASHBOARD -->
            <?php if($page == "dashboard"){ ?>

            <div class="dashboard-grid">

                <div class="dashboard-card">

                    <h3>
                        <i class="ri-building-2-fill"></i>
                        Top 5 Ruangan
                    </h3>

                    <?php while($r = mysqli_fetch_assoc($top_ruangan)){ ?>

                    <div class="room-item">

                        <div class="room-image">
                            <i class="ri-building-4-fill"></i>
                        </div>

                        <div class="room-info">

                            <h4><?= $r['ruangan_nama']; ?></h4>

                            <p>Ruangan favorit pengguna</p>

                        </div>

                        <div class="booking-count">

                            <?= $r['jumlah']; ?>x

                        </div>

                    </div>

                    <?php } ?>

                </div>


                <div class="dashboard-card">

                    <h3>
                        <i class="ri-time-fill"></i>
                        Booking Pending
                    </h3>

                    <?php

                    $pending_dashboard = mysqli_query($conn,"
                        SELECT *
                        FROM bookings
                        WHERE status='pending'
                        ORDER BY created_at DESC
                        LIMIT 4
                    ");

                    while($p = mysqli_fetch_assoc($pending_dashboard)){

                    ?>

                    <div class="pending-modern">

                        <div class="pending-head">

                            <div class="pending-user">

                                <div class="pending-avatar">
                                    <i class="ri-user-3-fill"></i>
                                </div>

                                <div>

                                    <div class="pending-name">
                                        <?= $p['nama']; ?>
                                    </div>

                                    <div class="pending-room">
                                        <?= $p['ruangan_nama']; ?>
                                    </div>

                                </div>

                            </div>

                            <div class="pending-badge">
                                Pending
                            </div>

                        </div>

                        <div class="pending-date">

                            <div class="pending-date-box">

                                <small>Check In</small>

                                <p><?= $p['checkin']; ?></p>

                            </div>

                            <div class="pending-date-box">

                                <small>Check Out</small>

                                <p><?= $p['checkout']; ?></p>

                            </div>

                        </div>

                    </div>

                    <?php } ?>

                </div>

            </div>

            <?php } ?>


            <!-- DATA RUANGAN -->
            <?php if($page == "ruangan"){ ?>

            <div class="room-top-action">

                <h2>Data Ruangan</h2>

                <a href="?page=ruangan&tambah=1"
                   class="btn-tambah">

                    + Tambah Ruangan

                </a>

            </div>


            <?php

            if(isset($_GET['edit'])){

                $idEdit = $_GET['edit'];

                $edit = mysqli_query($conn,"
                    SELECT *
                    FROM ruangan
                    WHERE id='$idEdit'
                ");

                $e = mysqli_fetch_assoc($edit);

            ?>

            <div class="form-ruangan">

                <form method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="id" value="<?= $e['id']; ?>">
                    <input type="hidden" name="gambar_lama" value="<?= $e['gambar']; ?>">

                    <div class="form-grid">

                        <div class="input-group">
                            <label>Nama Ruangan</label>
                            <input type="text" name="nama" value="<?= $e['nama']; ?>" required>
                        </div>

                        <div class="input-group">
                            <label>Kapasitas</label>
                            <input type="number" name="kapasitas" value="<?= $e['kapasitas']; ?>" required>
                        </div>

                    </div>

                    <div class="input-group" style="margin-top:16px;">
                        <label>Fasilitas</label>
                        <textarea name="fasilitas"><?= $e['fasilitas']; ?></textarea>
                    </div>

                    <div class="input-group" style="margin-top:16px;">
                        <label>Ganti Foto</label>
                        <input type="file" name="gambar">
                    </div>

                    <button type="submit"
                            name="update_ruangan"
                            class="btn-submit">

                        Update Ruangan

                    </button>

                </form>

            </div>

            <?php } ?>


            <?php if(isset($_GET['tambah'])){ ?>

            <div class="form-ruangan">

                <form method="POST" enctype="multipart/form-data">

                    <div class="form-grid">

                        <div class="input-group">
                            <label>Nama Ruangan</label>
                            <input type="text" name="nama" required>
                        </div>

                        <div class="input-group">
                            <label>Kapasitas</label>
                            <input type="number" name="kapasitas" required>
                        </div>

                    </div>

                    <div class="input-group" style="margin-top:16px;">
                        <label>Fasilitas</label>
                        <textarea name="fasilitas" required></textarea>
                    </div>

                    <div class="input-group" style="margin-top:16px;">
                        <label>Upload Foto</label>
                        <input type="file" name="gambar" required>
                    </div>

                    <button type="submit"
                            name="tambah_ruangan"
                            class="btn-submit">

                        Simpan Ruangan

                    </button>

                </form>

            </div>

            <?php } ?>


            <div class="ruangan-grid">

            <?php while($r = mysqli_fetch_assoc($data_ruangan)){ ?>

            <div class="ruangan-card">

                <img src="<?= $r['gambar']; ?>">

                <div class="ruangan-body">

                    <h3><?= $r['nama']; ?></h3>

                    <div class="ruangan-kapasitas">
                        <?= $r['kapasitas']; ?> Orang
                    </div>

                    <div class="ruangan-fasilitas">
                        <?= $r['fasilitas']; ?>
                    </div>

                    <div class="ruangan-action">

                        <a href="?page=ruangan&edit=<?= $r['id']; ?>"
                           class="btn-edit">

                            <i class="ri-edit-2-fill"></i>
                            Edit

                        </a>

                        <a href="?page=ruangan&hapus_ruangan=<?= $r['id']; ?>"
                           class="btn-hapus"
                           onclick="return confirm('Hapus ruangan ini?')">

                            <i class="ri-delete-bin-5-fill"></i>
                            Hapus

                        </a>

                    </div>

                </div>

            </div>

            <?php } ?>

            </div>

            <?php } ?>


            <!-- BOOKING -->
            <?php if($page == "booking"){ ?>

            <div class="table-box">

                <div class="table-header">
                    <h2>Booking Pending</h2>
                </div>

                <table>

                    <thead>

                    <tr>
                        <th>Nama</th>
                        <th>Ruangan</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                    </thead>

                    <tbody>

                    <?php while($row = mysqli_fetch_assoc($booking_pending)){ ?>

                    <tr>

                        <td><?= $row['nama']; ?></td>

                        <td><?= $row['ruangan_nama']; ?></td>

                        <td><?= $row['checkin']; ?></td>

                        <td><?= $row['checkout']; ?></td>

                        <td>

                            <span class="status pending">
                                Pending
                            </span>

                        </td>

                        <td>

                            <a href="?page=booking&aksi=setuju&id=<?= $row['id']; ?>"
                               class="btn-action btn-approve">

                                <i class="ri-check-fill"></i>
                                Approve

                            </a>

                            <a href="?page=booking&aksi=tolak&id=<?= $row['id']; ?>"
                               class="btn-action btn-reject">

                                <i class="ri-close-fill"></i>
                                Reject

                            </a>

                        </td>

                    </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

            <?php } ?>


            <!-- RIWAYAT -->
            <?php if($page == "riwayat"){ ?>

            <div class="table-box">

                <div class="table-header">
                    <h2>Riwayat Booking</h2>
                </div>

                <table>

                    <thead>

                    <tr>
                        <th>Nama</th>
                        <th>Ruangan</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                    </tr>

                    </thead>

                    <tbody>

                    <?php while($row = mysqli_fetch_assoc($riwayat)){ ?>

                    <tr>

                        <td><?= $row['nama']; ?></td>

                        <td><?= $row['ruangan_nama']; ?></td>

                        <td><?= $row['checkin']; ?></td>

                        <td><?= $row['checkout']; ?></td>

                        <td>

                            <span class="status <?= $row['status']; ?>">

                                <?= ucfirst($row['status']); ?>

                            </span>

                        </td>

                    </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>