<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Adicionar à Watchlist</title>
    <link rel="stylesheet" href="css/form_adicionar.css">
</head>

<body>
    <h1>Adicionar Novo Item (via TMDB)</h1>

    <div id="busca_container">
        <label for="busca_titulo">Buscar Filme ou Série:</label>
        <input type="text" id="busca_titulo" placeholder="Ex: Interestelar, The Office...">
        <button type="button" id="btn_buscar">Buscar</button>
    </div>

    <div id="resultados_busca">
    </div>

    <!-- 
        ALTERAÇÃO: Adicionei accept-charset="UTF-8" 
        Isso ajuda a garantir que o acento de 'Média' seja enviado corretamente 
    -->
    <form action="acoes/acao_adicionar.php" method="POST" id="form_salvar" accept-charset="UTF-8">

        <h2>Item Selecionado</h2>
        <p id="titulo_selecionado"><strong></strong></p>

        <input type="hidden" name="titulo" id="hidden_titulo">
        <input type="hidden" name="tmdb_id" id="hidden_tmdb_id">
        <input type="hidden" name="poster_path" id="hidden_poster_path">
        <input type="hidden" name="sinopse" id="hidden_sinopse">
        <input type="hidden" name="tipo" id="hidden_tipo"> <input type="hidden" name="genero_ids"
            id="hidden_genero_ids">
        <div>
            <label for="prioridade">Minha Prioridade:</label>
            <select id="prioridade" name="prioridade">
                <option value="Alta">Alta</option>
                <!-- 
                    CORREÇÃO: Voltei para "Média" (com acento), pois o seu banco 
                    provavelmente é um ENUM que exige a palavra exata.
                    O accept-charset acima deve resolver o problema do envio.
                -->
                <option value="Média" selected>Média</option>
                <option value="Baixa">Baixa</option>
            </select>
        </div>

        <button type="submit">Adicionar à Minha Watchlist</button>
    </form>

    <br>
    <a href="watchlist.php">Voltar para minha lista</a>

    <script>
        // --------------- CONFIGURAÇÃO ---------------
        // !! COLOQUE SUA CHAVE AQUI !!
        const API_KEY = 'a1cfa57fd73c352ae82aea487e44fab8';
        const API_URL = 'https://api.themoviedb.org/3';
        const IMG_URL = 'https://image.tmdb.org/t/p/w200'; // URL base das imagens

        // --------------- ELEMENTOS ---------------
        const btnBuscar = document.getElementById('btn_buscar');
        const inputBusca = document.getElementById('busca_titulo');
        const divResultados = document.getElementById('resultados_busca');
        const formSalvar = document.getElementById('form_salvar');
        const pTituloSelecionado = document.getElementById('titulo_selecionado');

        // --------------- EVENTOS ---------------

        // Evento de clique no botão "Buscar"
        btnBuscar.addEventListener('click', buscarItens);

        // Opcional: Buscar ao pressionar "Enter"
        inputBusca.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                buscarItens();
            }
        });

        // --------------- FUNÇÕES ---------------

        async function buscarItens() {
            const query = inputBusca.value;
            if (!query) return; // Não faz nada se a busca estiver vazia

            // 'multi' busca por filmes e séries ao mesmo tempo
            const urlBusca =
                `${API_URL}/search/multi?api_key=${API_KEY}&language=pt-BR&query=${encodeURIComponent(query)}`;

            try {
                const response = await fetch(urlBusca);
                const data = await response.json();
                mostrarResultados(data.results);
            } catch (error) {
                console.error("Erro ao buscar na API:", error);
                divResultados.innerHTML = "Erro ao buscar. Tente novamente.";
            }
        }

        // Função para mostrar os resultados da busca na tela
        function mostrarResultados(resultados) {
            divResultados.innerHTML = ''; // Limpa resultados anteriores
            formSalvar.style.display = 'none'; // Esconde o formulário final

            // Filtra para incluir apenas filmes (movie) ou séries (tv) e que tenham poster
            const filmesESeries = resultados.filter(item =>
                (item.media_type === 'movie' || item.media_type === 'tv') && item.poster_path
            );

            filmesESeries.forEach(item => {
                const titulo = item.title || item.name; // 'title' para filmes, 'name' para séries
                const ano = (item.release_date || item.first_air_date || '').split('-')[0] || 'N/A';
                const poster = `${IMG_URL}${item.poster_path}`;

                const itemDiv = document.createElement('div');
                itemDiv.className = 'resultado-item';
                itemDiv.innerHTML = `
                    <img src="${poster}" alt="Poster de ${titulo}">
                    <div>
                        <strong>${titulo} (${ano})</strong>
                        <p>${item.overview ? item.overview.substring(0, 150) + '...' : 'Sem descrição.'}</p>
                    </div>
                `;

                // Evento de clique no item do resultado
                itemDiv.addEventListener('click', () => {
                    selecionarItem(item);
                });

                divResultados.appendChild(itemDiv);
            });
        }

        // Função chamada quando o usuário clica em um item da busca
        function selecionarItem(item) {
            const titulo = item.title || item.name;

            // 1. Destaca o item selecionado e limpa o resto
            pTituloSelecionado.innerHTML = `<strong>${titulo}</strong>`;
            divResultados.innerHTML = ''; // Limpa os resultados da busca

            // 2. Preenche o formulário oculto
            document.getElementById('hidden_titulo').value = titulo;
            document.getElementById('hidden_tmdb_id').value = item.id;
            document.getElementById('hidden_poster_path').value = item.poster_path || '';
            document.getElementById('hidden_sinopse').value = item.overview || '';
            document.getElementById('hidden_tipo').value = item.media_type; // 'movie' ou 'tv'

            // Pega os IDs de gênero e junta em uma string (ex: "28,12,878")
            document.getElementById('hidden_genero_ids').value = item.genre_ids.join(',');

            // 3. Mostra o formulário final para o usuário salvar
            formSalvar.style.display = 'block';
        }
    </script>

</body>

</html>