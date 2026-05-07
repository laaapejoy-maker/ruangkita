<?php
session_start();

if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

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
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
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

<link rel="stylesheet" href="style.css">
</head>

<body class="login-page">

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