/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `SHOP`
--

DROP TABLE IF EXISTS `SHOP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SHOP` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CODE` varchar(36) NOT NULL,
  `NAME` varchar(200) NOT NULL,
  `CREATED_DATE` bigint(20) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `FILE_TYPE` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:IMAGE, 2:VIDEO, 3:FILE',
  `THUMB_ID` varchar(512) DEFAULT NULL,
  `LINK` varchar(512) NOT NULL,
  `CATEGORY` varchar(10) DEFAULT NULL,
  `USE_ADBLOCK` tinyint(1) NOT NULL DEFAULT '0',
  `SCORE` int(11) DEFAULT '0',
  `PALIO_ID` varchar(60) DEFAULT NULL,
  `CAN_DELETE` tinyint(1) NOT NULL DEFAULT '1',
  `TOTAL_FOLLOWER` int(11) NOT NULL DEFAULT '0',
  `IS_VERIFIED` tinyint(1) NOT NULL DEFAULT '0',
  `TOTAL_VISITOR` int(11) NOT NULL DEFAULT '0',
  `IS_LIVE_STREAMING` int(11) NOT NULL DEFAULT '0',
  `CREATED_BY` varchar(20) DEFAULT NULL,
  `TOTAL_LIKES` int(11) NOT NULL DEFAULT '0',
  `SHOW_FOLLOWS` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `CODE_UNIQUE` (`CODE`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SHOP_FOLLOW`
--

DROP TABLE IF EXISTS `SHOP_FOLLOW`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SHOP_FOLLOW` (
  `F_PIN` varchar(20) NOT NULL,
  `STORE_CODE` varchar(36) NOT NULL,
  `FOLLOW_DATE` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`F_PIN`,`STORE_CODE`,`FOLLOW_DATE`),
  KEY `FOLOW$AK1` (`STORE_CODE`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SHOP_VISIT`
--

DROP TABLE IF EXISTS `SHOP_VISIT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SHOP_VISIT` (
  `F_PIN` varchar(20) NOT NULL,
  `STORE_CODE` varchar(36) NOT NULL,
  PRIMARY KEY (`F_PIN`,`STORE_CODE`),
  KEY `FOLOW$AK1` (`STORE_CODE`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SHOP_LS`
--

DROP TABLE IF EXISTS `SHOP_LS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SHOP_LS` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `F_PIN` VARCHAR(45) NOT NULL,
  `COMPANY_ID` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SHOP_LS$UK1` (`F_PIN`, `COMPANY_ID`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SHOP_LS_INFO`
--

DROP TABLE IF EXISTS `SHOP_LS_INFO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SHOP_LS_INFO` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `F_PIN` VARCHAR(45) NOT NULL,
  `STORE_CODE` varchar(36) NOT NULL,
  `TITLE` varchar(100) NOT NULL,
  `COVER_ID` varchar(512) DEFAULT NULL,
  `DESCRIPTION` text DEFAULT NULL,
  `FEATURED_PRODUCTS` text DEFAULT NULL  COMMENT 'list of product code, separate by pipe',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SHOP_LS_INFO$UK1` (`F_PIN`, `STORE_CODE`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `SHOP_REELS`
--

DROP TABLE IF EXISTS `SHOP_REELS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SHOP_REELS` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `F_PIN` VARCHAR(45) NOT NULL,
  `STORE_CODE` varchar(36) NOT NULL,
  `TITLE` varchar(100) NOT NULL,
  `COVER_ID` varchar(512) DEFAULT NULL,
  `DESCRIPTION` text DEFAULT NULL,
  `URL` text DEFAULT NULL,
  `CREATED_DATE` bigint(20) DEFAULT NULL,
  `FEATURED_PRODUCTS` text DEFAULT NULL  COMMENT 'list of product code, separate by pipe',
  PRIMARY KEY (`ID`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `SHOP_REWARD_POINT`
--

DROP TABLE IF EXISTS `SHOP_REWARD_POINT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SHOP_REWARD_POINT` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `F_PIN` varchar(20) NOT NULL,
  `STORE_CODE` varchar(36) NOT NULL,
  `AMOUNT` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SHOP_REWARD_POINT$UK1` (`F_PIN`,`STORE_CODE`),
  KEY `REWARDPOINT$AK1` (`F_PIN`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `SHOP_SHIPPING_ADDRESS`
--

DROP TABLE IF EXISTS `SHOP_SHIPPING_ADDRESS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SHOP_SHIPPING_ADDRESS` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `STORE_CODE` varchar(36) NOT NULL,
  `ADDRESS` text DEFAULT NULL,
  `VILLAGE` varchar(100) DEFAULT NULL,
  `DISTRICT` varchar(100) DEFAULT NULL,
  `CITY` varchar(100) DEFAULT NULL,
  `PROVINCE` varchar(100) DEFAULT NULL,
  `ZIP_CODE` varchar(6) DEFAULT NULL,
  `PHONE_NUMBER` varchar(20) DEFAULT NULL,
  `COURIER_NOTE` text DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SHOP_SHIPPING_ADDRESS$UK1` (`STORE_CODE`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `SHOP_SETTINGS`
--

CREATE TABLE `SHOP_SETTINGS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PROPERTY` varchar(64) DEFAULT NULL,
  `VALUE` int(11) DEFAULT NULL,
  `VALUE_TEXT` TEXT DEFAULT NULL,
  PRIMARY KEY (`ID`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data for the table `SHOP_SETTINGS`
--

insert  into `SHOP_SETTINGS`(`ID`,`PROPERTY`,`VALUE`, `VALUE_TEXT`) values 
(1,'VIEW_FOLLOW_TAB1',1, ''),
(2,'CHAT_GREETING',1, 'Selamat datang di toko _storename_'),
(3,'SHOW_LINKLESS_STORE',2, '0: show with link only, 1: show linkless only, 2: show all');

--
-- Table structure for table `PRODUCT`
--

DROP TABLE IF EXISTS `PRODUCT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRODUCT` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CODE` varchar(20) NOT NULL UNIQUE,
  `MERCHANT_CODE` varchar(20) DEFAULT NULL,
  `NAME` varchar(200) NOT NULL,
  `CREATED_DATE` bigint(20) NOT NULL,
  `SHOP_CODE` varchar(36) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `THUMB_ID` varchar(512) DEFAULT NULL,
  `CATEGORY` varchar(10) DEFAULT NULL,
  `SCORE` int(11) DEFAULT '0',
  `TOTAL_LIKES` int(11) DEFAULT '0',
  `PRICE` int(11) NOT NULL DEFAULT '0',
  `IS_SHOW` smallint(1) NOT NULL DEFAULT '1',
  `FILE_TYPE` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:IMAGE, 2:VIDEO, 3:FILE',
  `REWARD_POINT` int(11) NOT NULL DEFAULT '0',
  `QUANTITY` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UK1` (`CODE`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PRODUCT_REACTION`
--

DROP TABLE IF EXISTS `PRODUCT_REACTION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRODUCT_REACTION` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PRODUCT_CODE` varchar(40) NOT NULL,
  `F_PIN` varchar(20) NOT NULL,
  `FLAG` tinyint(1) NOT NULL COMMENT '1:LIKE, -1:DISLIKE, 0:NETRAL',
  `LAST_UPDATE` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `PRODUCT_REACTION$UK1` (`PRODUCT_CODE`,`F_PIN`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PRODUCT_COMMENT`
--

DROP TABLE IF EXISTS `PRODUCT_COMMENT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRODUCT_COMMENT` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `COMMENT_ID` varchar(40) NOT NULL,
  `PRODUCT_CODE` varchar(40) NOT NULL,
  `F_PIN` varchar(20) NOT NULL,
  `COMMENT` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `CREATED_DATE` bigint(20) DEFAULT NULL,
  `REF_COMMENT_ID` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `PRODUCT_COMMENT$UK1` (`COMMENT_ID`),
  KEY `PRODUCT_COMMENT$AK1` (`PRODUCT_CODE`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PRODUCT_SHIPMENT_DETAIL`
--

DROP TABLE IF EXISTS `PRODUCT_SHIPMENT_DETAIL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRODUCT_SHIPMENT_DETAIL` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PRODUCT_CODE` varchar(40) NOT NULL,
  `LENGTH` int(11) NOT NULL DEFAULT 0,
  `WIDTH` int(11) NOT NULL DEFAULT 0,
  `HEIGHT` int(11) NOT NULL DEFAULT 0,
  `IS_FRAGILE` tinyint(1) NOT NULL DEFAULT 0,
  `WEIGHT` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `PRODUCT_SHIPMENT_DETAIL$UK1` (`PRODUCT_CODE`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ARTIST_PRICING`
--

DROP TABLE IF EXISTS `ARTIST_PRICING`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ARTIST_PRICING` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ARTIST_CODE` varchar(200) NOT NULL,
  `TIME_INDEX` varchar(200) NOT NULL DEFAULT 0,
  `PRICE` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ARTIST_PRICING$UK1` (`ARTIST_CODE`,`TIME_INDEX`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `PURCHASE`
--

DROP TABLE IF EXISTS `PURCHASE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PURCHASE` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TRANSACTION_ID` varchar(256) DEFAULT NULL,
  `MERCHANT_ID` varchar(256) DEFAULT NULL,
  `PRODUCT_ID` varchar(256) DEFAULT NULL,
  `PRICE` int(11) DEFAULT NULL,
  `AMOUNT` int(11) DEFAULT NULL,
  `METHOD` varchar(256) DEFAULT NULL,
  `FPIN` varchar(256) DEFAULT NULL,
  `CREATED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SHOP_CATEGORY`
--

DROP TABLE IF EXISTS `SHOP_CATEGORY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SHOP_CATEGORY` (
  `ID` int(11) NOT NULL UNIQUE,
  `NAME` varchar(200) NOT NULL,
  `SORT_ORDER` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

insert  into `SHOP_CATEGORY`(`ID`,`NAME`,`SORT_ORDER`) values 
(0,'Lainnya',9),
(1,'Kuliner',0),
(2,'Busana',2),
(3,'Kriya',1),
(4,'Hiburan',3);

--
-- Table structure for table `PRODUCT_CATEGORY`
--

DROP TABLE IF EXISTS `PRODUCT_CATEGORY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRODUCT_CATEGORY` (
  `ID` int(11) NOT NULL UNIQUE,
  `NAME` varchar(200) NOT NULL,
  `SORT_ORDER` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

insert  into `PRODUCT_CATEGORY`(`ID`,`NAME`,`SORT_ORDER`) values 
(0,'Lainnya',9),
(1,'Kuliner',0),
(2,'Busana',2),
(3,'Kriya',1),
(4,'Hiburan',3);

--
-- Table structure for table `VERSION_WHITELIST`
--

DROP TABLE IF EXISTS `VERSION_WHITELIST`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VERSION_WHITELIST` (
  `ID` int(11) NOT NULL UNIQUE,
  `APP_ID` varchar(200) NOT NULL,
  `VERSION` text DEFAULT NULL,
  PRIMARY KEY (`ID`)
) TABLESPACE `innodb_file_per_table` ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
