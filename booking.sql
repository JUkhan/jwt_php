-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2015 at 08:25 AM
-- Server version: 5.6.24
-- PHP Version: 5.5.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `booking`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addCustomer`(IN `name` VARCHAR(70), IN `email` VARCHAR(70), IN `phone` VARCHAR(12), IN `mobile` VARCHAR(12))
    NO SQL
INSERT into customer(customer.name, customer.email, customer.phone, customer.mobile) VALUES(name, email, phone, mobile)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteCustomer`(IN `cid` INT)
    NO SQL
DELETE from customer WHERE customer.id=cid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getCustomers`()
BEGIN
    SELECT * FROM customer ORDER BY name; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateCustomer`(IN `name` VARCHAR(50), IN `email` VARCHAR(50), IN `phone` VARCHAR(12), IN `mobile` VARCHAR(12), IN `id` INT)
    NO SQL
UPDATE customer

set customer.name=name, customer.email=email, customer.phone=phone,
customer.mobile=mobile

WHERE customer.id=id$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `name` varchar(70) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `email`, `phone`, `mobile`, `name`) VALUES
(6, 'Abdulla@gmail.com', '123', '321', 'Abdulla'),
(7, 'jamal@gmail.com', '111', '2222', 'jamal'),
(11, 'qw@gmail.com', '9334567', '01913095519', 'Nusrat up');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
