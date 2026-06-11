<?php
// BASE_URL e sessão já estão disponíveis via Front Controller (public/index.php)
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prev Dentistas | Seu Sorriso, Nossa Prioridade</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- VARIÁVEIS E RESET --- */
        :root {
            --primary-color: #005b96; 
            --primary-light: #0370b5;
            --secondary-color: #00b894; 
            --accent-color: #fab1a0; 
            --text-dark: #2d3436;
            --text-light: #636e72;
            --white: #ffffff;
            --bg-light: #f4f7f6;
            --shadow: 0 10px 30px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        /* COMPORTAMENTO DE SCROLL SUAVE ADICIONADO AQUI */
        html {
            scroll-behavior: smooth; /* Faz a animação de rolagem suave */
            scroll-padding-top: 80px; /* Compensa a altura do header fixo para não cobrir o conteúdo */
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Open Sans', sans-serif;
            color: var(--text-dark);
            background-color: var(--white);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        a { text-decoration: none; color: inherit; transition: var(--transition); }
        ul { list-style: none; }
        img { max-width: 100%; display: block; }

        /* --- UTILITÁRIOS --- */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
        }
        .btn-primary:hover {
            background-color: var(--primary-light);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,91,150,0.3);
        }

        .btn-success {
            background-color: var(--secondary-color);
            color: var(--white);
        }
        .btn-success:hover {
            background-color: #019e7f;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,184,148,0.4);
        }

        .section-padding { padding: 80px 0; }
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: var(--secondary-color);
            margin: 10px auto 0;
            border-radius: 2px;
        }

        /* --- HEADER & NAV --- */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 0;
            transition: var(--transition);
        }

        .nav-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo a {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-links a {
            font-weight: 600;
            font-size: 0.95rem;
            position: relative;
        }

        .nav-links a:not(.login-btn)::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: var(--secondary-color);
            transition: var(--transition);
        }

        .nav-links a:not(.login-btn):hover::after { width: 100%; }

        /* Login Form no Header */
        .header-login-form {
            display: flex;
            gap: 10px;
            align-items: center;
            background: #f0f2f5;
            padding: 5px 15px;
            border-radius: 30px;
        }

        .header-login-form input {
            border: none;
            background: transparent;
            padding: 8px;
            font-size: 0.85rem;
            width: 120px;
            outline: none;
        }

        .header-login-form button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: var(--transition);
        }
        .header-login-form button:hover { background: var(--primary-light); }

        .mobile-toggle { display: none; cursor: pointer; font-size: 1.5rem; color: var(--primary-color); }

        /* --- HERO SLIDER --- */
        .hero-slider {
            position: relative;
            height: 100vh;
            min-height: 600px;
            overflow: hidden;
            background: #000;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background-size: cover;
            background-position: center;
        }

        .slide.active { opacity: 1; }

        .slide::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to right, rgba(0,91,150,0.8), rgba(0,0,0,0.3));
        }

        .hero-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 600px;
            color: var(--white);
            margin-left: 10%; 
        }

        .hero-content h1 {
            font-size: 3.5rem;
            line-height: 1.2;
            color: var(--white);
            margin-bottom: 20px;
            opacity: 0;
            transform: translateY(30px);
            animation: slideUp 0.8s forwards 0.5s;
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0;
            transform: translateY(30px);
            animation: slideUp 0.8s forwards 0.7s;
        }

        .hero-features {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 30px;
            opacity: 0;
            animation: slideIn 0.8s forwards 0.9s;
        }
        
        .hero-tag {
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(5px);
        }

        .hero-btn-group {
            opacity: 0;
            animation: fadeIn 1s forwards 1.2s;
        }

        @keyframes slideUp { to { opacity: 1; transform: translateY(0); } }
        @keyframes slideIn { to { opacity: 1; transform: translateX(0); } }
        @keyframes fadeIn { to { opacity: 1; } }

        /* --- CARDS (EQUIPE & SERVIÇOS) --- */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .team-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            text-align: center;
            padding-bottom: 20px;
            border: 1px solid #eee;
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,91,150,0.15);
        }

        .img-wrapper {
            height: 250px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .team-card:hover .img-wrapper img {
            transform: scale(1.1);
        }

        .team-card h3 { font-size: 1.3rem; margin-bottom: 5px; }
        .team-card p { color: var(--text-light); padding: 0 15px; font-size: 0.9rem; }
        .role-tag {
            background: var(--bg-light);
            color: var(--primary-color);
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 10px;
        }

        /* --- MAPA & CONTATO --- */
        .location-section {
            background-color: var(--bg-light);
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .contact-info-box {
            background: var(--white);
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow);
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-item i {
            color: var(--secondary-color);
            font-size: 1.2rem;
            margin-top: 5px;
        }

        .map-frame {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            height: 400px;
        }

        /* --- FOOTER --- */
        footer {
            background: var(--primary-color);
            color: var(--white);
            text-align: center;
            padding: 30px 0;
            font-size: 0.9rem;
        }

        /* --- BOTÃO WHATSAPP FLUTUANTE --- */
        .fab-whatsapp {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #25D366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            z-index: 2000;
            transition: var(--transition);
            animation: pulse 2s infinite;
        }

        .fab-whatsapp:hover {
            transform: scale(1.1);
            background-color: #1ebc57;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(37, 211, 102, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
        }

        /* --- RESPONSIVIDADE --- */
        @media (max-width: 992px) {
            .contact-grid { grid-template-columns: 1fr; }
            .hero-content h1 { font-size: 2.5rem; }
        }

        @media (max-width: 768px) {
            .mobile-toggle { display: block; }
            
            .nav-links {
                position: absolute;
                top: 70px;
                left: 0;
                width: 100%;
                background: var(--white);
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 5px 10px rgba(0,0,0,0.1);
                clip-path: polygon(0 0, 100% 0, 100% 0, 0 0); 
                transition: all 0.4s ease-in-out;
            }

            .nav-links.active {
                clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%); 
            }

            .header-login-form {
                flex-direction: column;
                width: 100%;
                margin-top: 15px;
            }
            .header-login-form input { width: 100%; border-bottom: 1px solid #ddd; }
            .header-login-form button { width: 100%; }

            .hero-content { margin-left: 20px; margin-right: 20px; }
            .section-title { font-size: 2rem; }
        }
    </style>
</head>
<body>

    <header>
        <div class="container nav-flex">
            <div class="logo">
                <a href="#"><i class="fas fa-tooth"></i> Prev Dentistas</a>
            </div>
            
            <div class="mobile-toggle" id="mobile-toggle">
                <i class="fas fa-bars"></i>
            </div>

            <nav class="nav-links" id="nav-links">
                <a href="#inicio">Início</a>
                <a href="#especialistas">Especialistas</a>
                <a href="#localizacao">Localização</a>
                
                <form action="<?= BASE_URL ?>?rota=login" method="POST" class="header-login-form">
                    <input type="text" name="login" placeholder="Usuário" required>
                    <input type="password" name="senha" placeholder="Senha" required>
                    <button type="submit"><i class="fas fa-sign-in-alt"></i> Entrar</button>
                </form>
            </nav>
        </div>
    </header>

    <?php if(isset($_GET['erro'])): ?>
        <div style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 1100; background: #e74c3c; color: white; padding: 10px 20px; border-radius: 5px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
            <i class="fas fa-exclamation-circle"></i> Dados de acesso inválidos.
        </div>
    <?php endif; ?>

    <section id="inicio" class="hero-slider">
        <div class="slide active" style="background-image: url('<?= BASE_URL ?>assets/img/dentista-5.jpeg');"></div>
        <div class="slide" style="background-image: url('<?= BASE_URL ?>assets/img/cadeira.jpeg'); filter: hue-rotate(20deg);"></div> 
        <div class="slide" style="background-image: url('<?= BASE_URL ?>assets/img/card1.jpg'); background-position: center;"></div>

        <div class="container hero-content">
            <div class="hero-text-wrap">
                <h1>Transforme seu sorriso<br>com especialistas!</h1>
                <p>Na <strong>Prev Dentistas</strong>, unimos tecnologia de ponta e atendimento humanizado para devolver sua confiança.</p>
                
                <div class="hero-features">
                    <span class="hero-tag"><i class="fas fa-check-circle"></i> Estrutura Moderna</span>
                    <span class="hero-tag"><i class="fas fa-wifi"></i> Espaço VIP</span>
                    <span class="hero-tag"><i class="far fa-credit-card"></i> Até 10x sem juros</span>
                </div>

                <div class="hero-btn-group">
                    <a href="https://wa.me/5591983067459" target="_blank" class="btn btn-success btn-lg">
                        <i class="fab fa-whatsapp"></i> Agendar Consulta
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="especialistas" class="section-padding">
        <div class="container">
            <h2 class="section-title">Corpo Clínico</h2>
            <p style="text-align: center; color: var(--text-light); max-width: 600px; margin: 0 auto;">
                Nossa equipe é formada por especialistas dedicados a proporcionar o melhor tratamento para você e sua família.
            </p>
            
            <div class="team-grid">
                <div class="team-card">
                    <div class="img-wrapper">
                        <img src="<?= BASE_URL ?>assets/img/dentista-8.jpg" alt="Dra. Luciana Farias">
                    </div>
                    <span class="role-tag">Ortodontia</span>
                    <h3>Dra. Luciana Farias</h3>
                    <p>Especialista em criar sorrisos alinhados e saúde bucal integral. Cuidado e precisão em cada detalhe.</p>
                </div>

                <div class="team-card">
                    <div class="img-wrapper">
                        <img src="<?= BASE_URL ?>assets/img/dentista-2.jpeg" alt="Dra. Vitória Lobato">
                    </div>
                    <span class="role-tag">Saúde Coletiva</span>
                    <h3>Dra. Vitória Lobato</h3>
                    <p>Experiência e humanização no tratamento de pacientes de todas as idades.</p>
                </div>

                <div class="team-card">
                    <div class="img-wrapper">
                        <img src="<?= BASE_URL ?>assets/img/dentista-7.jpeg" class="img-centered" alt="Estética Dental">
                    </div>
                    <span class="role-tag">Especialização em endodontia</span>
                    <h3>Dra. Ana Lopes</h3>
                    <p>Excelência no tratamento de canal e recuperação da saúde dental, preservando a vitalidade e função dos seus dentes.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="localizacao" class="section-padding location-section">
        <div class="container">
            <h2 class="section-title">Onde Estamos</h2>
            
            <div class="contact-grid">
                <div class="contact-info-box">
                    <h3>Visite a Clínica</h3>
                    <p style="margin-bottom: 20px; color: var(--text-light);">Localização privilegiada em Ananindeua com estacionamento fácil.</p>
                    
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Endereço:</strong><br>
                            Rua União 1, Esquina com Rua D<br>
                            Atalaia, Ananindeua - PA, 67013-350
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <strong>Telefone / WhatsApp:</strong><br>
                            (91) 98306-7459
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="far fa-clock"></i>
                        <div>
                            <strong>Horário de Atendimento:</strong><br>
                            Seg - Sex: 08h às 12h e 15h às 18h<br>
                            Sábado: 08h às 12h
                        </div>
                    </div>

                    <a href="https://www.google.com/maps/dir/?api=1&destination=Prev+Dentistas+Ananindeua" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: 100%; text-align: center;">
                        <i class="fas fa-directions"></i> Ver no Google Maps
                    </a>
                </div>

                <div class="map-frame">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.645365860427!2d-48.42899942535724!3d-1.3893519985975165!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x92a48b002f32a7cf%3A0x353ede76a35e88fa!2sPrev%20Dentistas!5e0!3m2!1sen!2sbr!4v1771775822488!5m2!1sen!2sbr" 
                        width="600" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>© <?= date('Y') ?> Prev Dentistas. Feito com <i class="fas fa-heart" style="color: #fab1a0;"></i> para o seu sorriso.</p>
            <div style="margin-top: 10px; font-size: 0.8rem; opacity: 0.7;">
                Responsável Técnico: Dra. Luciana Farias
            </div>
        </div>
    </footer>

    <a href="https://wa.me/5591983067459" class="fab-whatsapp" target="_blank" title="Fale conosco no WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script>
        const mobileToggle = document.getElementById('mobile-toggle');
        const navLinks = document.getElementById('nav-links');

        mobileToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            const icon = mobileToggle.querySelector('i');
            if(navLinks.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                if(!link.closest('form')) { 
                    navLinks.classList.remove('active');
                    mobileToggle.querySelector('i').classList.remove('fa-times');
                    mobileToggle.querySelector('i').classList.add('fa-bars');
                }
            });
        });

        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % totalSlides;
            slides[currentSlide].classList.add('active');
        }

        setInterval(nextSlide, 5000);

        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if(window.scrollY > 50) {
                header.style.boxShadow = "0 5px 20px rgba(0,0,0,0.1)";
                header.style.padding = "10px 0";
            } else {
                header.style.boxShadow = "0 2px 10px rgba(0,0,0,0.05)";
                header.style.padding = "15px 0";
            }
        });
    </script>
</body>
</html>