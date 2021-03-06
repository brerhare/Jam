SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `opendoorsart_com`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `opendoorsart_com`.`category` ;

CREATE  TABLE IF NOT EXISTS `opendoorsart_com`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `opendoorsart_com`.`food_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `opendoorsart_com`.`food_type` ;

CREATE  TABLE IF NOT EXISTS `opendoorsart_com`.`food_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `opendoorsart_com`.`member`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `opendoorsart_com`.`member` ;

CREATE  TABLE IF NOT EXISTS `opendoorsart_com`.`member` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(255) NOT NULL ,
  `password` VARCHAR(255) NOT NULL ,
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
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `opendoorsart_com`.`member_has_food_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `opendoorsart_com`.`member_has_food_type` ;

CREATE  TABLE IF NOT EXISTS `opendoorsart_com`.`member_has_food_type` (
  `member_id` INT NOT NULL ,
  `food_type_id` INT NOT NULL ,
  PRIMARY KEY (`member_id`, `food_type_id`) ,
  INDEX `fk_member_has_food_type_food_type1` (`food_type_id` ASC) ,
  INDEX `fk_member_has_food_type_member` (`member_id` ASC) ,
  CONSTRAINT `fk_member_has_food_type_member`
    FOREIGN KEY (`member_id` )
    REFERENCES `opendoorsart_com`.`member` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_member_has_food_type_food_type1`
    FOREIGN KEY (`food_type_id` )
    REFERENCES `opendoorsart_com`.`food_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `opendoorsart_com`.`member_has_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `opendoorsart_com`.`member_has_category` ;

CREATE  TABLE IF NOT EXISTS `opendoorsart_com`.`member_has_category` (
  `member_id` INT NOT NULL ,
  `category_id` INT NOT NULL ,
  PRIMARY KEY (`member_id`, `category_id`) ,
  INDEX `fk_member_has_category_category1` (`category_id` ASC) ,
  INDEX `fk_member_has_category_member1` (`member_id` ASC) ,
  CONSTRAINT `fk_member_has_category_member1`
    FOREIGN KEY (`member_id` )
    REFERENCES `opendoorsart_com`.`member` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_member_has_category_category1`
    FOREIGN KEY (`category_id` )
    REFERENCES `opendoorsart_com`.`category` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
