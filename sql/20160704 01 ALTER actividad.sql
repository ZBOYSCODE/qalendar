ALTER TABLE `actividad` 
DROP COLUMN `actv_duracion_minutos`,
DROP COLUMN `actv_duracion_horas`;

ALTER TABLE `actividad` 
ADD COLUMN `activo` TINYINT NULL DEFAULT 1 COMMENT '' AFTER `actv_updated_at`;
