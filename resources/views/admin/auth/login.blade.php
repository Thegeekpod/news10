<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>एडमिन लॉगिन | भारत समाचार</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', 'Noto Sans Devanagari', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            border-radius: 16px;
            padding: 38px 32px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-icon {
            font-size: 42px;
            margin-bottom: 8px;
        }

        .brand-title {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
        }

        .brand-subtitle {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14.5px;
            outline: none;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #c1121f;
            box-shadow: 0 0 0 3px rgba(193, 18, 31, 0.12);
        }

        .btn-submit {
            width: 100%;
            background: #c1121f;
            color: #ffffff;
            border: none;
            padding: 13px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: #a50e1a;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .demo-credentials {
            margin-top: 24px;
            padding: 14px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            font-size: 12.5px;
            color: #475569;
            line-height: 1.5;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-header">
        <div class="brand-icon">🇮🇳</div>
        <h1 class="brand-title">भारत समाचार</h1>
        <p class="brand-subtitle">एडमिन कंट्रोल पैनल लॉगिन (Admin Login)</p>
    </div>

    @if(session('error'))
        <div class="alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form action="{{ route('admin.login') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label class="form-label" for="email">ईमेल पता (Email Address)</label>
            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', 'admin@bharatsamachar.com') }}" required autofocus />
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">पासवर्ड (Password)</label>
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" id="password" name="password" class="form-control" value="admin123" required />
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; font-size: 13.5px; color: #475569;">
            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                <input type="checkbox" name="remember" checked /> मुझे याद रखें
            </label>
        </div>

        <button type="submit" class="btn-submit">लॉगिन करें (Sign In)</button>
    </form>

    <div class="demo-credentials">
        <strong>🔑 डिफ़ॉल्ट एडमिन क्रेडेंशियल्स (Default Login):</strong><br>
        ईमेल: <code>admin@bharatsamachar.com</code><br>
        पासवर्ड: <code>admin123</code>
    </div>
</div>

</body>
</html>
