<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugestões de filmes</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
</head>

<body>
    <header>

        <h1>Sugestões para sua Lista</h1>
        <div class="header-right">
            <button id="btnTema" class="btn-tema" onclick="alternarTema()">☀️</button>
            <a href="watchlist.php">
                <p>Watchlist</p>
            </a>
        </div>

    </header>

    <div id="hero">

    </div>

    <main>

        <h2>FILMES EM ALTA</h2>

        <div id="Filmes-em-alta">

        </div>

        <h2>SERIES EM ALTA</h2>

        <div id="Series-em-alta">
        </div>

    </main>

    <footer>
        <h1>REDES SOCIAIS</h1>
        <div class="social-group">
            <a href="https://www.linkedin.com/in/guilherme-chuisso-21b555203/" target="_blank">
                <div class="social-item">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/linkedin.svg" alt="LinkedIn">
                    <p>LinkedIn</p>
                </div>
            </a>
            <a href="https://github.com/Chuisso0" target="_blank">
                <div class="social-item">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/github.svg" alt="GitHub">
                    <p>GitHub</p>
                </div>
            </a>
            <a href="https://www.instagram.com/chuisso1502" target="_blank">
                <div class="social-item">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg"
                        alt="Instagram">
                    <p>Instagram</p>
                </div>
            </a>
        </div>
    </footer>

    <script>
    const btnTema = document.getElementById('btnTema');
    const body = document.body;

    // 1. Verifica se já existe um tema salvo
    const temaSalvo = localStorage.getItem('tema');
    if (temaSalvo === 'light') {
        body.classList.add('light-mode');
        btnTema.innerText = '🌙'; // Muda ícone para lua
    }

    // 2. Função de Trocar
    function alternarTema() {
        body.classList.toggle('light-mode');

        if (body.classList.contains('light-mode')) {
            localStorage.setItem('tema', 'light');
            btnTema.innerText = '🌙';
        } else {
            localStorage.setItem('tema', 'dark');
            btnTema.innerText = '☀️';
        }
    }
    </script>



    <script src="js/script.js"></script>

</body>

</html>