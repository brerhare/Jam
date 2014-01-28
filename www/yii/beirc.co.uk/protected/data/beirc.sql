SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `beirc_co_uk` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `beirc_co_uk` ;

-- -----------------------------------------------------
-- Table `beirc_co_uk`.`member_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `beirc_co_uk`.`member_type` ;

CREATE  TABLE IF NOT EXISTS `beirc_co_uk`.`member_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(255) NOT NULL ,
  `slots` INT NOT NULL ,
  `week_month` INT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `beirc_co_uk`.`member`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `beirc_co_uk`.`member` ;

CREATE  TABLE IF NOT EXISTS `beirc_co_uk`.`member` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(255) NOT NULL ,
  `password` INT(11) NOT NULL ,
  `displayname` VARCHAR(255) NULL ,
  `email` VARCHAR(255) NULL ,
  `member_type_id` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_member_member_type` () ,
  INDEX `PASSWORD` (`password` ASC) ,
  CONSTRAINT `fk_member_member_type`
    FOREIGN KEY ()
    REFERENCES `beirc_co_uk`.`member_type` ()
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `beirc_co_uk`.`event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `beirc_co_uk`.`event` ;

CREATE  TABLE IF NOT EXISTS `beirc_co_uk`.`event` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `arena` INT NOT NULL ,
  `description` VARCHAR(255) NOT NULL ,
  `start` DATETIME NULL ,
  `end` DATETIME NULL ,
  `share` INT NULL ,
  `confirmed` INT NULL ,
  `password` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `password` (`password` ASC) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
