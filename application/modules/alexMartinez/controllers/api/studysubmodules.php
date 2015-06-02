<?php defined('BASEPATH') OR exit('No direct script access allowed') ;

/**
 * UFs: study_submodules API
 *
 * @package     Ebre-escool
 * @subpackage  API
 * @category    Controller
 * @author         Alex Martinez Capilla
 * @link        http://acacha.org/meidawiki/index.php/ebre-escool
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
 //Carrega la llibreria
require APPPATH.'/libraries/REST_Controller.php';

class studysubmodules extends REST_Controller
{

    //Carrega el constructor pare
    function __construct()
    {
        // Construct our parent class
        parent::__construct();
        
        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
        //limitar el numero de peticiones que hace el cliente por seguridad
        $this->methods['study_submodules_get']['limit'] = 500; //500 requests per hour per user/key
        $this->methods['study_submodules_post']['limit'] = 100; //100 requests per hour per user/key
        $this->methods['study_submodules_delete']['limit'] = 50; //50 requests per hour per user/key
        //Load model
        $this->load->model('studysubmodulesmodel');
    }
    
#############################################################################################

    public function index_get(){  
        $this->studysubmodules_get();
        
    }


#############################################################################################

    //get just one study_submodules with id
    function studysubmodule_get()
    {
        //obtenir l'identificado que se li passa en la url
        if(!$this->get('id'))
        {
            //Si no hi ha identificador s'envia el codi de resposta
            $message = array('id'=>'','message'=>'YOU MUST SEND AN ID');
            $this->response(NULL, 400);
        }

        $study_submodule = $this->studysubmodulesmodel->getstudysubmodule( $this->get('id') );      
        /*$persons = array(
            1 => array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
            2 => array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com', 'fact' => 'Has a huge face'),
            3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => 'Is a Scott!', array('hobbies' => array('fartings', 'bikes'))),
        );*/
        //$person = @$persons[$this->get('id')]; 

        //If exists $study_submodule everything it's ok
        if($study_submodule)
        {
            $this->response($study_submodule, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('id' => $this->get('id'),'message' => 'STUDY SUBMODULE NOT EXISTS!'), 404);        
        }
    }
#############################################################################################
    //Create new Study submodule
    function studysubmodule_post()
    {
        /*if(isset($_POST)){
        $message = array('id' => $this->input->get_post('id'), 'date' => $this->input->get_post('studysubmodule_entryDate'));
        $this->response($message, 200);
        }*/
        if (isset($_POST)){
            //GET DATA FROM POST
            $postdata = file_get_contents("php://input");
            //log_message('debug',$postdata);
            $studySubmodulesObject = json_decode($postdata);

             $data = array(
            'study_submodules_shortname'=>$studySubmodulesObject->{'shortname'},
            'study_submodules_name'=>$studySubmodulesObject->{'name'},
            'study_submodules_study_module_id'=>$studySubmodulesObject->{'study_module_id'},
            'study_submodules_courseid'=>$studySubmodulesObject->{'course_id'},
            'study_submodules_order'=>$studySubmodulesObject->{'order'},
            'study_submodules_description'=>$studySubmodulesObject->{'description'},
            'study_submodules_creationUserid'=>$studySubmodulesObject->{'creator_user_id'},
            'study_submodules_entryDate'=>$studySubmodulesObject->{'entry_date'},
            'study_submodules_lastupdateUserId'=>$studySubmodulesObject->{'last_update_user_id'},
            'study_submodules_markedForDeletion'=>$studySubmodulesObject->{'marked_for_deletion'},
            'study_submodules_markedForDeletionDate'=>$studySubmodulesObject->{'marked_for_deletion_date'});
                   
             //CALL TO MODEL
        $response = $this->studysubmodules->insertStudy($data);
        }
        //$message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
       
        if($response['response']){
          $message = array('id' => $response['id'], 'message' => 'NEW STUDY SUBMODULE INSERTED!');
          $this->response($message, 200); // 200 being the HTTP response code
        }else{
            $message = array('id' =>$response['id'], 'message' => 'ERROR INSERTING!');
            $this->response($message, 404); // 404 being the HTTP response code(Not Found)
        }
    }
    
############################################################################################
    //Delete study submodule using the id
    function studysubmodule_delete()
    {
        //test if user sends the id
        if(!$this->get('id'))
        {
            //Si no hay identificador se manda el código de respuesta
            $message = array('id'=>'','message'=>'YOU MUST SEND AN ID');
            $this->response($message, 400);
        }
        log_message('debug',"delete id: ".$this->get('id'));

        $response = $this->studysubmodulesmodel->deletestudysubmodule( $this->get('id') );
        
        if($response)
        {
            $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
            $this->response($message, 200); // 200 being the HTTP response code
        }
        else
        {
            $message = array('id' => $this->get('id'), 'message' => 'ERROR DELETING!');

            $this->response($message, 404); // 404 being the HTTP response code
        } 
    }

############################################################################################
    function studysubmodules_get()
    {
        //$persons = $this->some_model->getSomething( $this->get('limit') );
        /*$persons = array(
            array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com'),
            array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com'),
            3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => array('hobbies' => array('fartings', 'bikes'))),
        );*/
        //Get all studysubmodules from studysubmoduleS table
        $studysubmodules = $this->studysubmodulesmodel->getstudysubmodules();

        if($studysubmodules)
        {
            $this->response($studysubmodules, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('id' => $this->get('id'),'message' => 'Couldn\'t find any study submodules!'), 404);
        }
    }

############################################################################################

    //UPDATE studysubmodules
    function studysubmodule_put(){
        //GET THE ID
         $id = $this->put('id');
        //Get the array we send from RestClient
        $data = array(
            'study_submodules_shortname'=>$this->put('shortname'),
            'study_submodules_name'=>$this->put('name'),
            'study_submodules_study_module_id'=>$this->put('study_module_id'),
            'study_submodules_courseid'=>$this->put('course_id'),
            'study_submodules_order'=>$this->put('order'),
            'study_submodules_description'=>$this->put('description'),
            'study_submodules_creationUserid'=>$this->put('creator_user_id'),
            'study_submodules_entryDate'=>$this->put('entry_date'),
            'study_submodules_lastupdateUserId'=>$this->put('last_update_user_id'),
            'study_submodules_markedForDeletion'=>$this->put('marked_for_deletion'),
            'study_submodules_markedForDeletionDate'=>$this->put('marked_for_deletion_date'));  
            
         //Call inset method in model
         $updateResponse = $this->studysubmodules->updateStudySubmodule($id,$data);
         //echo $insertResponse['response']." ".$insertResponse['id'];
         
         //Response
         if($updateResponse){
            $message = array('id' => $id,'message' => 'UPDATED!');
            $this->response($message,200);//200 being the HTTP response code

         }else{
            $message = array('id' => $id, 'message' => 'ERROR UPDATING!');
            $this->response($message, 422); // 422 being the HTTP response code
         }

        
    }

############################################################################################  

    //mark for deletion update
    function markForDeletion_put(){
        
            //Today
            $today = date('Y-m-d H:i:s');
            $id = $this->put('id');
             $data = array(
            'study_submodules_markedForDeletion'=>$this->put('marked_for_deletion'),
            'study_submodules_markedForDeletionDate'=>$today);
           
             //CALL TO MODEL
             $response = $this->studysubmodules->updateStudySubmodule($id,$data);
        
    
       
        if($response){
          $message = array('id' => $id, 'message' => 'UPDATED!');
          $this->response($message, 200); // 200 being the HTTP response code
        }else{
            $message = array('id' =>$id, 'message' => 'ERROR UPDATING!');
            $this->response($message, 404); // 404 being the HTTP response code(Not Found)
        }
    
    }

############################################################################################  

    public function send_post()
    {
        var_dump($this->request->body);
    }

############################################################################################
    
    public function send_put()
    {
        var_dump($this->put('foo'));
    }
}
