<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SIMO | Login Panel</title>
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
      font-size: 2rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 0.3rem;
      text-align: center;
      line-height: 1.2;
    }

    .welcome-sub {
      font-size: 1rem;
      color: #64748b;
      margin-bottom: 2.5rem;
      font-weight: 400;
      text-align: center;
    }

    .form-group-custom {
      margin-bottom: 1.8rem;
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

    .form-control-custom::placeholder {
      color: #94a3b8;
      font-weight: 300;
    }

    .checkbox-custom {
      display: flex;
      align-items: center;
      gap: 0.8rem;
      margin: 1.5rem 0 2rem;
    }

    .checkbox-custom input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: #3498db;
      border-radius: 4px;
    }

    .checkbox-custom label {
      color: #475569;
      font-size: 0.95rem;
      font-weight: 400;
      cursor: pointer;
      margin: 0;
    }

    .btn-custom {
      width: 100%;
      padding: 1rem;
      background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
      border: none;
      border-radius: 15px;
      color: white;
      font-weight: 600;
      font-size: 1.1rem;
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

    .register-custom {
      text-align: center;
      margin-top: 2rem;
    }

    .register-custom p {
      color: #64748b;
      font-size: 0.95rem;
    }

    .register-custom a {
      color: #3498db;
      font-weight: 600;
      text-decoration: none;
      margin-left: 0.5rem;
    }

    .register-custom a:hover {
      text-decoration: underline;
    }

    .forgot-link {
      text-align: center;
      margin-top: 1rem;
    }

    .forgot-link a {
      color: #e74c3c;
      font-weight: 500;
      font-size: 0.95rem;
      text-decoration: none;
      transition: all 0.3s;
    }

    .forgot-link a:hover {
      color: #c0392b;
      text-decoration: underline;
    }

    .forgot-link i {
      margin-right: 5px;
    }

    .alert-danger-custom {
      background: #f8d7da;
      color: #721c24;
      border: none;
      border-left: 4px solid #e53e3e;
      border-radius: 12px;
      padding: 0.8rem 1.2rem;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
    }

    @media (max-width: 480px) {
      .card-custom { padding: 2rem 1.5rem; }
      .welcome-title { font-size: 1.8rem; }
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

      <h1 class="welcome-title">Selamat Datang Kembali</h1>
      <p class="welcome-sub">Silakan login untuk mengakses sistem</p>

      @if ($errors->any())
        <div class="alert-danger-custom">
          <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-group-custom">
          <label for="email">Email</label>
          <div class="input-group-custom">
            <i class="far fa-envelope"></i>
            <input type="email" class="form-control-custom" name="email" id="email" placeholder="Masukkan email" value="{{ old('email') }}" required />
          </div>
        </div>

        <div class="form-group-custom">
          <label for="password">Password</label>
          <div class="input-group-custom">
            <i class="fas fa-lock"></i>
            <input type="password" class="form-control-custom" name="password" id="password" placeholder="Masukkan password" required />
          </div>
        </div>

        <div class="checkbox-custom">
          <input type="checkbox" id="show-password" />
          <label for="show-password">
            <i class="far fa-eye" style="margin-right: 5px;"></i> Tampilkan password
          </label>
        </div>

        <button type="submit" class="btn-custom">
          <i class="fas fa-sign-in-alt"></i> Masuk ke Sistem
        </button>
      </form>

      <div class="forgot-link">
        <a href="{{ route('password.request') }}">
          <i class="fas fa-key"></i> Lupa Password?
        </a>
      </div>

    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const showPasswordCheck = document.getElementById('show-password');
      const passwordField = document.getElementById('password');

      if (showPasswordCheck && passwordField) {
        showPasswordCheck.addEventListener('change', function() {
          passwordField.type = this.checked ? 'text' : 'password';
        });
      }
    });
  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>