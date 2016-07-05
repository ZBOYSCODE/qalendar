<?php
namespace Gabs\Dto;
use Gabs\Models\Disponible;
use Gabs\Models\Actividad;
use Gabs\Models\ConfiguradorDisponibilidad;
use Gabs\Models\Users;

class Calendar
{

	private $hora_inicio;
	private $hora_fin;
	private $intervalo;

	public $day = array();
	public $week = array();
	public $month = array();


	/**
	 * Inicializa los parametros del calendario desde la BD
     */
	public function __construct() {

		$cnfg = ConfiguradorDisponibilidad::findFirst("cnfg_id = 1");

		$this->hora_inicio = $cnfg->cnfg_hora_inicio ;
		$this->hora_fin = $cnfg->cnfg_hora_fin;
		$this->intervalo = $cnfg->cnfg_intervalo;

	}


	/**
	 * @param $data
	 * @return mixed
	 */
	public function getWeek($data){

		$horas = $this->getHorasWeek();
		$fechas = $this->getFechasWeek($data['fecha']);

		$d_model = new Disponible;

		$disponibles = $d_model->getDisponiblesWeek($data);

		## SETEA TO DO EN 0 POR DEFECTO
		foreach ($fechas as $fecha) {
			foreach ($horas as $hora) {
				if(!isset($week[$hora][$fecha]))
					$week[$hora][$fecha] = 0;
			}
		}


		foreach ($disponibles as $disponible) {
			$fecha = date('d-m-Y',strtotime($disponible->dspn_fecha));
			$hora = date('H:i',strtotime($disponible->dspn_hora));
			$disponible = Disponible::findFirst($disponible->dspn_id);

			#Si hay que saltarse el bloque, pasamos a la siguiente iteracion
			if($week[$hora][$fecha]["status"] == "skip" )
				continue;

			#Obtenemos la duración de la actividad (que viene desde la categoría)
			#De momento solo tiene una sola categoría, por lo tanto: obtenemos el primero.
			foreach($disponible->Actividad->CategoriaActividad as $CategoriaActIter){
				$duracion_actividad  = $CategoriaActIter->Categoria->duracion;
				break;
			}

			#cantidad de bloques a ocupar por la actividad lo aproximamos al entero siguiente.
			$bloques = ceil($duracion_actividad/$this->intervalo);

			#si la cantidad de bloques es mayor a 1, se deja un flag para saltarse esa casilla en el calendario
			if($bloques > 1) {
				for($i = 1; $i < $bloques; $i++) {

					#obtenemos la hora de inicio del bloque siguiente
					$cantMinToAdd = (string) ($this->intervalo*$i);
					$bloqueSig = (string) date("H:i", strtotime($hora. "+ ".$cantMinToAdd." minutes"));

					$week[$bloqueSig][$fecha] = array("status"=>"skip");
				}
			}

			//print($bloques);
			$week[$hora][$fecha] = array(
				"obj" =>$disponible,
				"status"=>"disponible",
				"rowspan"=>$bloques,
				"duracion"=>$bloques*$this->intervalo,
			);
		}

		//print_r($week);exit;
		return $week;
	}

	/**
	 * @return array
     */
	public function getHorasWeek(){
		$horaActual=$this->hora_inicio;
		while ( $horaActual <= $this->hora_fin) {
			$horas[] = date('H:i',strtotime($horaActual));
			$horaActual = date('H:i',strtotime($horaActual." + {$this->intervalo} minutes "));
		}

		return $horas;
	}

	/**
	 * @param $fecha
	 * @return array
     */
	public function getFechasWeek($fecha){
		$week = date('W',strtotime($fecha));
		$year = date('Y',strtotime($fecha));
	    $i = 1;
	    while($i < 8 ){
	    	$fechas[] = date("d-m-Y", strtotime("{$year}-W{$week}-{$i}"));
	    	$i++;
    	}
    	return $fechas;
	}


	/**
	 * @param $data
	 * @return array
     */
	public function getDay($fecha){

		$conditions = "actv_fecha = :fecha:";
		$params = array("fecha" => $fecha);

		# obtenemos todad las actividades por día especifico
		$actividades = Actividad::find(array(
			$conditions,
			"bind" => $params
		));

		return $this->ordenar($actividades);

	}

	/**
	 * @param $actividades
	 * @return array
     */
	private function ordenar($actividades)
	{
		$data = array();

		#traemos las horas de la config
		$horas = $this->getHorasWeek();
		foreach ($horas as $hora) {
			$data[$hora] = array();
		}

		# Iteramos las actividades para ordenarlas
		foreach ($actividades as $value) {

			#Guardamos hora como key
			$hora = date('H:i',strtotime($value->actv_hora));
			# Guardamos el objeto actividad en un array con la hora de inicio como indice
			$data[$hora][] = $value;
		}

		# ordenamos por hora de menor a mayor
		ksort($data);
		return $data;
	}


	public function _getDay($fecha) {

		$horas = $this->getHorasWeek();

		#traemos users QA
		$users = Users::find("rol_id = 3");

		$data['fecha'] = $fecha;

		$d_model = new Disponible;
		$disponibles = $d_model->getDisponiblesDay($data);



		## SETEA TO DO EN 0 POR DEFECTO
		foreach ($users as $user) {
			foreach ($horas as $hora) {
				if(!isset($week[$hora][$user->id]))
					$day[$hora][$user->id] = 0;
			}
		}


		foreach ($disponibles as $disponible) {
			$hora = date('H:i',strtotime($disponible->dspn_hora));
			$disponible = Disponible::findFirst($disponible->dspn_id);

			#Si hay que saltarse el bloque, pasamos a la siguiente iteracion
			if($week[$hora][$disponible->user_id]["status"] == "skip" )
				continue;

			#Obtenemos la duración de la actividad (que viene desde la categoría)
			#De momento solo tiene una sola categoría, por lo tanto: obtenemos el primero.
			foreach($disponible->Actividad->CategoriaActividad as $CategoriaActIter){
				$duracion_actividad  = $CategoriaActIter->Categoria->duracion;
				break;
			}

			#cantidad de bloques a ocupar por la actividad lo aproximamos al entero siguiente.
			$bloques = ceil($duracion_actividad/$this->intervalo);

			#si la cantidad de bloques es mayor a 1, se deja un flag para saltarse esa casilla en el calendario
			if($bloques > 1) {
				for($i = 1; $i < $bloques; $i++) {

					#obtenemos la hora de inicio del bloque siguiente
					$cantMinToAdd = (string) ($this->intervalo*$i);
					$bloqueSig = (string) date("H:i", strtotime($hora. "+ ".$cantMinToAdd." minutes"));

					$week[$bloqueSig][$fecha] = array("status"=>"skip");
				}
			}

			//print($bloques);
			$week[$hora][$fecha] = array(
				"obj" =>$disponible,
				"status"=>"disponible",
				"rowspan"=>$bloques,
				"duracion"=>$bloques*$this->intervalo,
			);
		}

		//print_r($week);exit;
		return $week;
	}



}