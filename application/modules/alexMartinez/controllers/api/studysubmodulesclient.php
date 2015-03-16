<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Persons API
 *
 * @package     Ebre-escool
 * @subpackage  API
 * @category    Controller
 * @author      Sergi Tur Badenas
 * @link        http://acacha.org/mediawiki/index.php/ebre-escool
 * @editedBy    Alex Martínez
*/


class studysubmodulesclient extends CI_Controller
{
    function __construct()
    {
       parent::__construct();
        $this->load->library('Rest');
        $config = array('server' =>'http://localhost/ebre-escool/index.php/');
        $this->rest->initialize($config);
    
        //We can add:
        // 'http_user' => 'admin',
        // 'http_pass' => '1234',
        // 'http_auth' => 'basic' // or 'digest'
        // Load the rest client spark
        $this->load->library('Curl');
    }

########################################################################

 //Get one or all studysubmodules
     function getStudySubmodule($id = null){
        
       
       $getStudySubmodule_Url = 'alexMartinez/api/studysubmodules/studysubmodule/id/';
       $getAllStudySubmodule_Url = 'alexMartinez/api/studysubmodules/studysubmodules/';

        //If not null get just one studysubmodule by 'id'
        if($id!=null){
            // We call the function studysubmodule_get of studysubmodules controller
            $studysubmodule = $this->rest->get($getStudySubmodule_Url,array('id'=>$id));
        }else{
            //we want to get all studysubmodules from database
            $studysubmodule = $this->rest->get($getAllStudySubmodule_Url,null);
        }
        echo json_encode($studysubmodule);
    }

########################################################################

    //Update studysubmodule
    function updateStudySubmodule(){
            $updateStudySubmodule_Url = 'alexMartinez/api/studysubmodules/studysubmodule/';
            //EXAMPLE UPDATE WITHOUT FORM
             $id = 200;
             $column = 'name';
             $officialId = 'Prova de modificar';
             $data = array(
                'id'=>$id,
                 $column=>$name);
             
             $updateResponse = $this->rest->post($updateStudySubmodule_Url,$data);
             echo json_encode($updateResponse);
  }

########################################################################

    //Delete studysubmodule
    function deleteStudySubmodule($id = null){
        $deleteStudySubmodule_Url = 'alexMartinez/api/studysubmodules/studysubmodule/';

        if($id){
            
            $deleteResponse = $this->rest->delete($deleteStudySubmodule_Url.'/id/'.$id);
            echo json_encode($deleteResponse);
            
        }else{
          //call server without id if we don't have it
           $message = $this->rest->delete($deleteStudySubmodule_Url.'/id/');
           echo json_encode($message);
        }
    }

########################################################################

    //Insert studysubmodule into studysubmodules table
    function insertStudySubmodule(){
        //Example array to insert into table

        $studysubmodule = array(

            'study_submodules_shortname'=>Uf4,
            'study_submodules_name'=>Cicle de Alex,
            'study_submodules_study_module_id'=>3,
            'study_submodules_courseid'=>4,
            'study_submodules_order'=>5,
            'study_submodules_description'=>Prova de insertar una UF,
            'study_submodules_entryDate'=>'1970-01-11 00:00:00',
            'study_submodules_creationUserid'=>15,
            'study_submodules_lastupdateUserId'=>15,
            'study_submodules_markedForDeletion'=>'n',
            'study_submodules_markedForDeletionDate'=>'0000-00-00 00:00:00');

      
        //Call the RestServer
        $insertStudySubmodule_Url = 'alexMartinez/api/studysubmodules/studysubmodule/';
        $insertResponse = $this->rest->put($insertStudySubmodule_Url,$studysubmodule);
        echo json_encode($insertResponse); 

    }


 }

 ?>