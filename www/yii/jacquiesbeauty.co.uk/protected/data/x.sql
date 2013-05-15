SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `insightklg_co_uk` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `insightklg_co_uk` ;

-- -----------------------------------------------------
-- Table `insightklg_co_uk`.`carousel_block`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `insightklg_co_uk`.`carousel_block` ;

CREATE  TABLE IF NOT EXISTS `insightklg_co_uk`.`carousel_block` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sequence` INT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL ,
  `active` VARCHAR(1) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
