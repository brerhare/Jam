SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `content_block`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `content_block` ;

CREATE  TABLE IF NOT EXISTS `content_block` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `parent_id` INT NULL ,
  `sequence` INT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `url` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL ,
  `active` INT NULL ,
  `home` INT NULL ,
  `meta_title` TEXT NULL ,
  `meta_description` TEXT NULL ,
  `meta_keywords` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) ,
  INDEX `parent_id` (`parent_id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `carousel_block`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `carousel_block` ;

CREATE  TABLE IF NOT EXISTS `carousel_block` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sequence` INT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tab_block`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tab_block` ;

CREATE  TABLE IF NOT EXISTS `tab_block` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sequence` INT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL ,
  `image` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `accordion_block`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `accordion_block` ;

CREATE  TABLE IF NOT EXISTS `accordion_block` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sequence` INT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `url` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL ,
  `image` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jelly_slider_image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jelly_slider_image` ;

CREATE  TABLE IF NOT EXISTS `jelly_slider_image` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sequence` INT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `image` VARCHAR(255) NULL ,
  `url` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jelly_slider_html`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jelly_slider_html` ;

CREATE  TABLE IF NOT EXISTS `jelly_slider_html` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sequence` INT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jelly_download`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jelly_download` ;

CREATE  TABLE IF NOT EXISTS `jelly_download` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sequence` INT NULL ,
  `description` VARCHAR(255) NOT NULL ,
  `file` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jelly_gallery`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jelly_gallery` ;

CREATE  TABLE IF NOT EXISTS `jelly_gallery` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sequence` INT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `text` TEXT NULL ,
  `image` VARCHAR(255) NULL ,
  `active` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jelly_gallery_image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jelly_gallery_image` ;

CREATE  TABLE IF NOT EXISTS `jelly_gallery_image` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sequence` INT NULL ,
  `text` TEXT NULL ,
  `image` VARCHAR(255) NOT NULL ,
  `url` VARCHAR(255) NULL ,
  `jelly_gallery_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) ,
  INDEX `fk_jelly_gallery_image_jelly_gallery` (`jelly_gallery_id` ASC) ,
  CONSTRAINT `fk_jelly_gallery_image_jelly_gallery`
    FOREIGN KEY (`jelly_gallery_id` )
    REFERENCES `jelly_gallery` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jelly_download_collection`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jelly_download_collection` ;

CREATE  TABLE IF NOT EXISTS `jelly_download_collection` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jelly_download_file`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jelly_download_file` ;

CREATE  TABLE IF NOT EXISTS `jelly_download_file` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `filename` VARCHAR(255) NOT NULL ,
  `description` VARCHAR(255) NULL ,
  `jelly_download_collection_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_jelly_download_file_jelly_download_collection1` (`jelly_download_collection_id` ASC) ,
  CONSTRAINT `fk_jelly_download_file_jelly_download_collection1`
    FOREIGN KEY (`jelly_download_collection_id` )
    REFERENCES `jelly_download_collection` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
