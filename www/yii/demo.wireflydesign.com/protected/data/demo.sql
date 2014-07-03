-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: demo_wireflydesign_com
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
-- Table structure for table `accordion_block`
--

DROP TABLE IF EXISTS `accordion_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accordion_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sequence` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `content` text,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sequence` (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accordion_block`
--

LOCK TABLES `accordion_block` WRITE;
/*!40000 ALTER TABLE `accordion_block` DISABLE KEYS */;
/*!40000 ALTER TABLE `accordion_block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carousel_block`
--

DROP TABLE IF EXISTS `carousel_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carousel_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sequence` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `sequence` (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carousel_block`
--

LOCK TABLES `carousel_block` WRITE;
/*!40000 ALTER TABLE `carousel_block` DISABLE KEYS */;
/*!40000 ALTER TABLE `carousel_block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_block`
--

DROP TABLE IF EXISTS `content_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `content` text,
  `active` int(11) DEFAULT NULL,
  `home` int(11) DEFAULT NULL,
  `meta_title` text,
  `meta_description` text,
  `meta_keywords` text,
  PRIMARY KEY (`id`),
  KEY `sequence` (`sequence`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_block`
--

LOCK TABLES `content_block` WRITE;
/*!40000 ALTER TABLE `content_block` DISABLE KEYS */;
INSERT INTO `content_block` VALUES (1,0,NULL,'Home','index','<p>This is the home page</p>',1,1,'','',''),(2,0,NULL,'Products','products','<p>The user guide to the Product and shopping basket can be downloaded <a href=\"http://demo.wireflydesign.com/?layout=index&amp;page=user-guides\" target=\"_blank\">from here</a>&nbsp;</p><p><b><i><b><i>{{department 91 Gifts}}</i></b></i></b></p>\r\n',1,0,'','',''),(4,2,NULL,'Product two','product-two','<p>Product two</p>',0,0,'','',''),(7,1,NULL,'Fonts','font-test','<h1>Header 1</h1>\r\n<p>Lorem ipsum dolor sit amet, <a href=\"www.wireflydesign.com\" target=\"_blank\"></a><a href=\"www.wireflydesign.com\" target=\"_blank\">wireflydesign</a> consectetur adipiscing elit. <a href=\"?layout=index&amp;page=test\">Sed hendrerit</a> eu magna lobortis accumsan. Nulla facilisi. Praesent molestie porta lectus, nec condimentum lacus porttitor vel. Donec sollicitudin turpis sed facilisis volutpat. Quisque posuere felis dolor, vel lobortis mi adipiscing ac. In nec tempor sapien. Vivamus imperdiet purus a neque dictum, a tincidunt velit tempus. Etiam turpis turpis, dignissim vel velit id, hendrerit faucibus arcu. Maecenas ac hendrerit tellus. Cras congue ante ut ipsum pretium placerat.</p>\r\n\r\n<h2>Header 2</h2>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed hendrerit eu magna lobortis accumsan. Nulla facilisi. Praesent molestie porta lectus, nec condimentum lacus porttitor vel. Donec sollicitudin turpis sed facilisis volutpat. Quisque posuere felis dolor, vel lobortis mi adipiscing ac. In nec tempor sapien.</p>\r\n\r\n<h3>Header 3</h3>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed hendrerit eu magna lobortis accumsan. Nulla facilisi. Praesent molestie porta lectus, nec condimentum lacus porttitor vel. Donec sollicitudin turpis sed facilisis volutpat. Quisque posuere felis dolor, vel lobortis mi adipiscing ac. In nec tempor sapien.</p>\r\n\r\n<h4>Header 4</h4>\r\n<blockquote>Quote : Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed hendrerit eu magna lobortis accumsan. Nulla facilisi. Praesent molestie porta lectus, nec condimentum lacus porttitor vel. Donec sollicitudin turpis sed facilisis volutpat. Quisque posuere felis dolor, vel lobortis mi adipiscing ac. In nec tempor sapien. Vivamus imperdiet purus a neque dictum, a tincidunt velit tempus. Etiam turpis turpis, dignissim vel velit id, hendrerit faucibus arcu. Maecenas ac hendrerit tellus. Cras congue ante ut ipsum pretium placerat.</blockquote>\r\n\r\n<p>paragraph - Donec ut iaculis arcu, sit amet bibendum turpis. Aenean id nisi gravida mauris luctus imperdiet vitae in erat. Fusce sed libero at sem consequat lacinia sit amet vitae lectus. Quisque non condimentum enim, et lobortis diam. Donec placerat, sem vel dignissim pretium, nibh justo vehicula justo, sed sagittis quam urna id magna. Nulla porta sollicitudin velit, vitae consectetur ante feugiat sit amet. Proin commodo felis in condimentum aliquam. Nam sodales nunc posuere diam iaculis, non euismod nisl consectetur. Duis vel nisi nec odio rhoncus eleifend vel eu nunc. Curabitur blandit at elit sit amet condimentum.</p>\r\n\r\n<p>Nulla consequat ligula a magna sagittis eleifend. Curabitur dictum, velit vel aliquet dignissim, velit urna gravida eros, in gravida dui risus quis erat. In mattis scelerisque risus, ac luctus erat iaculis ac. Nam volutpat venenatis lectus. Etiam quis neque ac dolor tempor gravida nec eget ligula. Curabitur nec felis quis odio pretium iaculis at id ipsum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce blandit sem vitae nunc porta, porttitor mollis lorem vulputate.</p>\r\n\r\n<p>Ut convallis velit sed massa faucibus sagittis. Vivamus a elit ut sem varius consequat sit amet nec dolor. Donec eu eros accumsan orci pulvinar euismod. Cras pellentesque lorem adipiscing, fringilla arcu non, adipiscing lectus. Nullam vitae pretium elit. Suspendisse potenti. Nulla eu ligula mollis, pretium quam interdum, sodales elit. Phasellus vel malesuada enim, pulvinar placerat nunc. Pellentesque pretium consequat semper. Proin et ultrices risus. Pellentesque massa orci, molestie quis blandit ut, posuere quis erat. Phasellus sit amet libero iaculis, commodo purus id, ornare metus. Donec placerat et ante at adipiscing. Nam vehicula viverra magna a eleifend.</p>\r\n<p>Proin a est sollicitudin, rhoncus nisl a, suscipit elit. Etiam ac rhoncus libero, a volutpat nulla. Ut at ultrices mauris. Morbi vitae congue libero. Nullam at enim imperdiet turpis consequat blandit fringilla sed nisi. Sed ac sem ante. Etiam faucibus tempus aliquam. Curabitur enim arcu, rhoncus non pharetra gravida, tincidunt eget metus. Suspendisse ut imperdiet lorem, quis tincidunt risus. Phasellus eget egestas massa. Etiam congue, lectus ac ullamcorper semper, augue tortor molestie nisl, et auctor ante nisi at nunc. Donec turpis tortor, pulvinar sit amet consectetur vel, auctor sit amet nisl.</p>\r\n',1,0,'','',''),(8,0,NULL,'Tickets','tickets','<p>To log into the backend for this demo account please head to our <a href=\"http://plugin.wireflydesign.com/ticket/backend.php\" target=\"_blank\">ticketing backend</a>. The log in is simply demo / demo. You can pick up our user guide in <a href=\"http://demo.wireflydesign.com/?layout=index&amp;page=user-guides\" target=\"_blank\">this page</a></p><p>There are 3 main parts to your ticketing, create your event (date description etc) create the number of tickets in the Area section, then create your ticket types and prices under each area.</p><p><span style=\"font-family: Arial, Helvetica, Verdana, Tahoma, sans-serif; font-size: 15px; line-height: 1.45em;\">Some live examples can be seen on </span><a href=\"http://www.electricfieldsfestival.com/\" target=\"_blank\" style=\"font-family: Arial, Helvetica, Verdana, Tahoma, sans-serif; font-size: 15px; line-height: 1.45em;\">Electric Fields Festival</a><span style=\"font-family: Arial, Helvetica, Verdana, Tahoma, sans-serif; font-size: 15px; line-height: 1.45em;\">, </span><a href=\"http://www.absoluteclassics.co.uk/\" target=\"_blank\" style=\"font-family: Arial, Helvetica, Verdana, Tahoma, sans-serif; font-size: 15px; line-height: 1.45em;\">Absolute Classics</a><span style=\"font-family: Arial, Helvetica, Verdana, Tahoma, sans-serif; font-size: 15px; line-height: 1.45em;\"> and the </span><a href=\"http://www.dglink.co.uk/\" target=\"_blank\" style=\"font-family: Arial, Helvetica, Verdana, Tahoma, sans-serif; font-size: 15px; line-height: 1.45em;\">DG Link</a></p><p>Below you will see the results of your ticketing. This will only show active events...</p>\r\n\r\n<iframe width=\"100%\" scrolling=\"no\" style=\"overflow: hidden; height: 459px;\" src=\"https://plugin.wireflydesign.com/ticket/index.php/ticket?sid=demo\" &amp;ref=\"none&quot;\" id=\"iFrameSizer0\"></iframe>\r\n\r\n<hr>\r\n\r\n<p>An inline ticket event bypasses the event selection and displays as follows. You can book directly from here. Note that this will show a single event,&nbsp;<b style=\"font-style: inherit; font-variant: inherit; line-height: 1.45em;\">whether active or not.</b></p><p></p><div>(Internal Lik)</div>&nbsp;{{ticket 228 Looking up}}&nbsp;<p><br></p>\r\n',1,0,'','',''),(9,7,NULL,'Support','support','<p>For support enquiries</p>',1,0,NULL,NULL,NULL),(10,0,NULL,'Galleries','galleries','<p>{{gallery}}</p><p>Some other content on the page</p>\r\n',1,0,NULL,NULL,NULL),(12,0,NULL,'News','text-blog','<p>{{blog}}</p>',0,0,'','',''),(13,0,NULL,'Checkout','checkout','<h1>Your Shopping Basket</h1><p>If you would like to test the shopping cart all the way through to completion you can download this file and use the {{download file PDF Test Cards}}</p><p>{{checkout}}\r\n</p><p></p>\r\n<p></p>\r\n',0,0,'','',''),(14,0,NULL,'Downloads','downloads','<p>This is a list of credit card details to use for the demo checkout {{download file PDF Test Cards}} (PDF format)<br></p>',0,0,'','',''),(15,0,NULL,'Event','event','<h1>Test events for program 12</h1>\r\n<iframe style=\"overflow-x: hidden; overflow-y: auto;\" src=\"http://plugin.wireflydesign.com/event/?programid=12\" height=\"2000\" width=\"750\"></iframe>\'\r\n',1,0,'','',''),(16,0,NULL,'User Guides','user-guides','<h1>User Guides</h1><div>Below you will find some of our user guides that you can use in conjunction with this demo site. They will open in the same window.</div><p>{{download collection User Guides}}</p>\r\n',0,0,'','',''),(17,0,NULL,'Blog','blog','<h1>My Test Blog</h1><p>{{blog}}</p>',1,0,'','',''),(18,0,NULL,'Blog750','blog750','<p>{{blog750}}</p>',1,0,'','',''),(19,8,NULL,'Electric Field Ticket','deep-link-test','<h1>Deep Link Test</h1><p><br></p>\r\n\r\n<iframe width=\"100%\" scrolling=\"no\" style=\"overflow: hidden;\" src=\"https://plugin.wireflydesign.com/ticket/index.php/ticket/book/117?sid=jb95lgqpoc6i8min1sj6ofc0k7&amp;ref=none\" id=\"iFrameSizer0\"></iframe>\r\n',1,0,'','','');
/*!40000 ALTER TABLE `content_block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jelly_download_collection`
--

DROP TABLE IF EXISTS `jelly_download_collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jelly_download_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jelly_download_collection`
--

LOCK TABLES `jelly_download_collection` WRITE;
/*!40000 ALTER TABLE `jelly_download_collection` DISABLE KEYS */;
INSERT INTO `jelly_download_collection` VALUES (2,'Test Card Details'),(3,'User Guides');
/*!40000 ALTER TABLE `jelly_download_collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jelly_download_file`
--

DROP TABLE IF EXISTS `jelly_download_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jelly_download_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `jelly_download_collection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_jelly_download_file_jelly_download_collection1` (`jelly_download_collection_id`),
  CONSTRAINT `fk_jelly_download_file_jelly_download_collection1` FOREIGN KEY (`jelly_download_collection_id`) REFERENCES `jelly_download_collection` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jelly_download_file`
--

LOCK TABLES `jelly_download_file` WRITE;
/*!40000 ALTER TABLE `jelly_download_file` DISABLE KEYS */;
INSERT INTO `jelly_download_file` VALUES (2,'TestCardDetails.pdf','PDF Test Cards',2),(5,'Wirefly Product User Guide 1401.pdf','Product User Guide',3),(6,'Ticketing Backend Instructions 1405.pdf','Ticketing',3),(7,'Wirefly JellyShell Backend 1405.pdf','Website Pages',3);
/*!40000 ALTER TABLE `jelly_download_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jelly_gallery`
--

DROP TABLE IF EXISTS `jelly_gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jelly_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sequence` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `text` text,
  `image` varchar(255) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sequence` (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jelly_gallery`
--

LOCK TABLES `jelly_gallery` WRITE;
/*!40000 ALTER TABLE `jelly_gallery` DISABLE KEYS */;
INSERT INTO `jelly_gallery` VALUES (1,10,'Artists in the Area','Here we have a lovely collection of local Artists work','background4.jpg',1),(2,20,'Local Viewpoints','For those photographers among you here are some shots of the local area to tempt you outdoors.','Allan image 1.jpg',1);
/*!40000 ALTER TABLE `jelly_gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jelly_gallery_image`
--

DROP TABLE IF EXISTS `jelly_gallery_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jelly_gallery_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sequence` int(11) DEFAULT NULL,
  `text` text,
  `image` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `jelly_gallery_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sequence` (`sequence`),
  KEY `fk_jelly_gallery_image_jelly_gallery` (`jelly_gallery_id`),
  CONSTRAINT `fk_jelly_gallery_image_jelly_gallery` FOREIGN KEY (`jelly_gallery_id`) REFERENCES `jelly_gallery` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jelly_gallery_image`
--

LOCK TABLES `jelly_gallery_image` WRITE;
/*!40000 ALTER TABLE `jelly_gallery_image` DISABLE KEYS */;
INSERT INTO `jelly_gallery_image` VALUES (1,10,'This is a close up of a wet weaved wall hanging that measured 3 foot wide by 2 foot high. Needless to say it didn\'t stay in the gallery for long','background3.jpg','',1),(2,20,'Here we have a fish bowl, almost as literal as a real live fish. Again using wet weave techniques.','fish bowl.jpg','',1),(3,30,'The Poppy bowl, standinh about 8\" high','poppy bowl.jpg','',1),(4,40,'And another wall hanging in blue abstract','blue wall art temp.jpg','',1),(5,10,'A view over Loch Ken','Loch Ken.jpg','',2),(6,20,'Merrick','Merrick.jpg','',2),(7,30,'Some great fishing to be found on the Tay','fishing the Tay.jpg','',2),(8,40,'Rockcliffe looking like a fun family destination','DSCF1895.JPG','',2),(9,50,'Plenty of space for outdoor sports','craig and cab blokart13.jpg','',2);
/*!40000 ALTER TABLE `jelly_gallery_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jelly_slider_html`
--

DROP TABLE IF EXISTS `jelly_slider_html`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jelly_slider_html` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sequence` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `sequence` (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jelly_slider_html`
--

LOCK TABLES `jelly_slider_html` WRITE;
/*!40000 ALTER TABLE `jelly_slider_html` DISABLE KEYS */;
/*!40000 ALTER TABLE `jelly_slider_html` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jelly_slider_image`
--

DROP TABLE IF EXISTS `jelly_slider_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jelly_slider_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slider` int(11) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sequence` (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jelly_slider_image`
--

LOCK TABLES `jelly_slider_image` WRITE;
/*!40000 ALTER TABLE `jelly_slider_image` DISABLE KEYS */;
INSERT INTO `jelly_slider_image` VALUES (2,1,10,'shell sand','shell.jpg','http://demo.wireflydesign.com/?layout=index&page=products'),(3,1,20,'kids sky','children sky.jpg','http://demo.wireflydesign.com/?layout=index&page=galleries'),(4,1,30,'View of Screel','Screel.jpg','http://demo.wireflydesign.com/?layout=index&page=text-blog');
/*!40000 ALTER TABLE `jelly_slider_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tab_block`
--

DROP TABLE IF EXISTS `tab_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tab_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sequence` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sequence` (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tab_block`
--

LOCK TABLES `tab_block` WRITE;
/*!40000 ALTER TABLE `tab_block` DISABLE KEYS */;
INSERT INTO `tab_block` VALUES (1,10,'Recommended','<p></p><p><img src=\"/userdata/tab/1771239516.jpg\" style=\"width: 134.66666666666666px; height: 202px; float: right; margin: 0px 0px 10px 10px;\" alt=\"Smoked Salmon\"></p><h1>Recommended Product of the Month</h1>We have just in some smoked salmon, caught on the Solway Coast using the traditional haaf nets&nbsp;<p></p>\r\n',NULL),(2,20,'Most Popular','<p><p><img src=\"/userdata/tab/1183367806.jpg\" style=\"width: 247.5px; height: 165px; float: left; margin: 0px 10px 10px 0px;\" alt=\"\"></p>Our venison is proving to be particularly popular with our regular customers.</p>',NULL);
/*!40000 ALTER TABLE `tab_block` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-06-25 13:32:36
