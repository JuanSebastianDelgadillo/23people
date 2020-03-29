<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Reststudent extends REST_Controller {

	 public function __construct() {
       parent::__construct();
       // load the model page
       $this->load->model('Student_model');
         // Load these helper to create JWT tokens
        $this->load->helper(['jwt', 'authorization']);
    }

    /**
	*	I get All data from students and per page, when It doesn't have a id, this show all Data per page, while it have the word "all", show all data whit format json
    **/

	public function students_get( $perPage = null  )
	{
		//initialize array second
		$dataPerPage = array();
		//initialize array main
		$data = array();
		// I question if the variable have the word all
		if ( $perPage === 'all') {
			// Show all data
			$data['students'] = $this->Student_model->get_students();
			
			
		}elseif($perPage==null){
			// if not show per page
			$perPage = 3; //count of register perpage
			$cantReg = $this->Student_model->get_students_count(); //find the count of register
			$cantPag = ceil($cantReg / $perPage); //Rounding count pages
			$dats 	= array();
			$page 	= 1; //page initial
			$start 	= 0; //initial of search

			for ($a=0; $a < $cantPag; $a++) {
				//I make a personalized pagination
				$dataPerPage['page_'.$page] = $this->Student_model->get_students_limit( $start, $perPage );
				$start 	= $start+$perPage;
				$page++;

			}
			//add data at array main
			$data['students'] = $dataPerPage;

		}else{

			//find the data of database
			$resp = $this->Student_model->get_students_where($perPage);

			if ($resp) {
				//add data at array main I add students array
				$data['students'] = $resp;
			}else{
				// if not show state 404
				$data['students'] = $this->show_404();

			}

		}
		// show data main
		$this->response($data);
		
	}

	/**
	*	Here I have function post to insert student
    **/

	function students_post()
    {
    	$data = array();
    	// decode json data
    	$data = json_decode($this->input->raw_input_stream);
    	// if data is not null
    	if (isset($data->rut) && isset($data->name) && isset($data->lastName) && isset($data->age) && isset($data->course)) 
    	{

    	// valid string
    		if ($this->valid_code($data->course) && $this->valid_name($data->name)  && $this->valid_name($data->lastName) && $this->valid_age($data->age) && $this->valid_code($data->course) && $this->valid_rut($data->rut)==1) 
    		{

    				// I make array with data
	    		$data = array(
		 			'rut' 		=> $data->rut,
		 			'name' 		=> $data->name,
		 			'lastName' 	=> $data->lastName,
		 			'age' 		=> $data->age,
		 			'course' 	=> $data->course
				);
	    		// I answer if exist code
	    		if ($this->Student_model->search_students_rut($data['rut'])===1) {
	 				// if exist show status 400
	 				$res['students'] = $this->show_400();
				}else{

				// 	// 
		 			if($this->Student_model->add_students($data)==1)
		 			{
		 				// that's correct, save student
		 				$res['students'] = $this->show_201();
		 			}else{
		 				// it can`t save student
		 				$res['students'] = $this->show_400();
		 			}
	 			}
    		}else{
    			$res['students'] = $this->show_400();
    		}

    	}else{
    		// if not status 400
    		$res['students'] = $this->show_400();
    	}

    	// show data
        $this->response($res);

    }

    /**
	*	Here I have function put to update student
    **/
 
    function students_put($rut = null)
    {
    	
    	if ($rut !== null) {
    		$data = array();
    		$data = json_decode($this->input->raw_input_stream);

    		if (isset($data->rut) && isset($data->name) && isset($data->lastName) && isset($data->age) && isset($data->course)) 
    		{
    			
    			if ($this->valid_code($data->course) && $this->valid_name($data->name)  && $this->valid_name($data->lastName) && $this->valid_age($data->age) && $this->valid_code($data->course) && $this->valid_rut($data->rut)==1) 
    			{
    					$data = array(
				 			'rut' 		=> $data->rut,
				 			'name' 		=> $data->name,
				 			'lastName' 	=> $data->lastName,
				 			'age' 		=> $data->age,
				 			'course' 	=> $data->course
						);

		    			// if exist the id
						if($this->Student_model->search_students_rut($rut )==1)
						{
							// update data
							if($this->Student_model->update_students($rut, $data)==1)
				 			{	
				 				// that's correct, update student
				 				$res['students'] = $this->show_201();
				 			}else{
				 				// it can`t update
				 				$res['students'] = $this->show_400();
				 			}

						}else{

				    		$res['students'] = $this->show_400();
				    	}

		    		}else{

			    		$res['students'] = $this->show_400();
			    	}
    			}else{
    				$res['students'] = $this->show_400();
    			}
    		
    	}else{

    		$res['students'] = $this->show_400();
    	}

       $this->response($res);
    }

    /**
	*	Here I have function delete to delete student
    **/
 
    function students_delete($rut = null)
    {
       if ($rut !== null) {
    		if($this->Student_model->search_students_rut($rut)==1)
			{
				// I get rut of the student to delete an answer if exists
	    		if($this->Student_model->delete_students($rut)==1)
				{
					// that's correct, delete student
					$res['students'] = $this->show_200();

				}else{
					// It can`t delete student
					$res['students'] = $this->show_404();
				}

			}else{

				$res['students'] = $this->show_404();

			}
    		
    	}else{
    		$res['students'] = $this->show_404();
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

	/**
	*	Here I have a function to valid code of course, if no exist code I show code 400
    **/

	public function valid_code($code)
	{

		$state = false;
		//valid if code of course exists
		if($this->Student_model->search_code($code)>=1)
		{
			$state = true;
		}
		
		return $state;

	}

	public function valid_name($name){
		//valid if It is string mayor of 4 characters
		$state = false;
		if (strlen($name)>0)
		{
			if (is_string($name)) {
				$state = true;
			}
		
		}

		return $state;
		
	}

	public function valid_rut($rut)
	{
		// code ripped
	    $state = false;
		$rut = preg_replace('/[^k0-9]/i', '', $rut);
	    $dv  = substr($rut, -1);
	    $numero = substr($rut, 0, strlen($rut)-1);
	    $i = 2;
	    $suma = 0;
	    foreach(array_reverse(str_split($numero)) as $v)
	    {
	        if($i==8)
	            $i = 2;

	        $suma += $v * $i;
	        ++$i;
	    }

	    $dvr = 11 - ($suma % 11);
	    
	    if($dvr == 11)
	        $dvr = 0;
	    if($dvr == 10)
	        $dvr = 'K';

	    if($dvr == strtoupper($dv)){
	       $state = true;
	    }
	    else{
	        $state = false;
	    }

	    return $state;

	}

	public function valid_age($age)
	{
		// valid if age is mayor 17 years old
		$state = false;
		if ($age>17) {
			$state = true;
		}

		return $state;

	}

}