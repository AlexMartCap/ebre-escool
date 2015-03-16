<?php
/**
* @package		Ebre-escool
* @subpackage	API
* @category	    Controller
* @author		Sergi Tur Badenas
* @editedBy     Alex Martinez
*/

class studysubmodulesmodel  extends CI_Model{
		
	function __construct()
	{
        parent::__construct();
        $this->load->database();
    }

    function getstudysubmodules(){
    	$this->db->select('study_submodules_id,study_submodules_shortname,
    	study_submodules_name,study_submodules_description');
    	
    	$this->db->from('study_submodules');
		
		$query = $this->db->get();
		//echo $this->db->last_query(). "<br/>";
		$study_submodules = array();

		if($query->num_rows()>0){
			foreach ($query->result() as $row ) {
				$study_submodule = new stdClass;
				// Adding rows to stdClass and changing their names.
				$study_submodule->id = $row->study_submodules_id;
				$study_submodule->shortname = $row->study_submodules_shortname;
				$study_submodule->name = $row->study_submodules_name;
				$study_submodule->description = $row->study_submodules_description;
				
				array_push($study_submodules, $study_submodule);
			}
			return $study_submodules;
		}else{
			return FALSE;
		}	
    }

    //Get just one study_submodules by id
	function getStudysubmodule($id){
		$this->db->select('study_submodules_id,study_submodules_shortname ,study_submodules_name,study_submodules_study_module_id,study_submodules_courseid,study_submodules_order,study_submodules_description,study_submodules_entryDate,study_submodules_last_update,study_submodules_creationUserId,study_submodules_lastupdateUserId,study_submodules_markedForDeletion,study_submodules_markedForDeletionDate');

		$this->db->from('study_submodules');
		$this->db->where('study_submodules_id',$id);

		$query = $this->db->get();
		//echo $this->db->last_query(). "<br/>";

        // Cheching if we've got any row.
		if ($query->num_rows()==1){
			$row = $query->row();
			
			$study_submodule = new stdClass();

			$study_submodule->id = $row->study_submodules_id;
			$study_submodule->shortname = $row->study_submodules_shortname;
			$study_submodule->name = $row->study_submodules_name;
			$study_submodule->module_id = $row->study_submodules_study_module_id;
			$study_submodule->courseid = $row->study_submodules_courseid;
			$study_submodule->order = $row->study_submodules_order;
			$study_submodule->description = $row->study_submodules_description;
			$study_submodule->entryDate = $row->study_submodules_entryDate;
			$study_submodule->last_update = $row->study_submodules_last_update;
			$study_submodule->creationUserId = $row->study_submodules_creationUserId;
			$study_submodule->lastupdateUserId = $row->study_submodules_lastupdateUserId;
			$study_submodule->markedForDeletion = $row->study_submodules_markedForDeletion;
			$study_submodule->markedForDeletionDate = $row->study_submodules_markedForDeletionDate;
			
			return $study_submodule;
		}else{
			return false;
		}
	}

	//Deleting a study_submodules by id.
    function deleteStudysubmodule($del) 
    {    
        if ($del) {
            $this->db->where('study_submodules_id',$del);
            $this->db->delete('study_submodules');
            return true;
        }else{
            return false;
        }
    }

	//update study_submodules_id
    function updateStudysubmodule($id,$data)
    {
        if ($id && $data){
            $this->db->where('study_submodule_id',$id);
            $this->db->update('study_submodule',$data);
            return true;
        }else{
            return false;
        }
    }

	function insertStudysubmodule($data){
        
        if ($data){
          //Make the insert
            $this->db->insert('study_submodules',$data);

            $row = $this->db->affected_rows();
            //log_message('debug','insert response:'.$row);
            $id = $this->db->insert_id();
            $result=array();
            $result['id'] = $id;
            //Check if it have gone right and return response
            if($row ==1){
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

