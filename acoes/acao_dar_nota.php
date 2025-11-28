<?php
/*
 * ARQUIVO: acao_dar_nota.php
 * Atualiza a nota e, se solicitado, muda o status para 'Assistido'.
 */

// 1. Inclui a conexão
require_once 'conexao.php';

// 2. Verifica se é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 3. Pega os dados
    $id = (int)$_POST['id'];

    // Lógica da Nota (NULL se vazio)
    $nota = !empty($_POST['nota']) ? (float)$_POST['nota'] : NULL;

    // NOVO: Pega o aviso se é para mudar o status (1 = sim, 0 = não)
    $mudar_status = isset($_POST['mudar_status']) ? $_POST['mudar_status'] : '0';

    if ($id > 0) {
        try {
            // 4. Lógica Inteligente
            if ($mudar_status == '1') {
                // SE veio do botão "Assistido": Atualiza a Nota E muda o Status
                $sql = "UPDATE itens SET nota = :nota, status = 'Assistido' WHERE id = :id";
            } else {
                // SE veio do botão de estrela: Atualiza APENAS a nota (mantém o status atual)
                $sql = "UPDATE itens SET nota = :nota WHERE id = :id";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nota', $nota);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // 5. Devolve o usuário para a lista (ajustei para index.php que parece ser sua home)
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
    header("Location: ../watchlist.php");
    exit;
}
