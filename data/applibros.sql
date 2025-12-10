-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema applibros
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema applibros
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `applibros` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci ;
USE `applibros` ;

-- -----------------------------------------------------
-- Table `applibros`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `applibros`.`usuario` (
  `usuario_id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`usuario_id`),
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `applibros`.`libro`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `applibros`.`libro` (
  `libro_id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NOT NULL,
  `sinopsis` TEXT NULL,
  `autor` VARCHAR(255) NULL,
  `portada` VARCHAR(255) NULL,
  `usuario_id` INT NOT NULL,
  PRIMARY KEY (`libro_id`),
  INDEX `fk_libro_usuario_idx` (`usuario_id` ASC) ,
  CONSTRAINT `fk_libro_usuario`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `applibros`.`usuario` (`usuario_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
