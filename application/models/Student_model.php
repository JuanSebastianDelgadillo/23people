<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student_model extends CI_Model {

	public function get_students(){
		$this->db->select('*');
		$this->db->from('student');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_students_limit( $start, $limit ){
		$this->db->select('*');
		$this->db->from('student');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_students_count(){
		$this->db->select('*');
		$this->db->from('student');
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_students_where($val){
		$this->db->select('*');
		$this->db->from('student');
		$this->db->where('rut',$val);
		$query = $this->db->get();
		return $query->result();
	}

	public function add_students($datos){
		$this->db->insert('student',$datos);
		return $this->db->affected_rows();
	}

	public function search_code($code){
		$this->db->select('*');
		$this->db->from('course');
		$this->db->where('code', $code);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function search_students_rut($rut){
		$this->db->select('*');
		$this->db->from('student');
		$this->db->where('rut', $rut);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function update_students($rut, $data){
		
		$this->db->where('rut', $rut);
		$this->db->update('student', $data);
		return $this->db->affected_rows();
	}

	public function delete_students($rut){
		
		$this->db->where('rut', $rut);
		$this->db->delete('student');
		return $this->db->affected_rows();
	}

	
	  
}


