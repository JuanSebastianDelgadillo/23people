<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Restserver extends REST_Controller {

	 public function __construct() {
       parent::__construct();
       // $this->load->database();
        // load the library api
       $this->load->library('pagination');
       // load the model page
       $this->load->model('Restapi_model');
    }

    /**
	*	Get All data from courses
    **/

	public function courses_get( $perPage = 3  )
	{
		$dataPerPage = array();
		$data = array();

		if ( $perPage === 'all') {

			$data['courses'] = $this->Restapi_model->get_courses();
			
			
		}else{
		
			$cantReg = $this->Restapi_model->get_courses_count();
			$cantPag = ceil($cantReg / $perPage);
			$dats 	= array();
			$page 	= 1;
			$start 	= 0;

			for ($a=0; $a < $cantPag; $a++) {

				$dataPerPage['page_'.$page] = $this->Restapi_model->get_courses_limit( $start, $perPage );
				$start 	= $start+$perPage;
				$page++;

			}

			$data['courses'] = $dataPerPage;

		}

		$this->response($data);
		
		
	}

	/**
	*	Get All data from course specific width id
    **/
		public function course_get( $id = 0 )
	{
		
		$array = array('no hay', 'datos', 'encontrados', $id);
		$this->response($array);
	}


}
