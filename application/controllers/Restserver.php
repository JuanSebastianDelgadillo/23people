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
    	$data = array();
    	$data = json_decode($this->input->raw_input_stream);

    	if (isset($data->code) && isset($data->name)) {
    		$data = array(
 			'name' => $data->name,
 			'code' => $data->code
			);

    		if ($this->Restapi_model->search_course($data['code'])===1) {
 			
 				$res['courses'] = $this->show_400();
			}else{
	 			if($this->Restapi_model->add_course($data)==1)
	 			{
	 				$res['courses'] = $this->show_201();
	 			}else{
	 				$res['courses'] = $this->show_400();
	 			}
 			}
    		
    	}else{
    		$res['courses'] = $this->show_400();
    	}

        $this->response($res);

    }
 
    function courses_put($id = null)
    {
    	
    	if ($id !== null) {
    		$data = array();
    		$data = json_decode($this->input->raw_input_stream);

    		if (isset($data->name) && isset($data->code))
    		{
    			
    			$data = array(
	 			'name' 	=> $data->name,
	 			'code' 	=> $data->code
				);

				if($this->Restapi_model->search_course_id($id)==1)
				{

					if($this->Restapi_model->update_course($id, $data)==1)
		 			{
		 				$res['courses'] = $this->show_201();
		 			}else{

		 				$res['courses'] = $this->show_400();
		 			}

				}else{

		    		$res['courses'] = $this->show_400();
		    	}

    		}else{

	    		$res['courses'] = $this->show_400();
	    	}


    	}else{

    		$res['courses'] = $this->show_400();
    	}

       $this->response($res);
    }
 
    function courses_delete($id = null)
    {
       if ($id !== null) {
    		$data = array();
    		$data = json_decode($this->input->raw_input_stream);

    		if($this->Restapi_model->delete_course($id)==1)
			{
				$res['courses'] = $this->show_200();

			}else{
				$res['courses'] = $this->show_404();
			}



    	}else{
    		$res['courses'] = $this->show_404();
    	}

    	$this->response($res);
    }

	public function show_404(){
		$data = array(
			'status'=>'404'
		);
		return $data;
	}

	public function show_400(){
		$data = array(
			'status'=>'400'
		);
		return $data;
	}

	public function show_200(){
		$data = array(
			'status'=>'200'
		);
		return $data;
	}

	public function show_201(){
		$data = array(
			'status'=>'201'
		);
		return $data;
	}


}
