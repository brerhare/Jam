SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table ``.`accordian_block`
-- -----------------------------------------------------
DROP TABLE IF EXISTS ``.`accordian_block` ;

CREATE  TABLE IF NOT EXISTS ``.`accordian_block` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sequence` INT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `url` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL ,
  `image` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
