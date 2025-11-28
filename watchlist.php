<?php

// Inclui o arquivo de conexão

require_once 'acoes/conexao.php';



// --- Lógica de Consulta (READ) COM JOIN ---

$stmt = $pdo->query("

    SELECT

        itens.*,

        GROUP_CONCAT(generos.nome_genero SEPARATOR ', ') as lista_generos

    FROM

        itens

    LEFT JOIN

        item_genero ON itens.id = item_genero.item_id

    LEFT JOIN

        generos ON item_genero.genero_id = generos.id_genero

    GROUP BY

        itens.id

    ORDER BY

        itens.status ASC,

        CASE itens.prioridade

            WHEN 'Alta' THEN 1

            WHEN 'Média' THEN 2

            WHEN 'Media' THEN 2

            WHEN 'Baixa' THEN 3

        END ASC

");

$itens = $stmt->fetchAll();

// -------------------------------

?>

<!DOCTYPE html>

<html lang="pt-br">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/watchlist.css">

    <title>Minha Watchlist</title>



</head>



<body>



    <header>

        <h1>Minha Watchlist</h1>

        <div>

            <a href="index.php">← Voltar para Home</a>

            <a href="form_adicionar.php" class="botao">Adicionar Novo Item</a>

            <a href="estatisticas.php" class="botao">📊 Estatísticas</a>

        </div>

    </header>



    <main>



        <?php if (count($itens) > 0): ?>

        <div class="grid-filmes">

            <?php foreach ($itens as $item): ?>

            <!-- INÍCIO DO CARD -->

            <div class="card">



                <!-- Pôster do Filme -->

                <?php

                        // Monta a URL da imagem. Se não tiver no banco, usa placeholder cinza

                        $posterUrl = $item->poster_path

                            ? "https://image.tmdb.org/t/p/w400" . $item->poster_path

                            : "https://via.placeholder.com/400x600?text=Sem+Imagem";

                        ?>

                <div>

                    <img src="<?php echo $posterUrl; ?>" alt="Poster" class="card-poster">



                    <!-- Nota flutuante (se existir) -->

                    <?php if ($item->nota !== null): ?>

                    <div class="nota-badge">★ <?php echo $item->nota; ?></div>

                    <?php endif; ?>

                </div>



                <!-- Corpo do Card -->

                <div class="card-body">

                    <h3 class="card-titulo"><?php echo htmlspecialchars($item->titulo); ?></h3>





                    <!-- Badges de Status e Prioridade -->

                    <div class="badges-container">

                        <!-- Status -->

                        <?php if ($item->status == 'Assistido'): ?>

                        <span class="badge badge-status-assistido">Visto</span>

                        <?php else: ?>

                        <span class="badge badge-status-paraver">Para Ver</span>

                        <?php endif; ?>







                        <!-- Prioridade -->

                        <?php

                                $classePrioridade = 'badge-prioridade-baixa';

                                $p = strtolower($item->prioridade); // Converte para minúsculo para comparar

                                if (strpos($p, 'alta') !== false) $classePrioridade = 'badge-prioridade-alta';

                                if (strpos($p, 'm') !== false) $classePrioridade = 'badge-prioridade-media'; // Pega media e média

                                ?>

                        <span class="badge <?php echo $classePrioridade; ?>">

                            <?php echo $item->prioridade; ?>

                        </span>

                    </div>

                </div>









                <div class="card-generos">

                    <?php echo htmlspecialchars($item->lista_generos ?? 'Sem gênero'); ?>

                </div>



                <!-- Botões de Ação -->

                <div class="card-acoes">



                    <?php if ($item->status == 'Para Ver'): ?>

                    <a href="acoes/marcar_assistido.php?id=<?php echo $item->id; ?>" class="btn-card btn-check">

                        Assistido

                    </a>

                    <?php elseif ($item->status == 'Assistido'): ?>

                    <button type="button" class="btn-card btn-avaliar" data-id="<?php echo $item->id; ?>"
                        data-titulo="<?php echo htmlspecialchars($item->titulo); ?>"
                        data-nota="<?php echo ($item->nota !== null) ? $item->nota : ''; ?>" onclick="abrirModal(this)">

                        ★ <?php echo ($item->nota !== null) ? 'Alterar Nota' : 'Dar Nota'; ?>

                    </button>

                    <?php endif; ?>



                    <a href="acoes/acao_excluir.php?id=<?php echo $item->id; ?>" class="btn-card btn-excluir"
                        onclick="return confirm('Tem certeza que deseja remover este filme?');">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                            <line x1="18" y1="6" x2="6" y2="18" />

                            <line x1="6" y1="6" x2="18" y2="18" />

                        </svg>



                    </a>

                </div>

            </div>

            <!-- FIM DO CARD -->

            <?php endforeach; ?>

        </div>

        <?php else: ?>

        <div style="text-align: center; padding: 50px; border-radius: 10px;">

            <h2>Sua lista está vazia! 🍿</h2>

            <p>Adicione alguns filmes ou séries para começar.</p>

        </div>

        <?php endif; ?>

    </main>



    <!-- ========================================== -->

    <!-- MODAL (Mantido igual)                      -->

    <!-- ========================================== -->

    <div id="modalNota" class="modal-overlay">

        <div class="modal-content">

            <span class="fechar-modal" onclick="fecharModal()">&times;</span>

            <div class="modal-header" style="margin-bottom: 15px;">

                <h2 style="margin: 0;">Avaliar Filme</h2>

            </div>

            <form action="acoes/acao_dar_nota.php" method="POST">

                <input type="hidden" name="id" id="modalIdFilme">

                <p style="margin-bottom: 15px;">Filme: <strong id="modalTituloFilme">...</strong></p>

                <div class="campo-modal">

                    <label for="modalInputNota" style="display:block; margin-bottom:5px; font-weight:bold;">Sua Nota

                        (0.0 a 10.0):</label>

                    <input type="number" id="modalInputNota" name="nota" min="0" max="10" step="0.1" required>

                </div>

                <div class="botoes-modal">

                    <button type="submit" class="btn-modal-salvar">Salvar</button>

                    <button type="button" class="btn-modal-cancelar" onclick="fecharModal()">Cancelar</button>

                </div>

            </form>

        </div>

    </div>



    <script>
    function abrirModal(botao) {

        var id = botao.getAttribute('data-id');

        var titulo = botao.getAttribute('data-titulo');

        var nota = botao.getAttribute('data-nota');

        document.getElementById('modalIdFilme').value = id;

        document.getElementById('modalTituloFilme').innerText = titulo;

        document.getElementById('modalInputNota').value = nota;

        document.getElementById('modalNota').style.display = 'flex';

    }



    function fecharModal() {

        document.getElementById('modalNota').style.display = 'none';

    }

    window.onclick = function(event) {

        var modal = document.getElementById('modalNota');

        if (event.target == modal) fecharModal();

    }
    </script>



</body>



</html>