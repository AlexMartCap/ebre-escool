<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Classroomgroup extends REST_Controller
{
	function __construct()
    {
        // Construct our parent class
        parent::__construct();
        
        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php

        $this->methods['classroom_group_get']['limit'] = 500; //500 requests per hour per classroom_group/key
        $this->methods['classroom_group_post']['limit'] = 100; //100 requests per hour per classroom_group/key
        $this->methods['classroom_group_delete']['limit'] = 50; //50 requests per hour per classroom_group/key
        $this->load->model('api');
    }
    
    function classroomgroup_get()
    {
       if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }
        $this->load->model('api');

        $classroom_group = $this->api->classroom_group_get( $this->get('id') );

        
        //$classroom_group = @$classroom_groups[$this->get('id')];
        
        if($classroom_group)
        {
            $this->response($classroom_group, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'classroom_group could not be found'), 404);
        }
    }
    
    function classroomgroup_post()
    {
        if (isset($_POST)){
            //GET DATA FROM POST
            $id = $this->input->get_post('id');
            $data = array(
            'classroom_group_code'=>$this->input->get_post('classroom_group_code'));
            //CALL TO MODEL
            $response = $this->api->classroom_groups_update($id,$data);
        }

        if($response){
            $message = array('id' => $id, 'message' => 'UPDATED!');
            $this->response($message, 200); // 200 being the HTTP response code
        }else{
            $message = array('id' =>$id, 'message' => 'ERROR UPDATING!');
            $this->response($message, 404); // 404 being the HTTP response code(Not Found)
        }
    }
    
    function classroom_group_put()
    {
        $data = array(
        'classroom_group_id'=>$this->put('classroom_group_id'),
        'classroom_group_code'=>$this->put('classroom_group_code'),
        'classroom_group_shortName'=>$this->put('classroom_group_shortName'),
        'classroom_group_name'=>$this->put('classroom_group_name'),
        'classroom_group_description'=>$this->put('classroom_group_description'),
        'classroom_group_course_id'=>$this->put('classroom_group_course_id'),
        'classroom_group_type'=>$this->put('classroom_group_type'),
        'classroom_group_entryDate'=>$this->put('classroom_group_entryDate'));

        $insertResponse = $this->api->classroom_groups_insert($data);
        //echo $insertResponse['response']." ".$insertResponse['id'];

        if($insertResponse['response']){
            $message = array('id' => $insertResponse['id'],'message' => 'NEW CLASSROOM GROUP INSERTED');
            $this->response($message,200);//200 being the HTTP response code
        }else{
            $message = array('id' => $insertResponse['id'], 'message' => 'ERROR INSERTING');
            $this->response($message, 422); // 422 being the HTTP response code
        }
    }

    function classroomgroup_delete()
    {
        if(!$this->get('id')){
            $message = array('id'=>'','message'=>'YOU MUST SEND AN ID');
            $this->response($message, 400);
        }
        $response = $this->api->classroom_groups_delete( $this->get('id') );

        if($response){
            $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
            $this->response($message, 200); // 200 being the HTTP response code
        }else{
            $message = array('id' => $this->get('id'), 'message' => 'ERROR DELETING!');
            $this->response($message, 404); // 400 being the HTTP response code(Not Found)
        }
    }
    
    function classroomgroups_get()
    {
        $this->load->model('api');

        $classroom_group = $this->api->classroom_groups_get();
        
        if($classroom_group)
        {
            $this->response($classroom_group, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'classroom_group could not be found'), 404);
        }
    }


	public function send_post()
	{
		var_dump($this->request->body);
	}


	public function send_put()
	{
		var_dump($this->put('foo'));
	}
}
