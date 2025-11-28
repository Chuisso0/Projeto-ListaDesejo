<?php
/*
 * ARQUIVO: acao_dar_nota.php
 * Apenas atualiza a nota de um item.
 */

// 1. Inclui a conexão
require_once 'conexao.php';

// 2. Verifica se é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 3. Pega os dados
    $id = (int)$_POST['id'];

    // Lógica da Nota (NULL se vazio)
    $nota = !empty($_POST['nota']) ? (float)$_POST['nota'] : NULL;

    if ($id > 0) {
        try {
            // 4. Prepara o SQL (UPDATE simples)
            $sql = "UPDATE itens SET nota = :nota WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nota', $nota);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // 5. Devolve o usuário para a lista
            header("Location: ../watchlist.php");
            exit;
        } catch (PDOException $e) {
            echo "Erro ao salvar nota: " . $e->getMessage();
        }
    } else {
        echo "ID inválido!";
        echo '<br><a href="../watchlist.php">Voltar</a>';
    }
} else {
    // Se não for POST
    header("Location: ../index.php");
    exit;
}
