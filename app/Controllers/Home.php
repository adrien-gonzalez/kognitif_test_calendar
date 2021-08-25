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
		$calendar = new CalendarModel();
		return $calendar->test();
	}
}
