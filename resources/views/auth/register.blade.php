<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pendaftaran Akun - GAOne</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        :root {
            --primary: #203A63;
            --primary-hover: #070A13;
            --primary-soft: rgba(32, 58, 99, 0.1);

            --accent: #D4B15A;
            --accent-soft: #E5C87A;
            --accent-glow: rgba(212, 177, 90, 0.25);

            --background: #f5f9ff;
            --surface: #FFFFFF;
            --surface-2: #f5f9ff;
            --surface-3: rgba(182, 191, 204, 0.15);

            --text: #070A13;
            --muted: #64748B;
            --text-2: #203A63;
            --text-3: #64748B;
            --text-4: #B6BFCC;

            --border: rgba(182, 191, 204, 0.4);
        }

        *{
            font-family:'Poppins',sans-serif;
            box-sizing:border-box;
        }

        body{
            margin:0;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;

            background:
                radial-gradient(
                    circle at top left,
                    var(--accent-glow),
                    transparent 35%
                ),
                radial-gradient(
                    circle at bottom right,
                    rgba(32,58,99,0.12),
                    transparent 40%
                ),
                linear-gradient(
                    135deg,
                    var(--surface-2) 0%,
                    #F8FAFC 100%
                );

            padding:32px 16px;
            color: var(--text);
        }

        .register-card{
            width:100%;
            max-width:700px;

            background:var(--surface);
            border:1px solid var(--border);
            border-radius:24px;

            padding:40px;

            box-shadow:
                0 1px 2px rgba(8,24,50,.05),
                0 10px 30px rgba(8,24,50,.06);
        }

        .logo{
            width:64px;
            height:64px;

            background:var(--primary-soft);
            color:var(--primary);

            display:flex;
            align-items:center;
            justify-content:center;

            margin:auto;

            border-radius:18px;

            font-size:24px;
            font-weight:700;
        }

        .title{
            text-align:center;
            margin-top:20px;
            color:var(--text);

            font-size:32px;
            font-weight:700;
        }

        .subtitle{
            text-align:center;
            color:var(--muted);

            margin-top:10px;
            margin-bottom:32px;

            line-height:1.6;
            font-size:14px;
        }

        .form-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:18px;
        }

        .form-group{
            margin-bottom:18px;
        }

        .full-width{
            grid-column:1 / -1;
        }

        .input{
            width:100%;
            padding:14px 18px;

            border:1px solid var(--border);
            border-radius:12px;

            outline:none;
            background:var(--surface);
            color:var(--text);

            font-size:14px;

            transition:.2s;
        }

        .input:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 4px rgba(32,58,99,.12);
        }

        .btn-primary{
            width:100%;

            border:none;
            background:var(--primary);

            color:var(--surface);

            padding:14px;

            border-radius:12px;

            font-size:14px;
            font-weight:600;

            cursor:pointer;

            transition:.2s;
        }

        .btn-primary:hover{
            background:var(--primary-hover);
        }

        .divider{
            margin:28px 0;
            text-align:center;
            position:relative;
        }

        .divider::before{
            content:'';
            position:absolute;
            left:0;
            top:50%;
            width:100%;
            height:1px;
            background:var(--border);
        }

        .divider span{
            background:var(--surface);
            padding:0 16px;
            position:relative;
            color:var(--muted);
            font-size:13px;
        }

        .login-btn{
            width:100%;
            display:block;

            text-align:center;

            background:var(--surface);

            border:1px solid var(--border);
            border-radius:12px;

            padding:14px;

            font-weight:600;
            color:var(--text);

            text-decoration:none;
        }

        .login-btn:hover{
            background:var(--surface-2);
            border-color:var(--primary);
            color:var(--primary);
        }

        .footer{
            text-align:center;
            margin-top:20px;

            color:var(--muted);
            font-size:14px;
        }

        .footer a{
            color:var(--primary);
            text-decoration:none;
            font-weight:600;
        }

        .alert{
            margin-bottom:20px;
        }

        @media(max-width:768px){

            .register-card{
                padding:30px 24px;
            }

            .form-grid{
                grid-template-columns:1fr;
            }

            .full-width{
                grid-column:auto;
            }
        }
    </style>
</head>
<body>

<div class="register-card">

    <div class="logo">
        GA
    </div>

    <div class="title">
        Buat Akun Baru
    </div>

    <div class="subtitle">
        Lengkapi data untuk membuat akun dan mendapatkan akses ke sistem GAOne
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0;padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <input
                    type="text"
                    name="name"
                    placeholder="Nama Lengkap"
                    class="input"
                    value="{{ old('name') }}"
                    required
                >
            </div>

            <div class="form-group">
                <input
                    type="email"
                    name="email"
                    placeholder="Alamat Email"
                    class="input"
                    value="{{ old('email') }}"
                    required
                >
            </div>

            <div class="form-group">
                <input
                    type="text"
                    name="phone"
                    placeholder="Nomor HP"
                    class="input"
                    value="{{ old('phone') }}"
                >
            </div>

            <div class="form-group">
                <input
                    type="text"
                    name="department"
                    placeholder="Departemen"
                    class="input"
                    value="{{ old('department') }}"
                    required
                >
            </div>

            <div class="form-group full-width">
                <input
                    type="text"
                    name="position"
                    placeholder="Posisi / Jabatan"
                    class="input"
                    value="{{ old('position') }}"
                    required
                >
            </div>

            <div class="form-group">
                <input
                    type="password"
                    name="password"
                    placeholder="Kata Sandi"
                    class="input"
                    required
                >
            </div>

            <div class="form-group">
                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Konfirmasi Kata Sandi"
                    class="input"
                    required
                >
            </div>

        </div>

        <button type="submit" class="btn-primary">
            Buat Akun
        </button>
    </form>

    <div class="divider">
        <span>ATAU</span>
    </div>

    <a href="{{ route('login') }}" class="login-btn">
        Masuk ke Akun
    </a>

    <div class="footer">
        Sudah memiliki akun?
        <a href="{{ route('login') }}">
            Masuk sekarang
        </a>
    </div>

</div>

</body>
</html>
