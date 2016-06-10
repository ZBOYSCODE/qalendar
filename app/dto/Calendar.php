<?php
namespace Gabs\Dto;
use Gabs\Models\Disponible;
use Gabs\Models\Actividad;

class Calendar
{

	private $hora_inicio = "09:00:00";
	private $hora_fin = "18:00:00";
	private $intervalo = 1;

	public $day = array();
	public $week = array();
	public $month = array();

	public function getWeek($data){
		$horas = $this->getHorasWeek();
		$fechas = $this->getFechasWeek($data['fecha']);

		$d_model = new Disponible;

		$disponibles = $d_model->getDisponiblesWeek($data);

		$dspnArray = $disponibles->toArray();


		foreach ($fechas as $fecha) {
			foreach ($horas as $hora) {
				if(!isset($week[$fecha][$hora]))
					$week[$fecha][$hora] = 0;
			}	
		}

		foreach ($disponibles as $disponible) {
			$fecha = date('d-m-Y',strtotime($disponible->dspn_fecha));
			$hora = date('H:i',strtotime($disponible->dspn_hora));
			$week[$fecha][$hora] = $disponible;
		}		

		return $week;
	}

	private function getHorasWeek(){
		$horaActual=$this->hora_inicio;
		while ( $horaActual < $this->hora_fin) { 
			$horas[] = date('H:i',strtotime($horaActual));
			$horaActual = date('H:i',strtotime($horaActual." + {$this->intervalo} hours "));
		}
		return $horas;
	}

	private function getFechasWeek($fecha){
		$week = date('W',strtotime($fecha));
		$year = date('Y',strtotime($fecha));
	    $i = 1;
	    while($i < 8 ){
	    	$fechas[] = date("d-m-Y", strtotime("{$year}-W{$week}-{$i}"));
	    	$i++;
    	}
    	return $fechas;
	}

	public function getDay($data){
		$horas = $this->getHorasWeek();

		$a_model = new Actividad;

		$actividades = $a_model->getActividadesDay($data);
	}	




}