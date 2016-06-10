<?php
namespace Gabs\Dto;
use Gabs\Models\Disponible;

class Calendar
{

	private $hora_inicio = "09:00:00";
	private $hora_fin = "18:00:00";
	private $intervalo = 1;

	public $day = array();
	public $week = array();
	public $month = array();

	public $d_model = new Disponible();

    public function initialize()
    {
        $this->settings = array(
            "mySetting" => "value"
        );
    }

	public function getWeek($data){
		$horas = $this->getHorasWeek();
		$fechas = $this->getFechasWeek($data['fecha']);

		$disponibles = $this->d_model->getDisponibles($data);

		foreach ($fechas as $fecha) {
			foreach ($horas as $hora) {
				$this->week[$fecha][$hora] = 0;
			}
		}

		print_r($this->week);
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




}