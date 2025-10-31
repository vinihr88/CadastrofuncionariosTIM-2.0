<?php
session_start();

// Se já estiver logado, vai para o sistema
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: funcionarios.php");
    exit;
}

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Credenciais fixas
    if ($username === 'admin123' && $password === 'admin123') {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: funcionarios.php");
        exit;
    } else {
        $erro = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema TIM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #0066cc;
            --secondary-color: #003366;
            --accent-color: #00a8ff;
            --text-color: #333;
            --light-text: #777;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        /* Estilos para partículas */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            pointer-events: none;
        }
        
        /* Estilos para o logo */
        .logo-container {
            position: relative;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .logo-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.8) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { left: -100%; }
            20% { left: 100%; }
            100% { left: 100%; }
        }
        
        /* Estilos para ícones nos inputs */
        .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: var(--light-text);
            pointer-events: none;
            transition: var(--transition);
        }
        
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: var(--light-text);
            cursor: pointer;
            transition: var(--transition);
        }
        
        .toggle-password:hover {
            color: var(--primary-color);
        }
        
        /* Estilos para o botão com ícone */
        .btn-login {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        
        .btn-text {
            transition: transform 0.3s ease;
        }
        
        .btn-icon {
            position: absolute;
            right: 20px;
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.3s ease;
        }
        
        /* Estilos para mensagem de erro com ícone */
        .error-message {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .error-message svg {
            color: var(--error-color);
        }
        
        /* Estilos para inputs com foco */
        .form-group {
            position: relative;
        }
        
        .form-group input {
            padding-left: 45px;
            padding-right: 45px;
        }
        
        .form-group.focused label {
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        body {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            top: -25%;
            left: -25%;
            animation: pulse 15s infinite alternate;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.5; }
            100% { transform: scale(1.05); opacity: 0.8; }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 50px 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 450px;
            text-align: center;
            position: relative;
            z-index: 10;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: translateY(0);
            transition: transform 0.5s ease-in-out;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .logo {
            color: var(--primary-color);
            font-size: 38px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: 1px;
            position: relative;
            display: inline-block;
        }

        .logo::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 4px;
            background: var(--accent-color);
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .subtitle {
            color: var(--light-text);
            font-size: 16px;
            margin-bottom: 40px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 500;
            font-size: 14px;
            transition: var(--transition);
        }

        input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 16px;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.9);
            font-family: 'Poppins', sans-serif;
        }

        input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.15);
        }

        input::placeholder {
            color: #aaa;
            opacity: 1;
            transition: var(--transition);
        }

        input:focus::placeholder {
            opacity: 0.7;
            transform: translateX(5px);
        }

        .form-group:focus-within label {
            color: var(--primary-color);
        }

        button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(to right, var(--primary-color), var(--accent-color));
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 15px;
            position: relative;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 15px rgba(0, 102, 204, 0.3);
        }

        button:hover::before {
            left: 100%;
        }

        button:active {
            transform: translateY(1px);
        }

        .error-message {
            color: var(--error-color);
            text-align: center;
            margin-top: 20px;
            padding: 12px;
            background: rgba(231, 76, 60, 0.1);
            border-radius: 8px;
            display: <?= isset($erro) && $erro ? 'block' : 'none' ?>;
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }

        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-3px, 0, 0); }
            40%, 60% { transform: translate3d(3px, 0, 0); }
        }

        .footer {
            margin-top: 35px;
            color: var(--light-text);
            font-size: 13px;
            font-weight: 300;
        }

        /* Responsividade */
        @media (max-width: 500px) {
            .login-container {
                padding: 40px 25px;
                margin: 0 20px;
                max-width: 90%;
            }
            
            .logo {
                font-size: 32px;
            }
            
            .subtitle {
                margin-bottom: 30px;
                font-size: 14px;
            }
            
            input {
                padding: 12px 12px 12px 40px;
                font-size: 14px;
            }
            
            button {
                padding: 14px;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .input-icon {
                left: 12px;
            }
            
            .toggle-password {
                right: 12px;
            }
        }
        
        @media (max-width: 350px) {
            .login-container {
                padding: 30px 20px;
            }
            
            .logo {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <!-- Partículas de fundo -->
    <div class="particles" id="particles"></div>
    
    <!-- Cabeçalho com logo -->
    <div class="logo-container">
        <div class="logo">TIM</div>
        <div class="logo-shine"></div>
    </div>
    <div class="subtitle">Acesso ao Sistema</div>
    
    <!-- Formulário de login -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Usuário</label>
            <input type="text" id="username" name="username" placeholder="Digite seu usuário" required>
            <span class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </span>
        </div>
        
        <div class="form-group">
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
            <span class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </span>
            <span class="toggle-password">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-closed" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
            </span>
        </div>
        
        <button type="submit" class="btn-login">
            <span class="btn-text">Entrar</span>
            <span class="btn-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
            </span>
        </button>
    </form>
    
    <!-- Mensagem de erro -->
    <div class="error-message">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <span>Usuário ou senha incorretos!</span>
    </div>
    
    <!-- Rodapé -->
    <div class="footer">
        Sistema de Gestão de Funcionários
    </div>
</div>

<script>
// Animação de partículas
document.addEventListener('DOMContentLoaded', function() {
    const particles = document.getElementById('particles');
    
    for (let i = 0; i < 20; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        // Posição aleatória
        const posX = Math.random() * 100;
        const posY = Math.random() * 100;
        
        // Tamanho aleatório
        const size = Math.random() * 5 + 2;
        
        // Velocidade aleatória
        const speedX = (Math.random() - 0.5) * 1;
        const speedY = (Math.random() - 0.5) * 1;
        
        // Aplicar estilos
        particle.style.left = posX + '%';
        particle.style.top = posY + '%';
        particle.style.width = size + 'px';
        particle.style.height = size + 'px';
        
        // Animar
        particle.animate(
            [
                { transform: 'translate(0, 0)', opacity: Math.random() * 0.5 + 0.3 },
                { transform: `translate(${speedX * 100}px, ${speedY * 100}px)`, opacity: 0 }
            ],
            {
                duration: Math.random() * 5000 + 3000,
                iterations: Infinity,
                direction: 'alternate',
                easing: 'ease-in-out'
            }
        );
        
        particles.appendChild(particle);
    }
    
    // Toggle de visibilidade da senha
    const togglePassword = document.querySelector('.toggle-password');
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.querySelector('.eye-open');
    const eyeClosed = document.querySelector('.eye-closed');
    
    togglePassword.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOpen.style.display = 'none';
            eyeClosed.style.display = 'block';
        } else {
            passwordInput.type = 'password';
            eyeOpen.style.display = 'block';
            eyeClosed.style.display = 'none';
        }
    });
    
    // Efeito nos inputs
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.parentElement.classList.remove('focused');
            }
        });
    });
    
    // Efeito no botão
    const loginButton = document.querySelector('.btn-login');
    loginButton.addEventListener('mouseenter', function() {
        this.querySelector('.btn-text').style.transform = 'translateX(-10px)';
        this.querySelector('.btn-icon').style.opacity = '1';
        this.querySelector('.btn-icon').style.transform = 'translateX(0)';
    });
    
    loginButton.addEventListener('mouseleave', function() {
        this.querySelector('.btn-text').style.transform = 'translateX(0)';
        this.querySelector('.btn-icon').style.opacity = '0';
        this.querySelector('.btn-icon').style.transform = 'translateX(-10px)';
    });
});
</script>
</body>
</html>