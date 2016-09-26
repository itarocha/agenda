-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: vereador
-- ------------------------------------------------------
-- Server version	5.6.32-log

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
-- Table structure for table `bairros`
--

DROP TABLE IF EXISTS `bairros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bairros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cidade` int(11) NOT NULL,
  `nome` varchar(64) NOT NULL,
  `id_usuario_criacao` int(11) DEFAULT NULL,
  `id_usuario_alteracao` int(11) DEFAULT NULL,
  `data_hora_criacao` timestamp NULL DEFAULT NULL,
  `data_hora_alteracao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bairros_un01` (`nome`,`id_cidade`),
  KEY `bairros_idx01` (`nome`),
  KEY `bairros_fk01_idx` (`id_cidade`),
  CONSTRAINT `bairros_fk01` FOREIGN KEY (`id_cidade`) REFERENCES `cidades` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bairros`
--

LOCK TABLES `bairros` WRITE;
/*!40000 ALTER TABLE `bairros` DISABLE KEYS */;
INSERT INTO `bairros` VALUES (1,1,'Floresta',NULL,NULL,NULL,NULL),(2,1,'Barreiro',NULL,NULL,NULL,NULL),(3,2,'Tubalina',NULL,NULL,NULL,NULL),(4,12,'Trezidela',NULL,NULL,NULL,NULL),(6,3,'Filadélfia',NULL,NULL,NULL,NULL),(7,12,'Inhamum',NULL,NULL,NULL,NULL),(8,1,'Pampulha',NULL,NULL,NULL,NULL),(9,1,'Santa Efigênia',NULL,NULL,NULL,NULL),(10,2,'Tocantins',NULL,NULL,NULL,NULL),(11,2,'Mansour',NULL,NULL,NULL,NULL),(12,2,'Santa Mônica',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `bairros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cidades`
--

DROP TABLE IF EXISTS `cidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(64) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `id_usuario_criacao` int(11) DEFAULT NULL,
  `id_usuario_alteracao` int(11) DEFAULT NULL,
  `data_hora_criacao` timestamp NULL DEFAULT NULL,
  `data_hora_alteracao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cidades_un01` (`nome`),
  KEY `cidades_idx01` (`uf`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cidades`
--

LOCK TABLES `cidades` WRITE;
/*!40000 ALTER TABLE `cidades` DISABLE KEYS */;
INSERT INTO `cidades` VALUES (1,'Belo Horizonte','MG',NULL,NULL,NULL,NULL),(2,'Uberlândia','MG',NULL,NULL,NULL,NULL),(3,'Betim','MG',NULL,NULL,NULL,NULL),(4,'Contagem','MG',NULL,NULL,NULL,NULL),(11,'Ourinhos','GO',NULL,NULL,NULL,NULL),(12,'Caxias','MA',NULL,NULL,NULL,NULL),(13,'Prata','MG',NULL,NULL,NULL,NULL),(15,'Blumenau','SC',NULL,NULL,NULL,NULL),(16,'Uberaba','MG',NULL,NULL,NULL,NULL),(17,'Belo Monte','MG',NULL,NULL,NULL,NULL),(18,'Montes Claros','MG',NULL,NULL,NULL,NULL),(19,'Patrocínio','MG',NULL,NULL,NULL,NULL),(20,'Caldas Novas','GO',NULL,NULL,NULL,NULL),(21,'Lima Duarte','MG',NULL,NULL,NULL,NULL),(22,'Itabirito','MG',NULL,NULL,NULL,NULL),(23,'Nova Iguaçu','RJ',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `cidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contatos`
--

DROP TABLE IF EXISTS `contatos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contatos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(64) NOT NULL,
  `data_nascimento` date NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `titulo` varchar(32) DEFAULT NULL,
  `secao` varchar(8) DEFAULT NULL,
  `zona` varchar(8) DEFAULT NULL,
  `endereco` varchar(64) NOT NULL,
  `numero` varchar(8) NOT NULL,
  `complemento` varchar(32) DEFAULT NULL,
  `id_bairro` int(11) NOT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `telefone1` varchar(16) DEFAULT NULL,
  `telefone2` varchar(16) DEFAULT NULL,
  `telefone3` varchar(16) DEFAULT NULL,
  `telefone4` varchar(16) DEFAULT NULL,
  `telefone5` varchar(16) DEFAULT NULL,
  `ligou` varchar(1) NOT NULL,
  `id_usuario_ligou` int(11) DEFAULT NULL,
  `data_hora_ligou` timestamp NULL DEFAULT NULL,
  `id_usuario_criacao` int(11) DEFAULT NULL,
  `id_usuario_alteracao` int(11) DEFAULT NULL,
  `data_hora_criacao` timestamp NULL DEFAULT NULL,
  `data_hora_alteracao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pessoas_un01` (`cpf`),
  KEY `pessoas_idx01` (`nome`),
  KEY `pessoas_idx02` (`data_nascimento`),
  KEY `pessoas_idx03` (`id_bairro`),
  KEY `pessoas_idx04` (`data_hora_ligou`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contatos`
--

LOCK TABLES `contatos` WRITE;
/*!40000 ALTER TABLE `contatos` DISABLE KEYS */;
INSERT INTO `contatos` VALUES (1,'Itamar Rocha Chaves Junior','1972-06-29','28289579349','15456','354','120','Av. Imbaúba','1400','Bl. 05 ap 204',1,'38413064','34984214666','34984214662','34984214665','34984214660','34984214668','N',NULL,NULL,NULL,NULL,NULL,NULL),(2,'Fernanda Beraldo Barbosa','1978-11-02','04439703607','--','','','Av. Brasil','4513','Apto 304',2,'38400355','3432225813','','','','','S',2,'2016-09-11 22:45:15',NULL,NULL,NULL,NULL),(3,'Raimundo Nonato','2000-04-30','55544433325','','','','Av. dos Andradas','243','Fundos',8,'32400325','','','','','','N',NULL,NULL,NULL,NULL,NULL,NULL),(4,'Pedro Paulo Bial','1930-05-18','44566688877','','','','Avenida Central','400','',7,'65400324','','','','','','N',NULL,NULL,NULL,NULL,NULL,NULL),(5,'Maria Helena Lima Chaves','1930-06-26','44455544433','','','','Rua Três','520','Fundos',12,'44666222','','','','','','N',NULL,NULL,NULL,NULL,NULL,NULL),(6,'Hortência Nazária Felipe Arruda','1984-06-30','55320403762','','','','Av. dos Ipês Floridos','300','',1,'32420077','','','','','','N',NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `contatos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('2014_10_12_000000_create_users_table',1),('2014_10_12_100000_create_password_resets_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES ('itarocha@gmail.com','6bd13783e91bade81c3a1b094cdd55a45f6f25a3255c9f5204419cca54cf75f6','2016-09-10 23:29:15');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isAdmin` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `podeAlterar` varchar(1) CHARACTER SET utf8 DEFAULT NULL,
  `podeIncluir` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Itamar Rocha','itarocha@gmail.com','$2y$10$2yDJaTPbNknLQ.DgEXl42u6gVfbD1JwRQY5Jd1Sb1JnxkSl3tQCd2','8LixlEfpv2S9qRE72CsXk0FWAq6vqoWc1qb5nBplodOMAx76DuOj0k511rpv','2016-09-10 15:41:42','2016-09-20 19:08:28','N','S','N'),(2,'Administrador','admin@gmail.com','$2y$10$6GW.zS1UeRwhZY.79XHhyOo7vVRY9zeULmXpRBua4PCjPivvA0Rrm',NULL,'2016-09-11 00:12:19','2016-09-11 00:12:19','S','N','N'),(3,'Fernanda Beraldo','beraldofernanda@yahoo.com.br','$2y$10$RsA.tBQjkrFQxSqJdo7youeZw9yIVm8Nd36SkoHRVaMDjyjhzKVYm',NULL,NULL,NULL,'N','N','N');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-09-20 13:31:16
