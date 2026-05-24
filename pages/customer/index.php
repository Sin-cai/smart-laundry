<?php

include '../../middleware/customer.php';
include '../../config/koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$queryUser = mysqli_query($conn, "
    SELECT *
    FROM users
    WHERE id = '$user_id'
");

$user = mysqli_fetch_assoc($queryUser);
$points = $user['points'];

$membership = 'Bronze';
$next_level = 'Silver';
$points_needed = 100 - $points;
$progress = min(($points / 100) * 100, 100);
$badge_color = '#CD7F32';
$badge_next = '#C0C0C0';

if ($points >= 100) {
    $membership = 'Silver';
    $next_level = 'Gold';
    $points_needed = 300 - $points;
    $progress = min((($points - 100) / 200) * 100, 100);
    $badge_color = '#C0C0C0';
    $badge_next = '#FFD700';
}

if ($points >= 300) {
    $membership = 'Gold';
    $next_level = null;
    $points_needed = 0;
    $progress = 100;
    $badge_color = '#FFD700';
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan</title>
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

        .wrapper {
            max-width: 480px;
            margin: 0 auto;
        }

        /* ---- Header ---- */
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
            letter-spacing: -.5px;
        }

        .brand span { color: var(--accent); }

        .logout-btn {
            font-size: .8rem;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            border: 1px solid var(--border);
            padding: .4rem .9rem;
            border-radius: 20px;
            transition: all .2s;
        }

        .logout-btn:hover {
            background: #fff0e8;
            border-color: var(--accent);
            color: var(--accent);
        }

        /* ---- Welcome card ---- */
        .welcome-card {
            background: var(--text);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.2rem;
            position: relative;
            overflow: hidden;
            animation: fadeUp .5s .1s ease both;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(193,124,60,.18);
        }

        .welcome-card::after {
            content: '';
            position: absolute;
            bottom: -30px; left: 60px;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: rgba(193,124,60,.1);
        }

        .welcome-label {
            font-size: .78rem;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: .5rem;
        }

        .welcome-name {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 1.4rem;
        }

        /* ---- Badge ---- */
        .badge-row {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .badge-icon {
            width: 42px; height: 42px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .badge-info { flex: 1; }

        .badge-level {
            font-size: .72rem;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255,255,255,.55);
            margin-bottom: .15rem;
        }

        .badge-name {
            font-family: 'DM Serif Display', serif;
            font-size: 1.3rem;
            color: #fff;
        }

        /* ---- Points card ---- */
        .points-card {
            background: var(--card);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.2rem;
            border: 1px solid var(--border);
            animation: fadeUp .5s .2s ease both;
        }

        .points-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.2rem;
        }

        .points-label {
            font-size: .78rem;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: .3rem;
        }

        .points-number {
            font-family: 'DM Serif Display', serif;
            font-size: 2.6rem;
            color: var(--text);
            line-height: 1;
        }

        .points-unit {
            font-family: 'DM Sans', sans-serif;
            font-size: .9rem;
            color: var(--muted);
            font-weight: 400;
            margin-left: .3rem;
        }

        .coin-icon {
            width: 46px; height: 46px;
            background: var(--accent-light);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }

        /* ---- Progress ---- */
        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: .78rem;
            color: var(--muted);
            margin-bottom: .5rem;
        }

        .progress-bar-bg {
            background: var(--border);
            border-radius: 99px;
            height: 8px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, var(--accent), #E8A048);
            transition: width 1s ease;
            width: <?= $progress ?>%;
        }

        .progress-note {
            margin-top: .6rem;
            font-size: .78rem;
            color: var(--muted);
            text-align: right;
        }

        .progress-note strong { color: var(--accent); }

        /* ---- Menu grid ---- */
        .menu-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            animation: fadeUp .5s .3s ease both;
        }

        .menu-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 1.4rem 1.2rem;
            text-decoration: none;
            color: var(--text);
            transition: all .2s;
            display: flex;
            flex-direction: column;
            gap: .6rem;
        }

        .menu-card:hover {
            transform: translateY(-3px);
            border-color: var(--accent);
            box-shadow: 0 8px 24px rgba(193,124,60,.12);
        }

        .menu-icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            background: var(--accent-light);
        }

        .menu-title {
            font-weight: 600;
            font-size: .9rem;
            color: var(--text);
        }

        .menu-desc {
            font-size: .75rem;
            color: var(--muted);
            line-height: 1.4;
        }

        /* ---- Animations ---- */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <div class="header">
        <div class="brand">Laundry<span>.</span></div>
        <a href="../../logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Welcome Card -->
    <div class="welcome-card">
        <div class="welcome-label">Selamat datang kembali</div>
        <div class="welcome-name"><?= htmlspecialchars($_SESSION['username']) ?></div>
        <div class="badge-row">
            <div class="badge-icon" style="background:<?= $badge_color ?>22; color:<?= $badge_color ?>;">
                <?php
                    if ($membership === 'Gold') echo '🥇';
                    elseif ($membership === 'Silver') echo '🥈';
                    else echo '🥉';
                ?>
            </div>
            <div class="badge-info">
                <div class="badge-level">Membership</div>
                <div class="badge-name"><?= $membership ?></div>
            </div>
        </div>
    </div>

    <!-- Points Card -->
    <div class="points-card">
        <div class="points-top">
            <div>
                <div class="points-label">Total Poin</div>
                <div class="points-number">
                    <?= number_format($points) ?><span class="points-unit">pts</span>
                </div>
            </div>
            <div class="coin-icon">🪙</div>
        </div>

        <?php if ($next_level): ?>
        <div class="progress-label">
            <span><?= $membership ?></span>
            <span><?= $next_level ?></span>
        </div>
        <div class="progress-bar-bg">
            <div class="progress-bar-fill"></div>
        </div>
        <div class="progress-note">
            Butuh <strong><?= $points_needed ?> poin</strong> lagi untuk <?= $next_level ?>
        </div>
        <?php else: ?>
        <div class="progress-bar-bg">
            <div class="progress-bar-fill"></div>
        </div>
        <div class="progress-note" style="color:var(--accent)">
            🎉 Kamu sudah di level tertinggi!
        </div>
        <?php endif; ?>
    </div>

    <!-- Menu Grid -->
    <div class="menu-grid">
        <a href="history.php" class="menu-card">
            <div class="menu-icon">📋</div>
            <div class="menu-title">Riwayat</div>
            <div class="menu-desc">Lihat semua transaksi laundry kamu</div>
        </a>
        <a href="my_laundry.php" class="menu-card">
            <div class="menu-icon">👕</div>
            <div class="menu-title">Laundry Saya</div>
            <div class="menu-desc">Cek status laundry yang sedang diproses</div>
        </a>
    </div>

</div>
</body>
</html>