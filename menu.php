<?php
// Informasi koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resto_db";

$conn = new mysqli($servername, $username, $password, $dbname);

$koneksi_error = "";
if ($conn->connect_error) {
    $koneksi_error = "Koneksi database gagal: " . $conn->connect_error;
}

$meja_error = "";
$nama_meja = "";
$meja_id = 0;

if (empty($koneksi_error)) {
    if (!isset($_GET['meja'])) {
        $meja_error = "Meja tidak ditemukan!";
    } else {
        $meja_id = (int)$_GET['meja'];
        $meja_result = $conn->query("SELECT nama_meja FROM meja WHERE id = $meja_id");
        if ($meja_result->num_rows == 0) {
            $meja_error = "Meja tidak valid!";
        } else {
            $meja_row = $meja_result->fetch_assoc();
            $nama_meja = $meja_row['nama_meja'];
        }
    }
}

$result = null;
if (empty($koneksi_error) && empty($meja_error)) {
    $result = $conn->query("SELECT * FROM menu");
}

$pesan_berhasil = isset($_GET['pesan']) && $_GET['pesan'] === 'berhasil';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Menu Restoran - <?php echo $nama_meja; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f2f7, #cdd8f1); /* Gradien Lembut */
            padding-bottom: 120px; /* Padding bawah untuk memberi ruang total-bar */
            min-height: 100vh;
            margin: 0;
            position: relative; /* Agar total-bar bisa absolute terhadap body */
        }
        .container {
            margin-top: 20px;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            animation: fadeIn 0.8s ease;
            background-color: #fff;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .card-title {
            font-weight: 600;
            color: #333;
        }
        .input-stepper {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
            width: 120px;
            margin-top: 10px;
        }
        .input-stepper button {
            background: #e9ecef;
            border: none;
            padding: 5px 15px;
            font-size: 1.2em;
            cursor: pointer;
            color: #555;
        }
        .input-stepper input {
            width: 50px;
            text-align: center;
            border: none;
            background: white;
            color: #333;
        }
        #total-bar {
            position: absolute; /* Ubah ke absolute */
            bottom: 0;
            left: 0;
            width: 100%;
            background: #fff;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 999;
        }
        #total-bar .total-text {
            font-weight: bold;
            font-size: 1.2em;
            color: #333;
        }
        #pesan-btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 20px;
            font-size: 18px;
            transition: all 0.3s;
            background-color: #007bff;
            color: white;
            border: none;
        }
        #pesan-btn:hover {
            background-color: #0056b3;
        }
        #pesan-btn:disabled {
            background-color: #6c757d !important;
            cursor: not-allowed;
            opacity: 0.6;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .btn-secondary {
            border-radius: 10px;
        }
    </style>
</head>
<body <?php if ($pesan_berhasil): ?>onload="showSuccessAlert()"<?php endif; ?>>

<div class="container mt-4">
    <a href="index.php" class="btn btn-secondary mb-3">← Kembali</a>
    <h2 class="text-center mb-4">🍽️ Menu Restoran - <?php echo $nama_meja; ?></h2>

    <?php if (!empty($koneksi_error)): ?>
        <div class="alert alert-danger"><?php echo $koneksi_error; ?></div>
    <?php elseif (!empty($meja_error)): ?>
        <div class="alert alert-danger"><?php echo $meja_error; ?></div>
    <?php else: ?>
        <form method="POST" action="pesan.php" id="form-pesan">
            <input type="hidden" name="meja" value="<?php echo $meja_id; ?>">
            <div class="row">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-4" data-item-id="<?php echo $row['id']; ?>" data-harga="<?php echo $row['harga']; ?>">
                        <div class="card h-100">
                            <?php if ($row['nama'] == 'Nasi Goreng'): ?>
                                <img src="nasgor.jpg" class="card-img-top" style="height:220px; object-fit:cover;" alt="Nasi Goreng">
                            <?php elseif ($row['nama'] == 'Mie Goreng'): ?>
                                <img src="miegoreng.jpg" class="card-img-top" style="height:220px; object-fit:cover;" alt="Mie Goreng">
                            <?php elseif ($row['nama'] == 'Ayam Bakar'): ?>
                                <img src="ayambakar.jpg" class="card-img-top" style="height:220px; object-fit:cover;" alt="Ayam Bakar">
                            <?php elseif ($row['nama'] == 'Sate Ayam'): ?>
                                <img src="sate_ayam.jpg" class="card-img-top" style="height:220px; object-fit:cover;" alt="Sate Ayam">
                            <?php elseif ($row['nama'] == 'Soto Ayam'): ?>
                                <img src="soto_ayam.jpg" class="card-img-top" style="height:220px; object-fit:cover;" alt="Soto Ayam">
                            <?php else: ?>
                                <img src="placeholder.jpg" class="card-img-top" style="height:220px; object-fit:cover;" alt="Gambar Default">
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo $row['nama']; ?></h5>
                                <p class="card-text"><?php echo $row['deskripsi']; ?></p>
                                <p class="card-text text-primary fw-bold mb-3">Rp<?php echo number_format($row['harga']); ?></p>
                                <div class="input-stepper mt-auto">
                                    <button type="button" class="minus">-</button>
                                    <input type="text" name="jumlah[<?php echo $row['id']; ?>]" id="jumlah_<?php echo $row['id']; ?>" value="0" readonly>
                                    <button type="button" class="plus">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </form>
    <?php endif; ?>
</div>

<div id="total-bar">
    <span class="total-text">Total: Rp <span id="total-harga-text">0</span></span>
    <button type="submit" class="btn btn-primary" id="pesan-btn" form="form-pesan" disabled>🛒 Pesan Sekarang</button>
</div>

<script>
    const totalHargaText = document.getElementById('total-harga-text');
    const tombolPesan = document.getElementById('pesan-btn');
    const formPesan = document.getElementById('form-pesan');
    let totalHarga = 0;
    const itemPilihan = {};

    document.querySelectorAll('.input-stepper').forEach(wrapper => {
        const minus = wrapper.querySelector('.minus');
        const plus = wrapper.querySelector('.plus');
        const input = wrapper.querySelector('input');
        const col = wrapper.closest('.col-12');
        const itemId = col.dataset.itemId;
        const hargaSatuan = parseFloat(col.dataset.harga);

        minus.addEventListener('click', () => {
            let jumlah = parseInt(input.value);
            if (jumlah > 0) {
                jumlah--;
                input.value = jumlah;
                updateItem(itemId, hargaSatuan, jumlah);
            }
        });
        plus.addEventListener('click', () => {
            let jumlah = parseInt(input.value);
            jumlah++;
            input.value = jumlah;
            updateItem(itemId, hargaSatuan, jumlah);
        });
    });

    function updateItem(itemId, harga, jumlah) {
        if (jumlah > 0) {
            itemPilihan[itemId] = { harga: harga, jumlah: jumlah };
        } else {
            delete itemPilihan[itemId];
        }
        hitungTotal();
    }

    function hitungTotal() {
        totalHarga = 0;
        for (const itemId in itemPilihan) {
            totalHarga += itemPilihan[itemId].harga * itemPilihan[itemId].jumlah;
        }
        totalHargaText.textContent = totalHarga.toLocaleString('id-ID');
        tombolPesan.disabled = Object.keys(itemPilihan).length === 0;
    }

    formPesan.addEventListener('submit', function(event) {
        event.preventDefault(); // Mencegah pengiriman form secara langsung

        if (Object.keys(itemPilihan).length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Anda belum memilih pesanan!',
            });
        } else {
            Swal.fire({
                title: 'Pesanan Sedang Dimasak!',
                html: `
                    <p style="margin: 10px 0;">Koki sedang memasak pesanan Anda, mohon ditunggu sebentar...</p>
                    <img src="https://media0.giphy.com/media/v1.Y2lkPTc5MGI3NjExYXR3NW12cTB4aXA1ZWU4d28wNDkyaHp3cnUyc280ODh1ZTJpZnF5aCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/Jfd5ws0ZaS4mie4zYN/giphy.gif" alt="Koki Memasak" style="width: 200px; height: auto; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.2);">
                    <p style="margin-top: 15px; font-size: 14px; color: #555;">Harap bersabar ya 😊</p>
                `,
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    setTimeout(() => {
                        formPesan.submit(); // Kirim form setelah animasi berjalan 8 detik
                    }, 8000);
                }
            });
        }
    });

    <?php if ($pesan_berhasil): ?>
    function showSuccessAlert() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Pesanan berhasil Dibuat!',
            timer: 6000,
            showConfirmButton: false
        });
    }
    <?php endif; ?>
</script>

</body>
</html>