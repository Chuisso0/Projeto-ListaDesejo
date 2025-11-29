<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar à Watchlist</title>
    <link rel="stylesheet" href="css/form_adicionar.css">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
</head>

<body>
    <header>
        <h1>Adicionar Novo Item</h1>

        <div class="header-right">
            <button id="btnTema" class="btn-tema" onclick="alternarTema()">☀️</button>
            <a href="watchlist.php">
                <p>Minha Lista</p>
            </a>
        </div>
    </header>

    <div class="main-content">
        <div id="busca_container">
            <label for="busca_titulo">Buscar Filme ou Série:</label>
            <div class="input-group">
                <input type="text" id="busca_titulo" placeholder="Ex: Interestelar, The Office...">
                <button type="button" id="btn_buscar">Buscar</button>
            </div>
        </div>

        <div id="resultados_busca"></div>

        <form action="acoes/acao_adicionar.php" method="POST" id="form_salvar" accept-charset="UTF-8">
            <h2>Confirmar Adição</h2>
            <p id="titulo_selecionado"><strong></strong></p>

            <input type="hidden" name="titulo" id="hidden_titulo">
            <input type="hidden" name="tmdb_id" id="hidden_tmdb_id">
            <input type="hidden" name="poster_path" id="hidden_poster_path">
            <input type="hidden" name="sinopse" id="hidden_sinopse">
            <input type="hidden" name="tipo" id="hidden_tipo">
            <input type="hidden" name="genero_ids" id="hidden_genero_ids">

            <div>
                <label for="prioridade">Prioridade:</label>
                <select id="prioridade" name="prioridade">
                    <option value="Alta">Alta</option>
                    <option value="Média" selected>Média</option>
                    <option value="Baixa">Baixa</option>
                </select>
            </div>

            <button type="submit" class="btn-salvar">Adicionar à Watchlist</button>
        </form>
    </div>


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
    // --------------- CONFIGURAÇÃO ---------------
    const API_KEY = 'a1cfa57fd73c352ae82aea487e44fab8';
    const API_URL = 'https://api.themoviedb.org/3';
    const IMG_URL = 'https://image.tmdb.org/t/p/w400';

    // --------------- ELEMENTOS ---------------
    const btnBuscar = document.getElementById('btn_buscar');
    const inputBusca = document.getElementById('busca_titulo');
    const divResultados = document.getElementById('resultados_busca');
    const formSalvar = document.getElementById('form_salvar');
    const pTituloSelecionado = document.getElementById('titulo_selecionado');

    // Variável de controle para o Auto-Click
    let deveSelecionarAutomatico = false;

    // --------------- EVENTOS ---------------
    btnBuscar.addEventListener('click', () => {
        deveSelecionarAutomatico = false; // Se o usuário clicou, não seleciona auto
        buscarItens();
    });

    inputBusca.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            deveSelecionarAutomatico = false;
            buscarItens();
        }
    });

    // --------------- FUNÇÕES ---------------
    async function buscarItens() {
        const query = inputBusca.value;
        if (!query) return;

        const urlBusca =
            `${API_URL}/search/multi?api_key=${API_KEY}&language=pt-BR&query=${encodeURIComponent(query)}`;

        try {
            const response = await fetch(urlBusca);
            const data = await response.json();
            mostrarResultados(data.results);
        } catch (error) {
            console.error("Erro:", error);
            divResultados.innerHTML = "<p style='color:white; text-align:center;'>Erro ao buscar.</p>";
        }
    }

    function mostrarResultados(resultados) {
        divResultados.innerHTML = '';
        formSalvar.style.display = 'none';

        const filmesESeries = resultados.filter(item =>
            (item.media_type === 'movie' || item.media_type === 'tv') && item.poster_path
        );

        if (filmesESeries.length === 0) {
            divResultados.innerHTML =
                "<p style='color:#ccc; width:100%; text-align:center;'>Nenhum resultado encontrado.</p>";
            return;
        }

        // --- LÓGICA DO AUTO-CLICK ---
        // Se viemos do Index (deveSelecionarAutomatico = true) e temos resultados:
        if (deveSelecionarAutomatico && filmesESeries.length > 0) {
            selecionarItem(filmesESeries[0]); // Seleciona o primeiro da lista
            deveSelecionarAutomatico = false; // Reseta
            return; // Para aqui e não desenha a lista de busca (foco no formulário)
        }

        filmesESeries.forEach(item => {
            const titulo = item.title || item.name;
            const ano = (item.release_date || item.first_air_date || '').split('-')[0] || 'N/A';
            const poster = `${IMG_URL}${item.poster_path}`;

            const itemDiv = document.createElement('div');
            itemDiv.className = 'resultado-item';
            itemDiv.innerHTML = `
                    <img src="${poster}" alt="${titulo}">
                    <div>
                        <strong>${titulo} (${ano})</strong>
                    </div>
                `;

            itemDiv.addEventListener('click', () => selecionarItem(item));
            divResultados.appendChild(itemDiv);
        });
    }

    function selecionarItem(item) {
        const titulo = item.title || item.name;

        // Mostra visualmente qual foi selecionado
        pTituloSelecionado.innerHTML = `Filme/Série: <span style="color:orange">${titulo}</span>`;

        // Limpa a lista de busca para focar no formulário
        divResultados.innerHTML = '';

        // Preenche os inputs ocultos (Hidden)
        document.getElementById('hidden_titulo').value = titulo;
        document.getElementById('hidden_tmdb_id').value = item.id;
        document.getElementById('hidden_poster_path').value = item.poster_path || '';
        document.getElementById('hidden_sinopse').value = item.overview || '';
        document.getElementById('hidden_tipo').value = item.media_type;

        // Garante que genre_ids existe antes de dar join
        const generos = item.genre_ids ? item.genre_ids.join(',') : '';
        document.getElementById('hidden_genero_ids').value = generos;

        // Abre o "Modal" (Formulário)
        formSalvar.style.display = 'block';

        // Animação de rolagem até o formulário
        // setTimeout ajuda a garantir que o display:block já foi renderizado
        setTimeout(() => {
            formSalvar.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }, 100);
    }

    // --------------- MODO ESCURO / CLARO ---------------
    const btnTema = document.getElementById('btnTema');
    const body = document.body;

    const temaSalvo = localStorage.getItem('tema');
    if (temaSalvo === 'light') {
        body.classList.add('light-mode');
        btnTema.innerText = '🌙';
    }

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

    // --------------- AUTO-BUSCA AO CARREGAR ---------------
    window.addEventListener('DOMContentLoaded', () => {
        // Pega os parâmetros da URL (ex: ?busca=Batman)
        const params = new URLSearchParams(window.location.search);
        const termoBusca = params.get('busca');

        if (termoBusca) {
            // Preenche o campo
            inputBusca.value = termoBusca;

            // Ativa o modo automático
            deveSelecionarAutomatico = true;

            // Executa a busca
            buscarItens();
        }
    });
    </script>
</body>

</html>