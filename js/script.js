const API_KEY = "a1cfa57fd73c352ae82aea487e44fab8"; // coloque só a chave, sem Bearer
const IMG_PATH = "https://image.tmdb.org/t/p/w500";




// ---------------- FIlme Destaque ---------------------

async function pegarFilmeLancamento() {
    // 1. Pega a data de HOJE do seu computador/servidor (Brasil)
    // Isso evita o erro do UTC que jogava a data para o dia seguinte
    const hoje = new Date();
    const ano = hoje.getFullYear();
    const mes = String(hoje.getMonth() + 1).padStart(2, '0');
    const dia = String(hoje.getDate()).padStart(2, '0');
    const dataAtual = `${ano}-${mes}-${dia}`;

    // 2. A URL Mágica (Global)
    // - Tirei o '&region=BR' (agora busca no mundo todo)
    // - Tirei o 'with_release_type' (agora pega filme de streaming/digital também, não só cinema)
    // - Mantive 'lte=${dataAtual}' (Primary Release Date Menor ou Igual a Hoje)

    const url = `https://api.themoviedb.org/3/discover/movie?api_key=${API_KEY}&language=pt-BR&sort_by=primary_release_date.desc&primary_release_date.lte=${dataAtual}&vote_count.gte=5`;
    const resposta = await fetch(url);
    const dados = await resposta.json();

    // Retorna o primeiro filme da lista
    let filme = dados.results[0];

    if (!filme.overview || filme.overview.trim() === "") {
        const urlEN = `https://api.themoviedb.org/3/movie/${filme.id}?api_key=${API_KEY}&language=en-US`;
        const respostaEN = await fetch(urlEN);
        const filmeEN = await respostaEN.json();

        filme.overview = filmeEN.overview;
    }

    return filme;
}

async function carregarHero() {
    const filme = await pegarFilmeLancamento();

    const hero = document.getElementById("hero");

    // pega detalhes completos, incluindo generos
    const detalhes = await fetch(`https://api.themoviedb.org/3/movie/${filme.id}?api_key=${API_KEY}&language=pt-BR`)
        .then(r => r.json());

    const generos = detalhes.genres.map(g => g.name).join(", ");

    hero.style.backgroundImage = `url(https://image.tmdb.org/t/p/original${filme.backdrop_path})`;

    hero.innerHTML = `
        <div class="hero-content">
            <h3>LANÇAMENTO</h3>
            <h1>${filme.title}</h1>
            <p>${filme.overview}</p>
            <p>${filme.release_date}</p>
            <p><strong>Gêneros:</strong> ${generos}</p>
            <a href="https://www.themoviedb.org/movie/${filme.id}" target="_blank">Ver detalhes</a>
            <a href="" target="_blank" class="adicionar">Adicionar à Lista</a>
        </div>
    `;
}

// ---------------- FILMES EM ALTA ---------------------
async function carregarFilmes() {
    const url = `https://api.themoviedb.org/3/trending/movie/day?api_key=${API_KEY}&language=pt-BR`;

    const resposta = await fetch(url);
    const dados = await resposta.json();

    const divFilmes = document.getElementById("Filmes-em-alta");
    divFilmes.innerHTML = ""; // limpar

    dados.results.slice(0, 13).forEach(filme => {
        const filmeDiv = document.createElement("div");
        filmeDiv.classList.add("item");

        filmeDiv.innerHTML = `
            <img src="${IMG_PATH + filme.poster_path}" alt="${filme.title}">
            <a href="https://www.themoviedb.org/movie/${filme.id}" target="_blank"> <h3>${filme.title}</h3> </a>
            <p>Nota: ${filme.vote_average}</p>
                        <a href="" target="" class="adicionar">Adicionar à Lista</a>
        `;

        divFilmes.appendChild(filmeDiv);
    });
}

// ---------------- SÉRIES EM ALTA ---------------------
async function carregarSeries() {
    const url = `https://api.themoviedb.org/3/trending/tv/day?api_key=${API_KEY}&language=pt-BR`;

    const resposta = await fetch(url);
    const dados = await resposta.json();

    const divSeries = document.getElementById("Series-em-alta");
    divSeries.innerHTML = "";

    dados.results.slice(0, 13).forEach(serie => {
        const serieDiv = document.createElement("div");
        serieDiv.classList.add("item");

        serieDiv.innerHTML = `
            <img src="${IMG_PATH + serie.poster_path}" alt="${serie.name}">
            <a href="https://www.themoviedb.org/tv/${serie.id}" target="_blank"><h3>${serie.name}</h3> </a>
            <p>Nota: ${serie.vote_average}</p>
            <a href="" target="" class="adicionar">Adicionar à Lista</a>
        `;

        divSeries.appendChild(serieDiv);
    });
}

// Chama ao carregar a página
carregarFilmes();
carregarSeries();
carregarHero();
