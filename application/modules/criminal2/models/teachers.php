<?php
/**
 * 
 *
 *
 * @package    	Ebre-escool
 * @author     	Criminal
 * @version    	1.0
 * @link		crimiwiki.esy.es
 */
class Teachers  extends CI_Model  {
	
	function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

//GET ALL TEACHERS
function getAllTeachers() {
   //THE QUERY
    /*SELECT `teacher_id` , 
    `teacher_person_id` , `teacher_user_id` , `teacher_entryDate` , 
    `teacher_last_update` , `teacher_creationUserId` , 
    `teacher_lastupdateUserId` , `teacher_markedForDeletion` , 
    `teacher_markedForDeletionDate` , `person_officialid`
    FROM `teacher` */

    $this->db->select('teacher_id,teacher_person_id ,teacher_user_id ,teacher_entryDate, 
    teacher_last_update , teacher_creationUserId, 
    teacher_lastupdateUserId,teacher_markedForDeletion, 
    teacher_markedForDeletionDate , person_officialid');
    $this->db->from('teacher');
    $query = $this->db->get();
    //echo $this->db->last_query(). "<br/>";
    
    $teachers = array();
    if($query->num_rows()>0){
      
      foreach ($query->result() as $row ) {
        $teacher = new stdClass;
        $teacher->id = $row->teacher_id;
        $teacher->person_id = $row->teacher_person_id;
        $teacher->user_id = $row->teacher_user_id;
        $teacher->entry_date = $row->teacher_entryDate;
        $teacher->last_update = $row->teacher_last_update;
        $teacher->creator_id = $row->teacher_creationUserId;
        $teacher->last_update_user_id = $row->teacher_lastupdateUserId;
        $teacher->marked_for_deletion = $row->teacher_markedForDeletion;
        $teacher->marked_for_deletion_date = $row->teacher_markedForDeletionDate;
        $teacher->DNI_NIF = $row->person_officialid;
        array_push($teachers, $teacher);
      }
       return $teachers;
    }else{             
          return FALSE;
          }
     }


     //Get just one teacher by id
     function getOneTeacher($id){
      //THE QUERY
    /*SELECT `teacher_id` , 
    `teacher_person_id` , `teacher_user_id` , `teacher_entryDate` , 
    `teacher_last_update` , `teacher_creationUserId` , 
    `teacher_lastupdateUserId` , `teacher_markedForDeletion` , 
    `teacher_markedForDeletionDate` , `person_officialid`
    FROM `teacher` WHERE `teacher_id`= $id;*/
      $this->db->select('teacher_id,teacher_person_id ,teacher_user_id ,teacher_entryDate, 
      teacher_last_update , teacher_creationUserId, 
      teacher_lastupdateUserId,teacher_markedForDeletion, 
      teacher_markedForDeletionDate , person_officialid');
       $this->db->where('teacher_id',$id);
       $this->db->from('teacher');
       $query = $this->db->get();
       //echo $this->db->last_query(). "<br/>";
       //Test if we have row
       if ($query->num_rows()==1){
        $row = $query->row();
        $teacher = new stdClass;
        $teacher->id = $row->teacher_id;
        $teacher->person_id = $row->teacher_person_id;
        $teacher->user_id = $row->teacher_user_id;
        $teacher->entry_date = $row->teacher_entryDate;
        $teacher->last_update = $row->teacher_last_update;
        $teacher->creator_id = $row->teacher_creationUserId;
        $teacher->last_update_user_id = $row->teacher_lastupdateUserId;
        $teacher->marked_for_deletion = $row->teacher_markedForDeletion;
        $teacher->marked_for_deletion_date = $row->teacher_markedForDeletionDate;
        $teacher->DNI_NIF = $row->person_officialid;
        return $teacher;
      }else{
        return false;
      }
    }
    //Delete teacher using id
    function deleteTeacher($id) {
      //THE QUERY
      //DELETE FROM `teacher` WHERE teacher_id = $id
      if ($id) {
          $this->db->where('teacher_id',$id);
          $what = $this->db->delete('teacher');
          $row = $this->db->affected_rows();
          //echo $this->db->last_query()."<br/>";
          //TESTING IF WE DELETED IT 
          if($row == 1){
           return true;
          }else{
           return false;
          }
          
     
      }
    }

        //update teacher
      function updateTeacher($id,$data){
        // THE QUERY
        //UPDATE `teacher` SET `teacher_id`=[value-1],`teacher_person_id`=[value-2],
        //`teacher_user_id`=[value-3],`teacher_entryDate`=[value-4],
        //`teacher_last_update`=[value-5],`teacher_creationUserId`=[value-6],
        //`teacher_lastupdateUserId`=[value-7],`teacher_markedForDeletion`=[value-8],
        //`teacher_markedForDeletionDate`=[value-9],`person_officialid`=[value-10] WHERE teacher_id = $id
          
          //If exists $id AND $data we update(in this case only one camp)
          if ($id && $data){
            $this->db->where('teacher_id',$id);
            $this->db->update('teacher',$data);
            //echo $this->db->last_query(). "<br/>";
            //Check if it have gone right
            if($this->db->affected_rows()==1){
                return true;
            }else{
                return false;
            }
          }
      }

       function insertTeacher($data){
          //THE QUERY
          // INSERT INTO `teacher`(`teacher_id`, `teacher_person_id`, `teacher_user_id`, 
         //`teacher_entryDate`, `teacher_last_update`, `teacher_creationUserId`, 
         //`teacher_lastupdateUserId`, `teacher_markedForDeletion`, 
         //`teacher_markedForDeletionDate`, `person_officialid`) VALUES ($data);
        if ($data){
          //Make the insert
            $this->db->insert('teacher',$data);

            $row = $this->db->affected_rows();
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


            //echo $this->db->last_query()."<br/>";
           // echo $this->db->affected_rows();
            
            
          

?>