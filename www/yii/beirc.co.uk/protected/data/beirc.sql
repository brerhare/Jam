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
  `days` INT NOT NULL ,
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
  `password` VARCHAR(255) NOT NULL ,
  `email` VARCHAR(255) NULL ,
  `member_type_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_member_member_type` (`member_type_id` ASC) ,
  CONSTRAINT `fk_member_member_type`
    FOREIGN KEY (`member_type_id` )
    REFERENCES `beirc_co_uk`.`member_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
