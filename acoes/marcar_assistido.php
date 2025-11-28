<?php
/*
 * ARQUIVO: acao_marcar_assistido.php
 * Marca um item como 'Assistido' e redireciona para
 * a página de edição para o usuário dar a nota.
 */

// 1. Inclui a conexão (Ajuste o caminho)
require_once 'conexao.php';

// 2. Pega o ID da URL (GET) e valida
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        // 3. Prepara a consulta SQL (UPDATE)
        $sql = "UPDATE itens SET status = 'Assistido' WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // 4. ⭐ A MÁGICA: Redireciona para form_editar.php ⭐
        // O usuário clica, o status muda, e ele cai na
        // página de edição para dar a nota.
        header("Location: ../form_dar_nota.php?id=" . $id);
        exit;
    } catch (PDOException $e) {
        echo "Erro ao marcar como assistido: " . $e->getMessage();
    }
} else {
    echo "ID inválido!";
    echo '<br><a href="../watchlist.php">Voltar</a>';
}
