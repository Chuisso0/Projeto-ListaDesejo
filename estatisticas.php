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
    <link rel="stylesheet" href="css/estatisticas.css">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
</head>

<body>

    <header>
        <h1>Painel de Análise</h1>
        <div>
            <a href="index.php">Voltar</a>
            <button id="toggleDm" onclick="toggleDarkMode()">☀️</button>
        </div>
    </header>

    <main>

        <div class="header-dashboard">
            <h2>Dashboard da Watchlist</h2>
            <a href="gerarpdf.php" target="_blank" class="btn-print">
                Baixar Relatório
            </a>
        </div>

        <div class="container-charts">
            <div class="chart-box">
                <h3>Progresso (Assistidos vs Para Ver)</h3>
                <canvas id="chartStatus"></canvas>
            </div>

            <div class="chart-box">
                <h3>Preferência por Gênero</h3>
                <canvas id="chartGeneros"></canvas>
            </div>
        </div>

        <hr class="divider">

        <h2 class="relatorio-title">Relatório Detalhado de Itens</h2>
        <div class="table-container">
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

    <footer>
        <h1>REDES SOCIAIS</h1>
        <div class="social-group">
            <a href="https://www.linkedin.com/in/guilherme-chuisso-21b555203/" target="_blank">
                <div class="social-item">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/linkedin.svg" alt="LinkedIn">
                    <p>LinkedIn</p>
                </div>
            </a>
            <a href="https://github.com/Chuisso0" target="_blank">
                <div class="social-item">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/github.svg" alt="GitHub">
                    <p>GitHub</p>
                </div>
            </a>
            <a href="https://www.instagram.com/chuisso1502" target="_blank">
                <div class="social-item">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg"
                        alt="Instagram">
                    <p>Instagram</p>
                </div>
            </a>
        </div>
    </footer>

    <script>
    // --- 1. Inicializa Variáveis Globais dos Gráficos ---
    let myChartStatus;
    let myChartGeneros;

    // --- 2. Configuração do Gráfico de Status (Doughnut) ---
    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    myChartStatus = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($labelsStatus); ?>,
            datasets: [{
                data: <?php echo json_encode($valuesStatus); ?>,
                backgroundColor: ['#28a745', '#007bff'],
                borderWidth: 0
            }]
        },
        options: {
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            }
        }
    });

    // --- 3. Configuração do Gráfico de Gêneros (Barra) ---
    const ctxGeneros = document.getElementById('chartGeneros').getContext('2d');
    myChartGeneros = new Chart(ctxGeneros, {
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
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: 'white'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: 'white'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // --- 4. Lógica de Dark Mode / Light Mode ---
    const btnTema = document.getElementById('toggleDm');
    const body = document.body;

    // Função para atualizar as cores de DENTRO dos gráficos
    function updateChartsColor(isLight) {
        const textColor = isLight ? '#333' : 'white';
        const gridColor = isLight ? 'rgba(0, 0, 0, 0.1)' : 'rgba(255, 255, 255, 0.1)';

        // Atualiza Gráfico Pizza (Legenda)
        myChartStatus.options.plugins.legend.labels.color = textColor;
        myChartStatus.update();

        // Atualiza Gráfico Barras (Eixos, Legenda e Grid)
        myChartGeneros.options.plugins.legend.labels.color = textColor;
        myChartGeneros.options.scales.x.ticks.color = textColor;
        myChartGeneros.options.scales.y.ticks.color = textColor;
        myChartGeneros.options.scales.y.grid.color = gridColor;
        myChartGeneros.update();
    }

    // Verifica tema salvo ao carregar a página
    const temaSalvo = localStorage.getItem('tema');
    if (temaSalvo === 'light') {
        body.classList.add('light-mode');
        btnTema.innerText = '🌙';
        updateChartsColor(true);
    }

    function toggleDarkMode() {
        body.classList.toggle('light-mode');

        if (body.classList.contains('light-mode')) {
            localStorage.setItem('tema', 'light');
            btnTema.innerText = '🌙';
            updateChartsColor(true);
        } else {
            localStorage.setItem('tema', 'dark');
            btnTema.innerText = '☀️';
            updateChartsColor(false);
        }
    }
    </script>
</body>

</html>