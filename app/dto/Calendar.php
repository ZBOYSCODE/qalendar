<?php
namespace Gabs\Dto;
use Gabs\Models\Disponible;
use Gabs\Models\Actividad;

class Calendar
{

	private $hora_inicio = "08:00:00";
	private $hora_fin = "17:00:00";
	private $intervalo = 1;

	public $day = array();
	public $week = array();
	public $month = array();

	/**
	 * @param $data
	 * @return mixed
     */
	public function getWeek($data){
		$horas = $this->getHorasWeek();
		$fechas = $this->getFechasWeek($data['fecha']);

		$d_model = new Disponible;

		$disponibles = $d_model->getDisponiblesWeek($data);


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
			$week[$hora][$fecha] = $disponible;
		}
		return $week;
	}

	/**
	 * @return array
     */
	public function getHorasWeek(){
		$horaActual=$this->hora_inicio;
		while ( $horaActual <= $this->hora_fin) {
			$horas[] = date('H:i',strtotime($horaActual));
			$horaActual = date('H:i',strtotime($horaActual." + {$this->intervalo} hours "));
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

			# Obtenemos la hora de inicio de la actividad
			//$hora = explode(':', $value->actv_hora);
			//$hora = (int)$hora[0];

			#Guardamos hora como key
			$hora = date('H:i',strtotime($value->actv_hora));
			# Guardamos el objeto actividad en un array con la hora de inicio como indice
			$data[$hora][] = $value;
		}

		# ordenamos por hora de menor a mayor
		ksort($data);
		return $data;
	}



}