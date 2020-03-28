<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Restserver extends REST_Controller {

	 public function __construct() {
       parent::__construct();
       // load the model page
       $this->load->model('Restapi_model');
    }

    /**
	*	Get All data from courses
    **/

	public function courses_get( $perPage = null  )
	{
		$dataPerPage = array();
		$data = array();

		if ( $perPage === 'all') {

			$data['courses'] = $this->Restapi_model->get_courses();
			
			
		}elseif($perPage==null){
		
			$perPage = 3;
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

		}else{

			$resp = $this->Restapi_model->get_courses_where($perPage);

			if ($resp) {
				$data['courses'] = $resp;
			}else{
				
				$data['courses'] = $this->show_404();

			}

		}

		$this->response($data);
		
	}

	function courses_post()
    {
        // with this function we receive a new course
        $data = array('returned: '. $this->post('id'));
        $this->response($data);
    }
 
    function courses_put()
    {
        // with this function we update a course
        $data = array('returned: '. $this->put('id'));
        $this->response($data);
    }
 
    
 
    function courses_delete()
    {
         // with this function we delete a course
        $data = array('returned: '. $this->delete('id'));
        $this->response($data);
    }

	public function show_404(){
		$data = array(
			'status'=>'404'
		);
		return $data;
	}


}
