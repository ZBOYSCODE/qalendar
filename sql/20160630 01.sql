CREATE TABLE `areas` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `nombre` VARCHAR(100) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '');


CREATE TABLE `tecnologias` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `nombre` VARCHAR(100) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '');

CREATE TABLE `proyectos` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `jefep_id` INT NOT NULL COMMENT '',
  `creador_id` INT NOT NULL COMMENT '',
  `area_id` INT NOT NULL COMMENT '',
  `tecnologia_id` INT NOT NULL COMMENT '',
  `codigo` VARCHAR(45) NOT NULL COMMENT '',
  `nombre` VARCHAR(45) NOT NULL COMMENT '',
  `descripcion` VARCHAR(250) NULL COMMENT '',
  `created_at` DATETIME NOT NULL COMMENT '',
  `updated_at` DATETIME NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_areas_idx` (`area_id` ASC)  COMMENT '',
  INDEX `fk_tecnologias_idx` (`tecnologia_id` ASC)  COMMENT '',
  INDEX `fk_jefeproyecto_idx` (`jefep_id` ASC)  COMMENT '',
  INDEX `fk_creador_idx` (`creador_id` ASC)  COMMENT '',
  CONSTRAINT `fk_areas`
    FOREIGN KEY (`area_id`)
    REFERENCES `qalendar`.`areas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tecnologias`
    FOREIGN KEY (`tecnologia_id`)
    REFERENCES `qalendar`.`tecnologias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_jefeproyecto`
    FOREIGN KEY (`jefep_id`)
    REFERENCES `qalendar`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_creador`
    FOREIGN KEY (`creador_id`)
    REFERENCES `qalendar`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


ALTER TABLE `proyectos` 
ADD COLUMN `estado` BIT(1) NULL DEFAULT 1 COMMENT '' AFTER `updated_at`;

INSERT INTO `roles` (`rol_id`, `descripcion`) VALUES ('4', 'Jefe proyecto');
UPDATE `roles` SET `descripcion`='Gestor' WHERE `rol_id`='2';

CREATE TABLE `user_tecnologia` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `user_id` INT NOT NULL COMMENT '',
  `tecnologia_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_user_idx` (`user_id` ASC)  COMMENT '',
  INDEX `fk_tecnologia_idx` (`tecnologia_id` ASC)  COMMENT '',
  CONSTRAINT `fk_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `qalendar`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tecnologia`
    FOREIGN KEY (`tecnologia_id`)
    REFERENCES `qalendar`.`tecnologias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
