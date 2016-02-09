SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `test` ;

-- -----------------------------------------------------
-- Table `test`.`nonident1`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`nonident1` (
  `id` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `test`.`nonident2`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`nonident2` (
  `id` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  `nonident1_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_nonident2_nonident11_idx` (`nonident1_id` ASC),
  CONSTRAINT `fk_nonident2_nonident11`
    FOREIGN KEY (`nonident1_id`)
    REFERENCES `test`.`nonident1` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
