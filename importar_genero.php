<?php
/*
 * ARQUIVO: importar_generos.php
 * RODE ESTE SCRIPT APENAS UMA VEZ
 *
 * Ele busca a lista oficial de gêneros do TMDB e
 * insere na sua tabela local 'generos'.
 */

// 1. Inclui a conexão
require_once 'ações/conexao.php';

// 2. Configure sua API Key
$api_key = 'a1cfa57fd73c352ae82aea487e44fab8';

// 3. URL do Endpoint de Gêneros do TMDB
$url_api = "https://api.themoviedb.org/3/genre/movie/list?api_key=$api_key&language=pt-BR";

echo "<h1>Importador de Gêneros do TMDB</h1>";

try {
    // 4. Faz a chamada à API
    // (file_get_contents é a forma mais simples de fazer isso em PHP)
    $json_data = file_get_contents($url_api);

    if ($json_data === FALSE) {
        die("Erro: Não foi possível acessar a API do TMDB. Verifique sua chave ou conexão.");
    }

    // 5. Decodifica a resposta JSON
    $data = json_decode($json_data);

    if (!isset($data->genres) || empty($data->genres)) {
        die("Erro: A API não retornou uma lista de gêneros válida.");
    }

    // 6. Prepara a consulta SQL
    // (Usamos INSERT IGNORE para não dar erro se você rodar 2x)
    // (Note que usamos os nomes das suas colunas: id_genero, nome_genero)
    $sql = "INSERT IGNORE INTO generos (id_genero, nome_genero) VALUES (:id_genero, :nome_genero)";
    $stmt = $pdo->prepare($sql);

    echo "Iniciando importação...<br>";
    $contador = 0;

    // 7. Faz um loop nos gêneros retornados pela API
    foreach ($data->genres as $genero) {

        $id_api = $genero->id;
        $nome_api = $genero->name;

        // 8. Vincula e executa para cada gênero
        $stmt->bindParam(':id_genero', $id_api);
        $stmt->bindParam(':nome_genero', $nome_api);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Importado: $nome_api (ID: $id_api)<br>";
            $contador++;
        } else {
            echo "Já existe (Ignorado): $nome_api (ID: $id_api)<br>";
        }
    }

    echo "<hr><strong>Importação concluída!</strong><br>$contador novos gêneros foram adicionados.";
} catch (PDOException $e) {
    die("Erro de Banco de Dados: " . $e->getMessage());
} catch (Exception $e) {
    die("Erro geral: " . $e->getMessage());
}
