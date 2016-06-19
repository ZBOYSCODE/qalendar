<?php

namespace Gabs\Controllers;
use Gabs\Models\Actividad;
use \Gabs\Dto\Calendar;
use \Gabs\Models\Disponible;
use \Gabs\Models\Users;

class TestController extends ControllerBase
{


	private $hora_inicio = "08:00:00";
	private $hora_fin = "17:00:00";
	private $intervalo = 1;


	public function indexAction(){
		$calendar = new Calendar();
		$data = array('fecha'=>'2016-06-09','user_id'=>1
			);
		$week = $calendar->getWeek($data);

		$calendar->getDay($data);
		$this->script();
	}

	public function scriptAction(){

		$horas = $this->getHorasWeek();
		$fecha = '2016-06-08';
		$text = "";
		foreach ($horas as $val) {
			$text = $text."INSERT INTO `qalendar`.`disponible` (`dspn_id`, `actv_id`, `user_id`, `edsp_id`, `cnfg_id`, `dspn_fecha`, `dspn_hora`) VALUES (NULL, NULL, '1', '1', '1', '{$fecha}', '{$val}'); ";

			
		}
		print $text;

	}

	private function getHorasWeek(){
		$horaActual=$this->hora_inicio;
		while ( $horaActual < $this->hora_fin) { 
			$horas[] = date('H:i:s',strtotime($horaActual));
			$horaActual = date('H:i:s',strtotime($horaActual." + {$this->intervalo} hours "));
		}
		return $horas;
	}	

	public function testMigueloAction(){
		$a = Actividad::findFirst("actv_id = 1");
		
		print_r($a->Archivos->toArray());die();

		foreach ($a->Archivos as $key => $value) {
			print_r($value);
		}


		//$user = Users::findFirstById(1);
		//print_r($user->rol);
	}

}
