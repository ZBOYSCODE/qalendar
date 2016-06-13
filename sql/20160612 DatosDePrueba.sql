/*Borrar data anterior de estas tablas , antes de ejcutar este script*/

INSERT INTO `actividad` (`actv_id`, `accs_id`, `prrd_id`, `actv_descripcion_breve`, `actv_descripcion_ampliada`, `actv_status`, `actv_location`, `actv_fecha`, `actv_hora`, `actv_duracion_horas`, `actv_duracion_minutos`, `actv_categoria`, `actv_creado_por`, `actv_comentarios`, `actv_created_at`, `actv_updated_at`) VALUES
(1, 1, 1, '[Qalendar] Revisión de Código', 'Revisión de código y documentación del módulo 10.10.2 Eventos', 'Pendiente Aprobación', 'Dependencias Zenta', '2016-06-09', '12:00:00', 1, 60, 'Codigo', 'Jorge Silva', 'Sin comentarios', '2016-06-11 21:39:14', '0000-00-00 00:00:00'),
(2, 2, 1, '[Prueba] Revisión Integración', 'Pruebas de integración de sistema mediante Web Services', 'Aprobado', 'Dependencias Banco', '2016-06-06', '08:00:00', 1, 60, 'Prueba', 'Jorge Silva', 'SN', '2016-06-12 00:11:11', '0000-00-00 00:00:00'),
(3, 2, 1, '[Documentación] Revisión Documento Interfaces', 'Revisión detalle, documento para su posterior aprobación y difusión en fichero de la empresa.', 'Aprobado', 'Dependencias Zenta', '2016-06-10', '17:00:00', 1, 60, 'Documentacion', 'Jorge Silva', 'SC', '2016-06-12 00:27:19', '0000-00-00 00:00:00');


INSERT INTO `disponible` (`dspn_id`, `actv_id`, `user_id`, `edsp_id`, `cnfg_id`, `dspn_fecha`, `dspn_hora`) VALUES
(1, 1, 1, 1, 1, '2016-06-09', '13:00:00'),
(2, 3, 1, 1, 1, '2016-06-10', '17:00:00'),
(3, 2, 1, 1, 1, '2016-06-06', '08:00:00'),
(4, NULL, 1, 1, 1, '2016-06-06', '12:00:00');