<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SIMO | Reset Password</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f4f8;
    }

    .container-custom {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #1e2b3a 0%, #2c3e50 100%);
      padding: 20px;
    }

    .card-custom {
      background: white;
      border-radius: 30px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      width: 100%;
      max-width: 520px;
      overflow: hidden;
      padding: 3rem 2.5rem;
      transition: all 0.3s;
    }

    .logo-area {
      text-align: center;
      margin-bottom: 2rem;
    }

    .logo-area img {
      max-width: 180px;
      height: auto;
      display: inline-block;
    }

    .welcome-title {
      font-size: 1.8rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 0.3rem;
      text-align: center;
      line-height: 1.2;
    }

    .welcome-sub {
      font-size: 0.95rem;
      color: #64748b;
      margin-bottom: 2rem;
      font-weight: 400;
      text-align: center;
      line-height: 1.5;
    }

    .form-group-custom {
      margin-bottom: 1.6rem;
    }

    .form-group-custom label {
      font-size: 0.95rem;
      font-weight: 500;
      color: #334155;
      margin-bottom: 0.5rem;
      display: block;
    }

    .input-group-custom {
      position: relative;
    }

    .input-group-custom i {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 1.1rem;
    }

    .form-control-custom {
      width: 100%;
      padding: 1rem 1rem 1rem 3rem;
      font-size: 1rem;
      border: 2px solid #e2e8f0;
      border-radius: 15px;
      transition: all 0.3s;
      background: #f8fafc;
    }

    .form-control-custom:focus {
      border-color: #3498db;
      background: white;
      box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
      outline: none;
    }

    .form-control-custom[readonly] {
      background: #edf2f7;
      color: #718096;
      cursor: not-allowed;
    }

    .form-control-custom::placeholder {
      color: #94a3b8;
      font-weight: 300;
    }

    .btn-custom {
      width: 100%;
      padding: 1rem;
      background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
      border: none;
      border-radius: 15px;
      color: white;
      font-weight: 600;
      font-size: 1.05rem;
      box-shadow: 0 10px 20px rgba(52, 152, 219, 0.3);
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .btn-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 15px 25px rgba(52, 152, 219, 0.4);
      color: white;
    }

    .alert-danger-custom {
      background: #f8d7da;
      color: #721c24;
      border: none;
      border-left: 4px solid #e53e3e;
      border-radius: 12px;
      padding: 0.9rem 1.2rem;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
    }

    .alert-danger-custom ul {
      margin: 0;
      padding-left: 18px;
    }

    .back-link {
      text-align: center;
      margin-top: 1.5rem;
    }

    .back-link a {
      color: #64748b;
      text-decoration: none;
      font-size: 0.9rem;
      transition: all 0.3s;
    }

    .back-link a:hover {
      color: #3498db;
    }

    .back-link i {
      margin-right: 5px;
    }

    @media (max-width: 480px) {
      .card-custom { padding: 2rem 1.5rem; }
      .welcome-title { font-size: 1.5rem; }
      .logo-area img { max-width: 140px; }
    }
  </style>
</head>
<body>
  <div class="container-custom">
    <div class="card-custom">
      <div class="logo-area">
        <img src="{{ asset('assets/img/logopt.png') }}" alt="SIMO Logo" />
      </div>

      <h1 class="welcome-title">Reset Password</h1>
      <p class="welcome-sub">Masukkan password baru untuk akun Anda</p>

      @if ($errors->any())
        <div class="alert-danger-custom">
          <i class="fas fa-exclamation-circle"></i>
          <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group-custom">
          <label for="email">Email</label>
          <div class="input-group-custom">
            <i class="far fa-envelope"></i>
            <input id="email"
                   type="email"
                   class="form-control-custom"
                   name="email"
                   value="{{ $email ?? old('email') }}"
                   required
                   readonly />
          </div>
        </div>

        <div class="form-group-custom">
          <label for="password">Password Baru</label>
          <div class="input-group-custom">
            <i class="fas fa-lock"></i>
            <input id="password"
                   type="password"
                   class="form-control-custom"
                   name="password"
                   placeholder="Minimal 8 karakter"
                   required
                   autocomplete="new-password"
                   autofocus />
          </div>
        </div>

        <div class="form-group-custom">
          <label for="password-confirm">Konfirmasi Password</label>
          <div class="input-group-custom">
            <i class="fas fa-lock"></i>
            <input id="password-confirm"
                   type="password"
                   class="form-control-custom"
                   name="password_confirmation"
                   placeholder="Ulangi password baru"
                   required
                   autocomplete="new-password" />
          </div>
        </div>

        <button type="submit" class="btn-custom">
          <i class="fas fa-save"></i> Simpan Password Baru
        </button>
      </form>

      <div class="back-link">
        <a href="{{ route('login') }}">
          <i class="fas fa-arrow-left"></i> Kembali ke halaman login
        </a>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>