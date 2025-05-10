<?php
// Koneksi database
$conn = new mysqli("localhost", "root", "", "resto_db");

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meja_id = $_POST['meja'];
    $jumlah_pesanan = $_POST['jumlah'];

    foreach ($jumlah_pesanan as $id_menu => $jumlah) {
        if ($jumlah > 0) {
            $waktu_pesan = date("Y-m-d H:i:s");
            $status = 'pending'; // Tambahkan status default

            $sql = "INSERT INTO pesanan (id_meja, id_menu, jumlah, waktu, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiiss", $meja_id, $id_menu, $jumlah, $waktu_pesan, $status);
            $stmt->execute();
        }
    }

    // Tutup statement
    if ($stmt) {
        $stmt->close();
    }

    // Tutup koneksi database
    $conn->close();

    // Redirect kembali ke menu.php dengan parameter sukses
    header("Location: menu.php?meja=" . $meja_id . "&pesan=berhasil");
    exit();
} else {
    // Jika bukan metode POST, redirect kembali ke menu atau halaman lain
    header("Location: menu.php");
    exit();
}
?>
