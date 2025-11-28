<?php
/*
 * ARQUIVO: acao_adicionar.php (MODIFICADO PARA API)
 * Processa a Inclusão (CREATE) de um novo item vindo da API.
 */

// 1. Inclui a conexão
require_once 'conexao.php';

// 2. Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 3. Pega os dados do formulário (vindos da API e do usuário)
    $titulo = $_POST['titulo'];
    $tmdb_id = (int)$_POST['tmdb_id'];
    $poster_path = $_POST['poster_path'];
    $sinopse = $_POST['sinopse'];
    $tipo = $_POST['tipo']; // 'movie' ou 'tv'
    $genero_ids_string = $_POST['genero_ids']; // Ex: "28,12,878"

    // Dados pessoais
    $prioridade = $_POST['prioridade'];

    // Validação
    if (!empty($titulo) && $tmdb_id > 0) {

        // Inicia uma transação (bom para fazer múltiplos inserts)
        $pdo->beginTransaction();

        try {
            // 4. Salva na Tabela `itens`
            // (Verifique se 'tipo' aceita 'movie' ou 'tv'. Se não, ajuste o SQL ou a tabela)

            // ATENÇÃO: Ajuste este SQL para os nomes exatos das suas colunas
            $sql_item = "INSERT INTO itens (titulo, tipo, prioridade, status, tmdb_id, poster_path, sinopse) 
                         VALUES (:titulo, :tipo, :prioridade, 'Para Ver', :tmdb_id, :poster_path, :sinopse)";

            $stmt_item = $pdo->prepare($sql_item);

            $stmt_item->bindParam(':titulo', $titulo);
            $stmt_item->bindParam(':tipo', $tipo); // Assumindo que sua coluna 'tipo' é ENUM ou VARCHAR
            $stmt_item->bindParam(':prioridade', $prioridade);
            $stmt_item->bindParam(':tmdb_id', $tmdb_id);
            $stmt_item->bindParam(':poster_path', $poster_path);
            $stmt_item->bindParam(':sinopse', $sinopse);

            $stmt_item->execute();

            // 5. Pega o ID do item que acabamos de salvar
            $novo_item_id = $pdo->lastInsertId();

            // 6. Salva na Tabela `item_genero`
            $genero_ids = explode(',', $genero_ids_string); // Transforma "28,12" em [28, 12]

            if (!empty($genero_ids[0])) { // Verifica se a string não estava vazia

                // Usamos os nomes da sua tabela: item_id, genero_id
                $sql_genero = "INSERT INTO item_genero (item_id, genero_id) VALUES (:item_id, :genero_id)";
                $stmt_genero = $pdo->prepare($sql_genero);

                foreach ($genero_ids as $genero_id) {
                    if (empty($genero_id)) continue; // Pula se tiver ID vazio

                    $stmt_genero->execute([
                        ':item_id' => $novo_item_id,
                        ':genero_id' => (int)$genero_id
                    ]);
                }
            }

            // 7. Se tudo deu certo, confirma a transação
            $pdo->commit();

            // 8. Redireciona de volta para a lista
            header("Location: ../watchlist.php");
            exit;
        } catch (PDOException $e) {
            // 9. Se algo deu errado, desfaz a transação
            $pdo->rollBack();
            echo "Erro ao adicionar item: " . $e->getMessage();
            echo '<br><a href="../form_adicionar.php">Tentar novamente</a>';
        }
    } else {
        echo "Dados inválidos. O Título e o ID do TMDB são obrigatórios.";
        echo '<br><a href="../form_adicionar.php">Tentar novamente</a>';
    }
} else {
    // Se não for POST, redireciona para o index
    header("Location: ../index.php");
    exit;
}
