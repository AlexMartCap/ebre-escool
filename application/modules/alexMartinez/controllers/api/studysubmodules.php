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
require APPPATH.'/libraries/REST_Controller.php';

class studysubmodules extends REST_Controller
{

    public $LOGTAG = "EBRE_ESCOOL API: ";

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

############################################################################################

    public function index_get(){
        
        $this->studysubmodules_get();
        
    }

    function login_post()
    {

        log_message('debug', $this->LOGTAG . "login_post called");

        $result = new stdClass();
        $result->message = "LOGIN POST";

        $username = $this->post('username');
        $password = $this->post('password');
        $realm = $this->post('realm');

        log_message('debug', $this->LOGTAG . "Username: " . $username);
        log_message('debug', $this->LOGTAG . "Realm: " . $realm);

        if(false)
        {
            $this->response(NULL, 400);
        }

        $result->username= $username;
        $result->password= $password;
        $result->realm= $realm;

        //VALIDATION
        if ($username == "" || !$result->username) {
            log_message('debug', $this->LOGTAG . "Incorrect username value");
            $result->message = "Incorrect username value";
            $this->response($result, 400);
        }

        if ($password == "" || !$result->password) {
            log_message('debug', $this->LOGTAG . "Incorrect username value");
            $result->message = "Incorrect username value!";
            $this->response($result, 400);
        }

        if ($realm == "" || !$result->realm || !$this->validate_realm($realm) ) {
            log_message('debug', $this->LOGTAG . "No valid realm specified");
            $result->message = "No valid realm specified!";
            $this->response($result, 400);
        }

        //Check if username exists
        // TODO

        $this->skeleton_auth->skeleton_auth_model->setRealm($realm);
        //$remember = (bool) $this->input->post('remember');

        if ($this->skeleton_auth->login($username, $password, false))
        {
            //login is successful
            log_message('debug', $this->LOGTAG . "Login successful");
            $result->message = "Login successful!";

            $sessiondata = $this->ebre_escool_auth_model->getSessionData($username); 
            $result->sessiondata = $sessiondata;

            $api_user_profile = new stdClass();
            $api_user_profile->username = $username;
            $api_user_profile->prova = "TEST";
            $api_user_profile->another = "TEST 1";
            $result->api_user_profile = $api_user_profile;
            $this->response($result, 200);   
        }
        else
        {
            //if the login was un-successful
            log_message('debug', $this->LOGTAG . "Login not successful");
            $result->message = "Login not successful!";
            $this->response($result, 400);   
        }

        if (false) {
            log_message('debug', $this->LOGTAG . " username: " . $username . " does not exists!");
            $result->message = "Username does not exists!";
            $this->response($result, 404);
        }

        log_message('debug', $this->LOGTAG . " username: " . $username . " logged ok!");
        $this->response($result, 200); // 200 being the HTTP response code
    }
    
    function validate_realm($realm){

        if ( (strcasecmp ( $realm , "ldap" ) == 0) || (strcasecmp ( $realm , "mysql" ) == 0) ) {
            return true;
        }
        return false;
    }

############################################################################################

    //get just one study_submodules with id
    function studysubmodule_get()
    {
        if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }

        $study_submodule = $this->studysubmodulesmodel->getstudysubmodule( $this->get('id') );
        //$study_submodules = $this->studysubmodulesmodel->getPersonAlt( $this->get('id') );
        
        /*$persons = array(
            1 => array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
            2 => array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com', 'fact' => 'Has a huge face'),
            3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => 'Is a Scott!', array('hobbies' => array('fartings', 'bikes'))),
        );
        
        $person = @$persons[$this->get('id')];
        */
        if($study_submodule)
        {
            $this->response($study_submodule, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'study submodule could not be found'), 404);
        }
    }
    
#############################################################################################

    function studysubmodules_get()
    {
        //$persons = $this->some_model->getSomething( $this->get('limit') );
        /*$persons = array(
            array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com'),
            array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com'),
            3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => array('hobbies' => array('fartings', 'bikes'))),
        );*/
        $studysubmodules = $this->studysubmodulesmodel->getstudysubmodules();

        if($studysubmodules)
        {
            $this->response($studysubmodules, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Couldn\'t find any study submodules!'), 404);
        }
    }

############################################################################################

    function studysubmodule_post()
    {
        //$this->some_model->updateperson( $this->get('id') );
        //$message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        
        //$this->response($message, 200); // 200 being the HTTP response code
        
        if (isset($_POST))
        {
            $id = $this->input->get_post('id');
            $data = array('study_submodules_name'=>$this->input->get_post('study_submodules_name'));
            $response = $this->studysubmodulesmodel->updatestudysubmodules($id,$data);
        }
        
        if($response)
        {
            $message = array('id' => $id, 'message' => 'UPDATED!');
            $this->response($message, 200); // 200 being the HTTP response code
        }
        else
        {
            $message = array('id' =>$id, 'message' => 'ERROR UPDATING!');
            $this->response($message, 422); // 422 being the HTTP response code
        }
    }
    
############################################################################################

    function studysubmodule_delete()
    {
        //$this->some_model->deletesomething( $this->get('id') );
        //$message = array('id' => $this->get('id'), 'message' => 'DELETED!');
        
        //$this->response($message, 200); // 200 being the HTTP response code
        
        if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }

        $response = $this->studysubmodulesmodel->deletestudysubmodule( $this->get('id') );
        if($response)
        {
            $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
            $this->response($message, 200); // 200 being the HTTP response code
        }
        else
        {
            $message = array('id' => $this->get('id'), 'message' => 'ERROR DELETING!');

            $this->response($message, 422); // 422 being the HTTP response code
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
