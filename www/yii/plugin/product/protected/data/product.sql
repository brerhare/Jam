SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


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
  `department_id` INT NOT NULL ,
  `vat_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_product_department1` (`department_id` ASC) ,
  INDEX `fk_product_vat1` (`vat_id` ASC) ,
  INDEX `uid` (`uid` ASC) ,
  CONSTRAINT `fk_product_department1`
    FOREIGN KEY (`department_id` )
    REFERENCES `plugin`.`product_department` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_vat1`
    FOREIGN KEY (`vat_id` )
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
  `department_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_option_department1` (`department_id` ASC) ,
  INDEX `uid` (`uid` ASC) ,
  CONSTRAINT `fk_option_department1`
    FOREIGN KEY (`department_id` )
    REFERENCES `plugin`.`product_department` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`product_product_has_option`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`product_product_has_option` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`product_product_has_option` (
  `product_id` INT NOT NULL ,
  `option_id` INT NOT NULL ,
  `price` DECIMAL(10,2) NULL ,
  PRIMARY KEY (`product_id`, `option_id`) ,
  INDEX `fk_product_has_option_option1` (`option_id` ASC) ,
  INDEX `fk_product_has_option_product1` (`product_id` ASC) ,
  CONSTRAINT `fk_product_has_option_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `plugin`.`product_product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_has_option_option1`
    FOREIGN KEY (`option_id` )
    REFERENCES `plugin`.`product_option` (`id` )
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
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`product_product_has_feature`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`product_product_has_feature` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`product_product_has_feature` (
  `product_id` INT NOT NULL ,
  `feature_id` INT NOT NULL ,
  PRIMARY KEY (`product_id`, `feature_id`) ,
  INDEX `fk_product_has_feature_feature1` (`feature_id` ASC) ,
  INDEX `fk_product_has_feature_product1` (`product_id` ASC) ,
  CONSTRAINT `fk_product_has_feature_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `plugin`.`product_product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_has_feature_feature1`
    FOREIGN KEY (`feature_id` )
    REFERENCES `plugin`.`product_feature` (`id` )
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



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
