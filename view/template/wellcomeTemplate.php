<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CoelhoWork</title>
    <meta name="description" content="">
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="" />
    <link rel='stylesheet' type='text/css' href='/plugins/bootstrap/css/bootstrap.min.css' media='all'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: #181c24;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-cw {
            background: #232837;
            color: #fff;
            padding: 0.7rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            z-index: 100;
        }
        .navbar-cw .logo-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.2rem;
            color: #43b581;
            text-decoration: none;
        }
        .navbar-cw .logo-nav svg {
            width: 28px;
            height: 28px;
            fill: #43b581;
        }
        .navbar-cw .nav-links {
            display: flex;
            align-items: center;
            gap: 22px;
        }
        .navbar-cw .nav-links a {
            color: #e0e0e0;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.2s;
        }
        .navbar-cw .nav-links a:hover {
            color: #43b581;
        }
        .navbar-cw .search-box {
            margin-left: 24px;
            position: relative;
        }
        .navbar-cw .search-box input {
            background: #232837;
            border: 1px solid #353b4a;
            border-radius: 18px;
            padding: 6px 32px 6px 12px;
            color: #e0e0e0;
            font-size: 0.98rem;
            outline: none;
            transition: border 0.2s;
        }
        .navbar-cw .search-box input:focus {
            border: 1.5px solid #43b581;
        }
        .navbar-cw .search-box svg {
            position: absolute;
            right: 8px;
            top: 7px;
            width: 16px;
            height: 16px;
            fill: #888;
        }
        .hero-section {
            padding-top: 110px;
            padding-bottom: 40px;
            text-align: center;
            color: #fff;
            background: linear-gradient(120deg, #181c24 60%, #232837 100%);
        }
        .hero-logo {
            margin: 0 auto 18px auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero-logo svg {
            width: 60px;
            height: 60px;
            fill: #43b581;
        }
        .hero-title {
            font-size: 2.7rem;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .hero-slogan {
            font-size: 1.25rem;
            color: #b2c2d6;
            margin-bottom: 18px;
        }
        .badges {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .badge-cw {
            background: #232837;
            color: #43b581;
            border-radius: 14px;
            padding: 6px 18px;
            font-size: 0.98rem;
            font-weight: 600;
            border: 1px solid #43b58133;
            letter-spacing: 0.5px;
        }
        .hero-actions {
            margin-top: 18px;
            display: flex;
            justify-content: center;
            gap: 18px;
            flex-wrap: wrap;
        }
        .btn-docs, .btn-getstarted {
            border-radius: 24px;
            font-size: 1.08rem;
            font-weight: 700;
            padding: 12px 32px;
            transition: background 0.2s, color 0.2s;
            box-shadow: 0 2px 8px rgba(67,181,129,0.10);
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
        }
        .btn-docs {
            background: #232837;
            color: #43b581;
            border: 1.5px solid #43b581;
        }
        .btn-docs:hover {
            background: #43b581;
            color: #fff;
        }
        .btn-getstarted {
            background: #43b581;
            color: #fff;
            border: none;
            margin-left: 0;
        }
        .btn-getstarted:hover {
            background: #36996b;
            color: #fff;
        }
        .features-section {
            background: #1a1e27;
            padding: 48px 0 32px 0;
        }
        .features-title {
            color: #43b581;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 36px;
            letter-spacing: 1px;
        }
        .features-list {
            display: flex;
            justify-content: center;
            gap: 32px;
            flex-wrap: wrap;
        }
        .feature-item {
            background: #232837;
            border-radius: 14px;
            padding: 28px 24px 22px 24px;
            color: #e0e0e0;
            max-width: 260px;
            min-width: 200px;
            margin-bottom: 18px;
            box-shadow: 0 2px 8px rgba(67,181,129,0.04);
            text-align: left;
        }
        .feature-item h4 {
            color: #43b581;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .feature-item p {
            font-size: 0.98rem;
            color: #b2c2d6;
            margin-bottom: 0;
        }
        .footer-cw {
            background: #232837;
            color: #b2c2d6;
            text-align: center;
            padding: 18px 0 10px 0;
            font-size: 0.98rem;
            margin-top: auto;
        }
        @media (max-width: 900px) {
            .features-list {
                flex-direction: column;
                align-items: center;
                gap: 0;
            }
        }
        @media (max-width: 700px) {
            .navbar-cw {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                padding: 0.7rem 1rem;
            }
            .hero-title {
                font-size: 2rem;
            }
        }
        @media (max-width: 500px) {
            .hero-title {
                font-size: 1.3rem;
            }
            .feature-item {
                min-width: 0;
                max-width: 100vw;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar-cw">
        <a class="logo-nav" href="/">
            <svg viewBox="0 0 48 48">
                <circle cx="24" cy="30" r="12"/>
                <rect x="14" y="8" width="6" height="18" rx="3" transform="rotate(-18 17 17)"/>
                <rect x="28" y="8" width="6" height="18" rx="3" transform="rotate(18 31 17)"/>
            </svg>
            CoelhoWork
        </a>
        <div class="nav-links">
            <a href="/docs" target="_blank">Documentação</a>
            <a href="https://github.com/luizcoelhoc1/coelhowork" target="_blank">GitHub</a>
            <div class="search-box">
                <input type="text" placeholder="Buscar...">
                <svg viewBox="0 0 20 20">
                    <circle cx="9" cy="9" r="7" stroke="#888" stroke-width="2" fill="none"/>
                    <line x1="15" y1="15" x2="12.2" y2="12.2" stroke="#888" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
    </nav>
    <section class="hero-section">
        <div class="hero-logo">
            <svg viewBox="0 0 48 48">
                <circle cx="24" cy="30" r="12"/>
                <rect x="14" y="8" width="6" height="18" rx="3" transform="rotate(-18 17 17)"/>
                <rect x="28" y="8" width="6" height="18" rx="3" transform="rotate(18 31 17)"/>
            </svg>
        </div>
        <div class="hero-title">CoelhoWork</div>
        <div class="hero-slogan">Framework PHP modular, rápido e extensível.</div>
        <div class="badges">
            <span class="badge-cw">Open Source</span>
            <span class="badge-cw">Extensível</span>
            <span class="badge-cw">API REST</span>
        </div>
        <div class="hero-actions">
            <a href="/docs" class="btn btn-docs shadow" target="_blank">Documentação</a>
            <a href="https://github.com/luizcoelhoc1/coelhowork" class="btn btn-getstarted shadow" target="_blank">Get Started</a>
        </div>
    </section>
    <section class="features-section">
        <div class="features-list">
            <div class="feature-item">
                <h4>Arquitetura Modular</h4>
                <p>Crie módulos independentes e reutilizáveis para acelerar o desenvolvimento.</p>
            </div>
            <div class="feature-item">
                <h4>API RESTful</h4>
                <p>Construa APIs modernas facilmente, com autenticação e rotas flexíveis.</p>
            </div>
            <div class="feature-item">
                <h4>Extensibilidade</h4>
                <p>Adicione plugins e personalize o framework conforme a necessidade do seu projeto.</p>
            </div>
            <div class="feature-item">
                <h4>Fácil Integração</h4>
                <p>Integre com bancos de dados, serviços externos e outras ferramentas PHP.</p>
            </div>
        </div>
    </section>
    <footer class="footer-cw">
        CoelhoWork &copy; <?= date('Y') ?> &mdash; <a href="https://github.com/luizcoelhoc1/coelhowork" style="color:#43b581;text-decoration:none;" target="_blank">GitHub</a>
    </footer>
    <script type='text/javascript' src='/plugins/jquery/jquery-3.1.1.min.js'></script>
    <script type='text/javascript' src='/plugins/bootstrap/js/bootstrap.min.js'></script>
</body>
</html>