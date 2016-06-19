CREATE TABLE `qalendar`.`archivos` (
  `arch_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`arch_id`)
) DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;
SHOW CREATE TABLE `qalendar`.`archivos`;
ALTER TABLE `qalendar`.`archivos`
  ADD COLUMN `actv_id` int(11) NULL DEFAULT NULL;
ALTER TABLE `qalendar`.`archivos`
  ADD COLUMN `user_id` int(11) NULL DEFAULT NULL AFTER `arch_id`;
ALTER TABLE `qalendar`.`archivos`
  ADD COLUMN `hito_id` int(11) NULL DEFAULT NULL;
ALTER TABLE `qalendar`.`archivos`
  ADD COLUMN `arch_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `hito_id`,
  ADD COLUMN `arch_updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `qalendar`.`archivos`
  ADD COLUMN `arch_nombre` varchar(255) NULL DEFAULT NULL;
ALTER TABLE `qalendar`.`archivos`
  ADD COLUMN `arch_tipo` varchar(10) NULL DEFAULT NULL;
ALTER TABLE `qalendar`.`archivos`
  ADD COLUMN `arch_ruta` varchar(255) NULL DEFAULT NULL;
ALTER TABLE `qalendar`.`archivos`
  ADD COLUMN `arch_obs` varchar(255) NULL DEFAULT NULL;
ALTER TABLE `qalendar`.`archivos`
  CHANGE COLUMN `arch_created_at` `arch_created_at` datetime NULL DEFAULT NULL;
ALTER TABLE `qalendar`.`archivos`
  CHANGE COLUMN `arch_updated_at` `arch_updated_at` datetime NULL DEFAULT NULL;