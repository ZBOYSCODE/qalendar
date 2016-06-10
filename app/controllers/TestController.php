<?php

namespace Gabs\Controllers;
use \Gabs\Dto\Calendar;

class TestController extends ControllerBase
{
	public function indexAction(){
		$calendar = new Calendar();
		$data = array('fecha'=>'2016-06-15','user_id'=>1
			);
		$week = $calendar->getWeek($data);
	}
}
