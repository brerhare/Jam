SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `fadguide_com` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `fadguide_com` ;

-- -----------------------------------------------------
-- Table `fadguide_com`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fadguide_com`.`category` ;

CREATE  TABLE IF NOT EXISTS `fadguide_com`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fadguide_com`.`food_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fadguide_com`.`food_type` ;

CREATE  TABLE IF NOT EXISTS `fadguide_com`.`food_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fadguide_com`.`member`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fadguide_com`.`member` ;

CREATE  TABLE IF NOT EXISTS `fadguide_com`.`member` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `approved` INT NULL ,
  `business_name` VARCHAR(255) NOT NULL ,
  `address1` VARCHAR(255) NULL ,
  `address2` VARCHAR(255) NULL ,
  `address3` VARCHAR(255) NULL ,
  `address4` VARCHAR(255) NULL ,
  `postcode` VARCHAR(10) NULL ,
  `contact` VARCHAR(255) NULL ,
  `web` VARCHAR(255) NULL ,
  `email` VARCHAR(255) NULL ,
  `phone` VARCHAR(255) NULL ,
  `opening_hours` VARCHAR(255) NULL ,
  `html_content` TEXT NULL ,
  `logo_path` VARCHAR(255) NULL ,
  `slider_image_path` VARCHAR(255) NULL ,
  `public` INT NULL ,
  `category_id` INT NOT NULL ,
  `food_type_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_member_category` (`category_id` ASC) ,
  INDEX `fk_member_food_type1` (`food_type_id` ASC) ,
  CONSTRAINT `fk_member_category`
    FOREIGN KEY (`category_id` )
    REFERENCES `fadguide_com`.`category` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_member_food_type1`
    FOREIGN KEY (`food_type_id` )
    REFERENCES `fadguide_com`.`food_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
