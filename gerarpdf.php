<?php
// 1. Ajuste o caminho para onde você salvou a pasta do FPDF
require('fpdf/fpdf.php');

// 2. Inclui a sua conexão com o banco (PDO)
require_once 'acoes/conexao.php';

// --- CONSULTA AO BANCO DE DADOS (Igual ao estatisticas.php) ---
// Precisamos do JOIN para mostrar os gêneros junto com o filme
$sql = "
    SELECT 
        itens.*, 
        GROUP_CONCAT(generos.nome_genero SEPARATOR ', ') as lista_generos
    FROM 
        itens
    LEFT JOIN 
        item_genero ON itens.id = item_genero.item_id
    LEFT JOIN 
        generos ON item_genero.genero_id = generos.id_genero
    GROUP BY 
        itens.id
    ORDER BY 
        itens.status ASC, itens.titulo ASC
";

$stmt = $pdo->query($sql);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- CRIAÇÃO DO PDF ---

class PDF extends FPDF
{
	// Cabeçalho padrão para todas as páginas
	function Header()
	{
		$this->SetFont('Arial', 'B', 14);
		$this->Cell(0, 10, utf8_decode('Relatório: Minha Lista'), 0, 1, 'C');
		$this->Ln(5);

		// Cabeçalho da Tabela
		$this->SetFont('Arial', 'B', 10);
		$this->SetFillColor(200, 220, 255); // Cor de fundo azulzinho

		// Definição das larguras das colunas (Soma total ~190mm para A4)
		$this->Cell(60, 10, utf8_decode('Título'), 1, 0, 'C', true);
		$this->Cell(50, 10, utf8_decode('Gêneros'), 1, 0, 'C', true);
		$this->Cell(30, 10, 'Status', 1, 0, 'C', true);
		$this->Cell(30, 10, 'Prioridade', 1, 0, 'C', true);
		$this->Cell(20, 10, 'Nota', 1, 1, 'C', true); // O '1' no final pula linha
	}

	// Rodapé com número da página
	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial', 'I', 8);
		$this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
	}
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 9);

// --- LOOP PARA IMPRIMIR OS DADOS ---
foreach ($itens as $item) {
	// Tratamento de dados (utf8_decode é OBRIGATÓRIO no FPDF para acentos)
	$titulo = utf8_decode(substr($item['titulo'], 0, 35)); // Corta titulo se for gigante
	$generos = utf8_decode(substr($item['lista_generos'], 0, 30)); // Corta generos
	$status = utf8_decode($item['status']);
	$prioridade = utf8_decode($item['prioridade']);
	$nota = $item['nota'] ? number_format($item['nota'], 1) : '-';

	// Imprime as células
	$pdf->Cell(60, 10, $titulo, 1);
	$pdf->Cell(50, 10, $generos, 1);
	$pdf->Cell(30, 10, $status, 1, 0, 'C'); // Centralizado
	$pdf->Cell(30, 10, $prioridade, 1, 0, 'C');
	$pdf->Cell(20, 10, $nota, 1, 1, 'C');
}

// Rodapé final com data
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, utf8_decode('Relatório gerado em: ' . date('d/m/Y H:i')), 0, 1, 'R');

// Gera o arquivo (O 'I' abre no navegador, 'D' força o download)
$pdf->Output('I', 'Relatorio_Watchlist.pdf');
