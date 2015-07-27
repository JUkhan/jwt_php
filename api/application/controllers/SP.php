<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('./application/libraries/tools/JwtUtil.php');

class SP extends CI_Controller {

	protected function post(){
		return  json_decode(file_get_contents("php://input"));
	}

	public function call(){
		$res=new stdClass();
		$res->success=TRUE;
		try{

			$data=$this->post();
			
			$this->load->model('sp_model'); 
			 
	  		$res->data = $this->sp_model->call_sp($data->sp_name, $data->sp_params);		
		
		}catch(Exception $ex){
			$res->success=FALSE;
			$res->msg=$ex->getMessage();
		}
		$this->load->view('json', array('output' => $res));
	}
	public function get_all(){
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
		$res=new stdClass();
		$res->success=TRUE;
		try{
			$data=$this->post();
			
			$this->load->model('sp_model'); 
			 
	  		$res->data = $this->sp_model->remove($data->tableName, $data->id, $data->pk);		
		
		}catch(Exception $ex){
			$res->success=FALSE;
			$res->msg=$ex->getMessage();
		}
		
		$this->load->view('json', array('output' => $res));
	}
	public function where(){
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
		$res=new stdClass();
		$res->success=TRUE;
		try{
			$data=$this->post();
			
			$this->load->model('sp_model'); 
			 
	  		$res->data = $this->sp_model->create($data->tableName, $data);		
		
		}catch(Exception $ex){
			$res->success=FALSE;
			$res->msg=$ex->getMessage();
		}
		
		$this->load->view('json', array('output' => $res));
	}
	public function update(){
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
			 
	  		$res->data = $this->sp_model->update($tableName, $id, $data, $pk);		
		
		}catch(Exception $ex){
			$res->success=FALSE;
			$res->msg=$ex->getMessage();
		}
		
		$this->load->view('json', array('output' => $res));
	}
	public function call_out(){

		$data=$this->post();
		
		$this->load->model('sp_model');  
  		$res = $this->sp_model->call_sp_out($data->sp_name, $data->sp_params, $data->sp_out_params);

		$this->load->view('json', array('output' => $res));
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */