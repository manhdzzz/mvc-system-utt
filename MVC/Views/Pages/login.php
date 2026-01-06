<?php $err = $data["err"] ?? ""; ?>

<style>
  /* Modern Login Page Styles */
  .login-wrapper {
    min-height: 100vh;
    display: flex;
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0f172a 100%);
    position: relative;
    overflow: hidden;
  }

  /* Left side - Branding */
  .login-branding {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px;
    position: relative;
  }

  .login-branding::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 30% 50%, rgba(59, 130, 246, 0.15) 0%, transparent 60%);
  }

  .brand-logo {
    width: 180px;
    height: 180px;
    margin-bottom: 32px;
    position: relative;
    z-index: 1;
    filter: drop-shadow(0 10px 30px rgba(59, 130, 246, 0.3));
    animation: logoFloat 4s ease-in-out infinite;
  }

  .brand-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    /* Remove white background */
    mix-blend-mode: screen;
  }

  @keyframes logoFloat {

    0%,
    100% {
      transform: translateY(0);
    }

    50% {
      transform: translateY(-15px);
    }
  }

  .brand-title {
    font-size: 32px;
    font-weight: 800;
    color: #fff;
    text-align: center;
    margin-bottom: 12px;
    letter-spacing: -1px;
    position: relative;
    z-index: 1;
  }

  .brand-subtitle {
    font-size: 16px;
    color: rgba(255, 255, 255, 0.7);
    text-align: center;
    max-width: 320px;
    line-height: 1.6;
    position: relative;
    z-index: 1;
  }

  /* Decorative elements */
  .decor-circle {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(59, 130, 246, 0.2);
    animation: pulse-ring 3s ease-in-out infinite;
  }

  .decor-circle:nth-child(1) {
    width: 400px;
    height: 400px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  .decor-circle:nth-child(2) {
    width: 300px;
    height: 300px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation-delay: 0.5s;
  }

  .decor-circle:nth-child(3) {
    width: 200px;
    height: 200px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation-delay: 1s;
  }

  @keyframes pulse-ring {

    0%,
    100% {
      opacity: 0.3;
      transform: translate(-50%, -50%) scale(1);
    }

    50% {
      opacity: 0.6;
      transform: translate(-50%, -50%) scale(1.05);
    }
  }

  /* Right side - Login Form */
  .login-form-side {
    width: 480px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
    background: linear-gradient(180deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.98) 100%);
    backdrop-filter: blur(20px);
    border-left: 1px solid rgba(59, 130, 246, 0.2);
  }

  .login-card {
    width: 100%;
    max-width: 380px;
  }

  .login-header {
    text-align: center;
    margin-bottom: 36px;
  }

  .login-title {
    font-size: 28px;
    font-weight: 800;
    color: #fff;
    margin-bottom: 8px;
    letter-spacing: -0.5px;
  }

  .login-desc {
    color: rgba(255, 255, 255, 0.6);
    font-size: 14px;
  }

  .login-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .form-group {
    position: relative;
  }

  .form-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 8px;
    letter-spacing: 0.5px;
  }

  .form-input {
    width: 100%;
    height: 52px;
    padding: 0 20px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: #fff;
    font-size: 15px;
    transition: all 0.3s ease;
  }

  .form-input:focus {
    outline: none;
    border-color: #3b82f6;
    background: rgba(59, 130, 246, 0.1);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
  }

  .form-input::placeholder {
    color: rgba(255, 255, 255, 0.35);
  }

  .login-btn {
    width: 100%;
    height: 54px;
    margin-top: 12px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    border-radius: 12px;
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .login-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: 0.5s;
  }

  .login-btn:hover::before {
    left: 100%;
  }

  .login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
  }

  .login-btn:active {
    transform: translateY(0);
  }

  .login-footer {
    text-align: center;
    margin-top: 28px;
    padding-top: 24px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
  }

  .forgot-link {
    color: rgba(255, 255, 255, 0.6);
    font-size: 14px;
    text-decoration: none;
    transition: color 0.3s;
  }

  .forgot-link:hover {
    color: #3b82f6;
  }

  /* Copyright footer */
  .login-copyright {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    text-align: center;
    padding: 16px;
    color: rgba(255, 255, 255, 0.4);
    font-size: 13px;
    background: linear-gradient(transparent, rgba(15, 23, 42, 0.9));
  }

  /* Responsive */
  @media (max-width: 900px) {
    .login-wrapper {
      flex-direction: column;
    }

    .login-branding {
      padding: 40px 20px;
      min-height: 40vh;
    }

    .brand-logo {
      width: 120px;
      height: 120px;
      margin-bottom: 20px;
    }

    .brand-title {
      font-size: 24px;
    }

    .brand-subtitle {
      font-size: 14px;
    }

    .login-form-side {
      width: 100%;
      border-left: none;
      border-top: 1px solid rgba(59, 130, 246, 0.2);
    }

    .decor-circle {
      display: none;
    }
  }

  @media (max-width: 480px) {
    .login-form-side {
      padding: 30px 20px;
    }

    .login-header {
      margin-bottom: 24px;
    }

    .login-title {
      font-size: 22px;
    }
  }
</style>

<div class="login-wrapper">
  <!-- Left Branding Section -->
  <div class="login-branding">
    <div class="decor-circle"></div>
    <div class="decor-circle"></div>
    <div class="decor-circle"></div>

    <div class="brand-logo">
      <img src="https://utt.edu.vn/home/images/stories/logo-utt-border.png" alt="UTT Logo">
    </div>

    <h1 class="brand-title">TRƯỜNG ĐẠI HỌC CÔNG NGHỆ GTVT</h1>
    <p class="brand-subtitle">Hệ thống thi trắc nghiệm trực tuyến - University of Transport Technology</p>
  </div>

  <!-- Right Login Form Section -->
  <div class="login-form-side">
    <div class="login-card">
      <div class="login-header">
        <h2 class="login-title">Đăng nhập</h2>
        <p class="login-desc">Vui lòng nhập thông tin tài khoản của bạn</p>
      </div>

      <form method="post" action="<?= BASE_URL ?>/index.php?url=AuthController/doLogin" class="login-form">
        <div class="form-group">
          <label class="form-label">TÀI KHOẢN</label>
          <input type="text" name="username" class="form-input" placeholder="Nhập tài khoản hoặc mã học viên" required
            autocomplete="username">
        </div>

        <div class="form-group">
          <label class="form-label">MẬT KHẨU</label>
          <input type="password" name="password" class="form-input" placeholder="Nhập mật khẩu của bạn" required
            autocomplete="current-password">
        </div>

        <button type="submit" class="login-btn">Đăng nhập</button>
      </form>

      <div class="login-footer">
        <a href="#" class="forgot-link">Quên mật khẩu?</a>
      </div>
    </div>
  </div>
</div>