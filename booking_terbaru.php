<?php
$koneksi = new mysqli("localhost", "root", "", "ruangkita");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$query = "SELECT * FROM bookings ORDER BY created_at DESC LIMIT 10";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Booking</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #2c2f8f, #ff7a00);
            min-height: 100vh;
        }

        .header {
            color: white;
            font-size: 22px;
            font-weight: 600;
            padding: 18px 40px;
            background: linear-gradient(135deg, #2c2f8f, #ff7a00);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);

            display: flex;
            align-items: center;
        }

        .logo {
            width: 45px;
            height: 45px;
            margin-right: 12px;
            border-radius: 10px;
        }

        .wrapper {
            display: flex;
            justify-content: center;
            padding: 40px 20px; 
        }

        .container {
            width: 100%;
            max-width: 1000px;
            background: #ffffff;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .container:active {
            transform: scale(0.98) translateY(2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #ffffff;
            color: #333;
            border-bottom: 2px solid #ddd;
            padding: 12px;
            font-weight: 600;
            font-size: 14px;
        }

        td {
            padding: 12px;
            text-align: center;
            font-size: 14px;
            color: #444;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 6px;
            background: #e9ecef;
            color: #333;
            font-size: 12px;
        }

        .back-wrapper {
            margin-top: 25px;
            text-align: right;
        }

        .btn-back {
            display: inline-block;
            padding: 10px 18px;
            background: linear-gradient(135deg, #2c2f8f, #ff7a00);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.2s;
        }

        .btn-back:hover {
            opacity: 0.85;
        }

    </style>
</head>

<body>

<div class="header">
    <img src="assets/img/logo.png" class="logo"> Data Booking Terbaru</div>

<div class="wrapper">
    <div class="container">

        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Prodi</th>
                <th>Email</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Dibuat</th>
            </tr>

            <?php
            $no = 1;
            while($row = $result->fetch_assoc()){
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['prodi'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><span class="badge"><?= $row['checkin'] ?></span></td>
                <td><span class="badge"><?= $row['checkout'] ?></span></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
            <?php } ?>
        </table>

        <div class="back-wrapper">
            <a href="dashboard.php" class="btn-back">← Kembali</a>
        </div>

    </div>
</div>

</body>
</html>