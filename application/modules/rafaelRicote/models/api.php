<?php
/**
 *
 *
 * @package    	Ebre-escool
 * @author     	Sergi Tur <sergiturbadenas@gmail.com>
 * @version    	1.0
 * @link		http://www.acacha.com/index.php/ebre-escool
 */
class api extends CI_Model  {
	
	function __construct()
  {
    parent::__construct();
    $this->load->database();

    //$this->load->library('ebre_escool');
  }

  function classroom_group_get($classroom_group_id) {

    $this->db->select('*');
    $this->db->from('classroom_group');
    $this->db->where('classroom_group_id',$classroom_group_id);

    $query = $this->db->get();
    if ($query->num_rows() == 1){
      $row = $query->row();

      $classroom = new stdClass();

      $classroom->id = $row->classroom_group_id;
      $classroom->code = $row->classroom_group_code;

      return $classroom;
    } else
      return false;
  }
  
  function classroom_groups_get() {

    $this->db->select('*');
    $this->db->from('classroom_group');

    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $classrooms = array();

      foreach ($query->result()  as $row) {
        $classroom = new stdClass();

        $classroom->id = $row->classroom_group_id;
        $classroom->code = $row->classroom_group_code;

        array_push($classrooms, $classroom);
      }
      return $classrooms;
    } else
      return false;
  }

  function classroom_groups_delete($id) {
    if ($id) {
      $this->db->where('classroom_group_id',$id);
      $what = $this->db->delete('classroom_group');
      $row = $this->db->affected_rows();
      //echo $this->db->last_query();

      if($row == 1){
        return true;
      }else{
        return false;
      }
    }
  }

  function classroom_groups_update($id,$data){
    if ($id && $data){
      $this->db->where('classroom_group_id',$id);
      $this->db->update('classroom_group',$data);
      //echo $this->db->last_query();

      if($this->db->affected_rows()==1){
        return true;
      }else{
        return false;
      }
    }
  }

  function classroom_groups_insert($data){
    if ($data){
      $this->db->insert('classroom_group',$data);
      $row = $this->db->affected_rows();
      $id = $this->db->insert_id();

      $result=array();
      $result['id'] = $id;
      //Check if it have gone right and return response

      if($row == 1){
        $response = true;
        $result['response'] = $response;
      }else{
        $response = false;
        $result['response'] = $response;
      }
      return $result;
    }
  }
}