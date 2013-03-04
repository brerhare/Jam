SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `glitzaratti_com` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `glitzaratti_com` ;

-- -----------------------------------------------------
-- Table `glitzaratti_com`.`gallery`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glitzaratti_com`.`gallery` ;

CREATE  TABLE IF NOT EXISTS `glitzaratti_com`.`gallery` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `carousel` TINYINT(1) NULL ,
  `filter` TINYINT(1) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `glitzaratti_com`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glitzaratti_com`.`category` ;

CREATE  TABLE IF NOT EXISTS `glitzaratti_com`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `glitzaratti_com`.`size`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glitzaratti_com`.`size` ;

CREATE  TABLE IF NOT EXISTS `glitzaratti_com`.`size` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `text` VARCHAR(45) NULL ,
  `is_a_default` TINYINT(1) NULL ,
  `category_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_size_category` (`category_id` ASC) ,
  CONSTRAINT `fk_size_category`
    FOREIGN KEY (`category_id` )
    REFERENCES `glitzaratti_com`.`category` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `glitzaratti_com`.`product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glitzaratti_com`.`product` ;

CREATE  TABLE IF NOT EXISTS `glitzaratti_com`.`product` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `price` DECIMAL(10,2) NOT NULL ,
  `description` TEXT NOT NULL ,
  `weight_kg` DECIMAL(6,4) NULL ,
  `pack_height_mm` INT NULL ,
  `pack_width_mm` INT NULL ,
  `pack_depth_mm` INT NULL ,
  `category_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_product_category1` (`category_id` ASC) ,
  CONSTRAINT `fk_product_category1`
    FOREIGN KEY (`category_id` )
    REFERENCES `glitzaratti_com`.`category` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `glitzaratti_com`.`image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glitzaratti_com`.`image` ;

CREATE  TABLE IF NOT EXISTS `glitzaratti_com`.`image` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `filename` VARCHAR(255) NULL ,
  `product_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_image_product1` (`product_id` ASC) ,
  CONSTRAINT `fk_image_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `glitzaratti_com`.`product` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `glitzaratti_com`.`gallery_has_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glitzaratti_com`.`gallery_has_product` ;

CREATE  TABLE IF NOT EXISTS `glitzaratti_com`.`gallery_has_product` (
  `gallery_id` INT NOT NULL ,
  `product_id` INT NOT NULL ,
  PRIMARY KEY (`gallery_id`, `product_id`) ,
  INDEX `fk_gallery_has_product_product1` (`product_id` ASC) ,
  INDEX `fk_gallery_has_product_gallery1` (`gallery_id` ASC) ,
  CONSTRAINT `fk_gallery_has_product_gallery1`
    FOREIGN KEY (`gallery_id` )
    REFERENCES `glitzaratti_com`.`gallery` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_gallery_has_product_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `glitzaratti_com`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `glitzaratti_com`.`contact`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glitzaratti_com`.`contact` ;

CREATE  TABLE IF NOT EXISTS `glitzaratti_com`.`contact` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  `email` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
