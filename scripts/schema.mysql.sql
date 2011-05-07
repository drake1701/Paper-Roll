-- 
-- @package		PaperRoll
-- @author		Dennis Rogers
-- @address		www.drogers.net
-- 


SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `paperroll` ;
CREATE SCHEMA IF NOT EXISTS `paperroll` DEFAULT CHARACTER SET utf8 ;

USE `paperroll` ;

-- -----------------------------------------------------
-- Table `entry`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `entry` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL DEFAULT NULL ,
  `created_at` DATETIME NULL DEFAULT NULL ,
  `modified_at` DATETIME NULL DEFAULT NULL ,
  `published_at` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `tag`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tag` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `entry_tag`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `entry_tag` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `entry_id` INT(10) UNSIGNED NOT NULL ,
  `tag_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `entry-fk` (`entry_id` ASC) ,
  INDEX `tag-fk` (`tag_id` ASC) ,
  CONSTRAINT `entry-fk`
    FOREIGN KEY (`entry_id`)
    REFERENCES `entry` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `tag-fk`
    FOREIGN KEY (`tag_id`)
    REFERENCES `tag` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `config`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `config` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `key` VARCHAR(255) NOT NULL ,
  `value` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `image_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `image_type` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `path` VARCHAR(45) NOT NULL ,
  `label` VARCHAR(45) NOT NULL ,
  `is_required` TINYINT(1) NULL DEFAULT '0' ,
  `exclude` TINYINT(1) NULL DEFAULT '0' ,
  `position` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `image`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `image` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entry_id` INT(10) UNSIGNED NOT NULL ,
  `path` VARCHAR(255) NOT NULL ,
  `title` VARCHAR(45) NULL DEFAULT NULL ,
  `type` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `entry` (`entry_id` ASC) ,
  INDEX `post-fk` (`entry_id` ASC) ,
  INDEX `type-fk` (`type` ASC) ,
  CONSTRAINT `post-fk`
    FOREIGN KEY (`entry_id` )
    REFERENCES `entry` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `type-fk`
    FOREIGN KEY (`type` )
    REFERENCES `image_type` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

