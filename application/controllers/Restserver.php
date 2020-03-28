<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Restserver extends REST_Controller {

	 public function __construct() {
       parent::__construct();
       // load the model page
       $this->load->model('Restapi_model');
         // Load these helper to create JWT tokens
        $this->load->helper(['jwt', 'authorization']);
    }

    /**
	*	Function to get token
    **/
    public function token_get()
    {
        $tokenData = 'Hello 23people!';
        // Create a token
        $token = AUTHORIZATION::generateToken($tokenData);
        // Set HTTP status code
        $status = parent::HTTP_OK;
        // Prepare the response
        $response = ['status' => $status, 'token' => $token];
        // REST_Controller provide this method to send responses
        $this->response($response, $status);
    }

    /**
	*	I get All data from courses and per page, when It doesn't have a id, this show all Data per page, while it have the word "all", show all data whit format json
    **/

	public function courses_get( $perPage = null  )
	{
		//initialize array second
		$dataPerPage = array();
		//initialize array main
		$data = array();
		// I question if the variable have the word all
		if ( $perPage === 'all') {
			// Show all data
			$data['courses'] = $this->Restapi_model->get_courses();
			
			
		}elseif($perPage==null){
			// if not show per page
			$perPage = 3; //count of register perpage
			$cantReg = $this->Restapi_model->get_courses_count(); //find the count of register
			$cantPag = ceil($cantReg / $perPage); //Rounding count pages
			$dats 	= array();
			$page 	= 1; //page initial
			$start 	= 0; //initial of search

			for ($a=0; $a < $cantPag; $a++) {
				//I make a personalized pagination
				$dataPerPage['page_'.$page] = $this->Restapi_model->get_courses_limit( $start, $perPage );
				$start 	= $start+$perPage;
				$page++;

			}
			//add data at array main
			$data['courses'] = $dataPerPage;

		}else{

			//find the data of database
			$resp = $this->Restapi_model->get_courses_where($perPage);

			if ($resp) {
				//add data at array main I add at courses array
				$data['courses'] = $resp;
			}else{
				// if not show state 404
				$data['courses'] = $this->show_404();

			}

		}
		// show data main
		$this->response($data);
		
	}

	  /**
	*	Here I have function post
    **/

	function courses_post()
    {
    	$data = array();
    	// decode json data
    	$data = json_decode($this->input->raw_input_stream);

    	// if data is not null

    	if (isset($data->code) && isset($data->name)) {
    		// I make array with data
    		$data = array(
 			'name' => $data->name,
 			'code' => $data->code
			);
    		// I answer if exist code
    		if ($this->Restapi_model->search_course($data['code'])===1) {
 				// if exist show status 400
 				$res['courses'] = $this->show_400();
			}else{
				// 
	 			if($this->Restapi_model->add_course($data)==1)
	 			{
	 				// that's correct, save course
	 				$res['courses'] = $this->show_201();
	 			}else{
	 				// it can`t save course
	 				$res['courses'] = $this->show_400();
	 			}
 			}
    		
    	}else{
    		// if not status 400
    		$res['courses'] = $this->show_400();
    	}

    	// show data
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

    			// if exist the id
				if($this->Restapi_model->search_course_id($id)==1)
				{
					// update data
					if($this->Restapi_model->update_course($id, $data)==1)
		 			{	
		 				// that's correct, update course
		 				$res['courses'] = $this->show_201();
		 			}else{
		 				// it can`t update
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

    		// I get id of the course at delete an answer if exists
    		if($this->Restapi_model->delete_course($id)==1)
			{
				// that's correct, delete course
				$res['courses'] = $this->show_200();

			}else{
				// It can`t delete
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
