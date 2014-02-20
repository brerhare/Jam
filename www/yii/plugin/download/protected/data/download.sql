SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `plugin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `plugin` ;

-- -----------------------------------------------------
-- Table `plugin`.`download_collection`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `plugin`.`download_collection` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`download_file`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `plugin`.`download_file` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `filename` VARCHAR(255) NOT NULL ,
  `description` VARCHAR(255) NULL ,
  `download_collection_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_download_table_download_collection` (`download_collection_id` ASC) ,
  CONSTRAINT `fk_download_table_download_collection`
    FOREIGN KEY (`download_collection_id` )
    REFERENCES `plugin`.`download_collection` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
