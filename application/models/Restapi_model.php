<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restapi_model extends CI_Model {

	 function __construct() { 
        // Set table name 
        $this->table = 'course'; 
    } 

	public function get_courses(){
		$this->db->select('*');
		$this->db->from('course');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_courses_limit( $start, $limit ){
		$this->db->select('*');
		$this->db->from('course');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_courses_count(){
		$this->db->select('*');
		$this->db->from('course');
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_courses_where($val){
		$this->db->select('*');
		$this->db->from('course');
		$this->db->where('code',$val);
		$query = $this->db->get();
		return $query->result();
	}
	  
}


