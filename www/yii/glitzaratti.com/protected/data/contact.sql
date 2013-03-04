-- -----------------------------------------------------
-- Table `glitzaratti_com`.`contact`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glitzaratti_com`.`contact` ;

CREATE  TABLE IF NOT EXISTS `glitzaratti_com`.`contact` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  `email` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


