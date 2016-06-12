<?php

namespace Gabs\Controllers;
use \Gabs\Dto\Calendar;
use \Gabs\Models\Disponible;

class TestController extends ControllerBase
{
	public function indexAction(){
		$calendar = new Calendar();
		$data = array('fecha'=>'2016-06-09','user_id'=>1
			);
		$week = $calendar->getWeek($data);

		$calendar->getDay($data);

		print_r($calendar);exit();
	}
}
