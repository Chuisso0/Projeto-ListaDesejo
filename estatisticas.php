<?php
require_once 'acoes/conexao.php';

// --- 1. Consulta para o Gráfico de STATUS (Pizza) ---
$queryStatus = $pdo->query("SELECT status, COUNT(*) as total FROM itens GROUP BY status");
$dadosStatus = $queryStatus->fetchAll(PDO::FETCH_ASSOC);

// Prepara dados para o JS
$labelsStatus = [];
$valuesStatus = [];
foreach ($dadosStatus as $d) {
    $labelsStatus[] = $d['status'];
    $valuesStatus[] = $d['total'];
}

// --- 2. Consulta para o Gráfico de GÊNEROS (Barra) ---
$queryGeneros = $pdo->query("
    SELECT g.nome_genero, COUNT(ig.item_id) as total 
    FROM generos g
    JOIN item_genero ig ON g.id_genero = ig.genero_id
    GROUP BY g.nome_genero
    ORDER BY total DESC
");
$dadosGeneros = $queryGeneros->fetchAll(PDO::FETCH_ASSOC);

$labelsGeneros = [];
$valuesGeneros = [];
foreach ($dadosGeneros as $d) {
    $labelsGeneros[] = $d['nome_genero'];
    $valuesGeneros[] = $d['total'];
}

// --- 3. Consulta para a Tabela de Relatório (Listagem Completa) ---
$queryRelatorio = $pdo->query("
    SELECT itens.*, GROUP_CONCAT(generos.nome_genero SEPARATOR ', ') as lista_generos
    FROM itens
    LEFT JOIN item_genero ON itens.id = item_genero.item_id
    LEFT JOIN generos ON item_genero.genero_id = generos.id_genero
    GROUP BY itens.id
    ORDER BY itens.titulo ASC
");
$listaRelatorio = $queryRelatorio->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatísticas & Relatórios</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/watchlist.css">

    <style>
        /* Estilos específicos desta página */
        .container-charts {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-bottom: 40px;
        }

        .chart-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            width: 45%;
            min-width: 300px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Estilo da Tabela de Relatório */
        .tabela-relatorio {
            width: 100%;
            border-collapse: collapse;
            background: white;
            color: #333;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .tabela-relatorio th,
        .tabela-relatorio td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .tabela-relatorio th {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
        }

        .tabela-relatorio tr:hover {
            background-color: #f1f1f1;
        }

        /* Botão de Imprimir (Agora aplicado ao Link também) */
        .btn-print {
            background-color: #6610f2;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            text-decoration: none;
            /* Remove sublinhado do link */
        }

        .btn-print:hover {
            background-color: #520dc2;
            transform: scale(1.05);
            /* Um efeitinho visual */
            transition: 0.2s;
        }
    </style>
</head>

<body>

    <header>
        <h1>Painel de Análise</h1>
        <div>
            <a href="index.php">Voltar</a>
            <button id="toggleDm" onclick="toggleDarkMode()">🌗</button>
        </div>
    </header>

    <main style="padding: 20px; max-width: 1200px; margin: 10vh auto 0;">

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 style="color:white; margin-bottom: 20px;">Dashboard da Watchlist</h2>

            <a href="gerarpdf.php" target="_blank" class="btn-print">
                🖨️ Baixar Relatório (PDF)
            </a>

        </div>

        <div class="container-charts">
            <div class="chart-box">
                <h3 style="text-align: center; color:#333;">Progresso (Assistidos vs Para Ver)</h3>
                <canvas id="chartStatus"></canvas>
            </div>

            <div class="chart-box">
                <h3 style="text-align: center; color:#333;">Preferência por Gênero</h3>
                <canvas id="chartGeneros"></canvas>
            </div>
        </div>

        <hr style="border-color: rgba(255,255,255,0.1); margin: 40px 0;">

        <h2 style="color:white; margin-bottom: 15px;">Relatório Detalhado de Itens</h2>
        <div style="overflow-x: auto;">
            <table class="tabela-relatorio">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Gêneros</th>
                        <th>Prioridade</th>
                        <th>Nota</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaRelatorio as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item->titulo); ?></td>
                            <td><?php echo htmlspecialchars($item->lista_generos ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($item->prioridade); ?></td>
                            <td>
                                <?php echo $item->nota ? "★ " . $item->nota : 'N/A'; ?>
                            </td>
                            <td>
                                <?php
                                $cor = ($item->status == 'Assistido') ? 'green' : 'blue';
                                echo "<span style='color:$cor; font-weight:bold;'>$item->status</span>";
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </main>

    <footer style="text-align: center; padding: 20px; color: #888; margin-top: 50px; background: rgba(0,0,0,0.5);">
        <p>Desenvolvido por <strong>Guilherme Chuisso</strong> - ADS 3º Período</p>
        <div style="display: flex; gap: 15px; justify-content: center; margin-top: 10px;">
            <a href="#" style="color: white;">LinkedIn</a>
            <a href="#" style="color: white;">GitHub</a>
            <a href="#" style="color: white;">Instagram</a>
        </div>
    </footer>

    <script>
        // --- Configuração do Gráfico de Status (Doughnut) ---
        const ctxStatus = document.getElementById('chartStatus').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($labelsStatus); ?>,
                datasets: [{
                    data: <?php echo json_encode($valuesStatus); ?>,
                    backgroundColor: ['#28a745', '#007bff'],
                    borderWidth: 1
                }]
            }
        });

        // --- Configuração do Gráfico de Gêneros (Bar) ---
        const ctxGeneros = document.getElementById('chartGeneros').getContext('2d');
        new Chart(ctxGeneros, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labelsGeneros); ?>,
                datasets: [{
                    label: 'Qtd. Filmes',
                    data: <?php echo json_encode($valuesGeneros); ?>,
                    backgroundColor: '#6610f2',
                    borderColor: '#520dc2',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>
</body>

</html>