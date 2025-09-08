-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28-Ago-2025 às 16:00
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `conecta_bairro`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `admin_activity_logs`
--

CREATE TABLE `admin_activity_logs` (
  `id` int(11) NOT NULL,
  `admin_user_id` int(11) DEFAULT NULL,
  `admin_user_name` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `target_type` varchar(100) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `auth_level` enum('super','admin','editor') NOT NULL DEFAULT 'editor',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `admin_users`
--

INSERT INTO `admin_users` (`id`, `name`, `username`, `password`, `auth_level`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrador', 'admin', '$argon2i$v=19$m=65536,t=4,p=1$a0J1Y2NjSVBTSkljVWJDYg$neGo8NbNtbA5aAzyyS73Tevu5bX7OzeG5by0Z3KGZdc', 'super', '2025-08-22 15:55:25', '2025-08-22 15:55:25', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `collection_points`
--

CREATE TABLE `collection_points` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `number` varchar(50) DEFAULT NULL,
  `neighborhood` varchar(150) NOT NULL,
  `city` varchar(150) NOT NULL,
  `state` varchar(50) NOT NULL,
  `accepted_materials` text NOT NULL,
  `category` enum('Geral','Eletrônicos','Óleo','Pilhas e Baterias') NOT NULL,
  `Maps_link` varchar(2047) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `collection_points`
--

INSERT INTO `collection_points` (`id`, `name`, `street`, `number`, `neighborhood`, `city`, `state`, `accepted_materials`, `category`, `Maps_link`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Cooperita - Coop. de Reciclagem de Itapetininga', 'Rua Orlando Scotto', '68', 'Vila Prado', 'Itapetininga', 'SP', 'Papel, Alumínio, Plástico, Vidro', 'Geral', 'http://googleusercontent.com/maps.google.com/8Cooperita+-+Coop.+de+Reciclagem+de+Itapetininga%2C+Rua+Orlando+Scotto%2C+68%2C+Vila+Prado%2C+Itapetininga%2C+SP', '2025-08-25 18:28:05', '2025-08-28 16:00:00', NULL);


-- --------------------------------------------------------

--
-- Estrutura da tabela `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(1023) NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(1023) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `condition` enum('Novo','Seminovo','Usado') NOT NULL,
  `status` enum('Disponível','Reservado','Doado') NOT NULL DEFAULT 'Disponível',
  `neighborhood` varchar(255) NOT NULL COMMENT 'Bairro para retirada',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `donations`
--

INSERT INTO `donations` (`id`, `user_id`, `category_id`, `title`, `description`, `slug`, `image_url`, `condition`, `status`, `neighborhood`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'Sofá Retrátil 3 Lugares', 'Sofá em bom estado de conservação, tecido suede cinza. A parte retrátil está funcionando perfeitamente. Ideal para sala de estar. Motivo da doação: mudança.', 'sofa-retratil-3-lugares', 'media/donations/sofa-doacao.png', 'Usado', 'Disponível', 'Vila Aparecida', '2025-08-22 13:00:00', '2025-08-25 15:51:59', NULL),
(2, 1, 3, 'Monitor de Computador LG 19 Polegadas', 'Monitor LCD funcionando perfeitamente, sem nenhum pixel queimado. Acompanha cabo de força e cabo VGA. Ótimo para estudos ou como segunda tela.', 'monitor-de-computador-lg-19-polegadas', 'media/donations/monitor-doacao.png', 'Usado', 'Reservado', 'Centro', '2025-08-25 12:30:00', '2025-08-25 15:16:10', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `donation_categories`
--

CREATE TABLE `donation_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(511) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `donation_categories`
--

INSERT INTO `donation_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Móveis', 'moveis', '2025-08-25 14:36:11', '2025-08-25 14:36:11', NULL),
(3, 'Eletrônicos', 'eletronicos', '2025-08-25 15:52:37', '2025-08-25 15:52:37', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `site_users`
--

CREATE TABLE `site_users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `site_users`
--

INSERT INTO `site_users` (`id`, `full_name`, `email`, `password`, `phone_number`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Márcio Jose', 'marcio@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$VE5BWFhnZE1xd0xuOEN5Rw$CmoLjCaX9I/1Gx+5nLsc596ZHEas/PeYjtSLu0tar0s', '15991234567', '2025-08-25 14:36:11', '2025-08-27 18:13:27', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_user_id` (`admin_user_id`);

--
-- Índices para tabela `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Índices para tabela `collection_points`
--
ALTER TABLE `collection_points`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Índices para tabela `donation_categories`
--
ALTER TABLE `donation_categories`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `site_users`
--
ALTER TABLE `site_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `collection_points`
--
ALTER TABLE `collection_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `donation_categories`
--
ALTER TABLE `donation_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `site_users`
--
ALTER TABLE `site_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  ADD CONSTRAINT `admin_activity_logs_ibfk_1` FOREIGN KEY (`admin_user_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `site_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `donation_categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;