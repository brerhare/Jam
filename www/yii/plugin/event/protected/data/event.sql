SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `plugin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `plugin` ;

-- -----------------------------------------------------
-- Table `plugin`.`event_member`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_member` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_member` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_name` VARCHAR(255) NOT NULL ,
  `password` VARCHAR(255) NOT NULL ,
  `first_name` VARCHAR(255) NOT NULL ,
  `last_name` VARCHAR(255) NOT NULL ,
  `telephone` VARCHAR(45) NULL ,
  `email_address` VARCHAR(255) NOT NULL ,
  `organisation` VARCHAR(255) NULL ,
  `join_date` DATE NOT NULL ,
  `last_login_date` DATE NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`event_program_fields`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_program_fields` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_program_fields` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`event_program`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_program` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_program` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `thumb_path` VARCHAR(255) NULL ,
  `event_program_fields_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_event_program_event_program_fields1` (`event_program_fields_id` ASC) ,
  CONSTRAINT `fk_event_program_event_program_fields1`
    FOREIGN KEY (`event_program_fields_id` )
    REFERENCES `plugin`.`event_program_fields` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`event_event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_event` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_event` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `start` DATETIME NOT NULL ,
  `end` DATETIME NULL ,
  `address` TEXT NULL ,
  `post_code` VARCHAR(255) NULL ,
  `web` VARCHAR(255) NULL ,
  `price_band` VARCHAR(255) NOT NULL ,
  `contact` TEXT NULL ,
  `description` TEXT NOT NULL ,
  `thumb_path` VARCHAR(255) NULL ,
  `member_id` INT NOT NULL ,
  `program_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_event_program` (`program_id` ASC) ,
  INDEX `fk_event_member1` (`member_id` ASC) ,
  CONSTRAINT `fk_event_program`
    FOREIGN KEY (`program_id` )
    REFERENCES `plugin`.`event_program` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_member1`
    FOREIGN KEY (`member_id` )
    REFERENCES `plugin`.`event_member` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`event_interest`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_interest` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_interest` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `icon_path` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`event_format`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_format` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_format` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `icon_path` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`event_event_has_event_interest`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_event_has_event_interest` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_event_has_event_interest` (
  `event_event_id` INT NOT NULL ,
  `event_interest_id` INT NOT NULL ,
  PRIMARY KEY (`event_event_id`, `event_interest_id`) ,
  INDEX `fk_event_event_has_event_interest_event_interest1` (`event_interest_id` ASC) ,
  INDEX `fk_event_event_has_event_interest_event_event1` (`event_event_id` ASC) ,
  CONSTRAINT `fk_event_event_has_event_interest_event_event1`
    FOREIGN KEY (`event_event_id` )
    REFERENCES `plugin`.`event_event` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_event_has_event_interest_event_interest1`
    FOREIGN KEY (`event_interest_id` )
    REFERENCES `plugin`.`event_interest` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`event_event_has_event_format`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_event_has_event_format` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_event_has_event_format` (
  `event_event_id` INT NOT NULL ,
  `event_format_id` INT NOT NULL ,
  PRIMARY KEY (`event_event_id`, `event_format_id`) ,
  INDEX `fk_event_event_has_event_format_event_format1` (`event_format_id` ASC) ,
  INDEX `fk_event_event_has_event_format_event_event1` (`event_event_id` ASC) ,
  CONSTRAINT `fk_event_event_has_event_format_event_event1`
    FOREIGN KEY (`event_event_id` )
    REFERENCES `plugin`.`event_event` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_event_has_event_format_event_format1`
    FOREIGN KEY (`event_format_id` )
    REFERENCES `plugin`.`event_format` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`event_member_has_event_program`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_member_has_event_program` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_member_has_event_program` (
  `event_member_id` INT NOT NULL ,
  `event_program_id` INT NOT NULL ,
  `privilege_level` INT NULL ,
  PRIMARY KEY (`event_member_id`, `event_program_id`) ,
  INDEX `fk_event_member_has_event_program_event_program1` (`event_program_id` ASC) ,
  INDEX `fk_event_member_has_event_program_event_member1` (`event_member_id` ASC) ,
  CONSTRAINT `fk_event_member_has_event_program_event_member1`
    FOREIGN KEY (`event_member_id` )
    REFERENCES `plugin`.`event_member` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_member_has_event_program_event_program1`
    FOREIGN KEY (`event_program_id` )
    REFERENCES `plugin`.`event_program` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`event_feature`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_feature` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_feature` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `icon_path` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`event_event_has_event_feature`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_event_has_event_feature` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_event_has_event_feature` (
  `event_event_id` INT NOT NULL ,
  `event_feature_id` INT NOT NULL ,
  PRIMARY KEY (`event_event_id`, `event_feature_id`) ,
  INDEX `fk_event_event_has_event_feature_event_feature1` (`event_feature_id` ASC) ,
  INDEX `fk_event_event_has_event_feature_event_event1` (`event_event_id` ASC) ,
  CONSTRAINT `fk_event_event_has_event_feature_event_event1`
    FOREIGN KEY (`event_event_id` )
    REFERENCES `plugin`.`event_event` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_event_has_event_feature_event_feature1`
    FOREIGN KEY (`event_feature_id` )
    REFERENCES `plugin`.`event_feature` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
