<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Smart Laundry</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #F5F0EB; --card: #FFFFFF; --text: #1A1410;
            --muted: #8C7B6B; --border: #E8DDD4; --accent: #C17C3C; --accent-light: #FDF3E7;
        }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg); min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
        }
        .container { width: 100%; max-width: 420px; animation: fadeUp .5s ease both; }

        .brand { font-family: 'DM Serif Display', serif; font-size: 2rem; color: var(--text); text-align: center; margin-bottom: .3rem; }
        .brand span { color: var(--accent); }
        .brand-sub { text-align: center; font-size: .85rem; color: var(--muted); margin-bottom: 2rem; }

        .card { background: var(--card); border: 1px solid var(--border); border-radius: 24px; padding: 2rem; }
        .card-title { font-family: 'DM Serif Display', serif; font-size: 1.4rem; color: var(--text); margin-bottom: 1.5rem; }

        .field { margin-bottom: 1.1rem; }
        .field label { display: block; font-size: .78rem; font-weight: 600; letter-spacing: .6px; text-transform: uppercase; color: var(--muted); margin-bottom: .45rem; }
        .field input {
            width: 100%; padding: .75rem 1rem;
            border: 1.5px solid var(--border); border-radius: 12px;
            font-family: 'DM Sans', sans-serif; font-size: .9rem; color: var(--text);
            background: var(--bg); transition: border-color .2s, box-shadow .2s; outline: none;
        }
        .field input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(193,124,60,.1); background: #fff; }
        .field input::placeholder { color: #C4B5A5; }

        /* Field hint */
        .field-hint { font-size: .73rem; color: var(--muted); margin-top: .35rem; }

        /* 2-col grid */
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        .btn-submit {
            width: 100%; padding: .85rem;
            background: var(--accent); color: #fff;
            border: none; border-radius: 12px;
            font-family: 'DM Sans', sans-serif; font-size: .95rem; font-weight: 600;
            cursor: pointer; transition: all .2s; margin-top: .4rem;
        }
        .btn-submit:hover { background: #a8682e; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(193,124,60,.3); }
        .btn-submit:active { transform: translateY(0); }

        .form-footer { text-align: center; margin-top: 1.3rem; font-size: .83rem; color: var(--muted); }
        .form-footer a { color: var(--accent); text-decoration: none; font-weight: 600; }
        .form-footer a:hover { text-decoration: underline; }

        /* Membership info strip */
        .membership-strip {
            background: var(--accent-light);
            border: 1px solid #F0D5B8;
            border-radius: 12px;
            padding: .8rem 1rem;
            margin-bottom: 1.4rem;
            font-size: .78rem;
            color: #8A5520;
            line-height: 1.5;
        }
        .membership-strip strong { color: var(--accent); }

        @keyframes fadeUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
    </style>
</head>
<body>
<div class="container">

    <div class="brand">Smart Laundry<span>.</span></div>
    <div class="brand-sub">Buat akun baru untuk mulai</div>

    <div class="card">
        <div class="card-title">Daftar Sekarang ✨</div>

        <!-- Membership info -->
        <div class="membership-strip">
            🎖️ Kumpulkan poin setiap transaksi!
            <strong>Silver</strong> mulai 100 poin &middot; <strong>Gold</strong> mulai 300 poin
        </div>

        <form action="process/register_process.php" method="POST">

            <div class="field">
                <label>Nama Lengkap</label>
                <input type="text" name="name" placeholder="Nama lengkap kamu" required autofocus>
            </div>

            <div class="field-row">
                <div class="field">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="username unik" required>
                </div>
                <div class="field">
                    <label>No. Telepon</label>
                    <input type="text" name="phone" placeholder="08xxxxxxxxxx" required>
                </div>
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required>
                <div class="field-hint">Gunakan kombinasi huruf dan angka</div>
            </div>

            <button type="submit" class="btn-submit">Buat Akun</button>
        </form>

        <div class="form-footer">
            Sudah punya akun? <a href="index.php">Masuk di sini</a>
        </div>
    </div>

</div>
</body>
</html>