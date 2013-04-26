SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `plugin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `plugin` ;

-- -----------------------------------------------------
-- Table `plugin`.`ticket_vendor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`ticket_vendor` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`ticket_vendor` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `address` TEXT NULL ,
  `post_code` VARCHAR(45) NULL ,
  `email` VARCHAR(255) NULL ,
  `telephone` VARCHAR(255) NULL ,
  `vat_number` VARCHAR(45) NULL ,
  `bank_account_name` VARCHAR(255) NULL ,
  `bank_account_number` VARCHAR(255) NULL ,
  `bank_sort_code` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`ticket_event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`ticket_event` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`ticket_event` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `date` VARCHAR(45) NOT NULL ,
  `address` TEXT NOT NULL ,
  `post_code` VARCHAR(45) NOT NULL ,
  `ticket_logo_path` VARCHAR(255) NULL ,
  `ticket_text` TEXT NULL ,
  `ticket_terms` TEXT NULL ,
  `active` INT NOT NULL ,
  `active_start_date` DATE NULL ,
  `active_start_time` TIME NULL ,
  `active_end_date` DATE NULL ,
  `active_end_time` TIME NULL ,
  `ticket_vendor_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `fk_ticket_event_ticket_vendor` (`ticket_vendor_id` ASC) ,
  CONSTRAINT `fk_ticket_event_ticket_vendor`
    FOREIGN KEY (`ticket_vendor_id` )
    REFERENCES `plugin`.`ticket_vendor` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`ticket_area`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`ticket_area` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`ticket_area` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `description` VARCHAR(255) NOT NULL ,
  `max_places` VARCHAR(45) NULL ,
  `ticket_event_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `fk_ticket_area_ticket_event1` (`ticket_event_id` ASC) ,
  CONSTRAINT `fk_ticket_area_ticket_event1`
    FOREIGN KEY (`ticket_event_id` )
    REFERENCES `plugin`.`ticket_event` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`ticket_ticket_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`ticket_ticket_type` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`ticket_ticket_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `description` VARCHAR(255) NOT NULL ,
  `price` DECIMAL(10,2) NULL ,
  `places_per_ticket` INT NULL ,
  `max_tickets_per_order` INT NULL ,
  `ticket_area_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `fk_ticket_ticket_type_ticket_area1` (`ticket_area_id` ASC) ,
  CONSTRAINT `fk_ticket_ticket_type_ticket_area1`
    FOREIGN KEY (`ticket_area_id` )
    REFERENCES `plugin`.`ticket_area` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`ticket_transaction`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`ticket_transaction` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`ticket_transaction` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `ip` VARCHAR(255) NULL ,
  `order_number` VARCHAR(255) NULL ,
  `timestamp` TIMESTAMP NULL ,
  `email` VARCHAR(255) NULL ,
  `telephone` VARCHAR(255) NULL ,
  `amount` DECIMAL(10,2) NULL ,
  `vendor_id` INT NULL ,
  `event_id` INT NULL ,
  `area_id` INT NULL ,
  `ticket_type_id` INT NULL ,
  `ticket_number` VARCHAR(255) NULL ,
  `auth_code` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `vendor_id` (`vendor_id` ASC) ,
  INDEX `event_id` (`event_id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`ticket_scan`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`ticket_scan` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`ticket_scan` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `order_number` VARCHAR(255) NOT NULL ,
  `ticket_number` VARCHAR(255) NOT NULL ,
  `timestamp` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`ticket_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`ticket_order` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`ticket_order` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `ip` VARCHAR(255) NOT NULL ,
  `sid` VARCHAR(255) NULL ,
  `order_number` VARCHAR(255) NULL ,
  `vendor_id` INT NOT NULL ,
  `event_id` INT NOT NULL ,
  `http_ticket_type_area` VARCHAR(45) NULL ,
  `http_ticket_type_id` VARCHAR(45) NULL ,
  `http_ticket_type_qty` VARCHAR(45) NULL ,
  `http_ticket_type_price` VARCHAR(45) NULL ,
  `http_ticket_type_total` VARCHAR(45) NULL ,
  `http_total` VARCHAR(45) NULL ,
  `auth_code` VARCHAR(45) NULL ,
  `return_url` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `ip` (`ip` ASC) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
