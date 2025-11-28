-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28/11/2025 às 03:15
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `watchlist_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `generos`
--

CREATE TABLE `generos` (
  `id_genero` int(11) NOT NULL,
  `nome_genero` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `generos`
--

INSERT INTO `generos` (`id_genero`, `nome_genero`) VALUES
(12, 'Aventura'),
(14, 'Fantasia'),
(16, 'Animação'),
(18, 'Drama'),
(27, 'Terror'),
(28, 'Ação'),
(35, 'Comédia'),
(36, 'História'),
(37, 'Faroeste'),
(53, 'Thriller'),
(80, 'Crime'),
(99, 'Documentário'),
(878, 'Ficção Científica'),
(9648, 'Mistério'),
(10402, 'Música'),
(10749, 'Romance'),
(10751, 'Família'),
(10752, 'Guerra'),
(10759, 'Action & Adventure'),
(10762, 'Kids'),
(10763, 'News'),
(10764, 'Reality'),
(10765, 'Sci-Fi & Fantasy'),
(10766, 'Soap'),
(10767, 'Talk'),
(10768, 'War & Politics');

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_genero`
--

CREATE TABLE `item_genero` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `genero_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `item_genero`
--

INSERT INTO `item_genero` (`id`, `item_id`, `genero_id`) VALUES
(1, 7, 16),
(2, 7, 28),
(3, 8, 9648),
(4, 8, 18),
(5, 8, 10759);

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens`
--

CREATE TABLE `itens` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `tipo` enum('Filme','Desenho','Document','Outro') DEFAULT 'Outro',
  `prioridade` enum('Alta','Média','Baixa') DEFAULT 'Baixa',
  `status` enum('Para Ver','Assistido') DEFAULT 'Para Ver',
  `nota` decimal(3,1) DEFAULT NULL,
  `tmdb_id` int(11) DEFAULT NULL,
  `poster_path` varchar(255) DEFAULT NULL,
  `sinopse` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `itens`
--

INSERT INTO `itens` (`id`, `titulo`, `tipo`, `prioridade`, `status`, `nota`, `tmdb_id`, `poster_path`, `sinopse`) VALUES
(5, 'IT: Bem-Vindos a Derry', 'Filme', 'Baixa', 'Assistido', NULL, NULL, '/gMTfrLvrDaD0zrhpLZ7zXIIpKfJ.jpg', 'Durante as férias de 1958, em uma pacata cidadezinha chamada Derry, um grupo de sete amigos começa a ver coisas bizarras. Um conta que viu um palhaço, outro que viu uma múmia. Finalmente, acabam descobrindo que estavam todos vendo a mesma coisa: um ser sobrenatural e maligno que pode assumir várias formas. Ele mesmo. O Pennywise.'),
(6, 'SAKAMOTO DAYS', 'Desenho', 'Baixa', 'Assistido', NULL, NULL, '/qTNIxDIzKjLLuS17fxdXjcKAlW7.jpg', 'Taro Sakamoto já foi o maior assassino de aluguel de todos os tempos, mas largou tudo por amor. Agora, com o passado à espreita, ele vai ter que lutar como nunca.'),
(7, 'JUJUTSU KAISEN: Execução', '', '', 'Para Ver', NULL, 1539104, '/sa646tMUGfCdOvwnHvzqycxzSoB.jpg', 'Yuji Itadori, acompanhado por seus colegas de classe e outros feiticeiros jujutsu de alto nível, entra na briga em um confronto sem precedentes de maldições: o Incidente de Shibuya. No rescaldo, dez colônias em todo o Japão são transformadas em antros de maldições em um plano orquestrado por Noritoshi Kamo, o feiticeiro mais perverso da história.'),
(8, 'Alice in Borderland', '', '', 'Assistido', NULL, 110316, '/i0i7kGDrArtM1sCd8niZDC7iboV.jpg', 'Um gamer e seus dois amigos são transportados para uma versão paralela de Tóquio, onde precisam participar de diversos jogos mortais caso queiram sobreviver.');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id_genero`);

--
-- Índices de tabela `item_genero`
--
ALTER TABLE `item_genero`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item` (`item_id`),
  ADD KEY `fk_genero` (`genero_id`);

--
-- Índices de tabela `itens`
--
ALTER TABLE `itens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `item_genero`
--
ALTER TABLE `item_genero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `item_genero`
--
ALTER TABLE `item_genero`
  ADD CONSTRAINT `fk_genero` FOREIGN KEY (`genero_id`) REFERENCES `generos` (`id_genero`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_item` FOREIGN KEY (`item_id`) REFERENCES `itens` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
