CREATE TABLE `qalendar`.`tipo_estados` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `nombre` VARCHAR(100) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '');

ALTER TABLE `qalendar`.`actividad` 
CHANGE COLUMN `actv_status` `actv_status` INT NOT NULL DEFAULT 2 COMMENT '' ;


INSERT INTO `qalendar`.`tipo_estados` (`nombre`) VALUES ('Aprobado');
INSERT INTO `qalendar`.`tipo_estados` (`nombre`) VALUES ('Pendiente');

ALTER TABLE `qalendar`.`actividad` 
ADD CONSTRAINT `fk_estados`
  FOREIGN KEY (`actv_status`)
  REFERENCES `qalendar`.`tipo_estados` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

