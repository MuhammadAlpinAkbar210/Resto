<?php
$conn = new mysqli("localhost", "root", "", "resto_db");

// Ambil semua meja
$result = $conn->query("SELECT * FROM meja");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Meja - Restoran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #ff9966, #ff5e62);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            animation: fadeIn 1s ease;
        }
        .table-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .table-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .table-card .btn {
            font-size: 18px;
            font-weight: 600;
            padding: 20px;
            border-radius: 15px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container text-center">
    <h2 class="mb-4">🍽️ Selamat Datang di Restoran Kami</h2>
    <p class="mb-4">Silakan pilih meja Anda untuk mulai memesan.</p>
    <div class="row g-3">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="col-6 col-md-4">
                <div class="table-card">
                    <a href="menu.php?meja=<?php echo $row['id']; ?>" class="btn btn-primary w-100">
                        <?php echo $row['nama_meja']; ?>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
