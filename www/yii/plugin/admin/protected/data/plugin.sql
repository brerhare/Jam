SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `plugin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `plugin` ;

-- -----------------------------------------------------
-- Table `plugin`.`admin_plugin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`admin_plugin` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`admin_plugin` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(256) NOT NULL ,
  `container_url` VARCHAR(256) NULL ,
  `container_width` INT NULL ,
  `container_height` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`admin_image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`admin_image` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`admin_image` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `filename` VARCHAR(256) NOT NULL ,
  `plugin_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_image_plugin1` (`plugin_id` ASC) ,
  CONSTRAINT `fk_image_plugin1`
    FOREIGN KEY (`plugin_id` )
    REFERENCES `plugin`.`admin_plugin` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`admin_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`admin_user` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`admin_user` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email_address` VARCHAR(128) NOT NULL ,
  `password` VARCHAR(128) NOT NULL ,
  `display_name` VARCHAR(128) NOT NULL ,
  `sid` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `sid_UNIQUE` (`sid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`admin_user_has_plugin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`admin_user_has_plugin` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`admin_user_has_plugin` (
  `plugin_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  PRIMARY KEY (`plugin_id`, `user_id`) ,
  INDEX `fk_plugin_has_user_user1` (`user_id` ASC) ,
  INDEX `fk_plugin_has_user_plugin` (`plugin_id` ASC) ,
  CONSTRAINT `fk_plugin_has_user_plugin`
    FOREIGN KEY (`plugin_id` )
    REFERENCES `plugin`.`admin_plugin` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_plugin_has_user_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `plugin`.`admin_user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
