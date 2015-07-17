<?php

/**
 * AppManager short summary.
 *
 * AppManager description.
 *
 * @version 1.0
 * @author Jasim.Uddin
 */
require_once('JwtApp.php');
require_once('JwtUtil.php');
require_once('CodeGen.php');
class AppManager
{
    private $defaultNavigation = "";
    private $jwtApp;
    public $rootPath="";
    public $has_template_authorization="";
    public function __construct($rootPath="", $defaultNavigation = ""){
        $this->rootPath=  $rootPath;
        $this->defaultNavigation=$defaultNavigation;
    }
    private function serialize()
    {
        JwtUtil::putContent('jwt.json', json_encode($this->jwtApp));
    }
    private function deserialize()
    {        
        $obj=json_decode(JwtUtil::getContent('jwt.json'), TRUE);
        $this->jwtApp=new JwtApp();
        if(isset($obj['Name'])){
            $this->jwtApp->Name=$obj['Name'];
        }
        $this->jwtApp->Layouts=$obj['Layouts'];
        $this->jwtApp->Navigations=$obj['Navigations'];
    }
    
    public function addLayout($layout){
        try{
            $this->deserialize();
            if (!$this->layoutExist($layout))
            {
                $layout->_id=JwtUtil::GUID();
                $this->jwtApp->Layouts[] = $layout;
                $this->serialize();
                $this->generateConfig();
            }else{
                return "$layout->LayoutName already exist";
            }
            return $layout->_id;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    private function layoutExist($layout){
        foreach($this->jwtApp->Layouts as $item){
            if($item["LayoutName"]==$layout->LayoutName){
                return TRUE;
            }
        }
        return FALSE;
    }
    private function navigationExist($layout){
        foreach($this->jwtApp->Navigations as $item){
            if($item["NavigationName"]==$layout->NavigationName){
                return TRUE;
            }
        }
        return FALSE;
    }
    private function array_find($arr, $id, &$key){
        foreach($arr as $i=>$item){
            if($item['_id']==$id){
                $key=$i;
                return $item;
            }
        }
        return null;
    }
    public function updateLayout($layout)
    {           
        try
        { 
            $key=-1;
            $this->deserialize();
            $temp = $this->array_find($this->jwtApp->Layouts, $layout->_id, $key);           
            if ($temp== null)
            {
                return "$layout->LayoutName not exist.";
            }
            
            if (!JwtUtil::IsNullOrEmptyString($layout->LayoutName) && ($layout->LayoutName != $temp['LayoutName'])){
                JwtUtil::rename($this->rootPath . "Scripts/Layouts/", $layout->LayoutName, $temp['LayoutName']);
                
            }
            $this->updateRelatedLayout($temp['LayoutName'], $layout->LayoutName);

            $temp['LayoutName'] = $layout->LayoutName;
            $temp['Extend'] = $layout->Extend;
            
            $this->jwtApp->Layouts[$key]=$temp;
            JwtUtil::log( $key.json_encode($this->jwtApp->Layouts));
            $this->serialize();
            $this->generateConfig();
            return "Successfully Updted.";
            
        }
        catch (Exception $ex)
        {
            
            return $ex->getMessage();
        }
    }

    public function updateRelatedLayout($oldName, $newName)
    {
        
        if ($oldName == $newName) return;
        foreach ($this->jwtApp->Layouts as $i=>&$item)
        {
            if ($item['Extend'] == $oldName){
                $item['Extend'] = $newName;
                $this->jwtApp->Layouts[$i]=$item;                
            }
        }
        
        foreach ($this->jwtApp->Navigations as $i=>&$item)
        {
            if ($item['HasLayout'] == $oldName){
                $item['HasLayout'] = $newName;
                $this->jwtApp->Navigations[$i]=$item;
            }
        }
        
    }
    public function removeLayout($layout){
        $this->deserialize();
        $key=-1;
        $temp = $this->array_find($this->jwtApp->Layouts, $layout->_id, $key);           
        if ($temp== null)
        {
            return "$layout->LayoutName not exist.";
        }
        
        unset($this->jwtApp->Layouts[$key]);
        //$this->updateParentLayout($temp['LayoutName'], NULL);
        $this->serialize();
        $this->generateConfig();
        return "Successfully Removed.";
    }
    public function getLayoutList(){
        $this->deserialize();
        $arr=array();
        foreach( $this->jwtApp->Layouts as $item){
            $arr[]=$item;
        }
        return $arr;
    }
    public function addNavigation($navigation){
        try{
            $this->deserialize();
            if (!$this->navigationExist($navigation))
            {
                $navigation->_id=JwtUtil::GUID();
                $this->jwtApp->Navigations[] = $navigation;
                $this->serialize();
                $this->generateConfig();
            }else{
                return "$navigation->NavigationName already exist";
            }
            return $navigation->_id;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function updateNavigation($navigation)
    {
        
        $this->deserialize();
        $key=-1;        
        $temp = $this->array_find($this->jwtApp->Navigations, $navigation->_id, $key);        
        if ($temp == null)
        {
            return "$navigation->NavigationName not exist.";
        }       
        if (!JwtUtil::IsNullOrEmptyString($navigation->WidgetName) && ($navigation->WidgetName != $temp['WidgetName'])){
            JwtUtil::rename($this->rootPath . "Scripts/Components/", $navigation->WidgetName, $temp['WidgetName']);            
        }
        
        $this->updateRelatedNavigation($temp['WidgetName'], $navigation->WidgetName);

        $temp['NavigationName'] = $navigation->NavigationName;
        $temp['WidgetName'] = $navigation->WidgetName;
        $temp['ParamName'] = $navigation->ParamName;
        $temp['UIViews'] = $navigation->UIViews;
        $temp['HasLayout'] = $navigation->HasLayout;
        
        $this->jwtApp->Navigations[$key]=$temp;
        
        $this->serialize();
        $this->generateConfig();
        return "Successfully Updted.";
        
    }
    private function updateRelatedNavigation($oldName, $newName)
    {
        if ($oldName == $newName) return;
        foreach ($this->jwtApp->Navigations as $i=>&$item)
        {            
            if (isset($item['Views']))
            {
                foreach ($item['Views'] as $v=>&$view)
                {
                    if ($view['WidgetName'] == $oldName){
                        $view['WidgetName'] = $newName;                        
                    }
                    $item['Views'][$v]=$view;
                }
                //$this->jwtApp->Navigations[$item["NavigationName"]]=$item;
            }
            $this->jwtApp->Navigations[$i]=$item;

        }
    }
    public function removeNavigation($navigation){
        $this->deserialize();
        $key=-1;        
        $temp = $this->array_find($this->jwtApp->Navigations, $navigation->_id, $key);
        if ($temp== null)
        {
            return "$navigation->NavigationName not exist.";
        }        
        unset($this->jwtApp->Navigations[$key]);       
        $this->serialize();
        $this->generateConfig();
        return "Successfully Removed.";
    }
    public function getNavigationList(){
        $this->deserialize();
        $arr=array();
        foreach( $this->jwtApp->Navigations as $item){
            $arr[]=$item;
        }
        return $arr;
    }
    public function getWidgetList(){
        $this->deserialize();
        $arr=array();
        foreach( $this->jwtApp->Navigations as $item){
            $arr[]=$item['WidgetName'];
        }
        return $arr;
    }
    public function generateConfig()
    {
        try
        {                
            $this->deserialize();
            $codeGen = new CodeGen();
            $codeGen->has_template_authorization=$this->has_template_authorization;
            $codeGen->app = $this->jwtApp;
            $codeGen->root=$this->rootPath;
            $codeGen->defaultNavigation = $this->defaultNavigation;
            $codeGen->execute();
            
            
        }
        catch (Exception $ex)
        {
            
            throw $ex;
        }

    }

}
