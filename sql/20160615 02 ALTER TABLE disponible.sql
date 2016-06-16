ALTER TABLE `qalendar`.`disponible` 
DROP FOREIGN KEY `fk_configuradorDisponible`;
ALTER TABLE `qalendar`.`disponible` 
CHANGE COLUMN `cnfg_id` `cnfg_id` INT(11) NULL COMMENT '' ;
ALTER TABLE `qalendar`.`disponible` 
ADD CONSTRAINT `fk_configuradorDisponible`
  FOREIGN KEY (`cnfg_id`)
  REFERENCES `qalendar`.`configurador_disponibilidad` (`cnfg_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;