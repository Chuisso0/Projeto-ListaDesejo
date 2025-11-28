<?php
/*
 * ARQUIVO: acao_excluir.php (MODIFICADO)
 * Processa a Exclusão (DELETE) de um item e seus dados relacionados.
 */

// 1. Inclui a conexão
// (Ajuste o caminho se seu 'conexao.php' estiver na pasta raiz)
require_once 'conexao.php';

// 2. Pega o ID da URL (GET) e valida
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // 3. Inicia uma transação
    $pdo->beginTransaction();

    try {
        // 4. ETAPA 1: Excluir os "filhos" (os links de gênero)
        $sql_filhos = "DELETE FROM item_genero WHERE item_id = :id";
        $stmt_filhos = $pdo->prepare($sql_filhos);
        $stmt_filhos->bindParam(':id', $id);
        $stmt_filhos->execute();

        // 5. ETAPA 2: Excluir o "pai" (o item principal)
        $sql_pai = "DELETE FROM itens WHERE id = :id";
        $stmt_pai = $pdo->prepare($sql_pai);
        $stmt_pai->bindParam(':id', $id);
        $stmt_pai->execute();

        // 6. Se tudo deu certo, confirma as exclusões
        $pdo->commit();

        // 7. Redireciona para a watchlist
        header("Location: ../watchlist.php"); // (Ajuste o caminho de volta)
        exit;
    } catch (PDOException $e) {
        // 8. Se algo deu errado, desfaz tudo
        $pdo->rollBack();
        echo "Erro ao excluir item: " . $e->getMessage();
    }
} else {
    // Se o ID for inválido
    echo "ID inválido!";
    echo '<br><a href="../watchlist.php">Voltar</a>';
}
