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
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_lrn` text NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `birthday` varchar(32) NOT NULL,
  `present_house_no` varchar(255) NOT NULL,
  `present_brgy` varchar(255) NOT NULL,
  `present_town_city` varchar(255) NOT NULL,
  `present_province` varchar(255) NOT NULL,
  `perm_house_no` varchar(255) NOT NULL,
  `perm_brgy` varchar(255) NOT NULL,
  `perm_townCity` varchar(255) NOT NULL,
  `perm_province` varchar(255) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `father_educ` varchar(255) NOT NULL,
  `father_occu` varchar(255) NOT NULL,
  `father_contact` varchar(255) NOT NULL,
  `mother_name` varchar(255) NOT NULL,
  `mother_educ` varchar(255) NOT NULL,
  `mother_occu` varchar(255) NOT NULL,
  `mother_contact` varchar(255) NOT NULL,
  `no_siblings` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birth_order` varchar(255) NOT NULL,
  `living_with` varchar(255) NOT NULL,
  `guardian_name` varchar(255) NOT NULL,
  `guardian_relation` varchar(255) NOT NULL,
  `guardian_address` varchar(255) NOT NULL,
  `total_fam_income` varchar(255) NOT NULL,
  `hand_type` varchar(255) NOT NULL,
  `vision_type` varchar(255) NOT NULL,
  `hearing_condition` varchar(255) NOT NULL,
  `speech_condition` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `grade_level` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_lrn`, `firstname`, `middlename`, `lastname`, `birthday`, `present_house_no`, `present_brgy`, `present_town_city`, `present_province`, `perm_house_no`, `perm_brgy`, `perm_townCity`, `perm_province`, `father_name`, `father_educ`, `father_occu`, `father_contact`, `mother_name`, `mother_educ`, `mother_occu`, `mother_contact`, `no_siblings`, `gender`, `birth_order`, `living_with`, `guardian_name`, `guardian_relation`, `guardian_address`, `total_fam_income`, `hand_type`, `vision_type`, `hearing_condition`, `speech_condition`, `password`, `grade_level`) VALUES
(1, '300368-22-0001', 'Marcelo', 'Policarpio', 'Osmena', '1996-12-29', '175', 'Pantal', 'Dagupan City', 'Pangasinan', '175', 'Pantal', 'Dagupan City', 'Pangasinan', 'Marco', 'High School Graduate', 'fisherman', 'N/A', 'Mylene', 'College Graduate', 'vendor', '00001', '5', 'Girl', '4th', 'Mother', '', '', '', '5,001-10,000', 'Right', 'Uses Reading Glasses', 'Normal', 'Normal', 'Osmena', '2'),
(2, '300368-22-0002', 'Denver', 'Ignacio', 'Bustamante', '9/23/2002', '233', 'Bolosan', 'Dagupan City', 'Pangasinan', '233', 'Bolosan', 'Dagupan City', 'Pangasinan', 'Toni', 'College Graduate', 'construction worker', 'N/A', 'Luogee', 'High School Graduate', 'clerk', '00002', '4', 'Boy', '3rd', 'Relative', 'Lee Min Ho', 'Grandfather', 'SoKor', '5,001-10,000', 'Left', 'Far-Sighted', 'Normal', 'Normal', 'Bustamante', '4'),
(3, '300368-22-0003', 'Albert', 'David', 'Carpioa', '8/16/2002', '178', 'Lucao', 'Dagupan City', 'Pangasinan', '178', 'Lucao', 'Dagupan City', 'Pangasinan', 'Anton Carpio', 'High School Graduate', 'N/A', '0020', 'Maysh David', 'College Graduate', 'cleaner', '00003', '5', 'Boy', 'Eldest', 'Mother', '', '', '', '1,000 and below', 'Right', 'Normal', 'Normal', 'Normal', 'Carpio', '2'),
(4, '300368-22-0004', 'Grace', 'Lomibao', 'Alonzo', '8/15/2003', '001', 'Pugaro', 'Dagupan City', 'Pangasinan', '001', 'Pugaro', 'Dagupan City', 'Pangasinan', 'Leonardo', 'High School Graduate', 'farmer', '0011', 'Marites', 'High School Graduate', 'labandera', '00004', '3', 'Girl', 'Eldest', 'Parents', '', '', '', '1,000 and below', 'Ambidextrous', 'Uses Reading Glasses', 'Normal', 'Normal', 'Alonzo', '2'),
(5, '300368-22-0005', 'Katherine', 'Villanueva', 'De Guzman', '2003-08-19', '024', 'Mangin', 'Dagupan City', 'Pangasinan', '024', 'Mangin', 'Dagupan City', 'Pangasinan', 'Armando', 'High School Graduate', 'fisherman', '0014', 'Alexa', 'College Undergraduate', 'programmer', '00005', '5', 'Girl', 'Youngest', 'Relative', '', '', '', '20,001 and above', 'Foot', 'Uses Reading Glasses', 'Tinnitus', 'Slurred', 'De Guzman', '1'),
(6, '300368-22-0006', 'Yvone', 'Cortes', 'Lagdaneo', '4/2/2004', '012', 'Bonuan Binloc', 'Dagupan City', 'Pangasinan', '012', 'Bonuan Binloc', 'Dagupan City', 'Pangasinan', 'Macario', 'College Graduate', 'driver', '0019', 'Tanya', 'College Undergraduate', 'N/A', '00006', '3', 'Girl', 'Eldest', 'Father', '', '', '', '5,001-10,000', 'Right', 'Uses Reading Glasses', 'Normal', 'Normal', 'Lagdaneo', '2'),
(7, '300368-22-0007', 'Tiburcia', 'Yamashida', 'Wisconsin', '8/4/2003', '465', 'Tebeng', 'Dagupan City', 'Pangasinan', '465', 'Tebeng', 'Dagupan City', 'Pangasinan', 'Kael', 'College Graduate', 'fisherman', 'N/A', 'Katleen', 'High School Graduate', 'vendor', '00007', '2', 'Girl', '2nd', 'Others', 'Hilary Clinton', 'Auntie', 'Pelepens', '1,000 and below', 'Left', 'Normal', 'Normal', 'Normal', 'Wisconsin', '1'),
(8, '300368-22-0008', 'Gilbert', 'Aquino', 'Wilson', '8/13/2004', '331', 'Calmay', 'Dagupan City', 'Pangasinan', '331', 'Calmay', 'Dagupan City', 'Pangasinan', '', 'High School Graduate', 'driver', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Wilson', '4'),
(9, '300368-22-0009', 'Teodoro', 'Pimentel', 'Eden', '12/27/2003', '246', 'Lasip Grande', 'Dagupan City', 'Pangasinan', '246', 'Lasip Grande', 'Dagupan City', 'Pangasinan', '', 'High School Graduate', 'vendor', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Eden', '3'),
(10, '300368-22-0010', 'Sebastian', 'Ferrer', 'De Vera', '10/16/2003', '187', 'Bolosan', 'Dagupan City', 'Pangasinan', '187', 'Bolosan', 'Dagupan City', 'Pangasinan', '', 'High School Graduate', 'vendor', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'De Vera', '2'),
(11, '300368-22-0011', 'Ivan', 'Morales', 'Ferrer', '2/24/2003', '027', 'Mamalingling', 'Dagupan City', 'Pangasinan', '027', 'Mamalingling', 'Dagupan City', 'Pangasinan', '', 'High School Graduate', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Ferrer', '1'),
(12, '300368-22-0012', 'Aaron', 'Merrera', 'Idio', '4/25/2004', '460', 'Pantal', 'Dagupan City', 'Pangasinan', '460', 'Pantal', 'Dagupan City', 'Pangasinan', '', 'College Graduate', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Idio', '2'),
(13, '300368-22-0013', 'Ykaterina', 'Edinburg', 'Kavkaz', '12/10/2003', '512', 'Lasip Grande', 'Dagupan City', 'Pangasinan', '512', 'Lasip Grande', 'Dagupan City', 'Pangasinan', '', 'College Undergraduate', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Kavkaz', '1'),
(14, '300368-22-0014', 'Teodoro', 'Pimentel', 'Eden', '9/15/2003', '636', 'Lucao', 'Dagupan City', 'Pangasinan', '636', 'Lucao', 'Dagupan City', 'Pangasinan', '', 'High School Graduate', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Eden', '4'),
(15, '300368-22-0015', 'Sebastian', 'Ferrer', 'De Vera', '9/25/2004', '212', 'Salisay', 'Dagupan City', 'Pangasinan', '212', 'Salisay', 'Dagupan City', 'Pangasinan', '', 'High School Graduate', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'De Vera', '2'),
(16, '300368-22-0016', 'Ivan', 'Morales', 'Ferrer', '12/11/2003', '108', 'Pantal', 'Dagupan City', 'Pangasinan', '108', 'Pantal', 'Dagupan City', 'Pangasinan', '', 'College Undergraduate', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Ferrer', '4'),
(17, '300368-22-0017', 'Aaron', 'Merrera', 'Idio', '7/16/2002', '007', 'Mamalingling', 'Dagupan City', 'Pangasinan', '007', 'Mamalingling', 'Dagupan City', 'Pangasinan', '', 'College Undergraduate', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Idio', '2'),
(18, '300368-22-0018', 'Ykaterina', 'Edinburg', 'Kavkaz', '12/11/2003', '435', 'Malued', 'Dagupan City', 'Pangasinan', '435', 'Malued', 'Dagupan City', 'Pangasinan', '', 'College Undergraduate', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Kavkaz', '3'),
(19, '300368-22-0019', 'Kenneth', 'Policarpio', 'Osmena', '10/16/2003', '890', 'Caranglaan', 'Dagupan City', 'Pangasinan', '890', 'Caranglaan', 'Dagupan City', 'Pangasinan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Osmena', '2'),
(20, '300368-22-0020', 'Ahr Jhay', 'Bautista', 'Purisima', '12/7/2005', '005', 'Tambac', 'Dagupan City', 'Pangasinan', '005', 'Tambac', 'Dagupan City', 'Pangasinan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Purisima', '2'),
(21, '300368-22-0021', 'Reynard', 'Lambino', 'Ocampo', '11/21/2002', '046', 'Bonuan Boquig', 'Dagupan City', 'Pangasinan', '046', 'Bonuan Boquig', 'Dagupan City', 'Pangasinan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Ocampo', '2'),
(22, '300368-22-0022', 'Ralph', 'Tira', 'Gador', '11/20/2003', '032', 'Pantal', 'Dagupan City', 'Pangasinan', '032', 'Pantal', 'Dagupan City', 'Pangasinan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Gador', '4'),
(23, '300368-22-0023', 'Anneth', 'Morales', 'Ghost', '6/6/2004', '100', 'Poblacion Oeste', 'Dagupan City', 'Pangasinan', '100', 'Poblacion Oeste', 'Dagupan City', 'Pangasinan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Ghost', '1'),
(24, '300368-22-0024', 'Drake', 'Santiago', 'Costes', '5/1/2004', '145', 'Bonuan Gueset', 'Dagupan City', 'Pangasinan', '145', 'Bonuan Gueset', 'Dagupan City', 'Pangasinan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Costes', '3'),
(25, '300368-22-0025', 'Cosme', 'Gamboa', 'Kamatayan', '12/13/2004', '458', 'Bonuan Boquig', 'Dagupan City', 'Pangasinan', '458', 'Bonuan Boquig', 'Dagupan City', 'Pangasinan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Kamatayan', '1'),
(26, '300368-22-0026', 'Cherisse', 'Liones', 'Apolinario', '8/6/2006', '369', 'Malued', 'Dagupan City', 'Pangasinan', '369', 'Malued', 'Dagupan City', 'Pangasinan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Apolinario', '2'),
(27, '300368-22-0027', 'Layla', 'Lambino', 'Mabini', '6/18/2002', '363', 'Pogo Chico', 'Dagupan City', 'Pangasinan', '363', 'Pogo Chico', 'Dagupan City', 'Pangasinan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Mabini', '3'),
(28, '300368-22-0028', 'Trish', 'Tira', 'Rizal', '1/25/2004', '498', 'Herrero-Perez', 'Dagupan City', 'Pangasinan', '498', 'Herrero-Perez', 'Dagupan City', 'Pangasinan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Rizal', '4'),
(29, '300368-22-0029', 'Kathleen', 'Morales', 'Bonifacio', '10/22/2006', '531', 'Tambac', 'Dagupan City', 'Pangasinan', '531', 'Tambac', 'Dagupan City', 'Pangasinan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Bonifacio', '2'),
(30, '300368-22-0030', 'Dexter', 'Santiago', 'Aquinaldo', '11/9/2003', '078', 'Lasip Grande', 'Dagupan City', 'Pangasinan', '078', 'Lasip Grande', 'Dagupan City', 'Pangasinan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Aquinaldo', '2'),
(31, '300368-22-0031', 'James', 'Gamboa', 'Marcos', '9/16/2003', '094', 'Mayombo', 'Dagupan City', 'Pangasinan', '094', 'Mayombo', 'Dagupan City', 'Pangasinan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Marcos', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--
