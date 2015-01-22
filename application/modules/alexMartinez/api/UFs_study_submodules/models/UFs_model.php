<?php
/**
 * Reports_model Model
 *
 * @package    	Ebre-escool
 * @subpackage  API
 * @version    	1.0
 * @author      Alex Martinez Capilla	
 * @link		http://www.acacha.com/index.php/ebre-escool
 */

class UFs_model  extends CI_Model{
	
	function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function getAllUFs(){
    	$this->db->select('study_submodules_id,study_submodules_shortname ,study_submodules_name,study_submodules_study_module_id,study_submodules_courseid,study_submodules_order,study_submodules_description,study_submodules_entryDate,study_submodules_last_update,study_submodules_creationUserId,study_submodules_lastupdateUserId,study_submodules_markedForDeletion,study_submodules_markedForDeletionDate');
    	$this->db->from('study_submodules');
		$query = $this->db->get();
		//echo $this->db->last_query(). "<br/>";
		$ufs = array();
		if($query->num_rows()>0){
			foreach ($query->result() as $row ) {
				$uf = new stdClass;
				$uf->id = $row->study_submodules_id;
				$uf->shortname = $row->study_submodules_shortname;
				$uf->name = $row->study_submodules_name;
				$uf->module_id = $row->study_submodules_study_module_id;
				$uf->courseid = $row->study_submodules_courseid;
				$uf->order = $row->study_submodules_order;
				$uf->description = $row->study_submodules_description;
				$uf->entryDate = $row->study_submodules_entryDate;
				$uf->last_update = $row->study_submodules_last_update;
				$uf->creationUserId = $row->study_submodules_creationUserId;
				$uf->lastupdateUserId = $row->study_submodules_lastupdateUserId;
				$uf->markedForDeletion = $row->study_submodules_markedForDeletion;
				$uf->markedForDeletionDate = $row->study_submodules_markedForDeletionDate;
				array_push($ufs, $uf);
			}
			return $ufs;
		}else{
			return FALSE;
		}	
    }

    //Get just one study_submodules by id
	function getOneUf($id){
		$this->db->select('study_submodules_id,study_submodules_shortname ,study_submodules_name,study_submodules_study_module_id,study_submodules_courseid,study_submodules_order,study_submodules_description,study_submodules_entryDate,study_submodules_last_update,study_submodules_creationUserId,study_submodules_lastupdateUserId,study_submodules_markedForDeletion,study_submodules_markedForDeletionDate');

		$this->db->where('study_submodules_id',$id);
		$this->db->from('study_submodules');
		$query = $this->db->get();
		//echo $this->db->last_query(). "<br/>";
		//Test if we have row
		if ($query->num_rows()==1){
			$row = $query->row();
			$uf->id = $row->study_submodules_id;
				$uf->shortname = $row->study_submodules_shortname;
				$uf->name = $row->study_submodules_name;
				$uf->module_id = $row->study_submodules_study_module_id;
				$uf->courseid = $row->study_submodules_courseid;
				$uf->order = $row->study_submodules_order;
				$uf->description = $row->study_submodules_description;
				$uf->entryDate = $row->study_submodules_entryDate;
				$uf->last_update = $row->study_submodules_last_update;
				$uf->creationUserId = $row->study_submodules_creationUserId;
				$uf->lastupdateUserId = $row->study_submodules_lastupdateUserId;
				$uf->markedForDeletion = $row->study_submodules_markedForDeletion;
				$uf->markedForDeletionDate = $row->study_submodules_markedForDeletionDate;
			return $uf;
		}else{
			return false;
		}
	}

	//Delete study_submodules using id
	function deleteUf($id) {
		//THE QUERY
		//DELETE FROM `study_submodules` WHERE study_submodules_id = $id
		if ($id) {
			$this->db->where('study_submodules_id',$id);
			$this->db->delete('study_submodules');
			//echo $this->db->last_query(). "<br/>";
			
			if($query->num_rows()==0){
				return true;
			}else{
				return false;
			}
		}
	}

	//update study_submodules_id
	function updateUf($id,$data){
		if ($id && $data){
			$this->db->where('study_submodules_id',$id);
			$this->db->update('study_submodules',$data);
			//echo $this->db->last_query(). "<br/>";
			return true;
		}else{
			return false;
		}
	}

	function insertUf($data){
		if ($data){
			$this->db->insert('study_submodules',$data);
	//echo $this->db->last_query(). "<br/>";
			if(!$query->num_rows()==0){
				return true;
			}else{
				return false;
			}
		}
	}
}
?>

