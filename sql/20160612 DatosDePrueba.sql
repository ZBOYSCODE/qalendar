/*Borrar data anterior de estas tablas , antes de ejcutar este script*/

INSERT INTO `actividad` (`actv_id`, `accs_id`, `prrd_id`, `actv_descripcion_breve`, `actv_descripcion_ampliada`, `actv_status`, `actv_location`, `actv_fecha`, `actv_hora`, `actv_duracion_horas`, `actv_duracion_minutos`, `actv_categoria`, `actv_creado_por`, `actv_comentarios`, `actv_created_at`, `actv_updated_at`) VALUES
(1, 1, 1, '[Qalendar] Revisión de Código', 'Revisión de código y documentación del módulo 10.10.2 Eventos', 'Pendiente Aprobación', 'Dependencias Zenta', '2016-06-09', '13:00:00', 1, 60, 'Codigo', 'Jorge Silva', 'Sin comentarios', '2016-06-11 21:39:14', '0000-00-00 00:00:00'),
(2, 2, 1, '[Prueba] Revisión Integración', 'Pruebas de integración de sistema mediante Web Services', 'Aprobado', 'Dependencias Banco', '2016-06-06', '08:00:00', 1, 60, 'Prueba', 'Jorge Silva', 'SN', '2016-06-12 00:11:11', '0000-00-00 00:00:00'),
(3, 2, 1, '[Documentación] Revisión Documento Interfaces', 'Revisión detalle, documento para su posterior aprobación y difusión en fichero de la empresa.', 'Aprobado', 'Dependencias Zenta', '2016-06-10', '17:00:00', 1, 60, 'Documentacion', 'Jorge Silva', 'SC', '2016-06-12 00:27:19', '0000-00-00 00:00:00');


INSERT INTO `disponible` (`dspn_id`, `actv_id`, `user_id`, `edsp_id`, `cnfg_id`, `dspn_fecha`, `dspn_hora`) VALUES
(1, 1, 1, 1, 1, '2016-06-09', '13:00:00'),
(2, 3, 1, 1, 1, '2016-06-10', '17:00:00'),
(3, 2, 1, 1, 1, '2016-06-06', '08:00:00'),
(4, NULL, 1, 1, 1, '2016-06-06', '12:00:00'),
(5, NULL, 1, 1, 1, '2016-06-13', '08:00:00'),
(6, NULL, 1, 1, 1, '2016-06-13', '09:00:00'),
(7, NULL, 1, 1, 1, '2016-06-13', '10:00:00'),
(8,NULL, 1, 1, 1, '2016-06-13', '11:00:00'),
(9, NULL, 1, 1, 1, '2016-06-14', '08:00:00'),
(10, NULL, 1, 1, 1, '2016-06-14', '09:00:00'),
(11, NULL, 1, 1, 1, '2016-06-14', '10:00:00'),
(12,NULL, 1, 1, 1, '2016-06-14', '11:00:00'),
(13, NULL, 1, 1, 1, '2016-06-15', '08:00:00'),
(14, NULL, 1, 1, 1, '2016-06-15', '09:00:00'),
(15, NULL, 1, 1, 1, '2016-06-15', '10:00:00'),
(16,NULL, 1, 1, 1, '2016-06-15', '11:00:00'),
(17, NULL, 1, 1, 1, '2016-06-16', '08:00:00'),
(18, NULL, 1, 1, 1, '2016-06-16', '09:00:00'),
(19, NULL, 1, 1, 1, '2016-06-16', '10:00:00'),
(20,NULL, 1, 1, 1, '2016-06-16', '11:00:00');

INSERT INTO `user_actividad` (`user_id`, `actv_id`) VALUES
(1,1),
(11,1),
(1,2),
(1,3);