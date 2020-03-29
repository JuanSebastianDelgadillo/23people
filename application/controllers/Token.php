<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Token extends REST_Controller {

	 public function __construct() {
       parent::__construct();
         // Load these helper to create JWT tokens
        $this->load->helper(['jwt', 'authorization']);
    }

    /**
	*	Function to get token
    **/
    public function index_get()
    {
        $tokenData = 'CLAVE PARA TOKEN!';
        // Create a token
        $token = AUTHORIZATION::generateToken($tokenData);
        // Set HTTP status code
        $status = parent::HTTP_OK;
        // Prepare the response
        $response = ['status' => $status, 'token' => $token];
        // REST_Controller provide this method to send responses
        $this->response($response, $status);
    }
}