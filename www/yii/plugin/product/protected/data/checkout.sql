SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP TABLE IF EXISTS `plugin`.`product_order` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`product_order` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `ip` VARCHAR(255) NOT NULL ,
  `sid` VARCHAR(255) NULL ,
  `order_number` VARCHAR(255) NULL ,
  `vendor_gateway_id` VARCHAR(255) NULL ,
  `vendor_gateway_password` VARCHAR(255) NULL ,
  `http_product_id` VARCHAR(45) NULL ,
  `http_option_id` VARCHAR(45) NULL ,
  `http_qty` VARCHAR(45) NULL ,
  `http_price` VARCHAR(45) NULL ,
  `http_line_total` VARCHAR(45) NULL ,
  `http_shipping_id` VARCHAR(45) NULL ,
  `http_total` VARCHAR(45) NULL ,
  `auth_code` VARCHAR(45) NULL ,
  `return_url` VARCHAR(255) NULL ,
  `email_address` VARCHAR(255) NULL ,
  `delivery_address1` VARCHAR(255) NULL ,
  `delivery_address2` VARCHAR(255) NULL ,
  `delivery_address3` VARCHAR(255) NULL ,
  `delivery_address4` VARCHAR(255) NULL ,
  `delivery_post_code` VARCHAR(255) NULL ,
  `notes` TEXT NULL ,
  `telephone` VARCHAR(255) NULL ,
  `card_name` VARCHAR(255) NULL ,
  `card_number` VARCHAR(255) NULL ,
  `card_expiry_month` VARCHAR(45) NULL ,
  `card_expiry_year` VARCHAR(45) NULL ,
  `card_cv2` VARCHAR(255) NULL ,
  `card_address1` VARCHAR(255) NULL ,
  `card_address2` VARCHAR(255) NULL ,
  `card_address3` VARCHAR(255) NULL ,
  `card_address4` VARCHAR(255) NULL ,
  `card_city` VARCHAR(255) NULL ,
  `card_state` VARCHAR(255) NULL ,
  `card_post_code` VARCHAR(45) NULL ,
  `card_country_short` VARCHAR(45) NULL ,
  `card_currency_short` VARCHAR(45) NULL ,
  `card_amount` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `ip` (`ip` ASC) )
ENGINE = InnoDB;


DROP TABLE IF EXISTS `plugin`.`product_auth` ;

CREATE  TABLE IF NOT EXISTS `plugin`.`product_auth` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` INT NOT NULL ,
  `order_number` VARCHAR(255) NULL ,
  `card_name` VARCHAR(255) NULL ,
  `card_number` VARCHAR(255) NULL ,
  `expiry_month` VARCHAR(45) NULL ,
  `expiry_year` VARCHAR(45) NULL ,
  `cv2` VARCHAR(45) NULL ,
  `address1` VARCHAR(255) NULL ,
  `address2` VARCHAR(255) NULL ,
  `address3` VARCHAR(255) NULL ,
  `address4` VARCHAR(255) NULL ,
  `city` VARCHAR(255) NULL ,
  `state` VARCHAR(255) NULL ,
  `post_code` VARCHAR(255) NULL ,
  `country_short` VARCHAR(255) NULL ,
  `amount` VARCHAR(45) NULL ,
  `currency_short` VARCHAR(45) NULL ,
  `auth_code` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `order_number` (`order_number` ASC, `uid` ASC) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
