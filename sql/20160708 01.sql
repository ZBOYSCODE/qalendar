CREATE TABLE `qalendar`.`bloqueos` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `user_id` INT NOT NULL COMMENT '',
  `fecha_inicio` DATETIME NOT NULL COMMENT '',
  `fecha_termino` DATETIME NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_user_idx` (`user_id` ASC)  COMMENT '',
  CONSTRAINT `fk_user_bloqueos`
    FOREIGN KEY (`user_id`)
    REFERENCES `qalendar`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
