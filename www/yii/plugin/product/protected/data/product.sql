SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `plugin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `plugin` ;

-- -----------------------------------------------------
-- Table `plugin`.`product_department`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`product_department` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`product_department` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`product_vat`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`product_vat` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`product_vat` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `description` VARCHAR(255) NOT NULL ,
  `rate` DECIMAL(10,2) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`product_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`product_product` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`product_product` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL ,
  `weight` DECIMAL(10,2) NULL ,
  `height` DECIMAL(10,2) NULL ,
  `width` DECIMAL(10,2) NULL ,
  `depth` DECIMAL(10,2) NULL ,
  `volume` DECIMAL(10,2) NULL ,
  `duration` INT NULL ,
  `product_department_id` INT NOT NULL ,
  `product_vat_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `fk_product_product_product_department1` (`product_department_id` ASC) ,
  INDEX `fk_product_product_product_vat1` (`product_vat_id` ASC) ,
  CONSTRAINT `fk_product_product_product_department1`
    FOREIGN KEY (`product_department_id` )
    REFERENCES `plugin`.`product_department` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_product_product_vat1`
    FOREIGN KEY (`product_vat_id` )
    REFERENCES `plugin`.`product_vat` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`product_option`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`product_option` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`product_option` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `product_department_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `fk_product_option_product_department1` (`product_department_id` ASC) ,
  CONSTRAINT `fk_product_option_product_department1`
    FOREIGN KEY (`product_department_id` )
    REFERENCES `plugin`.`product_department` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`product_feature`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`product_feature` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`product_feature` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `product_department_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `fk_product_feature_product_department1` (`product_department_id` ASC) ,
  CONSTRAINT `fk_product_feature_product_department1`
    FOREIGN KEY (`product_department_id` )
    REFERENCES `plugin`.`product_department` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`product_image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`product_image` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`product_image` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `filename` VARCHAR(255) NOT NULL ,
  `product_product_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_image_product_product1` (`product_product_id` ASC) ,
  INDEX `uid` (`uid` ASC) ,
  CONSTRAINT `fk_image_product_product1`
    FOREIGN KEY (`product_product_id` )
    REFERENCES `plugin`.`product_product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`product_product_has_product_feature`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`product_product_has_product_feature` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`product_product_has_product_feature` (
  `product_product_id` INT NOT NULL ,
  `product_feature_id` INT NOT NULL ,
  PRIMARY KEY (`product_product_id`, `product_feature_id`) ,
  INDEX `fk_product_product_has_product_feature_product_feature1` (`product_feature_id` ASC) ,
  INDEX `fk_product_product_has_product_feature_product_product1` (`product_product_id` ASC) ,
  CONSTRAINT `fk_product_product_has_product_feature_product_product1`
    FOREIGN KEY (`product_product_id` )
    REFERENCES `plugin`.`product_product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_product_has_product_feature_product_feature1`
    FOREIGN KEY (`product_feature_id` )
    REFERENCES `plugin`.`product_feature` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`product_product_has_product_option`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`product_product_has_product_option` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`product_product_has_product_option` (
  `product_product_id` INT NOT NULL ,
  `product_option_id` INT NOT NULL ,
  `price` DECIMAL(10,2) NULL ,
  PRIMARY KEY (`product_product_id`, `product_option_id`) ,
  INDEX `fk_product_product_has_product_option_product_option1` (`product_option_id` ASC) ,
  INDEX `fk_product_product_has_product_option_product_product1` (`product_product_id` ASC) ,
  CONSTRAINT `fk_product_product_has_product_option_product_product1`
    FOREIGN KEY (`product_product_id` )
    REFERENCES `plugin`.`product_product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_product_has_product_option_product_option1`
    FOREIGN KEY (`product_option_id` )
    REFERENCES `plugin`.`product_option` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
