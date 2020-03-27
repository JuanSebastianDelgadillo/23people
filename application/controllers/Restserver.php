<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Restserver extends REST_Controller {

	 public function __construct() {
       parent::__construct();
       $this->load->database();
    }

	public function courses_get()
	{
		$array = array('Hola', 'Mundo', 'Codeginiter');
		$this->response($array);
	}
}
