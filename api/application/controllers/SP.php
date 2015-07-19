<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('./application/libraries/tools/JwtUtil.php');
class SP extends CI_Controller {

	protected function post(){
		return  json_decode(file_get_contents("php://input"));
	}

	public function call(){

		$data=$this->post();
		
		$this->load->model('sp_model');  
  		$res = $this->sp_model->call_sp($data->sp_name, $data->sp_params);

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