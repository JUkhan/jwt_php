-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2015 at 06:42 PM
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_user`()
    NO SQL
SELECT u.email, u.firstName, u.lastName,
u.joinDate, u.phoneNumber,u.userName, u.id
   FROM `jwt_user` as u$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_user_roles`()
    NO SQL
select u.userId, u.roleId, (select name from jwt_roles where u.roleId=jwt_roles.id) as role from jwt_user_roles as u$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_roles_by_userId`(IN `id` INT)
    NO SQL
select jwt_user_roles.roleId, (select jwt_roles.name FROM jwt_roles WHERE jwt_roles.id=jwt_user_roles.roleId) as role from jwt_user_roles WHERE jwt_user_roles.userId=id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_permission`(IN `id` INT)
    NO SQL
SELECT * from widgetviewrights WHERE 
(widgetviewrights.roleId in(SELECT jwt_user_roles.roleId FROM jwt_user_roles WHERE jwt_user_roles.userId=id)) or(widgetviewrights.userId=id)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_widget_permission`(IN `id` INT, IN `wname` VARCHAR(50))
    NO SQL
SELECT * from widgetviewrights WHERE 
(widgetviewrights.roleId in(SELECT jwt_user_roles.roleId FROM jwt_user_roles WHERE jwt_user_roles.userId=id) and widgetviewrights.widgetName=wname) or(widgetviewrights.widgetName=wname and widgetviewrights.userId=id)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `remove_role_from_user`(IN `userId` INT, IN `roleId` INT)
    NO SQL
DELETE FROM jwt_user_roles WHERE jwt_user_roles.roleId=roleId and jwt_user_roles.userId=userId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `remove_temp_boking`()
    NO SQL
delete from hall_booking where  ( CURRENT_DATE - bdate)>3 and btype='Temporary'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `remove_user`(IN `userId` INT)
    NO SQL
DELETE FROM jwt_user WHERE jwt_user.id=userId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addCustomer`(IN `name` VARCHAR(120), IN `email` VARCHAR(70), IN `phone` VARCHAR(12), IN `mobile` VARCHAR(12), IN `address` VARCHAR(200), IN `alt_num` VARCHAR(12), IN `ref` VARCHAR(120), IN `member` VARCHAR(10), IN `member_code` VARCHAR(255))
    NO SQL
INSERT into customer(customer.name, customer.email, customer.phone, customer.mobile, customer.address, customer.alt_number, customer.reference, customer.is_member, customer.member_code) VALUES(name, email, phone, mobile, address, alt_num, ref, member, member_code)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_add_booking`(IN `cid` VARCHAR(15), IN `hname` VARCHAR(100), IN `shift` VARCHAR(50), IN `ftype` VARCHAR(150), IN `bdate` DATE)
    NO SQL
INSERT INTO hall_booking(
    hall_booking.cid, 
    hall_booking.hall_name,
    hall_booking.shift,
    hall_booking.ftype,
    hall_booking.bdate
) VALUES(
    cid, hname, shift,ftype, bdate)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_add_hall`(IN `name` VARCHAR(50), IN `capacity` VARCHAR(50), IN `kcharge` FLOAT, IN `s1mr` FLOAT, IN `s1ms` FLOAT, IN `s1nmr` FLOAT, IN `s1nms` FLOAT, IN `s2mr` FLOAT, IN `s2ms` FLOAT, IN `s2nmr` FLOAT, IN `s2nms` FLOAT, IN `s1time` VARCHAR(11), IN `s2time` VARCHAR(11))
    NO SQL
INSERT INTO hall(
    hall.name,
    hall.capacity,
    hall.kitchen_charge,
    hall.s1_m_rent,
    hall.s1_m_sequrity,
    hall.s1_nm_rent,
    hall.s1_nm_security,
    hall.s1_time,
    hall.s2_m_rent,
    hall.s2_m_security,
    hall.s2_nm_rent,
    hall.s2_nm_security,
    hall.s2_time
) VALUES(
    name,
    capacity,
    kcharge,
    s1mr,
    s1ms,
    s1nmr,
    s1nms,
    s1time,
    s2mr,
    s2ms,
    s2nmr,
    s2nms,
    s2time
)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cance`(IN `id` INT, IN `refund` INT)
    NO SQL
UPDATE hall_booking set 

hall_booking.refund_money=refund,
hall_booking.cancel_date=CURRENT_DATE
WHERE hall_booking.id=id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteCustomer`(IN `cid` INT)
    NO SQL
DELETE from customer WHERE customer.id=cid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getCustomers`()
BEGIN
    SELECT * FROM customer ORDER BY name; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_allbooking`()
    NO SQL
SELECT * FROM hall_booking ORDER BY hall_booking.id DESC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_halls`()
    NO SQL
SELECT * from hall ORDER BY hall.name$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_hall_remove`(IN `id` INT)
    NO SQL
DELETE FROM hall WHERE hall.id=id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_Payback`(IN `id` INT, IN `fee` FLOAT, IN `back` FLOAT)
    NO SQL
update hall_booking set

hall_booking.sec_back=back,
hall_booking.late_fee=fee,
hall_booking.payback_date=CURRENT_DATE

WHERE hall_booking.id=id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_Payment`(IN `id` INT, IN `bmoney` FLOAT, IN `smoney` FLOAT)
    NO SQL
update hall_booking set

hall_booking.bmoney=bmoney,
hall_booking.smoney=smoney,
hall_booking.btype='Confirmed',
hall_booking.confirm_date=CURRENT_DATE

WHERE hall_booking.id=id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_remove_booking`(IN `id` INT)
    NO SQL
DELETE FROM hall_booking WHERE hall_booking.id=id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateCustomer`(IN `name` VARCHAR(50), IN `email` VARCHAR(50), IN `phone` VARCHAR(12), IN `mobile` VARCHAR(12), IN `address` VARCHAR(200), IN `alt_num` VARCHAR(12), IN `ref` VARCHAR(123), IN `is_mem` VARCHAR(12), IN `id` INT, IN `member_code` VARCHAR(255))
    NO SQL
UPDATE customer

set customer.name=name, customer.email=email, customer.phone=phone,
customer.mobile=mobile, customer.address=address, customer.alt_number=alt_num, customer.reference=ref, customer.is_member=is_mem,
customer.member_code=member_code

WHERE customer.id=id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_booking`(IN `hname` VARCHAR(123), IN `shift` VARCHAR(12), IN `ftype` VARCHAR(120), IN `bdate` DATE, IN `id` INT)
    NO SQL
UPDATE hall_booking set
   
    hall_booking.hall_name=hname,
    hall_booking.shift=shift,
    hall_booking.ftype=ftype,
    hall_booking.bdate=bdate
WHERE hall_booking.id=id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_hall`(IN `name` VARCHAR(50), IN `capacity` VARCHAR(50), IN `kcharge` FLOAT, IN `s1mr` FLOAT, IN `s1ms` FLOAT, IN `s1nmr` FLOAT, IN `s1nms` FLOAT, IN `s2mr` FLOAT, IN `s2ms` FLOAT, IN `s2nmr` FLOAT, IN `s2nms` FLOAT, IN `s1time` VARCHAR(11), IN `s2time` VARCHAR(11), IN `id` INT)
    NO SQL
UPDATE hall set
    hall.name=name,
    hall.capacity=capacity,
    hall.kitchen_charge=kcharge,
    hall.s1_m_rent=s1mr,
    hall.s1_m_sequrity=s1ms,
    hall.s1_nm_rent=s1nmr,
    hall.s1_nm_security=s1nms,
    hall.s1_time=s1time,
    hall.s2_m_rent=s2mr,
    hall.s2_m_security=s2nmr,
    hall.s2_nm_rent=s2nmr,
    hall.s2_nm_security=s2nms,
    hall.s2_time=s2time

where hall.id=id$$

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
  `name` varchar(70) NOT NULL,
  `address` varchar(200) NOT NULL,
  `alt_number` varchar(12) NOT NULL,
  `reference` varchar(200) NOT NULL,
  `member_code` varchar(255) DEFAULT NULL,
  `is_member` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `email`, `phone`, `mobile`, `name`, `address`, `alt_number`, `reference`, `member_code`, `is_member`) VALUES
(27, 'as@sss.g', 'dfgd', 'dfgdf', 'wwww', 'dfgdf', 'dgf', '', '', 0),
(28, 'sss@fff.cvf', 'dfgd', 'df', 'dddddddddd', 'dfgd', 'dfg', 'dfg', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `hall`
--

CREATE TABLE IF NOT EXISTS `hall` (
  `id` int(11) NOT NULL,
  `name` varchar(15) NOT NULL,
  `s1_m_rent` float NOT NULL,
  `s1_m_sequrity` float NOT NULL,
  `s1_nm_rent` float NOT NULL,
  `s1_nm_security` float NOT NULL,
  `s1_time` varchar(11) NOT NULL,
  `s2_m_rent` float NOT NULL,
  `s2_m_security` float NOT NULL,
  `s2_nm_rent` float NOT NULL,
  `s2_nm_security` float NOT NULL,
  `s2_time` varchar(11) NOT NULL,
  `capacity` varchar(50) NOT NULL,
  `kitchen_charge` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hall`
--

INSERT INTO `hall` (`id`, `name`, `s1_m_rent`, `s1_m_sequrity`, `s1_nm_rent`, `s1_nm_security`, `s1_time`, `s2_m_rent`, `s2_m_security`, `s2_nm_rent`, `s2_nm_security`, `s2_time`, `capacity`, `kitchen_charge`) VALUES
(9, 'Anchor', 10000, 2, 3, 4, '05:30 PM', 5, 7, 7, 8, '02:30 PM', '10', 9);

-- --------------------------------------------------------

--
-- Table structure for table `hall_booking`
--

CREATE TABLE IF NOT EXISTS `hall_booking` (
  `id` int(11) NOT NULL,
  `cid` varchar(15) NOT NULL,
  `hall_name` varchar(50) NOT NULL,
  `shift` varchar(50) NOT NULL,
  `btype` varchar(50) NOT NULL DEFAULT 'Temporary',
  `ftype` varchar(150) NOT NULL,
  `bmoney` float DEFAULT NULL,
  `smoney` float DEFAULT NULL,
  `late_fee` float DEFAULT NULL,
  `sec_back` float DEFAULT NULL,
  `confirm_date` date DEFAULT NULL,
  `bdate` date NOT NULL,
  `payback_date` date NOT NULL,
  `cancel_date` date NOT NULL,
  `refund_money` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hall_booking`
--

INSERT INTO `hall_booking` (`id`, `cid`, `hall_name`, `shift`, `btype`, `ftype`, `bmoney`, `smoney`, `late_fee`, `sec_back`, `confirm_date`, `bdate`, `payback_date`, `cancel_date`, `refund_money`) VALUES
(12, 'r21', 'Anchor', 'Shift1', 'Confirmed', 'Marriage', 10000, 2, 0, 2, '2015-08-08', '2015-08-03', '2015-08-08', '2015-08-08', 0),
(13, '28', 'Anchor', 'Shift1', 'Temporary', 'Marriage Anniversary', NULL, NULL, NULL, NULL, NULL, '2015-08-20', '0000-00-00', '0000-00-00', 0),
(14, '28', 'Anchor', 'Shift2', 'Confirmed', 'Marriage Anniversary', 7, 8, NULL, NULL, '2015-08-09', '2015-08-18', '0000-00-00', '0000-00-00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `jwt_roles`
--

CREATE TABLE IF NOT EXISTS `jwt_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jwt_roles`
--

INSERT INTO `jwt_roles` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'SuperAdmin'),
(3, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `jwt_user`
--

CREATE TABLE IF NOT EXISTS `jwt_user` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phoneNumber` varchar(15) NOT NULL,
  `joinDate` date NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jwt_user`
--

INSERT INTO `jwt_user` (`id`, `firstName`, `lastName`, `userName`, `email`, `phoneNumber`, `joinDate`, `password`) VALUES
(9, 'jasim', 'khan', 'jasim', 'jasim@gmail.com', '01913095519', '2015-08-08', 'sha256:1024:BkGoC7q93qjTUiQhulQPx50X2qzRHcwi:hdlrsk3NzG6Mtvbsfw0um0kId6WoKmLu');

-- --------------------------------------------------------

--
-- Table structure for table `jwt_user_roles`
--

CREATE TABLE IF NOT EXISTS `jwt_user_roles` (
  `userId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jwt_user_roles`
--

INSERT INTO `jwt_user_roles` (`userId`, `roleId`) VALUES
(9, 3),
(9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `widgetviewrights`
--

CREATE TABLE IF NOT EXISTS `widgetviewrights` (
  `id` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `widgetName` varchar(100) NOT NULL,
  `onlyView` tinyint(1) NOT NULL,
  `create` tinyint(1) NOT NULL,
  `update` tinyint(1) NOT NULL,
  `delete` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `widgetviewrights`
--

INSERT INTO `widgetviewrights` (`id`, `roleId`, `userId`, `widgetName`, `onlyView`, `create`, `update`, `delete`) VALUES
(9, 3, 0, 'widget1', 0, 1, 1, 0),
(10, 1, 0, 'WidgetViewRights', 0, 1, 1, 1),
(12, 1, 0, 'widget3', 0, 1, 1, 1),
(13, 1, 0, 'userInRoles', 0, 1, 1, 1),
(14, 1, 0, 'report', 0, 0, 0, 0),
(15, 1, 0, 'widget2', 0, 1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hall`
--
ALTER TABLE `hall`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hall_booking`
--
ALTER TABLE `hall_booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jwt_roles`
--
ALTER TABLE `jwt_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jwt_user`
--
ALTER TABLE `jwt_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `widgetviewrights`
--
ALTER TABLE `widgetviewrights`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `hall`
--
ALTER TABLE `hall`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `hall_booking`
--
ALTER TABLE `hall_booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `jwt_roles`
--
ALTER TABLE `jwt_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `jwt_user`
--
ALTER TABLE `jwt_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `widgetviewrights`
--
ALTER TABLE `widgetviewrights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
