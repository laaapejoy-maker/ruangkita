<?php
$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama      = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : "";
    $email     = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : "";
    $password  = isset($_POST['password']) ? $_POST['password'] : "";
    $jurusan   = isset($_POST['jurusan']) ? htmlspecialchars($_POST['jurusan']) : "";
    $angkatan  = isset($_POST['angkatan']) ? htmlspecialchars($_POST['angkatan']) : "";
    $kelas     = isset($_POST['kelas']) ? htmlspecialchars($_POST['kelas']) : "";

    if ($nama == "" || $email == "" || $password == "" || $jurusan == "" || $angkatan == "" || $kelas == "") {
        $message = "Semua field wajib diisi!";
        $messageType = "error";

    } elseif (strlen($password) < 8) {
        $message = "Password minimal 8 karakter!";
        $messageType = "error";

    } else {

        $file = "users.txt";

        if (!file_exists($file)) {
            file_put_contents($file, "");
        }

        $data = file_get_contents($file);

        if (strpos($data, $email) !== false) {
            $message = "Email sudah terdaftar!";
            $messageType = "error";

        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $newData = $nama . "|" . $email . "|" . $passwordHash . "|" . $jurusan . "|" . $angkatan . "|" . $kelas . PHP_EOL;

            file_put_contents($file, $newData, FILE_APPEND);

            $message = "Registrasi berhasil!";
            $messageType = "success";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #1e3c72, #ff7e00);
    padding: 20px;
}

.card {
    width: 420px;
    max-height: 90vh;
    overflow-y: auto;
    background: #f5f5f5;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
}

/* Scroll cantik */
.card::-webkit-scrollbar {
    width: 6px;
}
.card::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

label {
    font-size: 14px;
    color: #555;
}

input, select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 8px;
    border: 1px solid #ccc;
    outline: none;
}

input:focus, select:focus {
    border-color: #1e3c72;
}

button {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border: none;
    border-radius: 10px;
    background: linear-gradient(90deg, #1e3c72, #ff7e00);
    color: white;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    opacity: 0.9;
}

.message {
    text-align: center;
    margin-bottom: 15px;
    padding: 10px;
    border-radius: 8px;
    font-size: 14px;
}

.error {
    background: #ffe5e5;
    color: #cc0000;
}

.success {
    background: #e6ffea;
    color: #1a7f37;
}
</style>
</head>

<body>

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

    </form>

</div>

</body>
</html>