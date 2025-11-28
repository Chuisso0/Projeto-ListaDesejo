<?php
// 1. Inclui a conexão
require_once 'ações/conexao.php';

// 2. Pega o ID da URL (GET)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "ID inválido!";
    exit;
}

// 3. Busca o item específico (igual ao seu original)
try {
    $sql = "SELECT * FROM itens WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $item = $stmt->fetch();

    if (!$item) {
        echo "Item não encontrado!";
        exit;
    }

    // 4. ⭐ LÓGICA NOVA: Buscar TODOS os Gêneros do banco
    $sql_todos_generos = "SELECT * FROM generos ORDER BY nome_genero ASC";
    $stmt_todos_generos = $pdo->query($sql_todos_generos);
    $todos_generos = $stmt_todos_generos->fetchAll();

    // 5. ⭐ LÓGICA NOVA: Buscar os IDs dos gêneros que ESTE item JÁ TEM
    $sql_generos_item = "SELECT genero_id FROM item_genero WHERE item_id = :id";
    $stmt_generos_item = $pdo->prepare($sql_generos_item);
    $stmt_generos_item->bindParam(':id', $id);
    $stmt_generos_item->execute();

    // Transforma o resultado em um array simples, ex: [28, 12, 80]
    $generos_do_item = $stmt_generos_item->fetchAll(PDO::FETCH_COLUMN, 0);
} catch (PDOException $e) {
    die("Erro ao buscar item: " . $e->getMessage());
}

// Função auxiliar (igual à sua)
function checar($valorSalvo, $valorAtual)
{
    return ($valorSalvo == $valorAtual) ? 'selected' : '';
}

// ⭐ FUNÇÃO NOVA: Checar se o checkbox deve vir marcado
function checar_genero($id_genero_atual, $generos_do_item)
{
    return in_array($id_genero_atual, $generos_do_item) ? 'checked' : '';
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Item</title>
    <link rel="stylesheet" href="css/form_editar.css">
</head>

<body>
    <h1>Editar Item: <?php echo htmlspecialchars($item->titulo); ?></h1>

    <form action="acoes/acao_editar.php" method="POST">

        <input type="hidden" name="id" value="<?php echo $item->id; ?>">

        <div>
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($item->titulo); ?>"
                required>
        </div>

        <div>
            <label>Gêneros:</label>
            <div class="generos-container">
                <?php foreach ($todos_generos as $genero): ?>
                    <label class="genero-item">
                        <input type="checkbox" name="generos_selecionados[]" value="<?php echo $genero->id_genero; ?>"
                            <?php echo checar_genero($genero->id_genero, $generos_do_item); ?>>
                        <?php echo htmlspecialchars($genero->nome_genero); ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div>
            <label for="prioridade">Prioridade:</label>
            <select id="prioridade" name="prioridade">
                <option value="Alta" <?php echo checar($item->prioridade, 'Alta'); ?>>Alta</option>
                <option value="Média" <?php echo checar($item->prioridade, 'Média'); ?>>Média</option>
                <option value="Baixa" <?php echo checar($item->prioridade, 'Baixa'); ?>>Baixa</option>
            </select>
        </div>

        <div>
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="Para Ver" <?php echo checar($item->status, 'Para Ver'); ?>>Para Ver</option>
                <option value="Assistido" <?php echo checar($item->status, 'Assistido'); ?>>Assistido</option>
            </select>
        </div>

        <?php if ($item->status == 'Assistido'): ?>
            <div>
                <label for="nota">Nota (0.0 a 10.0):</label>
                <input type="number" id="nota" name="nota" min="0" max="10" step="0.1"
                    value="<?php echo htmlspecialchars($item->nota); ?>">
            </div>
        <?php else: ?>
            <input type="hidden" name="nota" value="">
        <?php endif; ?>

        <button type="submit">Atualizar</button>
    </form>

    <a href="watchlist.php">Cancelar e Voltar</a>

</body>

</html>