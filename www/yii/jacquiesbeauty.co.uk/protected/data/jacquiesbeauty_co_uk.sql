SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `jacquiesbeauty_co_uk`.`content_block`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jacquiesbeauty_co_uk`.`content_block` ;

CREATE  TABLE IF NOT EXISTS `jacquiesbeauty_co_uk`.`content_block` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `parent_id` INT NULL ,
  `sequence` INT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `url` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL ,
  `active` INT NULL ,
  `home` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) ,
  INDEX `parent_id` (`parent_id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jacquiesbeauty_co_uk`.`carousel_block`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jacquiesbeauty_co_uk`.`carousel_block` ;

CREATE  TABLE IF NOT EXISTS `jacquiesbeauty_co_uk`.`carousel_block` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sequence` INT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jacquiesbeauty_co_uk`.`tab_block`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jacquiesbeauty_co_uk`.`tab_block` ;

CREATE  TABLE IF NOT EXISTS `jacquiesbeauty_co_uk`.`tab_block` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sequence` INT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL ,
  `image` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
