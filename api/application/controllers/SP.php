<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('./application/libraries/tools/JwtUtil.php');

class SP extends CI_Controller {

	protected function post(){
		return  json_decode(file_get_contents("php://input"));
	}
	private $userId;
	private function authorize(){
		
		$arr=getallheaders();
		if(isset($arr['Authorization'])){
			//Bearer
			$token=str_replace("Bearer ","",$arr['Authorization']);
			$token=JWT::decode($token, $this->config->item('jwt_key'));
			if($token==false){
				http_response_code(401);
			}else{
				$this->userId=$token->id;

				return true;
			}
			
		}
		else{
			
			http_response_code(401);
		}
		return false;
	}
	private function hasPermission($procName){
		$procName=strtolower($procName);
		$widgetName='';
		$flag='';
		if(JwtUtil::startsWith($procName, 'create_')){
			$widgetName=str_replace("create_","",$procName);
			$flag='create';
		}
		else if(JwtUtil::startsWith($procName, 'update_')){
			$widgetName=str_replace("update_","",$procName);
			$flag='update';
		}
		else if(JwtUtil::startsWith($procName, 'remove_') || JwtUtil::startsWith($procName, 'delete_')){
			$widgetName=str_replace("remove_","",$procName);
			$flag='remove';
		}
		if(JwtUtil::IsNullOrEmptyString($widgetName)){
			return true;
		}
		
		$arr=$this->sp_model->call_sp('get_user_permission', [$this->userId]);
		
		if(count($arr)>0){
			foreach ($arr as  $row) {				
				if(strtolower($row->widgetName)==$widgetName){

					 switch ($flag) {
					 	case 'create':
					 		if($row->create=='1') return true;
					 		http_response_code(401);
							return false;
					 		break;
					 	case 'update':					 		
					 		if($row->update=='1') return true;
					 		http_response_code(401);
							return false;
					 		break;
					 	case 'remove':					 		
					 		if($row->delete=='1') return true;
					 		http_response_code(401);
							return false;
					 		break;
					 	default:
					 		http_response_code(401);
							return false;
					 	break;
					 }
				}
			}
		}
		http_response_code(401);
		return false;
	}
	public function call(){
		if(!$this->authorize()){return;}
		$res=new stdClass();
		$res->success=TRUE;
		try{

			$data=$this->post();
			
			$this->load->model('sp_model'); 
			if($this->hasPermission($data->sp_name)){
	  			$res->data = $this->sp_model->call_sp($data->sp_name, $data->sp_params);	
	  		}	
		
		}catch(Exception $ex){
			$res->success=FALSE;
			$res->msg=$ex->getMessage();
		}
		$this->load->view('json', array('output' => $res));
	}
	public function get_all(){
		if(!$this->authorize()){return;}
		$res=new stdClass();
		$res->success=TRUE;
		try{
			$data=$this->post();
			
			$this->load->model('sp_model'); 
			 
	  		$res->data = $this->sp_model->get_all($data->tableName, $data->fieldName, $data->order);		
		
		}catch(Exception $ex){
			$res->success=FALSE;
			$res->msg=$ex->getMessage();
		}

		$this->load->view('json', array('output' => $res));
	}
	public function find(){
		if(!$this->authorize()){return;}
		$res=new stdClass();
		$res->success=TRUE;
		try{
			$data=$this->post();
			
			$this->load->model('sp_model'); 
			 
	  		$res->data = $this->sp_model->find($data->tableName, $data->id, $data->pk);		
		
		}catch(Exception $ex){
			$res->success=FALSE;
			$res->msg=$ex->getMessage();
		}
		
		$this->load->view('json', array('output' => $res));
	}
	public function remove(){
		if(!$this->authorize()){return;}
		$res=new stdClass();
		$res->success=TRUE;
		try{
			$data=$this->post();
			
			 $this->load->model('sp_model'); 
			 if($this->hasPermission('remove_'.$data->tableName)){
	  			$res->data = $this->sp_model->remove($data->tableName, $data->id, $data->pk);
	  		}		
		
		}catch(Exception $ex){
			$res->success=FALSE;
			$res->msg=$ex->getMessage();
		}
		
		$this->load->view('json', array('output' => $res));
	}
	public function where(){
		//if(!$this->authorize()){return;}
		$res=new stdClass();
		$res->success=TRUE;
		try{
			$data=$this->post();
			
			$this->load->model('sp_model'); 
			 
	  		$res->data = $this->sp_model->where($data->tableName, $data->where, $data->fieldName, $data->order);		
		
		}catch(Exception $ex){
			$res->success=FALSE;
			$res->msg=$ex->getMessage();
		}
		
		$this->load->view('json', array('output' => $res));
	}
	public function create(){
		if(!$this->authorize()){return;}
		$res=new stdClass();
		$res->success=TRUE;
		try{
			$data=$this->post();
			
			$this->load->model('sp_model'); 
			if($this->hasPermission('create_'.$data->tableName)){
	  			$res->data = $this->sp_model->create($data->tableName, $data);	
	  		}	
		
		}catch(Exception $ex){
			$res->success=FALSE;
			$res->msg=$ex->getMessage();
		}
		
		$this->load->view('json', array('output' => $res));
	}
	public function update(){
		if(!$this->authorize()){return;}
		$res=new stdClass();
		$res->success=TRUE;
		try{
			$data=$this->post();
			
			$this->load->model('sp_model'); 
			 $tableName=$data->tableName;
			 $id=$data->id;
			 $pk=$data->pk;
			 unset($data->pk);
			 unset($data->tableName);
			 unset($data->id);
			 if($this->hasPermission('update_'. $tableName)){
	  		 	$res->data = $this->sp_model->update($tableName, $id, $data, $pk);	
	  		 }	
		
		}catch(Exception $ex){
			$res->success=FALSE;
			$res->msg=$ex->getMessage();
		}
		
		$this->load->view('json', array('output' => $res));
	}
	public function call_out(){
		if(!$this->authorize()){return;}
		$data=$this->post();
		
		$this->load->model('sp_model');  
  		$res = $this->sp_model->call_sp_out($data->sp_name, $data->sp_params, $data->sp_out_params);

		$this->load->view('json', array('output' => $res));
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */