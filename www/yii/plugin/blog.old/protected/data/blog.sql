SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `plugin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `plugin` ;

-- -----------------------------------------------------
-- Table `plugin`.`blog_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`blog_category` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`blog_category` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `plugin`.`blog_article`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `plugin`.`blog_article` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`blog_article` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `date` DATE NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `intro` TEXT NULL ,
  `content` LONGTEXT NULL ,
  `thumbnail_path` VARCHAR(255) NULL ,
  `blog_category_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_article_blog_category` (`blog_category_id` ASC) ,
  INDEX `uid` (`uid` ASC) ,
  CONSTRAINT `fk_article_blog_category`
    FOREIGN KEY (`blog_category_id` )
    REFERENCES `plugin`.`blog_category` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
