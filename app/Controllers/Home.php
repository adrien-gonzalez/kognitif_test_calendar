<?php

namespace App\Controllers;

use App\Models\CalendarModel;

class Home extends BaseController
{
	public function index() {
        $method = $_SERVER["REQUEST_METHOD"];
        $actions = [
            "GET" => "getCalendar",
            "POST" => "postCalendar",
            "PUT" => "putCalendar",
            "DELETE" => "deleteCalendar"
        ];

        $call = $actions[$method];
 
        $response = $this->$call();
        return $response;

    }
	
	public function getCalendar()
	{
		return view('calendar');
	}

	public function postCalendar()
	{
		$param = $this->request->getRawInput();
		$calendar = new CalendarModel();
		return $calendar->postCalendar($param);
	}

	public function putCalendar()
	{
		$param = $this->request->getRawInput();
		$calendar = new CalendarModel();
		return $calendar->putCalendar($param);
	}

	public function deleteCalendar()
	{
		$param = $this->request->getRawInput();
		$calendar = new CalendarModel();
		return $calendar->deleteCalendar($param);
	}

	public function load()
	{
		$calendar = new CalendarModel();
		echo json_encode($calendar->select());
	}
}
