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
  `captcha` VARCHAR(45) NULL ,
  `sid` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `user_name_UNIQUE` (`user_name` ASC) )
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
  `icon_path` VARCHAR(255) NULL ,
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
-- Table `plugin`.`event_price_band`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_price_band` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_price_band` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `icon_path` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
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
  `contact` TEXT NULL ,
  `description` TEXT NOT NULL ,
  `thumb_path` VARCHAR(255) NULL ,
  `approved` INT NULL ,
  `ticket_event_id` INT NULL ,
  `member_id` INT NOT NULL ,
  `program_id` INT NOT NULL ,
  `event_price_band_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_event_program` (`program_id` ASC) ,
  INDEX `fk_event_member1` (`member_id` ASC) ,
  INDEX `start` (`start` ASC) ,
  INDEX `end` (`end` ASC) ,
  INDEX `post_code` (`post_code` ASC) ,
  INDEX `approved` (`approved` ASC) ,
  INDEX `fk_event_event_event_price_band1` (`event_price_band_id` ASC) ,
  CONSTRAINT `fk_event_program`
    FOREIGN KEY (`program_id` )
    REFERENCES `plugin`.`event_program` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_member1`
    FOREIGN KEY (`member_id` )
    REFERENCES `plugin`.`event_member` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_event_event_price_band1`
    FOREIGN KEY (`event_price_band_id` )
    REFERENCES `plugin`.`event_price_band` (`id` )
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
-- Table `plugin`.`event_facility`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_facility` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_facility` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `icon_path` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`event_event_has_event_facility`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_event_has_event_facility` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_event_has_event_facility` (
  `event_event_id` INT NOT NULL ,
  `event_facility_id` INT NOT NULL ,
  PRIMARY KEY (`event_event_id`, `event_facility_id`) ,
  INDEX `fk_event_event_has_event_facility_event_facility1` (`event_facility_id` ASC) ,
  INDEX `fk_event_event_has_event_facility_event_event1` (`event_event_id` ASC) ,
  CONSTRAINT `fk_event_event_has_event_facility_event_event1`
    FOREIGN KEY (`event_event_id` )
    REFERENCES `plugin`.`event_event` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_event_has_event_facility_event_facility1`
    FOREIGN KEY (`event_facility_id` )
    REFERENCES `plugin`.`event_facility` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`event_ws`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`event_ws` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`event_ws` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `event_id` INT NOT NULL ,
  `os_grid_ref` VARCHAR(255) NOT NULL ,
  `grade` VARCHAR(255) NOT NULL ,
  `booking_essential` INT NULL ,
  `min_age` INT NULL ,
  `max_age` INT NULL ,
  `child_ages_restrictions` VARCHAR(255) NULL ,
  `additional_venue_info` VARCHAR(255) NULL ,
  `full_price_notes` VARCHAR(255) NULL ,
  `short_description` VARCHAR(255) NULL ,
  `wheelchair_accessible` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `index2` (`event_id` ASC) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
