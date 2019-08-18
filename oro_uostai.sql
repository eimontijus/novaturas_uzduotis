-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2019 at 07:26 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id10537123_oro_uostai`
--

-- --------------------------------------------------------

--
-- Table structure for table `avialinija`
--

CREATE TABLE `avialinija` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(64) NOT NULL,
  `salis_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orouostas`
--

CREATE TABLE `orouostas` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(64) NOT NULL,
  `salis` int(11) NOT NULL,
  `ilguma` double NOT NULL,
  `platuma` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `salis`
--

CREATE TABLE `salis` (
  `id` int(11) NOT NULL,
  `iso` varchar(5) NOT NULL,
  `pavadinimas` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salis`
--

INSERT INTO `salis` (`id`, `iso`, `pavadinimas`) VALUES
(1, 'AFG', 'Afganistanas'),
(2, 'AGO', 'Angola'),
(3, 'BEL', 'Belgija'),
(4, 'BTN', 'Butanas'),
(5, 'LTU', 'Lietuva'),
(6, 'POL', 'Lenkija'),
(7, 'IND', 'Indija'),
(8, 'FRA', 'Prancuzija'),
(9, 'BRA', 'Brazilija');

-- --------------------------------------------------------

--
-- Table structure for table `susieti`
--

CREATE TABLE `susieti` (
  `id` int(11) NOT NULL,
  `oro_uostas_id` int(11) NOT NULL,
  `avialinija_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avialinija`
--
ALTER TABLE `avialinija`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salis_id` (`salis_id`);

--
-- Indexes for table `orouostas`
--
ALTER TABLE `orouostas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salis` (`salis`);

--
-- Indexes for table `salis`
--
ALTER TABLE `salis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `susieti`
--
ALTER TABLE `susieti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oro_uostas_id` (`oro_uostas_id`),
  ADD KEY `avialinija_id` (`avialinija_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `avialinija`
--
ALTER TABLE `avialinija`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orouostas`
--
ALTER TABLE `orouostas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `salis`
--
ALTER TABLE `salis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `susieti`
--
ALTER TABLE `susieti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `avialinija`
--
ALTER TABLE `avialinija`
  ADD CONSTRAINT `avialinija_ibfk_1` FOREIGN KEY (`salis_id`) REFERENCES `salis` (`id`);

--
-- Constraints for table `orouostas`
--
ALTER TABLE `orouostas`
  ADD CONSTRAINT `orouostas_ibfk_1` FOREIGN KEY (`salis`) REFERENCES `salis` (`id`);

--
-- Constraints for table `susieti`
--
ALTER TABLE `susieti`
  ADD CONSTRAINT `susieti_ibfk_1` FOREIGN KEY (`avialinija_id`) REFERENCES `avialinija` (`id`),
  ADD CONSTRAINT `susieti_ibfk_2` FOREIGN KEY (`oro_uostas_id`) REFERENCES `orouostas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
