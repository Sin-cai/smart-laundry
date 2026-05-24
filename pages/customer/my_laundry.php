<?php

include '../../middleware/customer.php';
include '../../config/koneksi.php';

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT transactions.*, services.service_name
    FROM transactions
    JOIN services ON transactions.service_id = services.id
    WHERE transactions.user_id='$user_id'
    AND transactions.status != 'Picked Up'
    ORDER BY transactions.id DESC
");

$rows = [];
while ($data = mysqli_fetch_assoc($query)) { $rows[] = $data; }

$status_config = [
    'Pending'   => ['color' => '#8C7B6B', 'bg' => '#F0EBE4', 'emoji' => '⏳', 'step' => 1],
    'Washing'   => ['color' => '#1B6FB5', 'bg' => '#E8F1FB', 'emoji' => '🫧', 'step' => 2],
    'Drying'    => ['color' => '#0E8A87', 'bg' => '#E4F5F5', 'emoji' => '💨', 'step' => 3],
    'Ironing'   => ['color' => '#B07A10', 'bg' => '#FDF3E0', 'emoji' => '🌡️', 'step' => 4],
    'Ready'     => ['color' => '#2D7A4F', 'bg' => '#E8F5EE', 'emoji' => '✅', 'step' => 5],
    'Picked Up' => ['color' => '#1A1410', 'bg' => '#E8E4DF', 'emoji' => '📦', 'step' => 6],
];
$steps = ['Pending', 'Washing', 'Drying', 'Ironing', 'Ready'];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Laundry</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #F5F0EB; --card: #FFFFFF; --text: #1A1410;
            --muted: #8C7B6B; --border: #E8DDD4; --accent: #C17C3C; --accent-light: #FDF3E7;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); min-height: 100vh; padding: 2rem 1rem; color: var(--text); }
        .wrapper { max-width: 760px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; animation: slideDown .5s ease both; }
        .brand { font-family: 'DM Serif Display', serif; font-size: 1.5rem; color: var(--text); }
        .brand span { color: var(--accent); }
        .back-btn { font-size: .8rem; font-weight: 500; color: var(--muted); text-decoration: none; border: 1px solid var(--border); padding: .4rem .9rem; border-radius: 20px; transition: all .2s; }
        .back-btn:hover { background: #fff0e8; border-color: var(--accent); color: var(--accent); }
        .page-title { font-family: 'DM Serif Display', serif; font-size: 1.8rem; margin-bottom: .3rem; animation: fadeUp .5s .05s ease both; }
        .page-subtitle { font-size: .85rem; color: var(--muted); margin-bottom: 1.5rem; animation: fadeUp .5s .1s ease both; }

        .progress-steps { display: flex; align-items: center; background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 1rem 1.4rem; margin-bottom: 1.5rem; animation: fadeUp .5s .15s ease both; overflow-x: auto; }
        .step-item { display: flex; flex-direction: column; align-items: center; gap: .3rem; flex: 1; min-width: 58px; }
        .step-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .9rem; border: 2px solid var(--border); background: var(--bg); }
        .step-label { font-size: .68rem; font-weight: 500; color: var(--muted); text-align: center; white-space: nowrap; }
        .step-line { flex: 1; height: 2px; background: var(--border); margin-bottom: 1.2rem; min-width: 8px; }

        .cards-list { display: flex; flex-direction: column; gap: 1rem; animation: fadeUp .5s .2s ease both; }
        .laundry-card { background: var(--card); border: 1px solid var(--border); border-radius: 18px; padding: 1.3rem 1.5rem; transition: box-shadow .2s; }
        .laundry-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.06); }
        .card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
        .card-no { font-size: .72rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: var(--muted); margin-bottom: .25rem; }
        .card-service { font-family: 'DM Serif Display', serif; font-size: 1.1rem; color: var(--text); }
        .status-pill { display: inline-flex; align-items: center; gap: .35rem; padding: .3rem .8rem; border-radius: 20px; font-size: .78rem; font-weight: 600; white-space: nowrap; }
        .card-progress { margin-top: .8rem; margin-bottom: .2rem; }
        .mini-steps { display: flex; gap: 4px; }
        .mini-step { flex: 1; height: 4px; border-radius: 2px; background: var(--border); transition: background .3s; }
        .mini-step.active { background: var(--accent); }
        .card-details { display: grid; grid-template-columns: repeat(3, 1fr); gap: .6rem; padding-top: 1rem; border-top: 1px solid var(--border); }
        .detail-label { font-size: .68rem; font-weight: 500; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); margin-bottom: .2rem; }
        .detail-value { font-size: .85rem; font-weight: 600; color: var(--text); }
        .detail-value.accent { color: var(--accent); }

        .empty-state { background: var(--card); border: 1px solid var(--border); border-radius: 20px; padding: 3rem 1rem; text-align: center; animation: fadeUp .5s .2s ease both; }
        .empty-icon { font-size: 2.5rem; margin-bottom: .8rem; }
        .empty-title { font-family: 'DM Serif Display', serif; font-size: 1.2rem; margin-bottom: .4rem; }
        .empty-desc { font-size: .85rem; color: var(--muted); }

        @keyframes slideDown { from { opacity:0; transform:translateY(-16px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeUp    { from { opacity:0; transform:translateY(20px);  } to { opacity:1; transform:translateY(0); } }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <div class="brand">Laundry<span>.</span></div>
        <a href="index.php" class="back-btn">← Kembali</a>
    </div>

    <div class="page-title">Laundry Saya</div>
    <div class="page-subtitle">Pantau status laundry kamu yang sedang diproses</div>

    <!-- Progress legend -->
    <div class="progress-steps">
        <?php foreach ($steps as $i => $s):
            $cfg = $status_config[$s]; ?>
            <div class="step-item">
                <div class="step-dot"><?= $cfg['emoji'] ?></div>
                <div class="step-label"><?= $s ?></div>
            </div>
            <?php if ($i < count($steps) - 1): ?>
                <div class="step-line"></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <?php if (count($rows) > 0): ?>
    <div class="cards-list">
        <?php foreach ($rows as $i => $data):
            $st  = $data['status'];
            $cfg = $status_config[$st] ?? $status_config['Pending'];
            $pay_color = $data['payment_status'] === 'Paid' ? '#2D7A4F' : '#A0370A';
            $pay_bg    = $data['payment_status'] === 'Paid' ? '#E8F5EE' : '#FEF0E7';
            $est_date  = date('d M Y, H:i', strtotime($data['estimated_finish']));
            $trx_date  = date('d M Y', strtotime($data['transaction_date']));
        ?>
        <div class="laundry-card">
            <div class="card-top">
                <div>
                    <div class="card-no">Transaksi #<?= $i + 1 ?></div>
                    <div class="card-service"><?= htmlspecialchars($data['service_name']) ?></div>
                </div>
                <span class="status-pill" style="color:<?= $cfg['color'] ?>;background:<?= $cfg['bg'] ?>">
                    <?= $cfg['emoji'] ?> <?= $st ?>
                </span>
            </div>

            <div class="card-progress">
                <div class="mini-steps">
                    <?php for ($s = 1; $s <= 5; $s++): ?>
                        <div class="mini-step <?= $s <= $cfg['step'] ? 'active' : '' ?>"></div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="card-details">
                <div>
                    <div class="detail-label">Berat</div>
                    <div class="detail-value"><?= $data['weight'] ?> KG</div>
                </div>
                <div>
                    <div class="detail-label">Total</div>
                    <div class="detail-value accent">Rp <?= number_format($data['total_price']) ?></div>
                </div>
                <div>
                    <div class="detail-label">Pembayaran</div>
                    <span class="status-pill" style="color:<?= $pay_color ?>;background:<?= $pay_bg ?>;padding:.15rem .5rem;font-size:.72rem">
                        <?= $data['payment_status'] ?>
                    </span>
                </div>
                <div style="grid-column: span 2">
                    <div class="detail-label">Estimasi Selesai</div>
                    <div class="detail-value"><?= $est_date ?></div>
                </div>
                <div>
                    <div class="detail-label">Tanggal Masuk</div>
                    <div class="detail-value"><?= $trx_date ?></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">👕</div>
        <div class="empty-title">Tidak ada laundry aktif</div>
        <div class="empty-desc">Laundry kamu yang sedang diproses akan muncul di sini</div>
    </div>
    <?php endif; ?>

</div>
</body>
</html>