<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('./application/libraries/tools/JwtUtil.php');

class Account extends CI_Controller {

	protected function post(){
		return  json_decode(file_get_contents("php://input"));
	}
	public function register(){
		$res=new stdClass();
		$res->success=FALSE;

		

		$data=$this->post();			
		$this->load->model('sp_model'); 

		unset($data->confirmPassword);

		$data->joinDate= date("Y-m-d");
		$data->password=Password::create_hash($data->password);

 		$res->id= $this->sp_model->create('jwt_user', $data);
    	JwtUtil::log(json_encode($res));
		$this->load->view('json', array('output' => $res));
	}
	public function login(){
		$res=new stdClass();
		$res->success=FALSE;

		$data=new stdClass();
		parse_str(file_get_contents("php://input"), $data);		
		$data = (object)$data;

		$this->load->model('sp_model');		

		$where='userName="'.$data->username .'"';		
		
 		$arr= $this->sp_model->where('jwt_user', $where, 'id', 'asc');
 		if(count($arr)==1){
 			if (Password::validate_password($data->password, $arr[0]->password)){
 				$res->success=true;

 				$token=array();
 				$token['id']=$arr[0]->id;
 				$res->access_token=JWT::encode($token, $this->config->item('jwt_key'));
 				
 			}else{
 				$res->error='Invalid user name or password.';
 				http_response_code(401);
 			}
 		}else{
 			$res->error='Invalid user name or password.';
 			http_response_code(401);
 		}
    	
		$this->load->view('json', array('output' => $res));
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */