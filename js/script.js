const API_KEY = "a1cfa57fd73c352ae82aea487e44fab8";
const IMG_PATH = "https://image.tmdb.org/t/p/w500";

// ---------------- Filme Destaque (Hero) ---------------------

async function pegarFilmeLancamento() {
    const hoje = new Date();
    const ano = hoje.getFullYear();
    const mes = String(hoje.getMonth() + 1).padStart(2, '0');
    const dia = String(hoje.getDate()).padStart(2, '0');
    const dataAtual = `${ano}-${mes}-${dia}`;

    const url = `https://api.themoviedb.org/3/discover/movie?api_key=${API_KEY}&language=pt-BR&sort_by=primary_release_date.desc&primary_release_date.lte=${dataAtual}&vote_count.gte=5`;
    const resposta = await fetch(url);
    const dados = await resposta.json();

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

    const detalhes = await fetch(`https://api.themoviedb.org/3/movie/${filme.id}?api_key=${API_KEY}&language=pt-BR`)
        .then(r => r.json());

    const generos = detalhes.genres.map(g => g.name).join(", ");

    // --- CORREÇÃO AQUI: Cria o link para adicionar ---
    const linkAdicionar = `form_adicionar.php?busca=${encodeURIComponent(filme.title)}`;

    hero.style.backgroundImage = `url(https://image.tmdb.org/t/p/original${filme.backdrop_path})`;

    hero.innerHTML = `
        <div class="hero-content">
            <h3>LANÇAMENTO</h3>

            <h1>${filme.title}</h1>

            <p>${filme.overview}</p>

            <p>${filme.release_date}</p>

            <p><strong>Gêneros:</strong> ${generos}</p>
            
            <a href="https://www.themoviedb.org/movie/${filme.id}" target="_blank">Ver detalhes</a>
            
            <a href="${linkAdicionar}" class="adicionar">Adicionar à Lista</a>
        </div>
    `;
}

// ---------------- FILMES EM ALTA ---------------------
async function carregarFilmes() {
    const url = `https://api.themoviedb.org/3/trending/movie/day?api_key=${API_KEY}&language=pt-BR`;

    const resposta = await fetch(url);
    const dados = await resposta.json();

    const divFilmes = document.getElementById("Filmes-em-alta");
    divFilmes.innerHTML = "";

    dados.results.slice(0, 13).forEach(filme => {
        const filmeDiv = document.createElement("div");
        filmeDiv.classList.add("item");

        // --- CORREÇÃO AQUI ---
        const linkAdicionar = `form_adicionar.php?busca=${encodeURIComponent(filme.title)}`;

        filmeDiv.innerHTML = `
            <img src="${IMG_PATH + filme.poster_path}" alt="${filme.title}">
            <a href="https://www.themoviedb.org/movie/${filme.id}" target="_blank"> 
                <h3>${filme.title}</h3> 
            </a>
            <h3>Nota: ${filme.vote_average.toFixed(1)}</h3>
            
            <a href="${linkAdicionar}" class="adicionar">Adicionar à Lista</a>
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

        // --- CORREÇÃO AQUI (Séries usam .name, não .title) ---
        const linkAdicionar = `form_adicionar.php?busca=${encodeURIComponent(serie.name)}`;

        serieDiv.innerHTML = `
            <img src="${IMG_PATH + serie.poster_path}" alt="${serie.name}">
            <a href="https://www.themoviedb.org/tv/${serie.id}" target="_blank">
                <h3>${serie.name}</h3> 
            </a>
            <h3>Nota: ${serie.vote_average.toFixed(1)}</h3>
            
            <a href="${linkAdicionar}" class="adicionar">Adicionar à Lista</a>
        `;

        divSeries.appendChild(serieDiv);
    });
}

// Chama ao carregar a página
carregarHero(); // Mudei a ordem pra carregar o Hero primeiro que é mais bonito
carregarFilmes();
carregarSeries();