<?php
ob_start();

$message = "";
$messageType = "";

$conn = mysqli_connect("localhost", "root", "", "ruangkita");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama      = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : "";
    $email     = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : "";
    $password  = isset($_POST['password']) ? $_POST['password'] : "";
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : "";
    $jurusan   = isset($_POST['jurusan']) ? htmlspecialchars($_POST['jurusan']) : "";
    $angkatan  = isset($_POST['angkatan']) ? htmlspecialchars($_POST['angkatan']) : "";
    $kelas     = isset($_POST['kelas']) ? htmlspecialchars($_POST['kelas']) : "";

    if ($nama == "" || $email == "" || $password == "" || $confirm_password == "" || $jurusan == "" || $angkatan == "" || $kelas == "") {
        $message = "Semua field wajib diisi!";
        $messageType = "error";

    } elseif (strlen($password) < 8) {
        $message = "Password minimal 8 karakter!";
        $messageType = "error";

    } elseif ($password !== $confirm_password) {
        $message = "Konfirmasi password tidak cocok!";
        $messageType = "error";

    } else {

        $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

        if (mysqli_num_rows($cek) > 0) {
            $message = "Email sudah terdaftar!";
            $messageType = "error";

        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users (nama, email, password, jurusan, angkatan, kelas)
                      VALUES ('$nama', '$email', '$passwordHash', '$jurusan', '$angkatan', '$kelas')";

            if (mysqli_query($conn, $query)) {
                header("Location: login.php"); 
                exit;
            } else {
                $message = "Terjadi kesalahan!";
                $messageType = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register</title>

<link rel="stylesheet" href="style.css">
</head>

<body class="register-page">

<div class="card">

    <h2>Form Registrasi</h2>

    <?php if ($message != ""): ?>
        <div class="message <?= $messageType ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="confirm_password" required>
        </div>

        <div class="form-group">
            <label>Jurusan</label>
            <input type="text" name="jurusan" required>
        </div>

        <div class="form-group">
            <label>Angkatan</label>
            <select name="angkatan" required>
                <option value="">-- Pilih Angkatan --</option>
                <option value="2026">2026</option>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
            </select>
        </div>

        <div class="form-group">
            <label>Kelas</label>
            <input type="text" name="kelas" required>
        </div>

        <button type="submit">Daftar Sekarang</button>
          <p style="text-align:center; margin-top:15px;">
            sudah punya akun? 
            <a href="login.php">Kembali Ke Login</a>
        </p>

    </form>

</div>

</body>
</html>