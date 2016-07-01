ALTER TABLE `actividad` 
ADD COLUMN `proyecto_id` INT NOT NULL COMMENT '' AFTER `actv_updated_at`;


ALTER TABLE `actividad` 
CHANGE COLUMN `proyecto_id` `proyecto_id` INT(11) NOT NULL COMMENT '' AFTER `prrd_id`;


## esta sentencia se debe hacer cuando no haya datos de actividades y proyectos !!
ALTER TABLE `qalendar`.`actividad` 
ADD INDEX `fk_proyectos_idx` (`proyecto_id` ASC)  COMMENT '';
ALTER TABLE `qalendar`.`actividad` 
ADD CONSTRAINT `fk_proyectos`
  FOREIGN KEY (`proyecto_id`)
  REFERENCES `qalendar`.`proyectos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


ALTER TABLE `qalendar`.`actividad` 
CHANGE COLUMN `actv_comentarios` `actv_comentarios` TEXT NULL COMMENT '' ;

