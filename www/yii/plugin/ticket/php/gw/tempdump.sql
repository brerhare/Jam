-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: plugin
-- ------------------------------------------------------
-- Server version	5.5.31-0ubuntu0.12.04.1

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
-- Table structure for table `ticket_area`
--

DROP TABLE IF EXISTS `ticket_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `max_places` int(11) DEFAULT NULL,
  `ticket_event_id` int(11) NOT NULL,
  `used_places` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `fk_ticket_area_ticket_event1` (`ticket_event_id`),
  CONSTRAINT `fk_ticket_area_ticket_event1` FOREIGN KEY (`ticket_event_id`) REFERENCES `ticket_event` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_area`
--

LOCK TABLES `ticket_area` WRITE;
/*!40000 ALTER TABLE `ticket_area` DISABLE KEYS */;
INSERT INTO `ticket_area` VALUES (1,5,'Standard',40,1,0),(2,6,'VIP Tickets',40,2,6),(3,6,'Golden Tickets',310,2,0),(4,4,'Standard Seating',15,5,9),(5,4,'VIP - Inc Programme and Backstage access',7,5,7),(6,4,'Training Workshop - all material provided',30,6,13),(7,5,'Standard',20,3,0),(8,5,'Standard',20,4,0),(9,5,'Standard',20,7,0),(10,7,'Auditorium',100,8,0),(11,7,'Seating',45,9,0),(12,7,'The Gallery',100,8,0),(13,4,'Delegates',5,10,0),(14,7,'Standard',10,11,0),(15,4,'Delegates',20,12,26),(16,4,'Dinner & Show',30,13,8),(17,4,'Show Only',100,13,0);
/*!40000 ALTER TABLE `ticket_area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_auth`
--

DROP TABLE IF EXISTS `ticket_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `card_name` varchar(255) DEFAULT NULL,
  `card_number` varchar(255) DEFAULT NULL,
  `expiry_month` varchar(45) DEFAULT NULL,
  `expiry_year` varchar(45) DEFAULT NULL,
  `cv2` varchar(45) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `address3` varchar(255) DEFAULT NULL,
  `address4` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `post_code` varchar(255) DEFAULT NULL,
  `country_short` varchar(255) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `currency_short` varchar(45) DEFAULT NULL,
  `auth_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `order_number` (`order_number`,`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_auth`
--

LOCK TABLES `ticket_auth` WRITE;
/*!40000 ALTER TABLE `ticket_auth` DISABLE KEYS */;
INSERT INTO `ticket_auth` VALUES (1,4,'4-1370002956','James Jackson','4921810000005462','12','15','441','11 St Kitts Close','','','','Torquay','Devon','TQ2 7DQ','-1','1000','GBP','AuthCode: 602467'),(2,4,NULL,'Jo Seawright',NULL,NULL,NULL,NULL,'Crimond','Mavis Grove','','',NULL,NULL,'DG2 8EP',NULL,NULL,NULL,NULL),(3,4,NULL,'Jo Seawright',NULL,NULL,NULL,NULL,'Crimond','Mavis Grove','','',NULL,NULL,'DG2 8EP',NULL,NULL,NULL,NULL),(4,4,'4-1370252733','John Watson','4976000000003436','12','15','452','32 Edward Street','','','','Camborne','Cornwall','TR14 8PA','GBR','12500','GBP','AuthCode: 448044'),(5,4,NULL,'Jo Seawright',NULL,NULL,NULL,NULL,'Crimond','Mavis Grove','','',NULL,NULL,'DG2 8EP',NULL,NULL,NULL,NULL),(6,4,NULL,'Jo Seawright',NULL,NULL,NULL,NULL,'Crimond','Mavis Grove','','',NULL,NULL,'DG2 8EP',NULL,NULL,NULL,NULL),(7,4,'4-1370254076','John Watson','4976000000003436','12','15','452','32 Edward Street','','','','Camborne','Cornwall','TR14 8PA','GBR','12500','GBP','AuthCode: 056258'),(8,4,'4-1370254186','John Watson','4976000000003436','12','15','452','32 Edward Street','','','','Camborne','Cornwall','TR14 8PA','GBR','14500','GBP','AuthCode: 981410'),(9,4,'4-1370305949','Liuke Johns','5100000000005460','12','15','524','22 Kelgate','','','','Mosborough','South Yorkshire','S20 5EJ','-1','4500','GBP','AuthCode: 613210'),(10,4,'4-1370308495','Kim Hancock','No card required',NULL,NULL,NULL,'Borgue','Langshott','441557870337','441557870337',NULL,NULL,'B42 1SX',NULL,NULL,NULL,NULL),(11,4,'4-1370335262','Mr Punter','No card required',NULL,NULL,NULL,'Ad1','Ad2','Ad3','Ad4',NULL,NULL,'POCODE',NULL,NULL,NULL,NULL),(12,4,'4-1370335453','TestName','No card required',NULL,NULL,NULL,'TestA1','TestA2','TestA3','TestA4',NULL,NULL,'TestPC',NULL,NULL,NULL,NULL),(13,4,'4-1370335834','TestNameAgain','No card required',NULL,NULL,NULL,'TestA1','TestA2','TestA3','TestA4',NULL,NULL,'TestPC',NULL,NULL,NULL,NULL),(14,4,'4-1370336258','Kim Hancock','No card required',NULL,NULL,NULL,'The OSH','Borgue','KK','DFS',NULL,NULL,'B42 1SX',NULL,NULL,NULL,NULL),(15,4,'4-1370336421','Kim Hancock','No card required',NULL,NULL,NULL,'Borgue','Langshott','','',NULL,NULL,'POCODE',NULL,NULL,NULL,NULL),(16,4,'4-1370336634','Lee Jones','No card required',NULL,NULL,NULL,'aa1','aa2','aa3','aa4',NULL,NULL,'pp1',NULL,NULL,NULL,NULL),(17,4,'4-1370336984','John Watson','4976000000003436','12','15','452','32 Edward Street','Camborne','','','Camborne','Cornwall','TR14 8PA','GBR','10500','GBP','AuthCode: 720097'),(18,4,'4-1370337178','James Jackson','4921810000005462','12','15','441','11 St Kitts Close,','','','','Torquay','Devon','TQ2 7DQ','GBR','6000','GBP','AuthCode: 095067'),(19,4,'4-1370339008','Jo Seawright','No card required',NULL,NULL,NULL,'Crimond','Mavis Grove','Dumfries','',NULL,NULL,'DG1 8EP',NULL,NULL,NULL,NULL),(20,4,'4-1370339102','Jo Seaw','No card required',NULL,NULL,NULL,'Crimond','Mavis Grove','Dumfries','',NULL,NULL,'DG2 8EP',NULL,NULL,NULL,NULL),(21,4,'4-1370428266','Paul Taylor','6759000000005462','12','15','789','7 Balmore Drive,','','','','Reading','Berkshire','RG4 8NL','GBR','7500','GBP','AuthCode: 381746'),(22,4,'4-1370428622','Paul Taylor','6759000000005462','12','15','789','7 Balmore Drive,','','','','Reading','Berkshire','RG4 8NL','GBR','6500','GBP','AuthCode: 706391'),(23,4,'4-1370428821','Luke Johns','5100000000005460','12','15','524','22 Kelgate,','','','','Mosborough,','South Yorkshire','S20 5EJ','GBR','2500','GBP','AuthCode: 280142'),(24,4,'4-1370429368','Jo Seawright','No card required',NULL,NULL,NULL,'Crimond','Mavis Grove','','',NULL,NULL,'DG2 8EP',NULL,NULL,NULL,NULL),(25,4,'4-1370429438','Jo Seawright','No card required',NULL,NULL,NULL,'Crimond','Mavis Grove','','',NULL,NULL,'DG2 8EP',NULL,NULL,NULL,NULL),(26,4,'4-1370429521','Jo Seawright','No card required',NULL,NULL,NULL,'Crimond','Mavis Grove','','',NULL,NULL,'DG2 8EP',NULL,NULL,NULL,NULL),(27,6,'6-1370674783','Mrs Morven e Kerr','5299307908556390','03','15','610','Belltower house ','Kirtlebridge ','','','Lockerbie','Dumfriesshire','Dg113na','GBR','11600','GBP','AuthCode: T92128'),(28,4,'4-1370953049','Jo Seawright','No card required',NULL,NULL,NULL,'Crimond','Mavis Grove','Dumfries','',NULL,NULL,'DG2 8EP',NULL,NULL,NULL,NULL),(29,6,'6-1371371375','J Barr','5229486089688050','02','15','','5 ninian court','','','','dumfries','','dg29ps','GBR','5800','GBP','AuthCode: 912500'),(30,4,'4-1371806376','Jo Seawright','N/A',NULL,NULL,NULL,'Crimond','Mavis Grove','','',NULL,NULL,'DG2 8EP',NULL,NULL,NULL,NULL),(31,4,'4-1371806519','','N/A',NULL,NULL,NULL,'','','','',NULL,NULL,'',NULL,NULL,NULL,NULL),(32,4,'4-1371807490','Jo Seawright','N/A',NULL,NULL,NULL,'Crimond','','','',NULL,NULL,'',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `ticket_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_event`
--

DROP TABLE IF EXISTS `ticket_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` varchar(45) NOT NULL,
  `address` text NOT NULL,
  `post_code` varchar(45) NOT NULL,
  `ticket_logo_path` varchar(255) DEFAULT NULL,
  `ticket_text` text,
  `ticket_terms` text,
  `active` int(11) NOT NULL,
  `active_start_date` date DEFAULT NULL,
  `active_start_time` time DEFAULT NULL,
  `active_end_date` date DEFAULT NULL,
  `active_end_time` time DEFAULT NULL,
  `ticket_vendor_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `fk_ticket_event_ticket_vendor` (`ticket_vendor_id`),
  CONSTRAINT `fk_ticket_event_ticket_vendor` FOREIGN KEY (`ticket_vendor_id`) REFERENCES `ticket_vendor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_event`
--

LOCK TABLES `ticket_event` WRITE;
/*!40000 ALTER TABLE `ticket_event` DISABLE KEYS */;
INSERT INTO `ticket_event` VALUES (1,5,'Test Workshop','12/06/13','Aston Hotel','DG1 1##','Small logo.jpg','Please see my other<a target=\"_blank\" rel=\"nofollow\" href=\"http://www.insightklg.co.uk/index.php/contentBlock/page?url=workshops\">&nbsp;motivational&nbsp;workshops</a> on www.<a target=\"_blank\" rel=\"nofollow\" href=\"http://www.insightklg.co.uk\">insightklg.co.uk</a>','No Photography',0,NULL,NULL,NULL,NULL,1),(2,6,'Made in Dumfries','29/06/13','Easterbrook Hall\r\nThe Crichton\r\nBankend Rd\r\nDumfries','DG1 4TA','MID Primary Master Logo.png','We would like to welcome you all to Made In Dumfries. We are glad you are joining us at this massive event in Dumfries which includes celebrity guests, reality TV and more.&nbsp;<br><br>Our dress code is FORMAL, so please dress to impress.&nbsp;<br><br>Doors open for this event at 7pm, with the show kicking off at 8pm. Please come early as seating is unallocated. The event should run until around 11pm.&nbsp;','We reserve the right to refuse entry. Our event will be filmed and photographs taken, all rights remain to Made In Dumfries. Please visit our website for more details. <a target=\"_blank\" rel=\"nofollow\" href=\"http://www.madeindumfries.com\">http://www.madeindumfries.com</a>&nbsp;',1,NULL,NULL,NULL,NULL,2),(3,5,'Salon Sense ','cancelled','The Ryan Leisure Centre,	\r\nFairhurst Road, \r\nStranraer DG9 7AP',' DG9 7AP','Workshop Logo.jpg','<span class=\"wysiwyg-color-purple\">Salon Sense<br>Business Training Workshop for those in the Hair/Beauty Industry<br>£59 per head. Monday 1-4pm<br><br>13th May 2013<br>Ryan Centre&nbsp;<br>STRANRAER<br><br>kath@insightklg.co.uk<br>07921072792</span>','One ticket per person.<br>If the workshop is cancelled you will have the option of a refund or a switch to another course.<br>Tickets will only be refunded if we have 48 hours notice of cancellation.<br>Ticket refunds will be given within 5 working days of the&nbsp;event.<br><br>',0,NULL,NULL,NULL,NULL,1),(4,5,'Salon Sense ','20th May 2013','Freerange Artists Ltd\r\n6 Carlyles Court\r\nSt Marys Gate\r\nCarlisle\r\nCumbria. CA3 8RY','CA3 8RY','Workshop Logo.jpg','<span class=\"wysiwyg-color-purple\">Salon Sense<br>Business Training Workshop for those in the Hair/Beauty Industry<br>£59 per head. Monday 1-4pm<br><br>20th May 2013<br>Freerange&nbsp;<br>CARLISLE<br><br>kath@insightklg.co.uk<br>07921072792<br></span>','One ticket per person.<br>If the workshop is cancelled you will have the option of a refund or a switch to another course.<br>Tickets will only be refunded if we have 48 hours notice of cancellation.<br>Ticket refunds will be given within 5 working days of the&nbsp;event.',0,NULL,NULL,NULL,NULL,1),(5,4,'My Test Event','12/06/13','Middle of Dumfries & Galloway','DG7 1##','Rockcliffe small.jpg','<h1><span class=\"wysiwyg-color-purple\">Here you can say anything and perhaps advertise something else you are offering.</span><br></h1><div><span class=\"wysiwyg-color-navy\">Maybe you can tell people about a special offer and add a hyperlink to your <a target=\"_blank\" rel=\"nofollow\" href=\"http://www.wireflydesign.com\">website</a></span></div>','Here you can stipulate specific T&amp;Cs in addition to the DG Link\'s standard T&amp;Cs such as no Photography or all children must be accompanied by a responsible adult etc.',1,NULL,NULL,NULL,NULL,3),(6,4,'My Test Workshop','17/08/13','Another Town\r\nDumfries & Galloway','DG1 1##','Earlstoun Loch Dalry - John McBeth 010611.JPG','<h1><span class=\"wysiwyg-color-purple\">Here you can say anything and perhaps advertise something else you are offering.</span><br></h1><div><span class=\"wysiwyg-color-navy\">Maybe you can tell people about a special offer and add a hyperlink to your&nbsp;<a target=\"_blank\" rel=\"nofollow\" href=\"http://www.wireflydesign.com/\">website</a></span></div>','Here you can stipulate specific T&amp;Cs in addition to the DG Link\'s standard T&amp;Cs such as no Photography or all children must be accompanied by a responsible adult etc.',1,NULL,NULL,NULL,NULL,3),(7,5,'Salon Sense ','3rd June 2013','Higham Hall\r\nBassenthwaite Lake\r\nCockermouth\r\nCumbria\r\n','CA13 9SH',NULL,'Salon Sense<br>Business Training Workshop for those in the Hair/Beauty Industry<br>£59 per head. Monday 1-4pm<br><br>3rd June 2013<br><div>Higham Hall</div><div>Bassenthwaite Lake</div><div>Cockermouth</div><div>Cumbria</div><br>kath@insightklg.co.uk<br>07921072792','One ticket per person.<br>If the workshop is cancelled you will have the option of a refund or a switch to another course.<br>Tickets will only be refunded if we have 48 hours notice of cancellation.<br>Ticket refunds will be given within 5 working days of the&nbsp;event.',0,NULL,NULL,NULL,NULL,1),(8,7,'Simon Callaghan, Piano & Ellie Lovegrove, Trumpet','Saturday 15th of June','Greyfriars Church\r\nDumfries','DG1 1DF','Greyfriars.jpg','Program to include works by:<br>Boyce, Enescu,<br>Gershwin and Takemitsu','',0,NULL,NULL,NULL,NULL,4),(9,7,'Samantha Ward, Piano','Saturday, 6th of July','Greyfriars Church\r\nDumfries','DG1 1DF','Greyfriars2.jpg','Program:<br>Schubert Sonata in A major D959<br>Schubert Sonata D960','',0,NULL,NULL,NULL,NULL,4),(10,4,'Booking Reservation','12/12/14','Dumfries & Galloway','DG1 1##','Web Logo white outline.png','Welcome to my demo event. Please keep this reservation safe.','Not suitable for Children',1,NULL,NULL,NULL,NULL,3),(11,7,'Opera Rox','Friday 31st of May','The Vintage Lounge, Shakespeare St, Dumfries','DG1 2BY','Opera Rox.jpg','Opera, metal, power-pop and dance melt together then splinter anew when twin singing sensations Charlotte Pearson  (Miss England Finalists) and twin sister Pippa hit the Vintage Lounge.\r\n\r\nWith a high octane mix of contemporary an classic sounds delivered with no holds barred extra bombast, lets share the secret... OPERA ROX','',1,NULL,NULL,NULL,NULL,4),(12,4,'Booking Reservation 2','15/12/13','Somewhere','DG1 1DF','Buy Opera Rox.png','Testing Free reservations','',1,NULL,NULL,NULL,NULL,3),(13,4,'Christmas Concert Dinner','25/12/13','Dumfries','DG1 1##','small logo.png','Please bring your own bottle','No under 18\'s',1,NULL,NULL,NULL,NULL,3);
/*!40000 ALTER TABLE `ticket_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_order`
--

DROP TABLE IF EXISTS `ticket_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `sid` varchar(255) DEFAULT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `http_ticket_type_area` varchar(45) DEFAULT NULL,
  `http_ticket_type_id` varchar(45) DEFAULT NULL,
  `http_ticket_type_qty` varchar(45) DEFAULT NULL,
  `http_ticket_type_price` varchar(45) DEFAULT NULL,
  `http_ticket_type_total` varchar(45) DEFAULT NULL,
  `http_total` varchar(45) DEFAULT NULL,
  `auth_code` varchar(45) DEFAULT NULL,
  `return_url` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `free_address1` varchar(255) DEFAULT NULL,
  `free_address2` varchar(255) DEFAULT NULL,
  `free_address3` varchar(255) DEFAULT NULL,
  `free_address4` varchar(255) DEFAULT NULL,
  `free_post_code` varchar(255) DEFAULT NULL,
  `free_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_order`
--

LOCK TABLES `ticket_order` WRITE;
/*!40000 ALTER TABLE `ticket_order` DISABLE KEYS */;
INSERT INTO `ticket_order` VALUES (11,6,'88.96.96.62','32ohd930m336i0smojebjc8ol2','6-1366999706',2,2,'2','3','4','29.00','116.00','116.00','AuthCode: 026768','/ticket','Suzannelwhitby@gmail.com','07917044624',NULL,NULL,NULL,NULL,NULL,NULL),(13,6,'87.84.25.246','32ohd930m336i0smojebjc8ol2','6-1366999813',2,2,'2','3','2','29.00','58.00','58.00','AuthCode: 121876','/ticket','miss_k_shields@hotmail.com','07894401728',NULL,NULL,NULL,NULL,NULL,NULL),(28,4,'86.183.97.202','8ras9unv9mb6v92a7d8hqrj1d2','4-1367080189',3,6,'6','12','1','350.00','350.00','350.00',NULL,'/ticket','jo@wireflydesign.com','07777',NULL,NULL,NULL,NULL,NULL,NULL),(51,5,'86.184.217.49','04i303m6v8unfph9jhnge9tur5','5-1367165575',1,1,'1','1','1','59.00','59.00','59.00',NULL,'/ticket','jo@wireflydesign.com','07777',NULL,NULL,NULL,NULL,NULL,NULL),(54,5,'90.210.41.198','04i303m6v8unfph9jhnge9tur5','5-1367181443',1,3,'7','14','2','59.00','118.00','118.00','AuthCode: 965465','/ticket','nessa0301@hotmail.co.uk','01671403200',NULL,NULL,NULL,NULL,NULL,NULL),(58,5,'86.184.217.102','04i303m6v8unfph9jhnge9tur5','5-1367184602',1,3,'7','14','1','59.00','59.00','59.00',NULL,'/ticket','jo@wireflydesign.com','07777',NULL,NULL,NULL,NULL,NULL,NULL),(59,6,'92.18.173.212','32ohd930m336i0smojebjc8ol2','6-1367249258',2,2,'3','4','1','23.00','23.00','23.00','AuthCode: 884936','/ticket','amieatingwaffles@hotmail.co.uk','01461 339046',NULL,NULL,NULL,NULL,NULL,NULL),(60,5,'86.155.149.80','04i303m6v8unfph9jhnge9tur5','5-1367442463',1,7,'9','16','1','59.00','59.00','59.00',NULL,'/ticket','jo@dglink.co.uk','0777',NULL,NULL,NULL,NULL,NULL,NULL),(61,5,'31.95.180.246','04i303m6v8unfph9jhnge9tur5','5-1367512814',1,3,'7','14','1','59.00','59.00','59.00',NULL,'/ticket','jo@wireflydesign.com','07777',NULL,NULL,NULL,NULL,NULL,NULL),(62,5,'31.51.232.165','04i303m6v8unfph9jhnge9tur5','5-1367703011',1,7,'9','16','5','59.00','295.00','295.00',NULL,'/ticket','jo@wireflydesign.com','07777',NULL,NULL,NULL,NULL,NULL,NULL),(63,6,'2.126.172.51','32ohd930m336i0smojebjc8ol2','6-1367785990',2,2,'2','3','2','29.00','58.00','58.00',NULL,'/ticket','weemayzaii@hotmail.co.uk','07938240192',NULL,NULL,NULL,NULL,NULL,NULL),(65,6,'87.113.94.125','32ohd930m336i0smojebjc8ol2','6-1367866196',2,2,'2','3','2','29.00','58.00','58.00','AuthCode: 007866','/ticket','knitter62@hotmail.com','01683221009',NULL,NULL,NULL,NULL,NULL,NULL),(66,6,'2.126.172.2','32ohd930m336i0smojebjc8ol2','6-1367931328',2,2,'2','3','2','29.00','58.00','58.00',NULL,'/ticket','weemayzaii@hotmail.co.uk','01387419096',NULL,NULL,NULL,NULL,NULL,NULL),(67,6,'78.148.39.88','32ohd930m336i0smojebjc8ol2','6-1367934171',2,2,'2','3','2','29.00','58.00','58.00',NULL,'/ticket','weemayzaii@hotmail.co.uk','07856155223',NULL,NULL,NULL,NULL,NULL,NULL),(68,6,'92.18.169.100','32ohd930m336i0smojebjc8ol2','6-1367959825',2,2,'2','3','1','29.00','29.00','29.00','AuthCode: 044347','/ticket','katkinson_@hotmail.co.uk','07927145332',NULL,NULL,NULL,NULL,NULL,NULL),(70,6,'79.78.242.171','32ohd930m336i0smojebjc8ol2','6-1368120583',2,2,'2','3','3','29.00','87.00','87.00',NULL,'/ticket','fierball123@hotmail.co.uk','07546126462',NULL,NULL,NULL,NULL,NULL,NULL),(71,6,'79.78.255.181','32ohd930m336i0smojebjc8ol2','6-1368120904',2,2,'2','3','3','29.00','87.00','87.00',NULL,'/ticket','fierball123@hotmail.co.uk','07546126462',NULL,NULL,NULL,NULL,NULL,NULL),(73,6,'176.27.36.39','32ohd930m336i0smojebjc8ol2','6-1368357864',2,2,'2','3','2','29.00','58.00','58.00','AuthCode: 013884','/ticket','Mrsahyslop@aol.co.uk','07956325199',NULL,NULL,NULL,NULL,NULL,NULL),(74,6,'31.54.113.26','32ohd930m336i0smojebjc8ol2','6-1368468302',2,2,'2','3','4','29.00','116.00','116.00','AuthCode: 012254','/ticket','gaynor.thomson@btinternet.com','07739011315',NULL,NULL,NULL,NULL,NULL,NULL),(77,6,'92.18.163.15','32ohd930m336i0smojebjc8ol2','6-1368997146',2,2,'3','4','2','20.70','41.40','41.40','AuthCode: 078180','/ticket','Michaelandcooper@aol.com','07852153654',NULL,NULL,NULL,NULL,NULL,NULL),(78,4,'86.171.60.151','8ras9unv9mb6v92a7d8hqrj1d2','4-1369823875',3,6,'6','11','1','45.00','45.00','45.00',NULL,'/ticket','jo@wireflydesign.com','07777',NULL,NULL,NULL,NULL,NULL,NULL),(84,6,'86.167.89.113','32ohd930m336i0smojebjc8ol2','6-1370045826',2,2,'2','3','2','29.00','58.00','58.00',NULL,'/ticket','Marie102@btinternet.com','07809621621',NULL,NULL,NULL,NULL,NULL,NULL),(119,6,'194.83.96.13','32ohd930m336i0smojebjc8ol2',NULL,2,2,'2','3','5','29.00','NaN','NaN',NULL,'/ticket','ringlandamy@gmail.com','07798725740',NULL,NULL,NULL,NULL,NULL,NULL),(124,6,'86.155.144.222','32ohd930m336i0smojebjc8ol2','6-1370369279',2,2,'2','3','5','29.00','145.00','145.00',NULL,'/ticket','ringlandamy@gmail.com','07798725470',NULL,NULL,NULL,NULL,NULL,NULL),(125,4,'86.31.215.106','8ras9unv9mb6v92a7d8hqrj1d2','4-1370420341',3,6,'6','11','1','45.00','45.00','45.00',NULL,'/ticket','subscriptions@microboot.com','441557870337',NULL,NULL,NULL,NULL,NULL,NULL),(135,4,'86.155.91.187','8ras9unv9mb6v92a7d8hqrj1d2','4-1370429521',3,12,'15','25','2','0.00','0.00','0.00',NULL,'/ticket','jo@wireflydesign.com','07733','Crimond','Mavis Grove','','','DG2 8EP','Jo Seawright'),(136,4,'86.155.91.187','8ras9unv9mb6v92a7d8hqrj1d2','4-1370429521',3,12,'15','27','1','0.00','0.00','0.00',NULL,'/ticket','jo@wireflydesign.com','07733','Crimond','Mavis Grove','','','DG2 8EP','Jo Seawright'),(139,6,'146.191.241.130','32ohd930m336i0smojebjc8ol2',NULL,2,2,'2','3','1','29.00','NaN','NaN',NULL,'/ticket','louise.brown66@tiscali.co.uk','07845698615',NULL,NULL,NULL,NULL,NULL,NULL),(140,6,'85.211.200.186','32ohd930m336i0smojebjc8ol2','6-1370674783',2,2,'2','3','4','29.00','116.00','116.00','AuthCode: T92128','/ticket','relmkerr@tiscali.co.uk','01461500689',NULL,NULL,NULL,NULL,NULL,NULL),(141,6,'2.217.50.220','32ohd930m336i0smojebjc8ol2','6-1370760403',2,2,'2','3','2','29.00','58.00','58.00',NULL,'/ticket','g.mcculloch@live.co.uk','447866444725',NULL,NULL,NULL,NULL,NULL,NULL),(143,4,'81.151.60.46','8ras9unv9mb6v92a7d8hqrj1d2','4-1370953049',3,13,'16','28','1','0.00','0.00','0.00',NULL,'/ticket','jo@wireflydesign.com','07777','Crimond','Mavis Grove','Dumfries','','DG2 8EP','Jo Seawright'),(144,6,'81.151.57.254','32ohd930m336i0smojebjc8ol2','6-1371222505',2,2,'3','4','2','23.00','46.00','46.00',NULL,'/ticket','admin@dgchamber.co.uk','01775 678987',NULL,NULL,NULL,NULL,NULL,NULL),(146,6,'109.153.133.243','32ohd930m336i0smojebjc8ol2','6-1371371375',2,2,'2','3','2','29.00','58.00','58.00','AuthCode: 912500','/ticket','Ianbarr@live.co.uk','07850665567',NULL,NULL,NULL,NULL,NULL,NULL),(151,4,'81.151.60.49','8ras9unv9mb6v92a7d8hqrj1d2','4-1371807490',3,6,'6','11','2','45.00','90.00','90.00',NULL,'/ticket','jo@wireflydesign.com','','Crimond','','','','','Jo Seawright');
/*!40000 ALTER TABLE `ticket_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_scan`
--

DROP TABLE IF EXISTS `ticket_scan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_scan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `ticket_number` varchar(255) NOT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `ticket_number` (`ticket_number`,`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_scan`
--

LOCK TABLES `ticket_scan` WRITE;
/*!40000 ALTER TABLE `ticket_scan` DISABLE KEYS */;
INSERT INTO `ticket_scan` VALUES (12,6,'6-1366999706','22591337','0000-00-00 00:00:00'),(13,6,'6-1366999706','22936026','0000-00-00 00:00:00'),(14,6,'6-1366999706','22135677','0000-00-00 00:00:00'),(15,6,'6-1366999706','22237539','0000-00-00 00:00:00'),(16,6,'6-1366999813','22623967','0000-00-00 00:00:00'),(17,6,'6-1366999813','22291487','0000-00-00 00:00:00'),(22,5,'5-1367181443','1344063','0000-00-00 00:00:00'),(23,5,'5-1367181443','13638171','0000-00-00 00:00:00'),(24,6,'6-1367249258','22352818','0000-00-00 00:00:00'),(25,6,'6-1367866196','22137168','0000-00-00 00:00:00'),(26,6,'6-1367866196','22936045','0000-00-00 00:00:00'),(27,6,'6-1367959825','22825329','0000-00-00 00:00:00'),(28,6,'6-1368357864','22244948','0000-00-00 00:00:00'),(29,6,'6-1368357864','22762015','0000-00-00 00:00:00'),(30,6,'6-1368468302','22682630','0000-00-00 00:00:00'),(31,6,'6-1368468302','2274562','0000-00-00 00:00:00'),(32,6,'6-1368468302','22126393','0000-00-00 00:00:00'),(33,6,'6-1368468302','2215641','0000-00-00 00:00:00'),(34,6,'6-1368997146','22787958','0000-00-00 00:00:00'),(35,6,'6-1368997146','22115435','0000-00-00 00:00:00'),(37,4,'4-1370002956','35958649','0000-00-00 00:00:00'),(38,4,'4-1370002956','35941461','0000-00-00 00:00:00'),(39,4,'4-1370252733','35161648','0000-00-00 00:00:00'),(40,4,'4-1370252733','35550664','0000-00-00 00:00:00'),(41,4,'4-1370252733','35259759','0000-00-00 00:00:00'),(42,4,'4-1370254076','35786493','0000-00-00 00:00:00'),(43,4,'4-1370254076','35371128','0000-00-00 00:00:00'),(44,4,'4-1370254076','35251068','0000-00-00 00:00:00'),(45,4,'4-1370254186','35201472','0000-00-00 00:00:00'),(46,4,'4-1370254186','35231169','0000-00-00 00:00:00'),(47,4,'4-1370254186','35721130','0000-00-00 00:00:00'),(48,4,'4-1370254186','35471269','0000-00-00 00:00:00'),(49,4,'4-1370254186','35102665','0000-00-00 00:00:00'),(50,4,'4-1370261371','35609342','0000-00-00 00:00:00'),(51,4,'4-1370305949','36890397','0000-00-00 00:00:00'),(52,4,'4-1370308495','310575850','0000-00-00 00:00:00'),(53,4,'4-1370335262','310685161','0000-00-00 00:00:00'),(54,4,'4-1370335262','310324118','0000-00-00 00:00:00'),(55,4,'4-1370335453','310927173','0000-00-00 00:00:00'),(56,4,'4-1370335834','310906323','0000-00-00 00:00:00'),(57,4,'4-1370335834','310537234','0000-00-00 00:00:00'),(58,4,'4-1370336258','310370770','0000-00-00 00:00:00'),(59,4,'4-1370336421','310987468','0000-00-00 00:00:00'),(60,4,'4-1370336634','310376473','0000-00-00 00:00:00'),(61,4,'4-1370336634','310855828','0000-00-00 00:00:00'),(62,4,'4-1370336634','310391189','0000-00-00 00:00:00'),(63,4,'4-1370336984','35465436','0000-00-00 00:00:00'),(64,4,'4-1370336984','35646893','0000-00-00 00:00:00'),(65,4,'4-1370336984','35148686','0000-00-00 00:00:00'),(66,4,'4-1370336984','35476074','0000-00-00 00:00:00'),(67,4,'4-1370336984','35448796','0000-00-00 00:00:00'),(68,4,'4-1370336984','35892575','0000-00-00 00:00:00'),(69,4,'4-1370336984','35860409','0000-00-00 00:00:00'),(70,4,'4-1370336984','35799232','0000-00-00 00:00:00'),(71,4,'4-1370336984','35134379','0000-00-00 00:00:00'),(72,4,'4-1370336984','35914700','0000-00-00 00:00:00'),(73,4,'4-1370336984','35509772','0000-00-00 00:00:00'),(74,4,'4-1370337178','35147298','0000-00-00 00:00:00'),(75,4,'4-1370337178','35892522','0000-00-00 00:00:00'),(76,4,'4-1370337178','35620773','0000-00-00 00:00:00'),(77,4,'4-1370337178','35884486','0000-00-00 00:00:00'),(78,4,'4-1370337178','35643974','0000-00-00 00:00:00'),(79,4,'4-1370337178','3575668','0000-00-00 00:00:00'),(80,4,'4-1370339008','312594145','0000-00-00 00:00:00'),(81,4,'4-1370339008','312506027','0000-00-00 00:00:00'),(82,4,'4-1370339008','312610678','0000-00-00 00:00:00'),(83,4,'4-1370339008','312993399','0000-00-00 00:00:00'),(84,4,'4-1370339102','312951591','0000-00-00 00:00:00'),(85,4,'4-1370339102','312879421','0000-00-00 00:00:00'),(86,4,'4-1370428266','3568228','0000-00-00 00:00:00'),(87,4,'4-1370428622','35231717','0000-00-00 00:00:00'),(88,4,'4-1370428622','35732479','0000-00-00 00:00:00'),(89,4,'4-1370428622','35266623','0000-00-00 00:00:00'),(90,4,'4-1370428622','35854705','0000-00-00 00:00:00'),(91,4,'4-1370428622','35829776','0000-00-00 00:00:00'),(92,4,'4-1370428622','35335700','0000-00-00 00:00:00'),(93,4,'4-1370428821','35728078','0000-00-00 00:00:00'),(94,4,'4-1370429368','312775208','0000-00-00 00:00:00'),(95,4,'4-1370429368','312647124','0000-00-00 00:00:00'),(96,4,'4-1370429438','312887102','0000-00-00 00:00:00'),(97,4,'4-1370429521','312602202','0000-00-00 00:00:00'),(98,4,'4-1370429521','312256982','0000-00-00 00:00:00'),(99,4,'4-1370429521','312296104','0000-00-00 00:00:00'),(100,6,'6-1370674783','22685130','0000-00-00 00:00:00'),(101,6,'6-1370674783','22613293','0000-00-00 00:00:00'),(102,6,'6-1370674783','22153577','0000-00-00 00:00:00'),(103,6,'6-1370674783','22216058','0000-00-00 00:00:00'),(104,4,'4-1370953049','313970969','0000-00-00 00:00:00'),(105,6,'6-1371371375','22859718','0000-00-00 00:00:00'),(106,6,'6-1371371375','22422793','0000-00-00 00:00:00'),(107,4,'4-1371806376','313116537','0000-00-00 00:00:00'),(108,4,'4-1371806519','36118311','0000-00-00 00:00:00'),(109,4,'4-1371806519','36380946','0000-00-00 00:00:00'),(110,4,'4-1371807490','36869024','0000-00-00 00:00:00'),(111,4,'4-1371807490','36363753','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `ticket_scan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_ticket_type`
--

DROP TABLE IF EXISTS `ticket_ticket_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_ticket_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `places_per_ticket` int(11) DEFAULT NULL,
  `max_tickets_per_order` int(11) DEFAULT NULL,
  `ticket_area_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `fk_ticket_ticket_type_ticket_area1` (`ticket_area_id`),
  CONSTRAINT `fk_ticket_ticket_type_ticket_area1` FOREIGN KEY (`ticket_area_id`) REFERENCES `ticket_area` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_ticket_type`
--

LOCK TABLES `ticket_ticket_type` WRITE;
/*!40000 ALTER TABLE `ticket_ticket_type` DISABLE KEYS */;
INSERT INTO `ticket_ticket_type` VALUES (1,5,'Delegate',59.00,1,10,1),(2,5,'Table',450.00,10,2,1),(3,6,'Adult',29.00,1,NULL,2),(4,6,'Adult',23.00,1,NULL,3),(5,4,'Adults',10.00,1,9,4),(6,4,'Children - under 12\'s',5.00,1,9,4),(7,4,'Concessions',8.00,1,9,4),(8,4,'Family - 2 Adults, 2 children',25.00,4,2,4),(9,4,'Adults',15.00,1,5,5),(10,4,'Table of 6',75.00,6,1,5),(11,4,'Delegate',45.00,1,9,6),(12,4,'Group Discount (10 people)',350.00,10,1,6),(13,4,'Entire Workshop',900.00,30,1,6),(14,5,'Delegate',59.00,1,NULL,7),(15,5,'Delegate',59.00,1,NULL,8),(16,5,'Standard',59.00,1,9,9),(17,7,'Adult',10.00,1,NULL,10),(18,7,'Young Person - Under 26\'s',5.00,1,NULL,10),(19,7,'Adult',10.00,1,NULL,11),(20,7,'Young Person - Under 26\'s',5.00,1,NULL,11),(22,4,'Adults',0.00,1,7,13),(23,4,'Children - ',0.00,1,5,13),(24,7,'Adult',6.00,1,10,14),(25,4,'Adult',0.00,1,12,15),(26,4,'Child',0.00,1,12,15),(27,4,'Family',0.00,6,2,15),(28,4,'Table of 4',0.00,4,1,16),(29,4,'Dinner',0.00,1,4,16),(30,4,'Adults',0.00,1,20,17);
/*!40000 ALTER TABLE `ticket_ticket_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_transaction`
--

DROP TABLE IF EXISTS `ticket_transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `auth_code` varchar(45) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `http_area_id` varchar(255) DEFAULT NULL,
  `http_ticket_type_id` varchar(255) DEFAULT NULL,
  `http_ticket_qty` varchar(255) DEFAULT NULL,
  `http_ticket_price` varchar(255) DEFAULT NULL,
  `http_ticket_total` varchar(255) DEFAULT NULL,
  `http_total` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `vendor_id` (`vendor_id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_transaction`
--

LOCK TABLES `ticket_transaction` WRITE;
/*!40000 ALTER TABLE `ticket_transaction` DISABLE KEYS */;
INSERT INTO `ticket_transaction` VALUES (10,6,'88.96.96.62','2013-04-26 17:07:40','6-1366999706','AuthCode: 026768','Suzannelwhitby@gmail.com','07917044624',2,2,'2','3','4','29.00','116.00','116.00'),(11,6,'87.84.25.246','2013-04-26 17:12:07','6-1366999813','AuthCode: 121876','miss_k_shields@hotmail.com','07894401728',2,2,'2','3','2','29.00','58.00','58.00'),(20,5,'90.210.41.198','2013-04-28 20:42:37','5-1367181443','AuthCode: 965465','nessa0301@hotmail.co.uk','01671403200',1,3,'7','14','2','59.00','118.00','118.00'),(21,6,'92.18.173.212','2013-04-29 15:29:37','6-1367249258','AuthCode: 884936','amieatingwaffles@hotmail.co.uk','01461 339046',2,2,'3','4','1','23.00','23.00','23.00'),(22,6,'87.113.94.125','2013-05-06 18:52:06','6-1367866196','AuthCode: 007866','knitter62@hotmail.com','01683221009',2,2,'2','3','2','29.00','58.00','58.00'),(23,6,'92.18.169.100','2013-05-07 21:04:44','6-1367959825','AuthCode: 044347','katkinson_@hotmail.co.uk','07927145332',2,2,'2','3','1','29.00','29.00','29.00'),(24,6,'176.27.36.39','2013-05-12 11:26:06','6-1368357864','AuthCode: 013884','Mrsahyslop@aol.co.uk','07956325199',2,2,'2','3','2','29.00','58.00','58.00'),(25,6,'31.54.113.26','2013-05-13 18:07:09','6-1368468302','AuthCode: 012254','gaynor.thomson@btinternet.com','07739011315',2,2,'2','3','4','29.00','116.00','116.00'),(26,6,'92.18.163.15','2013-05-19 21:04:51','6-1368997146','AuthCode: 078180','Michaelandcooper@aol.com','07852153654',2,2,'3','4','2','20.70','41.40','41.40'),(27,4,'86.31.215.106','2013-05-31 12:23:56','4-1370002956','AuthCode: 602467','subscriptions@microboot.com','441557870337',3,5,'4','5','1','10.00','10.00','10.00'),(28,4,'86.31.215.106','2013-05-31 14:11:51','4-1370002956','AuthCode: 602467','subscriptions@microboot.com','441557870337',3,5,'4','5','1','10.00','10.00','10.00'),(29,4,'86.31.215.106','2013-06-01 20:32:31','4-1370002956','AuthCode: 602467','subscriptions@microboot.com','441557870337',3,5,'4','5','1','10.00','10.00','10.00'),(30,4,'86.155.91.187','2013-06-03 09:24:32',NULL,NULL,'jo@wireflydesign.com','07777',3,10,'13','22','2','0.00','0.00','0.00'),(31,4,'86.155.91.187','2013-06-03 09:42:22',NULL,NULL,'jo@wireflydesign.com','07777',3,10,'13','22','4','0.00','0.00','0.00'),(32,4,'86.155.91.187','2013-06-03 09:53:13','4-1370252733','AuthCode: 448044','jo@wireflydesign.com','07777',3,5,'4','8','2','25.00','50.00','125.00'),(33,4,'86.155.91.187','2013-06-03 09:53:13','4-1370252733','AuthCode: 448044','jo@wireflydesign.com','07777',3,5,'5','10','1','75.00','75.00','125.00'),(34,4,'86.155.91.187','2013-06-03 10:01:28',NULL,NULL,'jo@wireflydesign.com','07777',3,10,'13','22','2','0.00','0.00','0.00'),(35,4,'86.155.91.187','2013-06-03 10:01:28',NULL,NULL,'jo@wireflydesign.com','07777',3,10,'13','23','1','0.00','0.00','0.00'),(36,4,'86.155.91.187','2013-06-03 10:02:00',NULL,NULL,'jo@wireflydesign.com','07777',3,10,'13','22','2','0.00','0.00','0.00'),(37,4,'86.155.91.187','2013-06-03 10:02:00',NULL,NULL,'jo@wireflydesign.com','07777',3,10,'13','23','2','0.00','0.00','0.00'),(38,4,'86.155.91.187','2013-06-03 10:09:06','4-1370254076','AuthCode: 056258','jo@wireflydesign.com','07777',3,5,'4','8','2','25.00','50.00','125.00'),(39,4,'86.155.91.187','2013-06-03 10:09:06','4-1370254076','AuthCode: 056258','jo@wireflydesign.com','07777',3,5,'5','10','1','75.00','75.00','125.00'),(40,4,'86.155.91.187','2013-06-03 10:10:11','4-1370254186','AuthCode: 981410','jo@wireflydesign.com','07777',3,5,'4','5','2','10.00','20.00','145.00'),(41,4,'86.155.91.187','2013-06-03 10:10:11','4-1370254186','AuthCode: 981410','jo@wireflydesign.com','07777',3,5,'4','8','2','25.00','50.00','145.00'),(42,4,'86.155.91.187','2013-06-03 10:10:11','4-1370254186','AuthCode: 981410','jo@wireflydesign.com','07777',3,5,'5','10','1','75.00','75.00','145.00'),(43,4,'86.31.215.106','2013-06-03 18:46:53','4-1370261371','AuthCode: 602467','subscriptions@microboot.com','441557870337',3,5,'4','5','1','10.00','10.00','10.00'),(44,4,'86.31.215.106','2013-06-04 00:33:39','4-1370305949','AuthCode: 613210','subscriptions@microboot.com','441557870337',3,6,'6','11','1','45.00','45.00','45.00'),(45,4,'86.31.215.106','2013-06-04 01:14:55','4-1370308495',NULL,'subscriptions@microboot.com','441557870337',3,10,'13','22','1','0.00','0.00','0.00'),(46,4,'86.31.215.106','2013-06-04 08:41:02','4-1370335262',NULL,'k@microboot.com','01557 870337',3,10,'13','22','2','0.00','0.00','0.00'),(47,4,'86.31.215.106','2013-06-04 08:44:13','4-1370335453',NULL,'k@microboot.com','01557 870337',3,10,'13','22','1','0.00','0.00','0.00'),(48,4,'86.31.215.106','2013-06-04 08:50:34','4-1370335834',NULL,'k@microboot.com','01557 870337',3,10,'13','22','1','0.00','0.00','0.00'),(49,4,'86.31.215.106','2013-06-04 08:50:34','4-1370335834',NULL,'k@microboot.com','01557 870337',3,10,'13','23','1','0.00','0.00','0.00'),(50,4,'86.31.215.106','2013-06-04 08:57:38','4-1370336258',NULL,'subscriptions@microboot.com','441557870337',3,10,'13','22','1','0.00','0.00','0.00'),(51,4,'86.31.215.106','2013-06-04 09:00:21','4-1370336421',NULL,'subscriptions@microboot.com','441557870337',3,10,'13','22','1','0.00','0.00','0.00'),(52,4,'86.31.215.106','2013-06-04 09:03:54','4-1370336634',NULL,'k@microboot.com','01557 870337',3,10,'13','22','1','0.00','0.00','0.00'),(53,4,'86.31.215.106','2013-06-04 09:03:54','4-1370336634',NULL,'k@microboot.com','01557 870337',3,10,'13','23','2','0.00','0.00','0.00'),(54,4,'86.155.91.187','2013-06-04 09:12:09','4-1370336984','AuthCode: 720097','jo@wireflydesign.com','07777',3,5,'4','5','4','10.00','40.00','105.00'),(55,4,'86.155.91.187','2013-06-04 09:12:09','4-1370336984','AuthCode: 720097','jo@wireflydesign.com','07777',3,5,'4','6','4','5.00','20.00','105.00'),(56,4,'86.155.91.187','2013-06-04 09:12:09','4-1370336984','AuthCode: 720097','jo@wireflydesign.com','07777',3,5,'5','9','3','15.00','45.00','105.00'),(57,4,'86.155.91.187','2013-06-04 09:15:17','4-1370337178','AuthCode: 095067','jo@wireflydesign.com','07777',3,5,'4','5','2','10.00','20.00','60.00'),(58,4,'86.155.91.187','2013-06-04 09:15:17','4-1370337178','AuthCode: 095067','jo@wireflydesign.com','07777',3,5,'4','6','2','5.00','10.00','60.00'),(59,4,'86.155.91.187','2013-06-04 09:15:17','4-1370337178','AuthCode: 095067','jo@wireflydesign.com','07777',3,5,'5','9','2','15.00','30.00','60.00'),(60,4,'86.155.91.187','2013-06-04 09:43:28','4-1370339008',NULL,'jo@wireflydesign.com','07777',3,12,'15','25','2','0.00','0.00','0.00'),(61,4,'86.155.91.187','2013-06-04 09:43:28','4-1370339008',NULL,'jo@wireflydesign.com','07777',3,12,'15','27','2','0.00','0.00','0.00'),(62,4,'86.155.91.187','2013-06-04 09:45:02','4-1370339102',NULL,'jo@wireflydesign.com','07711',3,12,'15','25','2','0.00','0.00','0.00'),(63,4,'86.155.91.187','2013-06-05 10:32:22','4-1370428266','AuthCode: 381746','jo@wireflydesign.com','07777',3,5,'5','10','1','75.00','75.00','75.00'),(64,4,'86.155.91.187','2013-06-05 10:37:32','4-1370428622','AuthCode: 706391','jo@wireflydesign.com','07711',3,5,'4','5','5','10.00','50.00','65.00'),(65,4,'86.155.91.187','2013-06-05 10:37:32','4-1370428622','AuthCode: 706391','jo@wireflydesign.com','07711',3,5,'5','9','1','15.00','15.00','65.00'),(66,4,'86.155.91.187','2013-06-05 10:40:59','4-1370428821','AuthCode: 280142','jo@wireflydesign.com','07722',3,5,'4','8','1','25.00','25.00','25.00'),(67,4,'86.155.91.187','2013-06-05 10:49:28','4-1370429368',NULL,'jo@wireflydesign.com','07711',3,12,'15','27','2','0.00','0.00','0.00'),(68,4,'86.155.91.187','2013-06-05 10:50:38','4-1370429438',NULL,'jo@wireflydesign.com','07722',3,12,'15','27','1','0.00','0.00','0.00'),(69,4,'86.155.91.187','2013-06-05 10:52:01','4-1370429521',NULL,'jo@wireflydesign.com','07733',3,12,'15','25','2','0.00','0.00','0.00'),(70,4,'86.155.91.187','2013-06-05 10:52:01','4-1370429521',NULL,'jo@wireflydesign.com','07733',3,12,'15','27','1','0.00','0.00','0.00'),(71,6,'85.211.200.186','2013-06-08 07:02:58','6-1370674783','AuthCode: T92128','relmkerr@tiscali.co.uk','01461500689',2,2,'2','3','4','29.00','116.00','116.00'),(72,4,'81.151.60.46','2013-06-11 12:17:29','4-1370953049',NULL,'jo@wireflydesign.com','07777',3,13,'16','28','1','0.00','0.00','0.00'),(73,6,'109.153.133.243','2013-06-16 08:31:53','6-1371371375','AuthCode: 912500','Ianbarr@live.co.uk','07850665567',2,2,'2','3','2','29.00','58.00','58.00'),(74,4,'81.151.60.49','2013-06-21 09:19:36','4-1371806376',NULL,'jo@wireflydesign.com','07777',3,13,'16','28','1','0.00','0.00','0.00'),(75,4,'81.151.60.49','2013-06-21 09:21:59','4-1371806519',NULL,'','',3,6,'6','11','1','45.00','45.00','395.00'),(76,4,'81.151.60.49','2013-06-21 09:21:59','4-1371806519',NULL,'','',3,6,'6','12','1','350.00','350.00','395.00'),(77,4,'81.151.60.49','2013-06-21 09:38:10','4-1371807490',NULL,'jo@wireflydesign.com','',3,6,'6','11','2','45.00','90.00','90.00');
/*!40000 ALTER TABLE `ticket_transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_vendor`
--

DROP TABLE IF EXISTS `ticket_vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_vendor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text,
  `post_code` varchar(45) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `vat_number` varchar(45) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_sort_code` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_vendor`
--

LOCK TABLES `ticket_vendor` WRITE;
/*!40000 ALTER TABLE `ticket_vendor` DISABLE KEYS */;
INSERT INTO `ticket_vendor` VALUES (1,5,'Kath Lord-Green','33 Dalmun Ave\r\nDalbeattie','DG5 4PW','kath@insightklg.co.uk','07921072792','','Lloyds TSB','74304463','87-70-41'),(2,6,'Garry Gossip','22 MacDonald Loaning\r\nDumfries','DG13RX','hiya@garrygossip.com','07707711546','','G McCulloch ','00610569','831807'),(3,4,'Joe Public','Sometown\r\nDumfries & Galloway','dg7 1##','','','','','',''),(4,7,'Alex McQuiston','','','mcquiston.concerts@gmail.com','','','','','');
/*!40000 ALTER TABLE `ticket_vendor` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-06-21 13:46:13
