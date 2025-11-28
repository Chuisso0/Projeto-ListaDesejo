<?php
/*
 * ARQUIVO: acao_editar.php
 * Processa a Alteração (UPDATE) de um item existente.
 */

// 1. Inclui a conexão
require_once 'conexao.php';

// 2. Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 3. Pega os dados do formulário
    $id = (int)$_POST['id'];
    $titulo = $_POST['titulo'];
    $tipo = $_POST['tipo'];
    $genero = $_POST['genero'];
    $prioridade = $_POST['prioridade'];
    $status = $_POST['status'];

    // Lógica da Nota:
    // Se o campo 'nota' foi enviado e não está vazio, usa o valor.
    // Se estiver vazio, define como NULL.
    $nota = !empty($_POST['nota']) ? (float)$_POST['nota'] : NULL;

    // Se o status for 'Para Ver', força a nota a ser NULL
    if ($status == 'Para Ver') {
        $nota = NULL;
    }

    // 4. Validação básica
    if ($id > 0 && !empty($titulo)) {
        try {
            // 5. Prepara a consulta SQL (UPDATE)
            $sql = "UPDATE itens SET 
                        titulo = :titulo, 
                        tipo = :tipo, 
                        genero = :genero, 
                        prioridade = :prioridade, 
                        status = :status, 
                        nota = :nota 
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            // 6. Vincula os valores
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':prioridade', $prioridade);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':nota', $nota);
            $stmt->bindParam(':id', $id);


            // 7. Executa
            $stmt->execute();

            // 8. Redireciona para o index.php
            header("Location: ../watchlist.php");
            exit;
        } catch (PDOException $e) {
            echo "Erro ao atualizar item: " . $e->getMessage();
        }
    } else {
        echo "Dados inválidos. Título e ID são obrigatórios.";
        echo '<br><a href="../form_editar.php?id=' . $id . '">Tentar novamente</a>';
    }
} else {
    // Se não for POST, redireciona para o index
    header("Location: ../watchlist.php");
    exit;
}
