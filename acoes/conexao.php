<?php
/*
 * ARQUIVO: conexao.php
 * Gerencia a conexão com o banco de dados.
 */

// --- Configurações do Banco de Dados ---
$servidor = "localhost";    // Geralmente "localhost"
$usuario_db = "root";       // Usuário do MySQL (padrão: root)
$senha_db = "";             // Senha do MySQL (padrão: "" (vazio))
$banco = "watchlist_db";    // Nome do banco de dados que criamos
// ------------------------------------

try {
    // Cria a conexão PDO
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8", $usuario_db, $senha_db);

    // Define o modo de erro do PDO para exceção (bom para debugar)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define o modo de busca padrão para objetos (facilita o uso)
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (PDOException $e) {
    // Se a conexão falhar, exibe uma mensagem de erro
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
