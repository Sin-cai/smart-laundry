<?php

include '../../middleware/customer.php';
include '../../config/koneksi.php';

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT
        transactions.*,
        services.service_name
    FROM transactions
    JOIN services ON transactions.service_id = services.id
    WHERE transactions.user_id='$user_id'
    AND transactions.status='Picked Up'
    ORDER BY transactions.id DESC
");

$total_spent = 0;
$rows = [];
while ($data = mysqli_fetch_assoc($query)) {
    $total_spent += $data['total_price'];
    $rows[] = $data;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Laundry</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #F5F0EB;
            --card: #FFFFFF;
            --text: #1A1410;
            --muted: #8C7B6B;
            --border: #E8DDD4;
            --accent: #C17C3C;
            --accent-light: #FDF3E7;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            padding: 2rem 1rem;
            color: var(--text);
        }

        .wrapper { max-width: 760px; margin: 0 auto; }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            animation: slideDown .5s ease both;
        }

        .brand {
            font-family: 'DM Serif Display', serif;
            font-size: 1.5rem;
            color: var(--text);
        }
        .brand span { color: var(--accent); }

        .back-btn {
            font-size: .8rem;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            border: 1px solid var(--border);
            padding: .4rem .9rem;
            border-radius: 20px;
            transition: all .2s;
        }
        .back-btn:hover {
            background: #fff0e8;
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Page title */
        .page-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.8rem;
            color: var(--text);
            margin-bottom: .3rem;
            animation: fadeUp .5s .05s ease both;
        }
        .page-subtitle {
            font-size: .85rem;
            color: var(--muted);
            margin-bottom: 1.5rem;
            animation: fadeUp .5s .1s ease both;
        }

        /* Summary strip */
        .summary {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
            animation: fadeUp .5s .15s ease both;
        }

        .summary-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.2rem 1.4rem;
        }

        .summary-label {
            font-size: .72rem;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: .3rem;
        }

        .summary-value {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: var(--text);
        }

        .summary-value.accent { color: var(--accent); }

        /* Table card */
        .table-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            animation: fadeUp .5s .2s ease both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .85rem;
        }

        thead tr {
            background: var(--text);
        }

        thead th {
            padding: .9rem 1rem;
            font-weight: 500;
            font-size: .72rem;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: rgba(255,255,255,.7);
            text-align: left;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .15s;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--accent-light); }

        tbody td {
            padding: .9rem 1rem;
            color: var(--text);
            vertical-align: middle;
        }

        .no-cell {
            font-size: .75rem;
            color: var(--muted);
            font-weight: 600;
        }

        .service-name {
            font-weight: 600;
        }

        .weight-badge {
            background: #F0EBE4;
            color: var(--muted);
            padding: .2rem .6rem;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .price {
            font-weight: 600;
            color: var(--accent);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .25rem .7rem;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            background: #E8F5EE;
            color: #2D7A4F;
            white-space: nowrap;
        }

        .status-badge::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #2D7A4F;
        }

        .payment-badge {
            display: inline-block;
            padding: .2rem .6rem;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 500;
            background: #EEF2FF;
            color: #3B56B0;
        }

        .date-text {
            font-size: .78rem;
            color: var(--muted);
            white-space: nowrap;
        }

        /* Empty state */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-icon { font-size: 2.5rem; margin-bottom: .8rem; }

        .empty-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.2rem;
            color: var(--text);
            margin-bottom: .4rem;
        }

        .empty-desc { font-size: .85rem; color: var(--muted); }

        /* Animations */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 600px) {
            thead th:nth-child(7),
            tbody td:nth-child(7) { display: none; }
        }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <div class="header">
        <div class="brand">Laundry<span>.</span></div>
        <a href="index.php" class="back-btn">← Kembali</a>
    </div>

    <!-- Page title -->
    <div class="page-title">Riwayat Laundry</div>
    <div class="page-subtitle">
        Semua transaksi yang sudah selesai diambil
    </div>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-card">
            <div class="summary-label">Total Transaksi</div>
            <div class="summary-value"><?= count($rows) ?></div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-card">
        <?php if (count($rows) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Layanan</th>
                    <th>Berat</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $i => $data): ?>
                <tr>
                    <td class="no-cell"><?= $i + 1 ?></td>
                    <td class="service-name"><?= htmlspecialchars($data['service_name']) ?></td>
                    <td><span class="weight-badge"><?= $data['weight'] ?> KG</span></td>
                    <td class="price">Rp <?= number_format($data['total_price']) ?></td>
                    <td><span class="status-badge"><?= $data['status'] ?></span></td>
                    <td><span class="payment-badge"><?= $data['payment_status'] ?></span></td>
                    <td class="date-text"><?= date('d M Y', strtotime($data['transaction_date'])) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">🧺</div>
            <div class="empty-title">Belum ada riwayat</div>
            <div class="empty-desc">Transaksi yang sudah diambil akan muncul di sini</div>
        </div>
        <?php endif; ?>
    </div>

</div>
</body>
</html>