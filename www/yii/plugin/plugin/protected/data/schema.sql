SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `plugin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `plugin` ;

-- -----------------------------------------------------
-- Table `plugin`.`plugin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`plugin` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`plugin` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(128) NOT NULL ,
  `container_code` TEXT NULL ,
  `container_width` INT NULL ,
  `container_height` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`image` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`image` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `filename` VARCHAR(256) NOT NULL ,
  `plugin_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_image_plugin1` (`plugin_id` ASC) ,
  CONSTRAINT `fk_image_plugin1`
    FOREIGN KEY (`plugin_id` )
    REFERENCES `plugin`.`plugin` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`user` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `email_address` VARCHAR(128) NOT NULL ,
  `password` VARCHAR(128) NOT NULL ,
  `display_name` VARCHAR(128) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`plugin_has_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`plugin_has_user` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`plugin_has_user` (
  `plugin_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  PRIMARY KEY (`plugin_id`, `user_id`) ,
  INDEX `fk_plugin_has_user_user1` (`user_id` ASC) ,
  INDEX `fk_plugin_has_user_plugin` (`plugin_id` ASC) ,
  CONSTRAINT `fk_plugin_has_user_plugin`
    FOREIGN KEY (`plugin_id` )
    REFERENCES `plugin`.`plugin` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_plugin_has_user_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `plugin`.`user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
