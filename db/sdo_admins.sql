-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2022 at 05:02 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `deped`
--

-- --------------------------------------------------------

--
-- Table structure for table `sdo_admins`
--

CREATE TABLE `sdo_admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sdo_admins`
--

INSERT INTO `sdo_admins` (`id`, `username`, `firstname`, `middlename`, `lastname`, `password`) VALUES
(1, 'popoy', 'Prospero', 'A', 'De Vera', 'dv'),
(2, 'ling', 'Leonor', 'M', 'Briones', 'old'),
(3, 'ariel', 'Ariel', 'A', 'Almonte', 'cite'),
(4, 'arn', 'Arnold', 'C', 'Centino', 'nstp'),
(5, 'pope', 'Francis', 'M', 'Bergoglio', 'jorge'),
(6, 'tom', 'Thomas', 'Cruise', 'Mapother', 'mi7');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sdo_admins`
--
ALTER TABLE `sdo_admins`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sdo_admins`
--
ALTER TABLE `sdo_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;