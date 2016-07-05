<?php
	namespace Gabs\AccesoAcl;

	use Phalcon\Mvc\User\Component;
	use Phalcon\Mvc\Dispatcher;

	class AccesoAcl extends Component{

		private static $disp;

		/**
		 *  	1 - Admin
		 *		2 - Gestor
		 *		3 - Qa
		 *		4 - Jefe proyecto
		 */

		static public 	$res =   [	1	=> [ 'acceso' => ['denegado' => 1],
											'actividad' => [
                                                    'editarevento'    	=> 1,
                                                    'editar'    		=> 1,
													'crearevento'		=> 1,
													'guardarevento'		=> 1,
													'verperfilevento'	=> 1,
                                                    'crearhito'			=> 1,
                                                    'uploadarchivo'		=> 1,
                                                    'downloadfile'		=> 1,
                                                    'eliminararchivo'	=> 1,
                                                    'listaarchivos'		=> 1,
                                                    'updestado'			=> 1,
                                                    'buscar'			=> 1,
                                                    'cargaqabyproject'	=> 1,
                                                    'getduracioncat'	=> 1,
                                                    'delete'			=> 1,
                                                    'activar'			=> 1
                                                    ],
                                            'hito' => [
                                            		'crearhito' 	=> 1,
                                            		'deletehito'	=> 1
                                            		],
		                                    'gestionqa' => [
		                                                    'index'                 => 1,
		                                                    'vistasemanal'          => 1,
															'changecalendaruser'    => 1,
															'changecalendardate'    => 1,
		                                                    'vistadiaria'           => 1,
		                                                    'changedailydate'       => 1,
		                                                    'geteventdetail'        => 1,
		                                                    'encontrarbloque'       => 1,
		                                                    'seleccionarbloque'     => 1
		                                                ],
		                                    'usuarios' => [
		                                    				'index'			=> 1,
		                                    				'profile'		=> 1,
		                                    				'editprofile'	=> 1
		                                    			],
		                                    'proyecto' => [
		                                    				'index'			=> 1,
		                                    				'list'			=> 1,
		                                    				'create'		=> 1,
		                                    				'store'			=> 1,
		                                    				'edit'			=> 1,
		                                    				'update'		=> 1,
		                                    				'delete' 		=> 1,
		                                    				'perfil'		=> 1,
		                                    				'activar'		=> 1
		                                    ],
											'test' => [
												'testdir' 	=> 1,
												'testform' 	=> 1,
												'myformsave' 	=> 1
											],
		                                ],
		                            # GESTOR
									2   =>  [ 'acceso' => ['denegado' => 1],
											'actividad' => [
                                                    'editarevento'    	=> 0,
                                                    'editar'    		=> 0,
													'crearevento'		=> 0,
													'verperfilevento'	=> 1,
													'guardarevento'		=> 0,
													'uploadarchivo'		=> 1,
													'eliminararchivo'	=> 1,
													'downloadfile'		=> 1,
													'listaarchivos'		=> 1,
													'updestado'			=> 1,
													'buscar'			=> 1,
													'cargaqabyproject'	=> 1,
                                                    'getduracioncat'	=> 1,
                                                    'delete'			=> 1,
                                                    'activar'			=> 1
                                                    ],
                                            'hito' => [
                                            		'crearhito' => 1,
                                            		'deletehito'	=> 1
                                            		],
		                                    'gestionqa' => [
		                                                    'index'                 => 1,
		                                                    'vistasemanal'          => 1,
		                                                    'vistadiaria'           => 0,
		                                                    'changedailydate'       => 0,
		                                                    'geteventdetail'        => 0,
															'changecalendaruser'    => 0,
															'changecalendardate'    => 1,
		                                                    'encontrarbloque'       => 0,
		                                                    'seleccionarbloque'     => 0
		                                                ],
		                                    'usuarios' => [
		                                    				'index'			=> 1,
		                                    				'profile'		=> 1,
		                                    				'editprofile'	=> 1
		                                    			],
		                                    'proyecto' => [
		                                    				'index'			=> 1,
		                                    				'list'			=> 1,
		                                    				'create'		=> 1,
		                                    				'store'			=> 1,
		                                    				'edit'			=> 1,
		                                    				'update'		=> 1,
		                                    				'delete' 		=> 1,
		                                    				'perfil'		=> 1,
		                                    				'activar'		=> 1
		                                    				]
		                                ],
		                            #QA
		                            3 => [	'acceso' 	=> [
		                            						'denegado' => 1
		                                    			],
		                                    'actividad' => [
															'verperfilevento'	=> 1,
		                                                    'downloadfile'		=> 1,
		                                                    'listaarchivos'		=> 1,
		                                                    'buscar'			=> 1
	                                                    ],
	                                        'proyecto'	=> [
		                                    				'perfil'			=> 1
	                                        			]

		                            ],
		                            # JEFE PROYECTO
		                            4 => [	'acceso' 	=> [
		                            						'denegado' => 1
		                            					],
		                            		'proyecto' 	=> [
		                                    				'index'			=> 1,
		                                    				'list'			=> 1,
		                                    				'perfil'		=> 1
		                                    			],
		                                    'actividad' => [
		                                                    'editarevento'    	=> 1,
		                                                    'editar'    		=> 1,
															'crearevento'		=> 1,
															'guardarevento'		=> 1,
															'verperfilevento'	=> 1,
		                                                    'crearhito'			=> 1,
		                                                    'uploadarchivo'		=> 1,
		                                                    'downloadfile'		=> 1,
		                                                    'eliminararchivo'	=> 1,
		                                                    'listaarchivos'		=> 1,
		                                                    'updestado'			=> 1,
		                                                    'buscar'			=> 1,
		                                                    'cargaqabyproject'	=> 1,
                                                    		'getduracioncat'	=> 1,
                                                    		'delete'			=> 1,
                                                    		'activar'			=> 1
	                                                    ],
		                                    'gestionqa' => [
		                                                    'index'                 => 1,
		                                                    'vistasemanal'          => 1,
															'changecalendaruser'    => 1,
															'changecalendardate'    => 1,
		                                                    'vistadiaria'           => 1,
		                                                    'changedailydate'       => 1,
		                                                    'geteventdetail'        => 1,
		                                                    'encontrarbloque'       => 1,
		                                                    'seleccionarbloque'     => 1
		                                                ]                  

		                            ]
		                        ];
		//private $


		
		public static function tieneAcceso()
		{
			# instanciamos para poder obtener los datos
			$acceso 		= new AccesoAcl();
			$auth 			= $acceso->getRol();
			$action 		= $acceso->getAction();
			$controlador 	= $acceso->getControlador();

			# solo si está seteada la variable correspondiente devolvemos si estado
			if(isset(self::$res[$auth][$controlador][$action])){
				return self::$res[$auth][$controlador][$action];
			}

			# si no existe la variable, por defecto no tendrá acceso
			return false;
		}

		public static function tienePermiso($action, $controlador = null)
		{
			# instanciamos para poder obtener los datos
			$acceso 	= new AccesoAcl();
			$auth 		= $acceso->getRol();
			
			#obtenemos el action o metodo al que se requiere acceder
			$action = strtolower($action);

			# seteamos el controlador enviado como parametro
			if(isset($controlador)){
				$controlador = strtolower($controlador);
			}else{
				$controlador 	= $acceso->getControlador();
			}

			# solo si está seteada la variable correspondiente devolvemos si estado
			if(isset(self::$res[$auth][$controlador][$action])){
				return self::$res[$auth][$controlador][$action];
			}
			
			# si no existe la variable, por defecto no tendrá acceso
			return false;
		}

		private function getRol()
		{
			return $this->auth->getIdentity()['roleId'];
		}

		private function getControlador()
		{
			return strtolower($this->dispatcher->getControllerName());
		}

		private function getAction()
		{
			return strtolower($this->dispatcher->getActionName());
		}

	}