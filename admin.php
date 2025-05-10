<?php
$conn = new mysqli("localhost", "root", "", "resto_db");

// Inisialisasi variabel notifikasi dan total pendapatan
$notifikasi = null;
$total_pendapatan = 0;

// Proses perubahan status pesanan
if (isset($_POST['ubah_status']) && isset($_POST['id_pesanan']) && isset($_POST['status_baru'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $status_baru = $_POST['status_baru'];
    $update_query = $conn->query("UPDATE pesanan SET status = '$status_baru' WHERE id = $id_pesanan");
    if ($update_query) {
        $notifikasi = "<div class='alert alert-success'>Status pesanan berhasil diubah menjadi " . ucfirst($status_baru) . ".</div>";
    } else {
        $notifikasi = "<div class='alert alert-danger'>Gagal mengubah status pesanan. Silakan coba lagi.</div>";
    }
}

// Ambil semua pesanan beserta nama menu dan harga
$result = $conn->query("SELECT pesanan.*, menu.nama AS nama_menu, menu.harga AS harga_menu, meja.nama_meja
    FROM pesanan
    JOIN menu ON pesanan.id_menu = menu.id
    JOIN meja ON pesanan.id_meja = meja.id
    ORDER BY waktu DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin Restoran</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f8;
            color: #333;
            padding-top: 20px;
            padding-bottom: 60px;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            margin-bottom: 30px;
        }
        h2 {
            color: #375a7f;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 1200px; /* Lebar minimum tabel untuk menampung semua kolom */
        }
        .table thead th {
            background-color: #e9ecef;
            color: #495057;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-weight: 500;
            white-space: nowrap;
            font-size: 0.9em;
        }
        .table tbody td {
            padding: 8px;
            border: 1px solid #f0f0f0;
            white-space: nowrap;
            font-size: 0.85em;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .btn-success, .btn-warning, .btn-danger, .btn-info {
            border-radius: 5px;
            padding: 6px 10px;
            font-size: 0.8rem;
            transition: background-color 0.3s ease;
            margin-right: 5px;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            color: #fff;
        }
        .btn-success:hover {
            background-color: #1e7e34;
            border-color: #1c7430;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: #fff;
        }
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        .alert {
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 0.9em;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .text-center {
            text-align: center;
        }
        .no-print {
            display: none !important;
        }
        .total-pendapatan {
            margin-top: 15px;
            font-weight: bold;
            text-align: right;
            font-size: 1em;
        }

        /* Styling untuk tampilan cetak */
        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                font-size: 9px;
            }
            .container {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
            }
            h2 {
                text-align: left;
                font-size: 1.1em;
                margin-bottom: 8px;
            }
            .table {
                width: 100% !important;
                border-collapse: collapse;
                font-size: 0.7em;
            }
            .table thead th {
                border: 1px solid #000 !important;
                padding: 6px;
                font-weight: bold;
                white-space: nowrap;
            }
            .table tbody td {
                border: 1px solid #000 !important;
                padding: 4px;
                white-space: nowrap;
            }
            .aksi-column {
                display: none !important;
            }
            .no-print {
                display: none !important;
            }
            .total-pendapatan.no-print {
                display: none !important;
            }
            .total-pendapatan.print-only {
                font-size: 1em;
                font-weight: bold;
                margin-top: 8px;
                text-align: right;
                display: block !important;
            }
        }
        /* Responsiveness */
        @media (max-width: 768px) {
            .table {
                min-width: 768px;
            }
            .table thead {
                display: none;
            }
            .table tr {
                display: block;
                margin-bottom: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
            .table td {
                display: block;
                text-align: right;
                padding-left: 50%;
                position: relative;
                border-bottom: 1px dotted #ddd;
            }
            .table td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 10px;
                font-weight: bold;
                text-align: left;
                font-size: 0.9em;
            }
        }
        /* Lebih spesifik untuk lebar kolom */
        .table th:nth-child(5), /* Tanggal */
        .table td:nth-child(5),
        .table th:nth-child(6), /* Hari */
        .table td:nth-child(6),
        .table th:nth-child(7), /* Bulan */
        .table td:nth-child(7),
        .table th:nth-child(8), /* Waktu */
        .table td:nth-child(8) {
            text-align: center; /* Pusatkan teks pada kolom tanggal, hari, bulan, waktu */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Daftar Pesanan Restoran</h2>

        <?php if ($notifikasi): ?>
            <?php echo $notifikasi; ?>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Meja</th>
                        <th>Menu</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Bulan</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th class="aksi-column">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <?php
                                $timestamp = strtotime($row['waktu']);
                                $tanggal = date('d-m-Y', $timestamp);
                                $hari_indo = date('l', $timestamp);
                                $bulan_indo = date('F', $timestamp);
                                $jam = date('H:i:s', $timestamp);

                                $daftar_hari = array(
                                    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                                    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
                                    'Saturday' => 'Sabtu'
                                );
                                $daftar_bulan = array(
                                    'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                                    'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                                    'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                                    'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                                );

                                $hari_indo = isset($daftar_hari[$hari_indo]) ? $daftar_hari[$hari_indo] : $hari_indo;
                                $bulan_indo = isset($daftar_bulan[$bulan_indo]) ? $daftar_bulan[$bulan_indo] : $bulan_indo;

                                // Hitung total harga per item dan tambahkan ke total pendapatan
                                $total_harga_item = $row['harga_menu'] * $row['jumlah'];
                                $total_pendapatan += $total_harga_item;
                            ?>
                            <tr>
                                <td data-label="Meja"><?php echo $row['nama_meja']; ?></td>
                                <td data-label="Menu"><?php echo $row['nama_menu']; ?></td>
                                <td data-label="Harga">Rp <?php echo number_format($row['harga_menu']); ?></td>
                                <td data-label="Jumlah"><?php echo $row['jumlah']; ?></td>
                                <td data-label="Tanggal"><?php echo $tanggal; ?></td>
                                <td data-label="Hari"><?php echo $hari_indo; ?></td>
                                <td data-label="Bulan"><?php echo $bulan_indo; ?></td>
                                <td data-label="Waktu"><?php echo $jam; ?></td>
                                <td data-label="Status"><?php echo ucfirst($row['status']); ?></td>
                                <td class="aksi-column">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id_pesanan" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="status_baru" value="diproses">
                                        <button type="submit" name="ubah_status" class="btn btn-sm btn-warning">Proses</button>
                                    </form>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id_pesanan" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="status_baru" value="selesai">
                                        <button type="submit" name="ubah_status" class="btn btn-sm btn-success">Selesai</button>
                                    </form>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id_pesanan" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="status-baru" value="dibatalkan">
                                        <button type="submit" name="ubah_status" class="btn btn-sm btn-danger">Batal</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <tr>
                            <td colspan="9" class="text-right font-weight-bold">Total Pendapatan:</td>
                            <td class="font-weight-bold">Rp <?php echo number_format($total_pendapatan); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">Belum ada pesanan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="total-pendapatan no-print">
            Total Pendapatan: Rp <?php echo number_format($total_pendapatan); ?>
        </div>
        <div class="total-pendapatan print-only" style="display: none;">
            Total Pendapatan: Rp <?php echo number_format($total_pendapatan); ?>
        </div>
    </div>

    <div class="container mt-3">
        <button class="btn btn-info" onclick="window.print()">Cetak Daftar Pesanan</button>
    </div>

    <div class="no-print">
        <script>
            // Tidak ada logika JavaScript tambahan untuk saat ini
        </script>
    </div>
</body>
</html>