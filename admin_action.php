<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "ruangkita");
if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'get_stats':
        $total_ruangan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM ruangan"))['total'];
        $booking_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE DATE(checkin) = CURDATE()"))['total'];
        $pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status = 'pending'"))['total'];
        $disetujui = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status = 'disetujui'"))['total'];
        
        echo json_encode([
            'status' => 'success',
            'data' => [
                'total_ruangan' => $total_ruangan,
                'booking_hari_ini' => $booking_hari_ini,
                'pending' => $pending,
                'disetujui' => $disetujui
            ]
        ]);
        break;

    case 'get_bookings':
        $type = $_GET['type'] ?? 'all';
        $where = $type === 'pending' ? "WHERE status = 'pending'" : "";
        $query = "SELECT id, nama, ruangan_nama, checkin, checkout, status, keperluan_booking FROM bookings $where ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);
        $data = [];
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'update_status':
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? '';
        if (in_array($status, ['disetujui', 'ditolak']) && $id > 0) {
            $query = "UPDATE bookings SET status = '$status' WHERE id = $id";
            if(mysqli_query($conn, $query)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
        }
        break;

    case 'get_rooms':
        $query = "SELECT * FROM ruangan ORDER BY id ASC";
        $result = mysqli_query($conn, $query);
        $data = [];
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'add_room':
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $kapasitas = (int)$_POST['kapasitas'];
        $fasilitas = mysqli_real_escape_string($conn, $_POST['fasilitas']);
        $gambar = 'img/default.jpg'; // Dummy image for now

        $query = "INSERT INTO ruangan (nama, kapasitas, fasilitas, gambar) VALUES ('$nama', $kapasitas, '$fasilitas', '$gambar')";
        if(mysqli_query($conn, $query)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
        }
        break;

    case 'delete_room':
        $id = (int)$_POST['id'];
        if(mysqli_query($conn, "DELETE FROM ruangan WHERE id = $id")) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
        break;
}
