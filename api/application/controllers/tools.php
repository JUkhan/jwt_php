<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('./application/libraries/tools/AppManager.php');
class Tools extends CI_Controller {
    private $app=null;
	function __construct() {
		parent::__construct();		
	    $this->app=new AppManager("../") ;      
        $this->app->has_template_authorization=$this->get_config_item('has_template_authorization');
	}
	public function index()
	{
		if($this->get_config_item('app_builder')==TRUE){

			$this->load->view('jwt');
		}
		else
		{
			$this->load->view('forbidden');
		}
		
	}
	public function jwtex()
	{
		if($this->get_config_item('app_builder')==TRUE){

			$this->load->view('jwtex');
		}
		else
		{
			$this->load->view('forbidden');
		}
		
	}
	protected function post(){
		return  json_decode(file_get_contents("php://input"));
	}
    protected function get_config_item($key){
        return $this->config->item($key);
    }
	public function AddLayout(){
		
		$data=$this->post();	
		$data->_id=$this->app->addLayout($data); 
		 
		$this->load->view('json', array('output' => $data));		
		
	}
	public function UpdateLayout(){		
		$data=$this->app->updateLayout($this->post());       
		$this->load->view('json', array('output' => $data));
		
	}
	public function GetLayoutList(){

       $data=$this->app->getLayoutList();       
       $this->load->view('json', array('output' => $data));
	}
	public function RemoveLayout(){
		$this->app->removeLayout($this->post()); 
 		$this->load->view('json', array('output' => ""));
	}
	public function AddNavigation(){
		$data=$this->post();	
		$data->_id=$this->app->addNavigation($data); 
		 
		//$this->load->view('json', array('output' => $data));
		print json_encode($data);
	}
	public function UpdateNavigation(){
		$data=$this->post();	
		$data->_id=$this->app->updateNavigation($data); 
		 
		//$this->load->view('json', array('output' => $data));
		print json_encode($data);
	}
	public function RemoveNavigation(){
		$this->app->removeNavigation($this->post()); 
 		$this->load->view('json', array('output' => ""));
	}
	public function GetNavigationList(){

       $data=$this->app->getNavigationList();       
       $this->load->view('json', array('output' => $data));
	}
	public function getWidgetList(){
		$data=$this->app->getWidgetList();       
       $this->load->view('json', array('output' => $data));
	}
}

/* End of file role.php */
/* Location: ./application/controllers/tools.php */