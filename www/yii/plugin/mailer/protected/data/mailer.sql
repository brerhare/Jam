SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `plugin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `plugin` ;

-- -----------------------------------------------------
-- Table `plugin`.`mailer_list`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`mailer_list` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`mailer_list` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`mailer_content`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`mailer_content` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`mailer_content` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `date` DATE NOT NULL ,
  `content` LONGTEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`mailer_member`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`mailer_member` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`mailer_member` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `email_address` VARCHAR(255) NOT NULL ,
  `first_name` VARCHAR(255) NULL ,
  `last_name` VARCHAR(255) NULL ,
  `telephone` VARCHAR(45) NULL ,
  `address` TEXT NULL ,
  `active` INT NULL ,
  `mailer_list_id` INT NOT NULL ,
  PRIMARY KEY (`id`, `mailer_list_id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `fk_mailer_contact_mailer_list` (`mailer_list_id` ASC) ,
  CONSTRAINT `fk_mailer_contact_mailer_list`
    FOREIGN KEY (`mailer_list_id` )
    REFERENCES `plugin`.`mailer_list` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`mailer_list_has_mailer_content`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`mailer_list_has_mailer_content` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`mailer_list_has_mailer_content` (
  `mailer_list_id` INT NOT NULL ,
  `mailer_content_id` INT NOT NULL ,
  PRIMARY KEY (`mailer_list_id`, `mailer_content_id`) ,
  INDEX `fk_mailer_list_has_mailer_content_mailer_content1` (`mailer_content_id` ASC) ,
  INDEX `fk_mailer_list_has_mailer_content_mailer_list1` (`mailer_list_id` ASC) ,
  CONSTRAINT `fk_mailer_list_has_mailer_content_mailer_list1`
    FOREIGN KEY (`mailer_list_id` )
    REFERENCES `plugin`.`mailer_list` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mailer_list_has_mailer_content_mailer_content1`
    FOREIGN KEY (`mailer_content_id` )
    REFERENCES `plugin`.`mailer_content` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
