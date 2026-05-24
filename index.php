<?php
$error = isset($_GET['error']) ? $_GET['error'] : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Smart Laundry</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #F5F0EB; --card: #FFFFFF; --text: #1A1410;
            --muted: #8C7B6B; --border: #E8DDD4; --accent: #C17C3C; --accent-light: #FDF3E7;
        }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
        }
        .container {
            width: 100%; max-width: 400px;
            animation: fadeUp .5s ease both;
        }

        /* Brand */
        .brand {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem; color: var(--text);
            text-align: center; margin-bottom: .3rem;
        }
        .brand span { color: var(--accent); }
        .brand-sub {
            text-align: center; font-size: .85rem;
            color: var(--muted); margin-bottom: 2rem;
        }

        /* Card */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2rem;
        }

        .card-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.4rem; color: var(--text);
            margin-bottom: 1.5rem;
        }

        /* Error */
        .alert-error {
            background: #FEF0E7; border: 1px solid #F5C6A0;
            border-radius: 10px; padding: .7rem 1rem;
            font-size: .82rem; color: #A0370A;
            margin-bottom: 1.2rem;
            display: flex; align-items: center; gap: .5rem;
        }

        /* Form */
        .field { margin-bottom: 1.1rem; }
        .field label {
            display: block; font-size: .78rem; font-weight: 600;
            letter-spacing: .6px; text-transform: uppercase;
            color: var(--muted); margin-bottom: .45rem;
        }
        .field input {
            width: 100%; padding: .75rem 1rem;
            border: 1.5px solid var(--border);
            border-radius: 12px; font-family: 'DM Sans', sans-serif;
            font-size: .9rem; color: var(--text);
            background: var(--bg); transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .field input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(193,124,60,.1);
            background: #fff;
        }
        .field input::placeholder { color: #C4B5A5; }

        /* Button */
        .btn-submit {
            width: 100%; padding: .85rem;
            background: var(--text); color: #fff;
            border: none; border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
            font-size: .95rem; font-weight: 600;
            cursor: pointer; transition: all .2s;
            margin-top: .4rem;
        }
        .btn-submit:hover { background: var(--accent); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(193,124,60,.25); }
        .btn-submit:active { transform: translateY(0); }

        /* Footer link */
        .form-footer {
            text-align: center; margin-top: 1.3rem;
            font-size: .83rem; color: var(--muted);
        }
        .form-footer a { color: var(--accent); text-decoration: none; font-weight: 600; }
        .form-footer a:hover { text-decoration: underline; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
    </style>
</head>
<body>
<div class="container">

    <div class="brand">Smart Laundry<span>.</span></div>
    <div class="brand-sub">Masuk untuk melanjutkan</div>

    <div class="card">
        <div class="card-title">Selamat datang 👋</div>

        <?php if ($error == 1): ?>
        <div class="alert-error">⚠️ Username atau password salah. Silakan coba lagi.</div>
        <?php endif; ?>

        <form action="process/login_process.php" method="POST">
            <div class="field">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required autofocus>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-submit">Masuk</button>
        </form>

        <div class="form-footer">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>
</div>
</body>
</html>