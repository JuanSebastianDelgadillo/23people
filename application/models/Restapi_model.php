<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restapi_model extends CI_Model {

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

	public function add_course($datos){
		$this->db->insert('course',$datos);
		return $this->db->affected_rows();
	}

	public function search_course($code){
		$this->db->select('*');
		$this->db->from('course');
		$this->db->where('code', $code);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function search_course_id($id){
		$this->db->select('*');
		$this->db->from('course');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function update_course($id, $data){
		
		$this->db->where('id', $id);
		$this->db->update('course', $data);
		return $this->db->affected_rows();
	}

	public function delete_course($id){
		
		$this->db->where('id', $id);
		$this->db->delete('course');
		return $this->db->affected_rows();
	}

	
	  
}


