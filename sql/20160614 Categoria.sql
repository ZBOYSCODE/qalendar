CREATE TABLE categoria (
                ctgr_id INT AUTO_INCREMENT NOT NULL,
                ctgr_nombre VARCHAR(50) NOT NULL,
                PRIMARY KEY (ctgr_id)
);


CREATE TABLE categoria_actividad (
                ctgr_actv_id INT AUTO_INCREMENT NOT NULL,
                ctgr_id INT NOT NULL,
                actv_id INT NOT NULL,
                PRIMARY KEY (ctgr_actv_id, ctgr_id, actv_id)
);


ALTER TABLE categoria_actividad ADD CONSTRAINT actividad_categoria_actividad_fk
FOREIGN KEY (actv_id)
REFERENCES actividad (actv_id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE categoria_actividad ADD CONSTRAINT categoria_categoria_actividad_fk
FOREIGN KEY (ctgr_id)
REFERENCES categoria (ctgr_id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;


INSERT INTO `categoria` (`ctgr_id`, `ctgr_nombre`) VALUES
(1, 'Instalación Beta'),
(2, 'Revisión Código'),
(3, 'Servicio Ambiente'),
(4, 'Instalación Producción'),
(5, 'Validación MVC');