ALTER TABLE `qalendar`.`users`
  ADD COLUMN `rol_id` int(11) NULL DEFAULT 2;

  CREATE TABLE `qalendar`.`roles` (
  `rol_id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) NULL DEFAULT NULL,
  PRIMARY KEY (`rol_id`)
) DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;

INSERT INTO `qalendar`.`roles` SET `rol_id`=0,`descripcion`='Admin';
INSERT INTO `qalendar`.`roles` SET `descripcion`='Coordinador';
INSERT INTO `qalendar`.`roles` SET `descripcion`='Qa';

UPDATE `qalendar`.`users` SET `rol_id`=1 WHERE `id`=1;
UPDATE `qalendar`.`users` SET `rol_id`=2 WHERE `id`=11;
UPDATE `qalendar`.`users` SET `rol_id`=2 WHERE `id`=12;
UPDATE `qalendar`.`users` SET `rol_id`=2 WHERE `id`=13;
UPDATE `qalendar`.`users` SET `rol_id`=2 WHERE `id`=14;
UPDATE `qalendar`.`users` SET `rol_id`=2 WHERE `id`=15;
UPDATE `qalendar`.`users` SET `rol_id`=2 WHERE `id`=16;
UPDATE `qalendar`.`users` SET `rol_id`=2 WHERE `id`=17;
UPDATE `qalendar`.`users` SET `rol_id`=2 WHERE `id`=18;
UPDATE `qalendar`.`users` SET `rol_id`=2 WHERE `id`=19;
UPDATE `qalendar`.`users` SET `rol_id`=2 WHERE `id`=20;
UPDATE `qalendar`.`users` SET `rol_id`=2 WHERE `id`=22;
UPDATE `qalendar`.`users` SET `rol_id`=2 WHERE `id`=21;
UPDATE `qalendar`.`users` SET `rol_id`=2 WHERE `id`=23;