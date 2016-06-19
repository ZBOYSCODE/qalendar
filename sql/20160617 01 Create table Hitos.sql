CREATE TABLE `hitos` (
  `hito_id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `actv_id` INT NOT NULL COMMENT '',
  `created_at` DATETIME NOT NULL COMMENT '',
  `updated_at` DATETIME NOT NULL COMMENT '',
  `hito_nombre` VARCHAR(45) NOT NULL COMMENT '',
  `hito_descripcion` VARCHAR(250) NOT NULL COMMENT '',
  PRIMARY KEY (`hito_id`)  COMMENT '');
ALTER TABLE `qalendar`.`hitos`
  ADD COLUMN `hito_tipo` varchar(20) NULL DEFAULT NULL;
