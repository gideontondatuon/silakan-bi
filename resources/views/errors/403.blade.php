<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Dibatasi | SILAKAN Bank Indonesia</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bi2.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: linear-gradient(135deg, #020c1b 0%, #001f3f 50%, #003b73 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: #ffffff;
        }
        .error-card {
            background: rgba(255, 255, 255, 0.98);
            color: #0f172a;
            max-width: 520px;
            width: 100%;
            border-radius: 24px;
            padding: 44px 36px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
        }
        .error-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fee2e2;
            color: #dc2626;
            font-size: 13px;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 9999px;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }
        .error-code {
            font-size: 72px;
            font-weight: 900;
            line-height: 1;
            color: #003b73;
            letter-spacing: -2px;
            margin-bottom: 12px;
        }
        .error-title {
            font-size: 20px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 12px;
        }
        .error-desc {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .btn-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: linear-gradient(135deg, #003b73 0%, #005baa 100%);
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 12px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(0, 91, 170, 0.35);
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 91, 170, 0.45);
        }
        .system-info {
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-badge">
            <i class="bi bi-shield-slash-fill"></i> AKSES DIBATASI
        </div>
        <div class="error-code">403</div>
        <h1 class="error-title">Hak Akses Tidak Memadai</h1>
        <p class="error-desc">
            Maaf, akun Anda tidak memiliki kewenangan atau hak akses untuk membuka halaman ini. Silakan hubungi Administrator jika Anda memerlukan akses khusus.
        </p>
        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn-home">
            <i class="bi bi-house-door-fill"></i> Kembali ke Dashboard
        </a>
        <div class="system-info">
            SILAKAN &bull; Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara
        </div>
    </div>
</body>
</html>
