<?php
// Koneksi database
$conn = new mysqli("localhost", "root", "", "resto_db");

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Ambil data pesanan
$sql = "SELECT pesanan.id, meja.nomor_meja, menu.nama_menu, pesanan.jumlah, pesanan.waktu, pesanan.status
FROM pesanan
JOIN meja ON pesanan.id_meja = meja.id
JOIN menu ON pesanan.id_menu = menu.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Pesanan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Kelola Pesanan</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nomor Meja</th>
            <th>Nama Menu</th>
            <th>Jumlah</th>
            <th>Waktu</th>
            <th>Status</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nomor_meja'] . "</td>";
                echo "<td>" . $row['nama_menu'] . "</td>";
                echo "<td>" . $row['jumlah'] . "</td>";
                echo "<td>" . $row['waktu'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Tidak ada pesanan.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
// Tutup koneksi database
$conn->close();
?>
