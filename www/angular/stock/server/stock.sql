SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `stock` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `stock` ;

-- -----------------------------------------------------
-- Table `stock`.`stock_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_group` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_group` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `parent_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `uid` (`uid` ASC),
  INDEX `fk_stock_group_stock_group1_idx` (`parent_id` ASC),
  CONSTRAINT `fk_stock_group_stock_group1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `stock`.`stock_group` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_vat`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_vat` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_vat` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `rate` DECIMAL(10,2) NULL,
  `is_default` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `uid` (`uid` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_product` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_product` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `cost` DECIMAL(10,2) NULL,
  `weight` DECIMAL(10,2) NULL,
  `height` DECIMAL(10,2) NULL,
  `width` DECIMAL(10,2) NULL,
  `depth` DECIMAL(10,2) NULL,
  `volume` DECIMAL(10,2) NULL,
  `price` DECIMAL(10,2) NULL,
  `stock_group_id` INT NOT NULL,
  `stock_vat_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `uid` (`uid` ASC),
  INDEX `fk_stock_product_stock_group1` (`stock_group_id` ASC),
  INDEX `fk_stock_product_stock_vat1` (`stock_vat_id` ASC),
  CONSTRAINT `fk_stock_product_stock_group1`
    FOREIGN KEY (`stock_group_id`)
    REFERENCES `stock`.`stock_group` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_product_stock_vat1`
    FOREIGN KEY (`stock_vat_id`)
    REFERENCES `stock`.`stock_vat` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_image` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_image` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `filename` VARCHAR(255) NOT NULL,
  `stock_product_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `uid` (`uid` ASC),
  INDEX `fk_stock_image_stock_product1` (`stock_product_id` ASC),
  CONSTRAINT `fk_stock_image_stock_product1`
    FOREIGN KEY (`stock_product_id`)
    REFERENCES `stock`.`stock_product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_markup_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_markup_group` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_markup_group` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `percent` DECIMAL(10,2) NULL,
  `is_default` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = 'Customers belong to a markup group, which determines the pri /* comment truncated */ /*ce for products they buy.
Products dont carry a particular selling price - this is always determined by the markup group
(the price depends on who is buying)

is_default applies to anonymous customer sales (it till sales)
*/';


-- -----------------------------------------------------
-- Table `stock`.`stock_area`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_area` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_area` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = 'This can be user definable';


-- -----------------------------------------------------
-- Table `stock`.`stock_customer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_customer` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_customer` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `address1` VARCHAR(255) NULL,
  `address2` VARCHAR(255) NULL,
  `address3` VARCHAR(255) NULL,
  `post_code` VARCHAR(255) NULL,
  `contact_title` VARCHAR(255) NULL,
  `contact_first_name` VARCHAR(255) NULL,
  `contact_last_name` VARCHAR(255) NULL,
  `telephone` VARCHAR(255) NULL,
  `mobile` VARCHAR(255) NULL,
  `fax` VARCHAR(255) NULL,
  `email` VARCHAR(255) NULL,
  `discount_percent` DECIMAL(10,2) NULL,
  `balance` VARCHAR(255) NULL,
  `link_field` VARCHAR(255) NULL COMMENT 'Any common content here will link customers for any purpose like a report etc',
  `notes` TEXT NULL,
  `status` INT NULL,
  `CIF` VARCHAR(255) NULL,
  `forma_de_pago` VARCHAR(255) NULL,
  `stock_markup_group_id` INT NOT NULL,
  `stock_area_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_stock_customer_stock_markup_group1` (`stock_markup_group_id` ASC),
  INDEX `fk_stock_customer_stock_area1_idx` (`stock_area_id` ASC),
  CONSTRAINT `fk_stock_customer_stock_markup_group10`
    FOREIGN KEY (`stock_markup_group_id`)
    REFERENCES `stock`.`stock_markup_group` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_customer_stock_area1`
    FOREIGN KEY (`stock_area_id`)
    REFERENCES `stock`.`stock_area` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_location`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_location` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_location` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_supplier`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_supplier` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_supplier` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `address1` VARCHAR(255) NULL,
  `address2` VARCHAR(255) NULL,
  `address3` VARCHAR(255) NULL,
  `address4` VARCHAR(255) NULL,
  `post_code` VARCHAR(255) NULL,
  `telephone` VARCHAR(255) NULL,
  `email` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_supplier_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_supplier_order` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_supplier_order` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `date` DATE NOT NULL,
  `reference` VARCHAR(255) NOT NULL,
  `supplier_reference` VARCHAR(255) NULL,
  `status` INT NULL COMMENT '0=closed\n1=open',
  `carriage` DECIMAL(10,2) NULL,
  `vat` DECIMAL(10,2) NULL,
  `order_total_amount` DECIMAL(10,2) NULL,
  `stock_supplier_id` INT NOT NULL,
  `stock_user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_stock_supplier_order_stock_supplier1` (`stock_supplier_id` ASC),
  INDEX `fk_stock_supplier_order_stock_user1` (`stock_user_id` ASC),
  CONSTRAINT `fk_stock_supplier_order_stock_supplier1`
    FOREIGN KEY (`stock_supplier_id`)
    REFERENCES `stock`.`stock_supplier` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_supplier_order_stock_user1`
    FOREIGN KEY (`stock_user_id`)
    REFERENCES `stock`.`stock_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Table `stock`.`stock_supplier_order_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_supplier_order_item` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_supplier_order_item` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `qty_ordered` INT NOT NULL,
  `qty_received` INT NULL,
  `price` DECIMAL(10,2) NULL,
  `batch_number` VARCHAR(255) NULL,
  `sell_by_date` DATE NULL COMMENT 'Spanish law requires this',
  `stock_supplier_order_id` INT NOT NULL,
  `stock_product_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_stock_supplier_order_item_stock_supplier_order1` (`stock_supplier_order_id` ASC),
  INDEX `fk_stock_supplier_order_item_stock_product1` (`stock_product_id` ASC),
  CONSTRAINT `fk_stock_supplier_order_item_stock_supplier_order1`
    FOREIGN KEY (`stock_supplier_order_id`)
    REFERENCES `stock`.`stock_supplier_order` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_supplier_order_item_stock_product1`
    FOREIGN KEY (`stock_product_id`)
    REFERENCES `stock`.`stock_product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_barcode`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_barcode` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_barcode` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `barcode` VARCHAR(255) NOT NULL,
  `notes` TEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_product_has_barcode`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_product_has_barcode` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_product_has_barcode` (
  `stock_product_id` INT NOT NULL,
  `stock_barcode_id` INT NOT NULL,
  PRIMARY KEY (`stock_product_id`, `stock_barcode_id`),
  INDEX `fk_stock_product_has_stock_barcode_stock_barcode1` (`stock_barcode_id` ASC),
  INDEX `fk_stock_product_has_stock_barcode_stock_product1` (`stock_product_id` ASC),
  CONSTRAINT `fk_stock_product_has_stock_barcode_stock_product1`
    FOREIGN KEY (`stock_product_id`)
    REFERENCES `stock`.`stock_product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_product_has_stock_barcode_stock_barcode1`
    FOREIGN KEY (`stock_barcode_id`)
    REFERENCES `stock`.`stock_barcode` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_product_has_location`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_product_has_location` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_product_has_location` (
  `stock_product_id` INT NOT NULL,
  `stock_location_id` INT NOT NULL,
  `onhand` INT NULL,
  `min_level` INT NULL,
  `max_level` INT NULL,
  PRIMARY KEY (`stock_product_id`, `stock_location_id`),
  INDEX `fk_stock_product_has_stock_location_stock_location1` (`stock_location_id` ASC),
  INDEX `fk_stock_product_has_stock_location_stock_product1` (`stock_product_id` ASC),
  CONSTRAINT `fk_stock_product_has_stock_location_stock_product1`
    FOREIGN KEY (`stock_product_id`)
    REFERENCES `stock`.`stock_product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_product_has_stock_location_stock_location1`
    FOREIGN KEY (`stock_location_id`)
    REFERENCES `stock`.`stock_location` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_product_transaction`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_product_transaction` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_product_transaction` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `type` VARCHAR(255) NOT NULL COMMENT 'Movement (location)\nReceipt\nAdjust (eg stock take)\nCustomerSale\nCustomerReturn\n',
  `reference` VARCHAR(255) NOT NULL,
  `date` DATE NOT NULL,
  `qty` INT NULL,
  `stock_location_id` INT NOT NULL,
  `stock_product_id` INT NOT NULL,
  `stock_user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_stock_transaction_stock_location1` (`stock_location_id` ASC),
  INDEX `fk_stock_transaction_stock_product1` (`stock_product_id` ASC),
  INDEX `fk_stock_product_transaction_stock_user1` (`stock_user_id` ASC),
  CONSTRAINT `fk_stock_transaction_stock_location1`
    FOREIGN KEY (`stock_location_id`)
    REFERENCES `stock`.`stock_location` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_transaction_stock_product1`
    FOREIGN KEY (`stock_product_id`)
    REFERENCES `stock`.`stock_product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_product_transaction_stock_user1`
    FOREIGN KEY (`stock_user_id`)
    REFERENCES `stock`.`stock_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Table `stock`.`stock_carriage`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_carriage` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_carriage` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `amount` DECIMAL(10,2) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Table `stock`.`stock_customer_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_customer_order` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_customer_order` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `date` DATE NOT NULL,
  `reference` VARCHAR(255) NOT NULL,
  `customer_reference` VARCHAR(255) NULL,
  `status` INT NULL COMMENT '0=closed\n1=open',
  `carriage` DECIMAL(10,2) NULL COMMENT 'Store the carriage amount as well as the carriage id\n',
  `vat` DECIMAL(10,2) NULL,
  `order_total_amount` DECIMAL(10,2) NULL,
  `stock_customer_id` INT NOT NULL,
  `stock_carriage_id` INT NOT NULL,
  `stock_user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_stock_order_stock_customer1` (`stock_customer_id` ASC),
  INDEX `fk_stock_customer_order_stock_carriage1` (`stock_carriage_id` ASC),
  INDEX `fk_stock_customer_order_stock_user1` (`stock_user_id` ASC),
  CONSTRAINT `fk_stock_order_stock_customer100`
    FOREIGN KEY (`stock_customer_id`)
    REFERENCES `stock`.`stock_customer` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_customer_order_stock_carriage100`
    FOREIGN KEY (`stock_carriage_id`)
    REFERENCES `stock`.`stock_carriage` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_customer_order_stock_user1`
    FOREIGN KEY (`stock_user_id`)
    REFERENCES `stock`.`stock_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_customer_order_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_customer_order_item` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_customer_order_item` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `qty_ordered` INT NOT NULL,
  `qty_received` INT NULL,
  `price` DECIMAL(10,2) NULL,
  `vat` VARCHAR(255) NULL,
  `stock_customer_order_id` INT NOT NULL,
  `stock_product_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_stock_customer_order_item_stock_customer_order1` (`stock_customer_order_id` ASC),
  INDEX `fk_stock_customer_order_item_stock_product1` (`stock_product_id` ASC),
  CONSTRAINT `fk_stock_customer_order_item_stock_customer_order100`
    FOREIGN KEY (`stock_customer_order_id`)
    REFERENCES `stock`.`stock_customer_order` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_customer_order_item_stock_product10`
    FOREIGN KEY (`stock_product_id`)
    REFERENCES `stock`.`stock_product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_units`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_units` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_units` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `money_symbol` VARCHAR(255) NULL,
  `weight` VARCHAR(255) NULL,
  `volume` VARCHAR(255) NULL,
  `length` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;




-- -----------------------------------------------------
-- Table `stock`.`stock_customer_transaction`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_customer_transaction` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_customer_transaction` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `type` VARCHAR(255) NOT NULL,
  `reference` VARCHAR(255) NOT NULL,
  `date` DATE NOT NULL,
  `carriage` DECIMAL(10,2) NULL COMMENT 'Store the carriage amount as well as the carriage id\n',
  `vat` DECIMAL(10,2) NULL,
  `total_amount` DECIMAL(10,2) NULL,
  `stock_customer_id` INT NOT NULL,
  `stock_user_id` INT NOT NULL,
  `notes` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_stock_customer_transaction_stock_customer1` (`stock_customer_id` ASC),
  INDEX `fk_stock_customer_transaction_stock_user1` (`stock_user_id` ASC),
  CONSTRAINT `fk_stock_customer_transaction_stock_customer1`
    FOREIGN KEY (`stock_customer_id`)
    REFERENCES `stock`.`stock_customer` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_customer_transaction_stock_user1`
    FOREIGN KEY (`stock_user_id`)
    REFERENCES `stock`.`stock_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_customer_transaction_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_customer_transaction_item` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_customer_transaction_item` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `qty` INT NULL,
  `price` DECIMAL(10,2) NULL,
  `stock_customer_transaction_id` INT NOT NULL,
  `stock_product_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_stock_customer_transaction_item_stock_customer_transaction1` (`stock_customer_transaction_id` ASC),
  INDEX `fk_stock_customer_transaction_item_stock_product1` (`stock_product_id` ASC),
  CONSTRAINT `fk_stock_customer_transaction_item_stock_customer_transaction1`
    FOREIGN KEY (`stock_customer_transaction_id`)
    REFERENCES `stock`.`stock_customer_transaction` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_customer_transaction_item_stock_product1`
    FOREIGN KEY (`stock_product_id`)
    REFERENCES `stock`.`stock_product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_user` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `password` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_menu`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_menu` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_menu` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `menu_id` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_user_has_menu`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_user_has_menu` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_user_has_menu` (
  `stock_user_id` INT NOT NULL,
  `stock_menu_id` INT NOT NULL,
  PRIMARY KEY (`stock_user_id`, `stock_menu_id`),
  INDEX `fk_stock_user_has_stock_menu_stock_menu1` (`stock_menu_id` ASC),
  INDEX `fk_stock_user_has_stock_menu_stock_user1` (`stock_user_id` ASC),
  CONSTRAINT `fk_stock_user_has_stock_menu_stock_user1`
    FOREIGN KEY (`stock_user_id`)
    REFERENCES `stock`.`stock_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_user_has_stock_menu_stock_menu1`
    FOREIGN KEY (`stock_menu_id`)
    REFERENCES `stock`.`stock_menu` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_pack`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_pack` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_pack` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `container_name` VARCHAR(255) NOT NULL,
  `unit_name` VARCHAR(255) NOT NULL,
  `unit_qty` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = 'The parent is always PER, ie 1.\nThe units are how many are c /* comment truncated */ /*ontained in that 1. These are not necessarily base units, ie they might later be reduced further by themselves being parents*/';


-- -----------------------------------------------------
-- Table `stock`.`stock_label_translation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_label_translation` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_label_translation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `notes` TEXT NOT NULL,
  `stock_product_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_stock_label_translation_stock_product1` (`stock_product_id` ASC),
  CONSTRAINT `fk_stock_label_translation_stock_product1`
    FOREIGN KEY (`stock_product_id`)
    REFERENCES `stock`.`stock_product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_product_promotion_price`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_product_promotion_price` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_product_promotion_price` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `start_date` DATE NULL,
  `end_date` DATE NULL,
  `price` DECIMAL(10,2) NULL,
  `stock_product_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_stock_product_promotion_price_stock_product1` (`stock_product_id` ASC),
  CONSTRAINT `fk_stock_product_promotion_price_stock_product1`
    FOREIGN KEY (`stock_product_id`)
    REFERENCES `stock`.`stock_product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'This holds all price overrides, ie deviations from the marku /* comment truncated */ /*p calculation.
By default products use the customers markup group to calculate the price but any products in this table will use this fixed price.*/';


-- -----------------------------------------------------
-- Table `stock`.`stock_user_has_stock_location`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_user_has_stock_location` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_user_has_stock_location` (
  `stock_user_id` INT NOT NULL,
  `stock_location_id` INT NOT NULL,
  PRIMARY KEY (`stock_user_id`, `stock_location_id`),
  INDEX `fk_stock_user_has_stock_location_stock_location1` (`stock_location_id` ASC),
  INDEX `fk_stock_user_has_stock_location_stock_user1` (`stock_user_id` ASC),
  CONSTRAINT `fk_stock_user_has_stock_location_stock_user1`
    FOREIGN KEY (`stock_user_id`)
    REFERENCES `stock`.`stock_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_user_has_stock_location_stock_location1`
    FOREIGN KEY (`stock_location_id`)
    REFERENCES `stock`.`stock_location` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stock`.`stock_currencies`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stock`.`stock_currencies` ;

CREATE TABLE IF NOT EXISTS `stock`.`stock_currencies` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `local_value` DECIMAL(10,4) NOT NULL,
  `foreign_value` DECIMAL(10,4) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
