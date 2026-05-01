<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "ruangkita");

$error = "";

if (isset($_POST["tombol_login"])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if ($query && mysqli_num_rows($query) === 1) {

        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['password'])) {

            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];

            header("Location: dashboard.php"); // ✅ tujuan setelah login
            exit;
        }
    }

    $error = "Email atau password salah!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    height: 100vh;
    margin: 0;
    font-family: 'Segoe UI', sans-serif;

    background: linear-gradient(135deg, #2c2f8f, #ff7a00);
    display: flex;
    align-items: center;
    justify-content: center;
}

.login-card {
    width: 100%;
    max-width: 400px;
    padding: 30px;
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.input-group-text {
    background: #f1f1f1;
    border: none;
}

.form-control {
    border: none;
    background: #f7f7f7;
}

.form-control:focus {
    box-shadow: none;
    background: #f0f0f0;
}

.btn-login {
    background: linear-gradient(to right, #2c2f8f, #ff7a00);
    border: none;
    color: white;
    font-weight: 600;
    padding: 10px;
    border-radius: 8px;
    transition: 0.3s;
}

.btn-login:hover {
    opacity: 0.9;
}
</style>
</head>

<body>

<div class="login-card">
    <h3 class="text-center mb-4">Login</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center">
            <?= $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

        <button type="submit" name="tombol_login" class="btn btn-login w-100">
            Login
        </button>

        <p style="text-align:center; margin-top:15px;">
            Belum punya akun? 
            <a href="registrasi.php">Klik di sini</a>
        </p>
    </form>
</div>

</body>
</html>