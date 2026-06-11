-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 14/03/2026 às 04:00
-- Versão do servidor: 8.4.7
-- Versão do PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `clinica_prev_dentistas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos`
--

DROP TABLE IF EXISTS `atendimentos`;
CREATE TABLE IF NOT EXISTS `atendimentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `paciente_id` int NOT NULL,
  `id_dentista` int NOT NULL,
  `data_atendimento` datetime NOT NULL,
  `valor_total` decimal(10,2) DEFAULT '0.00',
  `taxa_cartao` decimal(10,2) DEFAULT '0.00',
  `valor_liquido_clinica` decimal(10,2) DEFAULT '0.00',
  `custo_auxiliar` decimal(10,2) NOT NULL DEFAULT '0.00',
  `comissao_dentista` decimal(10,2) DEFAULT '0.00',
  `status_pagamento` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_arquivo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_dentista` (`id_dentista`),
  KEY `paciente_id` (`paciente_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `atendimentos`
--

INSERT INTO `atendimentos` (`id`, `paciente_id`, `id_dentista`, `data_atendimento`, `valor_total`, `taxa_cartao`, `valor_liquido_clinica`, `custo_auxiliar`, `comissao_dentista`, `status_pagamento`, `url_arquivo`, `criado_em`) VALUES
(1, 2, 3, '2026-02-26 16:54:10', 150.00, 1.48, 118.52, 0.00, 30.00, 'pago', NULL, '2026-02-26 19:54:10'),
(2, 3, 3, '2026-02-26 17:05:03', 40.00, 0.40, 31.60, 0.00, 8.00, 'pago', NULL, '2026-02-26 20:05:03'),
(3, 3, 3, '2026-02-26 17:05:03', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-02-26 20:05:03'),
(4, 3, 3, '2026-02-26 17:40:25', 240.00, 2.37, 189.63, 0.00, 48.00, 'pago', NULL, '2026-02-26 20:40:25'),
(5, 4, 2, '2026-02-26 17:43:45', 790.00, 41.63, 590.37, 0.00, 158.00, 'pago', NULL, '2026-02-26 20:43:45'),
(6, 5, 3, '2026-02-27 09:52:35', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-02-27 12:52:35'),
(7, 6, 3, '2026-02-27 10:34:03', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-02-27 13:34:03'),
(8, 8, 3, '2026-02-27 15:59:28', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-02-27 18:59:28'),
(9, 10, 3, '2026-02-27 17:06:32', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-02-27 20:06:32'),
(10, 11, 2, '2026-02-28 10:11:22', 800.00, 0.00, 552.00, 150.00, 98.00, 'pago', NULL, '2026-02-28 13:11:22'),
(11, 11, 2, '2026-02-28 10:11:22', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-02-28 13:11:22'),
(12, 15, 3, '2026-02-28 11:28:56', 90.00, 0.00, 72.00, 0.00, 18.00, 'pago', NULL, '2026-02-28 14:28:56'),
(13, 15, 3, '2026-02-28 11:33:58', 10.00, 0.00, 8.00, 0.00, 2.00, 'pago', NULL, '2026-02-28 14:33:58'),
(14, 16, 2, '2026-03-02 09:52:43', 180.00, 0.00, 90.00, 0.00, 90.00, 'pago', NULL, '2026-03-02 12:52:43'),
(15, 17, 3, '2026-03-02 10:32:01', 170.00, 0.00, 136.00, 0.00, 34.00, 'pago', NULL, '2026-03-02 13:32:01'),
(16, 17, 3, '2026-03-02 10:32:01', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-03-02 13:32:01'),
(17, 6, 3, '2026-03-02 10:49:20', 150.00, 2.09, 117.91, 0.00, 30.00, 'pago', NULL, '2026-03-02 13:49:20'),
(19, 18, 3, '2026-03-02 12:11:50', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-03-02 15:11:50'),
(20, 19, 3, '2026-03-02 15:32:28', 80.00, 0.00, 64.00, 0.00, 16.00, 'pendente', NULL, '2026-03-02 18:32:28'),
(21, 21, 3, '2026-03-02 15:44:29', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-03-02 18:44:29'),
(22, 21, 3, '2026-03-02 15:46:06', 80.00, 0.00, 64.00, 0.00, 16.00, 'pago', NULL, '2026-03-02 18:46:06'),
(23, 23, 3, '2026-03-02 17:07:04', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-03-02 20:07:04'),
(24, 24, 3, '2026-03-02 17:41:20', 150.00, 1.48, 118.52, 0.00, 30.00, 'pago', NULL, '2026-03-02 20:41:20'),
(25, 30, 3, '2026-03-03 11:46:36', 310.00, 0.00, 248.00, 0.00, 62.00, 'pago', NULL, '2026-03-03 14:46:36'),
(27, 28, 2, '2026-03-03 17:42:56', 150.00, 0.00, 120.00, 0.00, 30.00, 'pago', NULL, '2026-03-03 20:42:56'),
(28, 28, 2, '2026-03-03 19:07:34', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-03-03 22:07:34'),
(30, 33, 3, '2026-03-04 10:49:29', 40.00, 0.40, 31.60, 0.00, 8.00, 'pago', NULL, '2026-03-04 13:49:29'),
(31, 33, 3, '2026-03-04 10:49:29', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-03-04 13:49:29'),
(32, 34, 3, '2026-03-04 12:31:44', 280.00, 0.00, 224.00, 0.00, 56.00, 'pago', NULL, '2026-03-04 15:31:44'),
(33, 33, 3, '2026-03-04 14:18:13', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-03-04 17:18:13'),
(34, 36, 2, '2026-03-04 15:16:44', 840.00, 0.00, 672.00, 0.00, 168.00, 'pago', NULL, '2026-03-04 18:16:44'),
(35, 36, 2, '2026-03-04 15:16:44', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-03-04 18:16:44'),
(36, 37, 3, '2026-03-04 17:10:50', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-03-04 20:10:50'),
(37, 28, 3, '2026-03-06 10:19:36', 350.00, 0.00, 280.00, 0.00, 70.00, 'pago', NULL, '2026-03-06 13:19:36'),
(38, 39, 3, '2026-03-06 12:56:13', 340.00, 15.07, 256.93, 0.00, 68.00, 'pago', NULL, '2026-03-06 15:56:13'),
(39, 41, 3, '2026-03-06 16:51:38', 150.00, 1.48, 118.52, 0.00, 30.00, 'pago', NULL, '2026-03-06 19:51:38'),
(40, 41, 3, '2026-03-06 16:51:38', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-03-06 19:51:38'),
(41, 42, 2, '2026-03-07 11:07:47', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-03-07 14:07:47'),
(42, 42, 2, '2026-03-07 12:29:21', 90.00, 0.89, 71.11, 0.00, 18.00, 'pago', NULL, '2026-03-07 15:29:21'),
(43, 43, 2, '2026-03-07 12:32:43', 910.00, 56.87, 534.13, 200.00, 119.00, 'pago', NULL, '2026-03-07 15:32:43'),
(44, 44, 2, '2026-03-07 14:52:51', 80.00, 0.00, 40.00, 0.00, 40.00, 'pago', NULL, '2026-03-07 17:52:51'),
(45, 45, 3, '2026-03-07 16:35:10', 100.00, 0.00, 70.00, 0.00, 30.00, 'pago', NULL, '2026-03-07 19:35:10'),
(46, 45, 3, '2026-03-07 17:05:01', 80.00, 0.00, 56.00, 0.00, 24.00, 'pago', NULL, '2026-03-07 20:05:01'),
(47, 45, 3, '2026-03-07 17:12:17', 100.00, 0.00, 70.00, 0.00, 30.00, 'pago', NULL, '2026-03-07 20:12:17'),
(48, 45, 3, '2026-03-07 17:13:47', 100.00, 0.00, 70.00, 0.00, 30.00, 'pago', NULL, '2026-03-07 20:13:47'),
(49, 45, 3, '2026-03-07 17:16:42', 100.00, 0.00, 70.00, 0.00, 30.00, 'pago', NULL, '2026-03-07 20:16:42'),
(50, 45, 3, '2026-03-07 17:20:02', 100.00, 0.00, 80.00, 0.00, 20.00, 'pago', NULL, '2026-03-07 20:20:02'),
(51, 45, 3, '2026-03-07 17:20:49', 7000.00, 0.00, 4900.00, 0.00, 2100.00, 'pago', NULL, '2026-03-07 20:20:49'),
(52, 45, 3, '2026-03-07 18:34:10', 50.00, 0.00, 35.00, 0.00, 15.00, 'pago', NULL, '2026-03-07 21:34:10'),
(53, 45, 2, '2026-03-14 00:08:48', 1130.00, 0.00, 440.00, 125.00, 565.00, 'pago', NULL, '2026-03-14 03:08:48'),
(54, 45, 2, '2026-03-14 00:25:54', 1450.00, 0.00, 780.00, 525.00, 145.00, 'pago', NULL, '2026-03-14 03:25:54'),
(57, 45, 3, '2026-03-14 00:34:03', 100.00, 0.00, 50.00, 0.00, 50.00, 'pago', NULL, '2026-03-14 03:34:03'),
(58, 45, 2, '2026-03-14 00:39:37', 800.00, 0.00, 400.00, 0.00, 400.00, 'pago', NULL, '2026-03-14 03:39:37'),
(59, 45, 2, '2026-03-14 00:39:37', 0.00, 0.00, 0.00, 0.00, 0.00, 'nao_aplicavel', NULL, '2026-03-14 03:39:37'),
(60, 45, 2, '2026-03-14 00:54:48', 1080.00, 0.00, 516.00, 400.00, 164.00, 'pago', NULL, '2026-03-14 03:54:48');

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimento_pagamentos`
--

DROP TABLE IF EXISTS `atendimento_pagamentos`;
CREATE TABLE IF NOT EXISTS `atendimento_pagamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_atendimento` int NOT NULL,
  `forma_pagamento` enum('dinheiro','pix','debito','credito') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `qtd_parcelas` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_atendimento` (`id_atendimento`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `atendimento_pagamentos`
--

INSERT INTO `atendimento_pagamentos` (`id`, `id_atendimento`, `forma_pagamento`, `valor`, `qtd_parcelas`) VALUES
(1, 1, 'debito', 150.00, 1),
(2, 2, 'debito', 40.00, 1),
(3, 4, 'debito', 240.00, 1),
(4, 5, 'credito', 790.00, 3),
(5, 10, 'pix', 800.00, 1),
(6, 12, 'dinheiro', 90.00, 1),
(7, 13, 'dinheiro', 10.00, 1),
(8, 14, 'pix', 180.00, 1),
(9, 15, 'dinheiro', 170.00, 1),
(10, 17, 'debito', 120.00, 1),
(11, 17, 'credito', 30.00, 1),
(12, 22, 'pix', 80.00, 1),
(13, 24, 'debito', 150.00, 1),
(14, 25, 'pix', 310.00, 1),
(15, 27, 'pix', 150.00, 1),
(16, 30, 'debito', 40.00, 1),
(17, 32, 'pix', 280.00, 1),
(18, 34, 'pix', 840.00, 1),
(19, 37, 'pix', 350.00, 1),
(20, 38, 'credito', 340.00, 2),
(21, 39, 'debito', 150.00, 1),
(22, 42, 'debito', 90.00, 1),
(23, 43, 'debito', 200.00, 1),
(24, 43, 'credito', 710.00, 6),
(25, 44, 'pix', 80.00, 1),
(26, 44, 'pix', 80.00, 1),
(27, 45, 'dinheiro', 100.00, 1),
(28, 46, 'dinheiro', 80.00, 1),
(29, 47, 'dinheiro', 100.00, 1),
(30, 48, 'dinheiro', 100.00, 1),
(31, 49, 'dinheiro', 100.00, 1),
(32, 50, 'dinheiro', 100.00, 1),
(33, 51, 'dinheiro', 7000.00, 1),
(34, 52, 'dinheiro', 50.00, 1),
(35, 53, 'dinheiro', 1130.00, 1),
(36, 54, 'dinheiro', 1450.00, 1),
(37, 57, 'dinheiro', 100.00, 1),
(38, 58, 'dinheiro', 800.00, 1),
(39, 60, 'dinheiro', 1080.00, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimento_procedimentos`
--

DROP TABLE IF EXISTS `atendimento_procedimentos`;
CREATE TABLE IF NOT EXISTS `atendimento_procedimentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_atendimento` int NOT NULL,
  `id_procedimento` int NOT NULL,
  `quantidade` int DEFAULT '1',
  `valor_procedimento` decimal(10,2) DEFAULT NULL,
  `custo_auxiliar` decimal(10,2) DEFAULT '0.00',
  `natureza` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_arquivo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `local` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status_execucao` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_execucao` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `atendimento_procedimentos`
--

INSERT INTO `atendimento_procedimentos` (`id`, `id_atendimento`, `id_procedimento`, `quantidade`, `valor_procedimento`, `custo_auxiliar`, `natureza`, `url_arquivo`, `local`, `descricao`, `status_execucao`, `data_execucao`) VALUES
(1, 1, 8, 1, 150.00, 0.00, '', NULL, '37', '', 'feito', NULL),
(2, 2, 1, 1, 40.00, 0.00, '', 'uploads/proc_2_69a0a88117878.jpg', '44', '', 'feito', NULL),
(4, 3, 5, 1, 90.00, 0.00, '', NULL, '25', '', 'pendente', NULL),
(6, 4, 8, 1, 150.00, 0.00, '', NULL, '44', '', 'feito', NULL),
(7, 4, 5, 1, 90.00, 0.00, '', NULL, '46', '', 'feito', NULL),
(8, 5, 11, 1, 250.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(9, 5, 5, 1, 90.00, 0.00, '', NULL, '11', '', 'feito', NULL),
(10, 5, 5, 1, 90.00, 0.00, '', NULL, '12', '', 'feito', NULL),
(11, 5, 5, 1, 90.00, 0.00, '', NULL, '46', '', 'feito', NULL),
(12, 5, 5, 1, 90.00, 0.00, '', NULL, '45', '', 'feito', NULL),
(13, 5, 5, 1, 90.00, 0.00, '', NULL, '44', '', 'feito', NULL),
(14, 5, 5, 1, 90.00, 0.00, '', NULL, '35', '', 'feito', NULL),
(15, 6, 6, 1, 120.00, 0.00, '', NULL, '11', '', 'pendente', NULL),
(16, 6, 37, 1, 150.00, 0.00, '', NULL, '11', '', 'pendente', NULL),
(18, 8, 8, 1, 180.00, 0.00, '', NULL, '27', '', 'pendente', NULL),
(19, 8, 17, 1, 780.00, 0.00, '', NULL, '25', '', 'pendente', NULL),
(20, 8, 19, 1, 200.00, 0.00, '', NULL, '25', '', 'pendente', NULL),
(21, 8, 22, 1, 280.00, 0.00, '', NULL, '25', '', 'pendente', NULL),
(22, 9, 8, 1, 150.00, 0.00, '', NULL, '22', '', 'pendente', NULL),
(23, 9, 15, 1, 580.00, 0.00, '', NULL, '22', '', 'pendente', NULL),
(24, 9, 23, 1, 2000.00, 0.00, '', NULL, '22', '', 'pendente', NULL),
(25, 9, 23, 1, 1700.00, 0.00, '', NULL, '22', '', 'pendente', NULL),
(26, 9, 5, 1, 90.00, 0.00, '', NULL, '26', '', 'pendente', NULL),
(27, 9, 3, 1, 150.00, 0.00, '', NULL, 'Todos', '', 'pendente', NULL),
(28, 10, 9, 1, 180.00, 0.00, '', NULL, '24', '', 'feito', NULL),
(29, 10, 15, 1, 620.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(30, 11, 5, 1, 90.00, 0.00, '', NULL, '15', '', 'pendente', NULL),
(31, 11, 5, 1, 90.00, 0.00, '', NULL, '14', '', 'pendente', NULL),
(32, 11, 5, 1, 90.00, 0.00, '', NULL, '13', '', 'pendente', NULL),
(33, 11, 5, 1, 90.00, 0.00, '', NULL, '23', '', 'pendente', NULL),
(34, 11, 5, 1, 90.00, 0.00, '', NULL, '45', '', 'pendente', NULL),
(35, 11, 9, 1, 180.00, 0.00, '', NULL, '44', '', 'pendente', NULL),
(36, 11, 5, 1, 90.00, 0.00, '', NULL, '36', '', 'pendente', NULL),
(37, 12, 5, 1, 90.00, 0.00, '', NULL, '11', '', 'feito', NULL),
(38, 13, 5, 1, 10.00, 0.00, '', NULL, '11', '', 'feito', NULL),
(39, 14, 28, 1, 180.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(40, 15, 8, 1, 170.00, 0.00, '', NULL, '37', '', 'feito', NULL),
(41, 16, 3, 1, 150.00, 0.00, '', NULL, 'Todos', '', 'pendente', NULL),
(42, 16, 5, 1, 90.00, 0.00, '', NULL, '36', '', 'pendente', NULL),
(43, 17, 8, 1, 150.00, 0.00, '', NULL, '36', '', 'feito', NULL),
(51, 19, 3, 1, 150.00, 0.00, '', NULL, 'Todos', '', 'pendente', NULL),
(52, 19, 15, 1, 590.00, 0.00, '', NULL, 'Todos', '', 'pendente', NULL),
(53, 19, 35, 1, 250.00, 0.00, '', NULL, 'Todos', '', 'pendente', NULL),
(54, 19, 15, 1, 590.00, 0.00, '', NULL, 'Todos', '', 'pendente', NULL),
(55, 19, 5, 1, 90.00, 0.00, '', NULL, '15', '', 'pendente', NULL),
(56, 19, 5, 1, 90.00, 0.00, '', NULL, '24', '', 'pendente', NULL),
(57, 19, 5, 1, 90.00, 0.00, '', NULL, '25', '', 'pendente', NULL),
(58, 19, 5, 1, 90.00, 0.00, '', NULL, '34', '', 'pendente', NULL),
(59, 19, 5, 1, 90.00, 0.00, '', NULL, '35', '', 'pendente', NULL),
(60, 19, 5, 1, 90.00, 0.00, '', NULL, '36', '', 'pendente', NULL),
(61, 19, 5, 1, 90.00, 0.00, '', NULL, '45', '', 'pendente', NULL),
(62, 20, 10, 1, 80.00, 0.00, '', NULL, '13', '', 'finalizado', NULL),
(64, 22, 10, 1, 80.00, 0.00, '', NULL, '13', 'Obs: Dente da filha.\r\nManoela Sousa De Sousa', 'feito', NULL),
(65, 23, 3, 1, 150.00, 0.00, '', NULL, 'Todos', '', 'pendente', NULL),
(66, 23, 29, 1, 90.00, 0.00, '', NULL, 'Todos', '', 'pendente', NULL),
(67, 24, 8, 1, 150.00, 0.00, '', NULL, '17', '', 'feito', NULL),
(68, 25, 5, 1, 120.00, 0.00, '', NULL, '41', '', 'feito', NULL),
(69, 25, 5, 1, 90.00, 0.00, '', NULL, '17', '', 'feito', NULL),
(70, 25, 3, 1, 100.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(73, 27, 3, 1, 150.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(74, 28, 8, 1, 180.00, 0.00, '', NULL, '15', '', 'pendente', NULL),
(77, 28, 9, 1, 350.00, 0.00, '', NULL, '48', '', 'pendente', NULL),
(78, 28, 8, 1, 180.00, 0.00, '', NULL, '37', '', 'pendente', NULL),
(79, 28, 9, 1, 350.00, 0.00, '', NULL, '38', '', 'pendente', NULL),
(80, 28, 8, 1, 170.00, 0.00, '', NULL, '27', '', 'pendente', NULL),
(81, 28, 8, 1, 170.00, 0.00, '', NULL, '25', '', 'pendente', NULL),
(82, 28, 5, 1, 90.00, 0.00, '', NULL, '45', '', 'pendente', NULL),
(83, 28, 5, 1, 90.00, 0.00, '', NULL, '36', '', 'pendente', NULL),
(84, 28, 5, 1, 90.00, 0.00, '', NULL, '26', '', 'pendente', NULL),
(92, 30, 1, 1, 40.00, 0.00, '', 'uploads/proc_92_69a869e6b4155.jpg', '18', '', 'feito', NULL),
(93, 31, 40, 1, 350.00, 0.00, '', NULL, '18', '', 'pendente', NULL),
(94, 32, 22, 1, 280.00, 0.00, '', NULL, '37', '', 'feito', NULL),
(95, 33, 40, 1, 300.00, 0.00, '', NULL, '18', '', 'pendente', NULL),
(96, 33, 6, 1, 100.00, 0.00, '', NULL, '11', '', 'pendente', NULL),
(97, 33, 5, 1, 90.00, 0.00, '', NULL, '24', '', 'pendente', NULL),
(98, 33, 5, 1, 90.00, 0.00, '', NULL, '47', '', 'pendente', NULL),
(99, 33, 5, 1, 90.00, 0.00, '', NULL, '46', '', 'pendente', NULL),
(100, 33, 8, 1, 150.00, 0.00, '', NULL, '44', '', 'pendente', NULL),
(101, 34, 5, 1, 90.00, 0.00, '', NULL, '14', '', 'feito', NULL),
(102, 34, 5, 1, 90.00, 0.00, '', NULL, '13', '', 'feito', NULL),
(103, 34, 5, 1, 90.00, 0.00, '', NULL, '12', '', 'feito', NULL),
(104, 34, 5, 1, 90.00, 0.00, '', NULL, '23', '', 'feito', NULL),
(105, 34, 5, 1, 90.00, 0.00, '', NULL, '45', '', 'feito', NULL),
(106, 34, 5, 1, 90.00, 0.00, '', NULL, '44', '', 'feito', NULL),
(107, 34, 5, 1, 90.00, 0.00, '', NULL, '33', '', 'feito', NULL),
(108, 34, 3, 1, 120.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(109, 34, 5, 1, 90.00, 0.00, '', NULL, '38', '', 'feito', NULL),
(110, 35, 9, 1, 170.00, 0.00, '', NULL, '22', '', 'pendente', NULL),
(111, 35, 8, 1, 170.00, 0.00, '', NULL, '41', '', 'pendente', NULL),
(112, 35, 8, 1, 170.00, 0.00, '', NULL, '31', '', 'pendente', NULL),
(113, 35, 8, 1, 170.00, 0.00, '', NULL, '34', '', 'pendente', NULL),
(114, 35, 15, 1, 630.00, 0.00, '', NULL, 'Todos', '', 'pendente', NULL),
(115, 35, 15, 1, 630.00, 0.00, '', NULL, 'Todos', 'INFERIOR', 'pendente', NULL),
(116, 36, 1, 1, 40.00, 0.00, '', NULL, '17', '', 'pendente', NULL),
(117, 36, 5, 1, 90.00, 0.00, '', NULL, '16', '', 'pendente', NULL),
(118, 36, 5, 1, 90.00, 0.00, '', NULL, '24', '', 'pendente', NULL),
(119, 36, 5, 1, 90.00, 0.00, '', NULL, '26', '', 'pendente', NULL),
(120, 36, 8, 1, 170.00, 0.00, '', NULL, '25', '', 'pendente', NULL),
(121, 36, 5, 1, 90.00, 0.00, '', NULL, '27', '', 'pendente', NULL),
(122, 36, 41, 1, 450.00, 0.00, '', NULL, '48', '', 'pendente', NULL),
(123, 36, 3, 1, 150.00, 0.00, '', NULL, 'Todos', '', 'pendente', NULL),
(124, 36, 1, 1, 40.00, 0.00, '', NULL, '46', '', 'pendente', NULL),
(125, 37, 8, 1, 180.00, 0.00, '', NULL, '46', '', 'feito', NULL),
(126, 37, 8, 1, 170.00, 0.00, '', 'uploads/proc_126_69aad52f63e78.jpg', '47', '', 'feito', NULL),
(127, 38, 41, 1, 300.00, 0.00, '', NULL, '48', '', 'feito', NULL),
(128, 38, 1, 1, 40.00, 0.00, '', NULL, '48', '', 'feito', NULL),
(129, 39, 3, 1, 150.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(130, 40, 11, 1, 250.00, 0.00, '', NULL, 'Todos', '', 'pendente', NULL),
(132, 42, 7, 1, 90.00, 0.00, '', NULL, '26', 'o dente e deciduo 65', 'feito', NULL),
(133, 43, 5, 1, 90.00, 0.00, '', NULL, '45', '', 'feito', NULL),
(134, 43, 5, 1, 90.00, 0.00, '', NULL, '33', '', 'feito', NULL),
(135, 43, 5, 1, 100.00, 0.00, '', NULL, '34', '', 'feito', NULL),
(136, 43, 13, 1, 630.00, 0.00, '', NULL, 'Todos', 'superior', 'feito', NULL),
(137, 44, 42, 1, 80.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(138, 45, 3, 1, 100.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(139, 46, 4, 1, 80.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(140, 47, 3, 1, 100.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(141, 48, 3, 1, 100.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(142, 49, 3, 1, 100.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(143, 50, 3, 1, 100.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(144, 51, 1, 1, 7000.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(145, 52, 2, 1, 50.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(146, 53, 35, 1, 250.00, 125.00, '', NULL, 'Todos', '', 'feito', NULL),
(147, 53, 18, 1, 800.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(148, 53, 42, 1, 80.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(149, 54, 16, 1, 650.00, 325.00, 'canal', NULL, 'Todos', 'Canal feito no paciente.', 'feito', NULL),
(150, 54, 18, 1, 800.00, 200.00, 'protese', NULL, 'Todos', 'Nova prótese.', 'feito', NULL),
(153, 57, 43, 1, 100.00, 0.00, 'orto', NULL, 'Todos', 'Orto', 'feito', NULL),
(154, 58, 18, 1, 800.00, 0.00, 'orto', NULL, 'Todos', '', 'feito', NULL),
(155, 59, 17, 1, 750.00, 200.00, 'protese', NULL, 'Todos', '', 'pendente', NULL),
(156, 59, 16, 1, 650.00, 325.00, 'canal', NULL, 'Todos', '', 'pendente', NULL),
(157, 60, 22, 1, 280.00, 0.00, '', NULL, 'Todos', '', 'feito', NULL),
(158, 60, 18, 1, 800.00, 400.00, 'canal', NULL, 'Todos', '', 'feito', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `despesas`
--

DROP TABLE IF EXISTS `despesas`;
CREATE TABLE IF NOT EXISTS `despesas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `tipo` enum('fixa','variavel') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_despesa` date NOT NULL,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `despesas`
--

INSERT INTO `despesas` (`id`, `descricao`, `valor`, `tipo`, `data_despesa`, `criado_em`) VALUES
(12, '11210101000000 - Taxa de Licença Para Localização, Funcionamento e Fiscalização 2023', 384.52, 'variavel', '2026-03-02', '2026-03-02 16:30:06'),
(13, '11210101000000 - Taxa de Licença Para Localização, Funcionamento e Fiscalização 2024', 321.52, 'variavel', '2026-03-02', '2026-03-02 16:30:38'),
(14, '11210101000000 - Taxa de Licença Para Localização, Funcionamento e Fiscalização 2025', 285.88, 'variavel', '2026-03-02', '2026-03-02 16:31:17'),
(15, '11210101000000 - Taxa de Licença Para Localização, Funcionamento e Fiscalização 2026', 224.84, 'variavel', '2026-03-02', '2026-03-02 19:43:15'),
(16, 'Refeita Federal - Residuo Juliana', 167.92, 'variavel', '2026-03-02', '2026-03-02 19:43:45'),
(17, 'Água 20 litros', 9.50, 'variavel', '2026-03-02', '2026-03-02 22:24:21'),
(18, 'Ônibus Recepcionista dias 02 e 03/03', 37.76, 'variavel', '2026-03-03', '2026-03-03 21:34:41'),
(19, 'Panfletos', 100.00, 'variavel', '2026-03-03', '2026-03-03 21:35:01'),
(20, 'Ônibus Recepcionista dias 04/03', 17.50, 'variavel', '2026-03-05', '2026-03-05 13:06:19'),
(21, 'Internet', 100.00, 'fixa', '2026-03-06', '2026-03-06 14:54:42'),
(22, 'Ônibus Recepcionista dias 05/03', 30.18, 'variavel', '2026-03-06', '2026-03-06 23:56:20'),
(23, 'Contador antigo', 300.00, 'variavel', '2026-03-07', '2026-03-07 17:44:57'),
(24, 'Ônibus Recepcionista dias 06 e 07/03', 27.34, 'variavel', '2026-03-07', '2026-03-07 17:52:38'),
(25, 'Detergente', 3.00, 'variavel', '2026-03-07', '2026-03-07 17:52:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pacientes`
--

DROP TABLE IF EXISTS `pacientes`;
CREATE TABLE IF NOT EXISTS `pacientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cpf` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complemento` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_paciente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf` (`cpf`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `pacientes`
--

INSERT INTO `pacientes` (`id`, `nome`, `cpf`, `data_nascimento`, `email`, `telefone`, `cep`, `endereco`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `foto_paciente`, `created_at`, `updated_at`) VALUES
(1, 'Elcio Manço Silva', NULL, '1976-06-11', NULL, '(91) 98957-7873', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-26 16:45:32', '2026-02-26 16:58:41'),
(2, 'Elena Leão Ferreira', NULL, NULL, NULL, NULL, NULL, 'Rua F ', '11', NULL, NULL, NULL, NULL, NULL, '2026-02-26 19:52:12', '2026-02-26 19:52:12'),
(3, 'Cleivy Eduardo Oliveira Chaves', '067.826.562-30', '2005-12-08', NULL, '(91) 98600-3104', NULL, 'Passagem Garafão', '15', NULL, 'Atalaia', 'Ananindeua', 'PA', NULL, '2026-02-26 19:59:27', '2026-02-26 20:45:04'),
(4, 'Anderleia Rodrigues ', NULL, NULL, NULL, '(91) 9991-4900', NULL, NULL, NULL, NULL, NULL, 'Belem', 'Pa', NULL, '2026-02-26 20:35:44', '2026-02-26 20:35:44'),
(5, 'Kailane Karine Lima', NULL, '2006-05-22', NULL, '(91) 98117-9225', NULL, 'Alameda Piauí', '57', NULL, 'Atalaia', 'Ananindeua', 'PA', NULL, '2026-02-27 12:49:51', '2026-02-27 12:49:51'),
(6, 'Allana Vitoria Silva Paiva', '068.799.142-09', '2004-07-30', NULL, '(91) 98428-5375', NULL, 'Passagem Dez De Maio ', '08', NULL, NULL, NULL, NULL, NULL, '2026-02-27 13:29:02', '2026-03-02 13:53:26'),
(7, 'Silvana Da Silva Cruz', NULL, NULL, NULL, '(91) 99372-6209', NULL, NULL, NULL, NULL, NULL, 'Benevides', 'PA', NULL, '2026-02-27 15:30:44', '2026-02-27 15:31:18'),
(8, 'Cleide Regina Botelho Carvalho', NULL, NULL, NULL, '(91) 98566-8517', NULL, 'Rua F', '20', NULL, 'Jardelandia I', 'Belem', 'PA', NULL, '2026-02-27 18:40:31', '2026-02-27 18:57:17'),
(9, 'Nalva Dias Amorim', '177.340.7', '1969-03-12', NULL, '(91) 98198-4729', NULL, 'Alameda Natal', '27 A', NULL, 'Jardelandia I', 'Belem', 'PA', NULL, '2026-02-27 19:26:10', '2026-02-27 19:26:10'),
(10, 'Melke Zedek Monteiro Pimentel', '751.837.302-53', '1980-07-30', NULL, '(91) 97400-7619', NULL, 'Rua D', '05', NULL, 'Jardelandia I', 'Belem', 'PA', NULL, '2026-02-27 19:32:11', '2026-02-27 19:32:11'),
(11, 'Hugo Nascimento Gomes', '793.361.592-91', '1984-08-17', NULL, '(91) 98233-2734', NULL, 'Passagem Sarmen', '31', NULL, 'Atalaia', 'Ananindeua', 'PA', NULL, '2026-02-28 11:23:52', '2026-02-28 11:23:52'),
(12, 'Marliene Fereira Da Paixão', '892.100.422-04', '1973-11-23', NULL, '(91) 98523-8588', NULL, 'Rua L', '91985238588', NULL, 'Atalaia', 'Ananindeua', 'PA', NULL, '2026-02-28 11:36:23', '2026-02-28 11:36:23'),
(13, 'Jailson Hugo Costa', '711.204.282-89', '2012-11-19', NULL, '(91) 98437-2929', NULL, 'Rua Santa Odilia', '535 C', NULL, 'Jardelandia I', 'Belem', 'PA', NULL, '2026-02-28 11:57:26', '2026-02-28 11:57:26'),
(14, 'Direne De Cassia Costa', '957.364.252-20', '1986-01-15', NULL, '(91) 98268-8783', NULL, 'Rua Santa Odilia', '535 C', NULL, 'Jardelandia I', 'BELEM', 'PA', NULL, '2026-02-28 11:59:55', '2026-02-28 11:59:55'),
(15, 'Inácio Martonio Bezerra de Souza', NULL, '2026-02-28', NULL, '(91) 82878-9986', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-28 13:48:11', '2026-02-28 13:48:11'),
(16, 'Rayane cristina veloso', NULL, NULL, NULL, '(91) 99830-4492', NULL, 'conjunto jaderlandia rua F ', '55', NULL, NULL, 'Belem', 'pa', NULL, '2026-03-02 12:51:51', '2026-03-02 12:51:51'),
(17, 'Monique Rayane Quaresma Silva', NULL, '2008-03-09', NULL, '(91) 98276-3732', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-02 13:30:02', '2026-03-02 13:30:02'),
(18, 'Vanderlucia Silva Pereira', '440.625.592-34', NULL, NULL, '(91) 98025-7037', NULL, 'Rua São Raimundo', '130', NULL, 'Francisquinho', 'Ananindeua', 'PA', NULL, '2026-03-02 14:05:31', '2026-03-02 14:05:31'),
(19, 'Manoela Sousa de Sousa ', NULL, '2015-10-15', NULL, '(91) 99292-7262', NULL, 'Rua F', '32', NULL, 'Jardelandia I', 'BELEM', 'PA', NULL, '2026-03-02 18:16:53', '2026-03-02 18:38:43'),
(21, 'Rejane Reiguem Meireles De Souza', '931.798.392-87', NULL, NULL, '(91) 3117-1211', NULL, 'Rua F', '32', NULL, 'Jardelandia I', 'BELEM', 'PA', NULL, '2026-03-02 18:41:43', '2026-03-02 18:41:43'),
(23, 'Cláudio Soares de Moraes', NULL, NULL, NULL, '(91) 98863-6341', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-02 20:03:57', '2026-03-02 20:03:57'),
(24, 'Jonas De Assis Monteiro', '798.794.622-20', '1982-02-13', NULL, '(91) 98028-0547', NULL, 'Alameda Rodrigues', '20', NULL, 'Coqueiro', 'Ananindeua', 'PA', NULL, '2026-03-02 20:21:04', '2026-03-02 20:21:04'),
(25, 'Ronald Bezerra Da Silva', '030.466.542-86', '1994-11-24', NULL, '(91) 98203-1064', NULL, 'Rua Macajas ', '88', NULL, NULL, NULL, NULL, NULL, '2026-03-03 12:27:11', '2026-03-03 12:27:11'),
(28, 'Gisele Moises Da Silva', '046.824.262-73', '2002-07-02', NULL, '(91) 98632-3507', NULL, 'Rua B', '17', NULL, 'Jardelandia I', 'BELEM', 'PA', NULL, '2026-03-03 12:31:50', '2026-03-03 12:31:50'),
(29, 'Maria Eduarda Cavalcante', '060.023.252-20', '2001-03-08', NULL, '(91) 98525-0880', NULL, 'Rua L', '98', NULL, NULL, NULL, NULL, NULL, '2026-03-03 13:36:43', '2026-03-03 13:36:43'),
(30, 'Valdecir Brasil', NULL, NULL, NULL, '(91) 98141-1934', NULL, 'Terra Santa Passagem F', '16', NULL, NULL, NULL, NULL, NULL, '2026-03-03 14:28:46', '2026-03-03 18:13:16'),
(31, 'Raj Yago Chaves', NULL, '2010-03-03', NULL, '(91) 98726-6720', NULL, 'Passagem Baiana', '50', NULL, NULL, NULL, NULL, NULL, '2026-03-03 20:15:25', '2026-03-03 20:15:25'),
(32, 'Adriana Cardoso Da Silva', '767.980.542-34', NULL, NULL, '(91) 98929-4850', NULL, 'Rua São Raimundo ', '52', NULL, 'Atalaia', 'Ananindeua', 'PA', NULL, '2026-03-04 11:54:00', '2026-03-04 11:54:00'),
(33, 'Jaqueline Samara Da Silva Menezes', NULL, NULL, NULL, '(91) 98248-5970', NULL, 'Rua C', '147', NULL, 'Jardelandia I', 'BELEM', 'PA', NULL, '2026-03-04 13:15:00', '2026-03-04 13:15:00'),
(34, 'Monica Ferreira', '884.710.982-53', '1983-03-21', NULL, '(91) 98985-0516', NULL, 'Rua Lurias', '2 C', NULL, 'Jardelandia I', 'BELEM', 'PA', NULL, '2026-03-04 13:28:29', '2026-03-04 13:31:40'),
(36, 'Roseane monteiro ', NULL, NULL, NULL, '(91) 98854-0097', NULL, 'conjunto jaderlandia  rua F', '118', NULL, NULL, 'belem', 'pa', NULL, '2026-03-04 18:09:05', '2026-03-06 20:26:02'),
(37, 'Tavisson Freitas Sena', NULL, NULL, NULL, '(91) 98183-2269', NULL, 'Alameda União ', '02', NULL, NULL, NULL, NULL, NULL, '2026-03-04 20:05:35', '2026-03-04 20:05:35'),
(38, 'Jose Henrique', NULL, '2017-08-02', NULL, '(91) 98062-6582', NULL, 'Rua N ', '49', NULL, 'Jardelandia I', NULL, NULL, NULL, '2026-03-05 13:26:10', '2026-03-05 13:26:10'),
(39, 'Elizangela Neri Da Silva', NULL, '1981-12-15', NULL, '(91) 99273-6601', NULL, 'Rua F', '30 C', NULL, 'Jardelandia I', 'BELEM', 'PA', NULL, '2026-03-06 15:50:34', '2026-03-06 15:50:34'),
(40, 'Paulo Pinto', NULL, '1967-06-22', NULL, '(91) 98133-8287', NULL, 'Rua São Clemente ', '991', NULL, 'Atalaia', 'Ananindeua', 'PA', NULL, '2026-03-06 19:14:50', '2026-03-06 19:14:50'),
(41, 'Diogo Tavares Silva', NULL, NULL, NULL, '(91) 99636-6137', NULL, 'Rua Providencia Q19 Rua 13', '192', NULL, NULL, NULL, NULL, NULL, '2026-03-06 19:46:39', '2026-03-06 19:46:39'),
(42, 'Heloisa Ferreira Matos', NULL, '2018-03-26', NULL, '(91) 98223-6705', NULL, 'Rua B', '46', NULL, 'Jardelandia II', NULL, NULL, NULL, '2026-03-07 14:05:00', '2026-03-07 14:05:00'),
(43, 'Irene Correia', NULL, '1968-10-02', NULL, '(91) 98151-2612', NULL, 'Rua I quadra 15', '49 A', NULL, 'Jardelandia I', NULL, NULL, NULL, '2026-03-07 14:16:46', '2026-03-07 14:16:46'),
(44, 'Elvis cirino ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Belém ', 'Pá', NULL, '2026-03-07 17:51:58', '2026-03-07 17:51:58'),
(45, 'FABRICIO DE SOUZA FARIAS', NULL, NULL, 'fabriciosouzafarias@gmail.com', '(91) 99299-2812', '66025-540', NULL, NULL, NULL, NULL, 'Belém', 'PA', NULL, '2026-03-07 19:34:48', '2026-03-07 19:34:48');

-- --------------------------------------------------------

--
-- Estrutura para tabela `procedimentos`
--

DROP TABLE IF EXISTS `procedimentos`;
CREATE TABLE IF NOT EXISTS `procedimentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` enum('geral','especializado','protese') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `valor_base` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `procedimentos`
--

INSERT INTO `procedimentos` (`id`, `nome`, `categoria`, `tipo`, `valor_base`) VALUES
(1, 'Radiografia (Uso no Consultório)', 'geral', NULL, 40.00),
(2, 'Radiografia (Para Levar)', 'geral', NULL, 50.00),
(3, 'Limpeza - Adulto', 'geral', NULL, 150.00),
(4, 'Limpeza - Criança', 'geral', NULL, 80.00),
(5, 'Restauração - Adulto', 'geral', NULL, 90.00),
(6, 'Restauração Estética', 'geral', NULL, 150.00),
(7, 'Restauração - Criança', 'geral', NULL, 90.00),
(8, 'Extração - Adulto (Simples)', 'geral', NULL, 150.00),
(9, 'Extração - Adulto (Complexa)', 'geral', NULL, 200.00),
(10, 'Extração - Criança', 'geral', NULL, 80.00),
(11, 'Clareamento', 'geral', NULL, 250.00),
(12, 'PPR (Prótese Parcial Removível)', 'protese', NULL, 740.00),
(13, 'P.T. (Prótese Total)', 'protese', NULL, 630.00),
(14, 'Prótese Flexível', 'protese', NULL, 750.00),
(15, 'Ponte Móvel', 'protese', NULL, 590.00),
(16, 'Endodontia - Incisivo', 'especializado', NULL, 650.00),
(17, 'Endodontia - Pré e Canino', 'especializado', NULL, 750.00),
(18, 'Endodontia - Molar', 'especializado', NULL, 800.00),
(19, 'Pino + Reconstrução', 'geral', NULL, 380.00),
(20, 'Restauração Pós Canal - Molar', 'geral', NULL, 150.00),
(21, 'Restauração Pós Canal - Outros', 'geral', NULL, 120.00),
(22, 'Bloco Direto', 'geral', NULL, 280.00),
(23, 'Implante', 'especializado', NULL, 2100.00),
(24, 'Coroa p/ Implante', 'protese', NULL, 1800.00),
(25, 'Protocolo', 'protese', NULL, 18000.00),
(26, 'Facetas - Unidade', 'especializado', NULL, 300.00),
(27, 'Facetas - Pré a Pré', 'especializado', NULL, 6000.00),
(28, 'Remoção de Aparelho', 'geral', NULL, 180.00),
(29, 'Aparelho Montagem', 'especializado', NULL, 80.00),
(30, 'Aparelho Montagem + Limpeza', 'especializado', NULL, 180.00),
(31, 'Contenção - Superior', 'especializado', NULL, 250.00),
(32, 'Contenção - Inferior', 'especializado', NULL, 150.00),
(33, 'Aparelho Estético Montagem', 'especializado', NULL, 600.00),
(34, 'Aparelho Autoligado', 'especializado', NULL, 800.00),
(35, 'Placa de Bruxismo', 'especializado', NULL, 250.00),
(36, 'Aumento de Coroa', 'especializado', NULL, 200.00),
(37, 'Clareamento Interno (Dente Unitário)', 'geral', NULL, 150.00),
(38, 'Coroa de Cerômero', 'protese', NULL, 450.00),
(39, 'Cimentação de Coroa', 'protese', NULL, 90.00),
(40, 'Extração de Siso Superior', 'geral', NULL, 300.00),
(41, 'Extração de Siso Inferior', 'geral', NULL, 400.00),
(42, 'Manutenção Aparelho', 'especializado', NULL, 80.00),
(43, 'Manutenção Aparelho Avulsa', 'especializado', NULL, 100.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `perfil` enum('proprietario','recepcionista','dentista') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `login`, `senha`, `perfil`, `criado_em`) VALUES
(1, 'Administrador', 'admin', '$2y$10$OCk/tInybfNZV2Gjzo5Lmu2If7WIoAw52pJD8h3sS5NDF7KcPNWuu', 'proprietario', '2026-02-01 01:59:59'),
(2, 'Dr. Luciana Farias', 'luciana', '$2y$10$OCk/tInybfNZV2Gjzo5Lmu2If7WIoAw52pJD8h3sS5NDF7KcPNWuu', 'dentista', '2026-02-01 01:59:59'),
(3, 'Dra. Vitoria Lobato', 'vitoria', '$2y$10$KdryAvgBPHXrWSaXPjPNx.E83rTy5qPIZcgTyt.jQT9mjEMHrzHt6', 'dentista', '2026-02-01 01:59:59'),
(4, 'Aline', 'aline', '$2y$10$MlM1fUQG7dQrv6NBbZi.ZO9./DWj9XZJDNt4VpPntqS6Y6qhNFOpC', 'recepcionista', '2026-02-01 02:18:40'),
(12, 'Luciana Farias', 'admin2', '$2y$10$9aXFaAoUXEeS9L8Pc/gGXOz9YdBDsUFSP06cxyWIw2wHyXqq5Lc2W', 'proprietario', '2026-02-26 13:41:33');

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `atendimentos`
--
ALTER TABLE `atendimentos`
  ADD CONSTRAINT `atendimentos_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Restrições para tabelas `atendimento_pagamentos`
--
ALTER TABLE `atendimento_pagamentos`
  ADD CONSTRAINT `atendimento_pagamentos_ibfk_1` FOREIGN KEY (`id_atendimento`) REFERENCES `atendimentos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
