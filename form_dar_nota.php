<?php
// 1. Inclui a conexão
require_once 'acoes/conexao.php';

// 2. Pega o ID da URL (GET) e valida
// Exemplo: se a URL for form_dar_nota.php?id=15, $id será 15
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "ID inválido na URL! Volte para a Watchlist e tente novamente.";
    exit;
}

// 3. Busca o item no banco para mostrar o Nome e a Nota atual
try {
    // Não precisamos buscar o 'id' aqui, pois já temos ele na variável $id
    $sql = "SELECT titulo, nota FROM itens WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // O seu conexao.php retorna OBJETOS por padrão ($item->titulo)
    $item = $stmt->fetch();

    if (!$item) {
        echo "Item não encontrado no banco de dados!";
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao buscar item: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Dar Nota - <?php echo htmlspecialchars($item->titulo); ?></title>
    <link rel="stylesheet" href="css/form_dar_nota.css">
</head>

<body>

    <div class="container">
        <form action="acoes/acao_dar_nota.php" method="POST">

            <h1>Dar nota para:</h1>
            <p class="titulo-filme"><?php echo htmlspecialchars($item->titulo); ?></p>

            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="campo-nota">
                <label for="nota">Minha Nota (0.0 a 10.0):</label>
                <input type="number" id="nota" name="nota" min="0" max="10" step="0.1"
                    value="<?php echo htmlspecialchars($item->nota ?? ''); ?>" required>
            </div>

            <div class="botoes">
                <button type="submit" class="botao-salvar">Salvar Nota</button>
                <a href="watchlist.php" class="botao-cancelar">Cancelar</a>
            </div>

        </form>
    </div>

</body>

</html>