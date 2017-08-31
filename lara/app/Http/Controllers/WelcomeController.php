<?php
/**
 * @author tanghuamao@noahwm.com
 * @datetime 2017-08-30 18:17
 */
namespace App\Http\Controllers;

use App\Models\Student;

class WelcomeController {

	public function index()
	{
		return 'welcome controller success';
	}

	public function get()
	{
		$student = Student::first();
		$data = $student->getAttributes();
		return json_encode($data);
	}
}