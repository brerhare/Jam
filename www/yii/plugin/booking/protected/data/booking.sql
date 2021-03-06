SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `plugin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `plugin` ;

-- -----------------------------------------------------
-- Table `plugin`.`booking_occupancy_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_occupancy_type` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_occupancy_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `description` VARCHAR(255) NOT NULL ,
  `is_default` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`booking_facility`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_facility` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_facility` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `description` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`booking_room`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_room` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_room` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `description` TEXT NOT NULL ,
  `qty` INT NOT NULL ,
  `max_adult` INT NULL ,
  `max_child` INT NULL ,
  `max_total` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`booking_room_has_occupancy_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_room_has_occupancy_type` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_room_has_occupancy_type` (
  `room_id` INT NOT NULL ,
  `occupancy_type_id` INT NOT NULL ,
  `uid` INT NOT NULL ,
  `adult_rate` DECIMAL(10,2) NULL ,
  `child_rate` DECIMAL(10,2) NULL ,
  `single_rate` DECIMAL(10,2) NULL ,
  `double_rate` DECIMAL(10,2) NULL ,
  `cap_rate` DECIMAL(10,2) NULL ,
  PRIMARY KEY (`room_id`, `occupancy_type_id`) ,
  INDEX `fk_room_has_occupancy_type_occupancy_type1` (`occupancy_type_id` ASC) ,
  INDEX `fk_room_has_occupancy_type_room` (`room_id` ASC) ,
  INDEX `uid` (`uid` ASC) ,
  CONSTRAINT `fk_room_has_occupancy_type_room`
    FOREIGN KEY (`room_id` )
    REFERENCES `plugin`.`booking_room` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_room_has_occupancy_type_occupancy_type1`
    FOREIGN KEY (`occupancy_type_id` )
    REFERENCES `plugin`.`booking_occupancy_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`booking_room_has_facility`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_room_has_facility` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_room_has_facility` (
  `room_id` INT NOT NULL ,
  `facility_id` INT NOT NULL ,
  `uid` INT NOT NULL ,
  PRIMARY KEY (`room_id`, `facility_id`) ,
  INDEX `fk_room_has_facility_facility1` (`facility_id` ASC) ,
  INDEX `fk_room_has_facility_room1` (`room_id` ASC) ,
  INDEX `uid` (`uid` ASC) ,
  CONSTRAINT `fk_room_has_facility_room1`
    FOREIGN KEY (`room_id` )
    REFERENCES `plugin`.`booking_room` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_room_has_facility_facility1`
    FOREIGN KEY (`facility_id` )
    REFERENCES `plugin`.`booking_facility` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`booking_extra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_extra` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_extra` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `description` VARCHAR(255) NOT NULL ,
  `daily_rate` DECIMAL(10,2) NULL ,
  `once_rate` DECIMAL(10,2) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`booking_room_has_extra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_room_has_extra` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_room_has_extra` (
  `room_id` INT NOT NULL ,
  `extra_id` INT NOT NULL ,
  `uid` INT NOT NULL ,
  PRIMARY KEY (`room_id`, `extra_id`) ,
  INDEX `fk_room_has_extra_extra1` (`extra_id` ASC) ,
  INDEX `fk_room_has_extra_room1` (`room_id` ASC) ,
  INDEX `uid` (`uid` ASC) ,
  CONSTRAINT `fk_room_has_extra_room1`
    FOREIGN KEY (`room_id` )
    REFERENCES `plugin`.`booking_room` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_room_has_extra_extra1`
    FOREIGN KEY (`extra_id` )
    REFERENCES `plugin`.`booking_extra` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`booking_calendar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_calendar` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_calendar` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `date` DATE NOT NULL ,
  `ref` VARCHAR(255) NOT NULL ,
  `room_id` INT NOT NULL ,
  PRIMARY KEY (`id`, `room_id`) ,
  INDEX `fk_booking_calendar_room1` (`room_id` ASC) ,
  INDEX `date` (`date` ASC) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `ref` (`uid` ASC, `ref` ASC) ,
  CONSTRAINT `fk_booking_calendar_room1`
    FOREIGN KEY (`room_id` )
    REFERENCES `plugin`.`booking_room` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`booking_reservation_room`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_reservation_room` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_reservation_room` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `ref` VARCHAR(255) NOT NULL ,
  `start_date` DATE NOT NULL ,
  `end_date` DATE NOT NULL ,
  `num_nights` INT NULL ,
  `num_adult` INT NULL ,
  `num_child` INT NULL ,
  `room_total` DECIMAL(10,2) NULL ,
  `occupancy_type_id` INT NULL ,
  `occupancy_type_description` VARCHAR(255) NULL ,
  `room_id` INT NOT NULL ,
  PRIMARY KEY (`id`, `room_id`) ,
  INDEX `fk_booking_room_room1` (`room_id` ASC) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `ref` (`uid` ASC, `ref` ASC) ,
  CONSTRAINT `fk_booking_room_room1`
    FOREIGN KEY (`room_id` )
    REFERENCES `plugin`.`booking_room` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`booking_reservation_extra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_reservation_extra` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_reservation_extra` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `ref` VARCHAR(255) NOT NULL ,
  `extra_id` INT NULL ,
  `extra_description` VARCHAR(255) NULL ,
  `extra_total` DECIMAL(10,2) NULL ,
  `reservation_room_id` INT NOT NULL ,
  `reservation_room_room_id` INT NOT NULL ,
  PRIMARY KEY (`id`, `reservation_room_id`, `reservation_room_room_id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `fk_booking_reservation_extra_booking_reservation_room1` (`reservation_room_id` ASC, `reservation_room_room_id` ASC) ,
  INDEX `ref` (`ref` ASC, `uid` ASC) ,
  CONSTRAINT `fk_booking_reservation_extra_booking_reservation_room1`
    FOREIGN KEY (`reservation_room_id` , `reservation_room_room_id` )
    REFERENCES `plugin`.`booking_reservation_room` (`id` , `room_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`booking_customer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_customer` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_customer` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `ref` VARCHAR(255) NOT NULL ,
  `name` VARCHAR(255) NULL ,
  `address_1` VARCHAR(255) NOT NULL ,
  `address_2` VARCHAR(255) NOT NULL ,
  `county` VARCHAR(255) NULL ,
  `town` VARCHAR(255) NULL ,
  `post_code` VARCHAR(255) NOT NULL ,
  `telephone` VARCHAR(255) NOT NULL ,
  `email` VARCHAR(255) NOT NULL ,
  `card_name` VARCHAR(255) NOT NULL ,
  `card_number` VARCHAR(255) NOT NULL ,
  `card_expiry_mm` INT NOT NULL ,
  `card_expiry_yy` INT NOT NULL ,
  `card_cvv` INT NOT NULL ,
  `reservation_total` DECIMAL(10,2) NOT NULL ,
  `coupon_code` VARCHAR(255) NULL ,
  `coupon_description` VARCHAR(255) NULL ,
  `coupon_type` INT NULL ,
  `coupon_value` DECIMAL(10,2) NULL ,
  `deposit_taken` DECIMAL(10,2) NULL ,
  `cancel_flag` TINYINT(1) NULL ,
  `cancel_reason` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `ref` (`uid` ASC, `ref` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`booking_document`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_document` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_document` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `text` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `title` (`title` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`booking_coupon`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_coupon` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_coupon` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `code` VARCHAR(255) NOT NULL ,
  `description` VARCHAR(255) NULL ,
  `type` INT NOT NULL ,
  `value` DECIMAL(10,2) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`booking_param`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`booking_param` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`booking_param` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `sender_name` VARCHAR(255) NOT NULL ,
  `sender_email_address` VARCHAR(255) NOT NULL ,
  `cc_email_address` VARCHAR(255) NULL ,
  `deposit_percent` DECIMAL(10,2) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
