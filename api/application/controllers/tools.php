<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('./application/libraries/tools/AppManager.php');

class Tools extends CI_Controller {
    private $app=null;
	function __construct() {
		parent::__construct();		
	    $this->app=new AppManager("../", $this->get_config_item('default_navigation')) ;      
        $this->app->has_template_authorization=$this->get_config_item('has_template_authorization');

	}
	private $userId;
	private function authorize(){
		if(!$this->get_config_item('has_template_authorization')){return true;}
		$arr=getallheaders();
		if(isset($arr['Authorization'])){			
			$token=str_replace("Bearer ","",$arr['Authorization']);
			$token=JWT::decode($token, $this->config->item('jwt_key'));
			if($token==false){
				return false;
			}else{
				$this->userId=$token->id;

				return true;
			}
			
		}		
		return false;
	}
	private function isIgnoredWidget($arr, $val){
		if(JwtUtil::endsWith($val,'__LAYOUT__')){
            $val=str_replace("__LAYOUT__","",$val);
        }
		foreach ($arr as  $value) {
			if($value==$val) return true;			 
		}
		return false;
	}
	public function tpl($name){
		$ignoreList=$this->get_config_item('widget_ignore_list');
		if($this->isIgnoredWidget($ignoreList, $name)){
			echo $this->app->getTemplate($name);
			return;
		}
		$output='<div class="alert alert-info not-auth">' . $this->get_config_item('template_authorize_message') . '</div>';
		
		if($this->authorize()){
			$this->load->model('sp_model'); 			 
		  	$data = $this->sp_model->call_sp('get_widget_permission', [$this->userId, $name]);
		  	
		  	if(count($data)>0)	{	
				$output=$this->app->getTemplate($name);
			}
		}
		
		echo $output;
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
	public function GetViewList(){
		$data=$this->post();		
		$res=$this->app->getViews($data->layoutName, $data->navName);
		$this->load->view('json', array('output' => $res));
		
	}

	/*EDITOT PART*/
	public function GetItems(){

		$res=$this->app->GetItems($this->input->get('name'));
		$this->load->view('json', array('output' => $res));
	}
	public function GetItemDetail(){
		
		$res=$this->app->GetItemDetail($this->input->get('name'), $this->input->get('mode'));
		$this->load->view('json', array('output' => $res));
	}
	public function GetFileContent(){
		$res=$this->app->GetFileContent($this->input->get('mode'), $this->input->get('directoryName'), $this->input->get('fileName'));
		$this->load->view('json', array('output' => $res));
		
	}

	public function SaveFile(){
		$data=$this->post();
		$res=$this->app->SaveFile($data->mode, $data->directoryName, $data->fileName, $data->content);
		$this->load->view('json', array('output' => $res));
		
	}
	public function IsFileExist(){		
		$res=$this->app->IsFileExist($this->input->get('mode'), $this->input->get('directoryName'), $this->input->get('fileName'), $this->input->get('ext'));
		$this->load->view('json', array('output' => $res));
		
	}
	public function AddFile(){
		$data=$this->post();
		$res=$this->app->AddFile($this->input->get('mode'), $this->input->get('directoryName'), $this->input->get('fileName'), $this->input->get('ext'));
		$this->load->view('json', array('output' => $res));
		
	}
	public function RemoveFile(){
		$data=$this->post();
		$res=$this->app->RemoveFile($this->input->get('mode'), $this->input->get('directoryName'), $this->input->get('fileName'), $this->input->get('ext'));
		$this->load->view('json', array('output' => $res));
		
	}
	public function IsExist(){
		$res=$this->app->IsExist($this->input->get('name'), $this->input->get('mode'));
		$this->load->view('json', array('output' => $res));
		
	}
	public function CreateItem(){
		$res= $this->app->CreateItem($this->input->get('name'), $this->input->get('mode'));
		//$this->load->view('json', array('output' => 'created'));
		print $res;
	}
	public function GetAllWidgets(){

		$res= $this->app->GetAllWidgets();
		$this->load->view('json', array('output' => $res));

	}
}

/* End of file role.php */
/* Location: ./application/controllers/tools.php */