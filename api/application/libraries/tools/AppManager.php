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
require_once('View.php');
require_once('JResult.php');
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
        $temp['Views'] = $navigation->Views;
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
       return JwtUtil::getSubDirectories($this->rootPath . 'Scripts/Components');
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
    private function get_navigation_by_name($navName){
         $this->deserialize();
        
        foreach( $this->jwtApp->Navigations as $item){
           if($navName==$item['NavigationName']){
                return $item;
           }
        }
        return null;
    }
    private function get_view_by_name($arr, $viewName){
        foreach( $arr as $item){           
           if($viewName==$item->ViewName){
                return $item;
           }
        }
        return null;
    }
    public function getViews($layoutName, $navName){
        
        $regex="/ui-view=\"([a-zA-Z0-9_]+)\"/";
        $input_string=JwtUtil::getContent("$this->rootPath/Scripts/Layouts/$layoutName/$layoutName.html");

        $views=array();
        if(preg_match_all($regex, $input_string, $matches_out)){
            
            foreach ($matches_out[1] as $value) {
                $view=new View();
                $view->ViewName=$value;
                $views[]=$view;
                 
        }
        
        $nav=$this->get_navigation_by_name($navName);            
        if($nav!=null){
                foreach ($nav['Views'] as $item) {                     
                    $view=$this->get_view_by_name($views, $item['ViewName']);                  
                    if($view!=null){
                        $view->WidgetName=$item['WidgetName'];
                    }
                }
            }
        }
       
        return $views;
    }
    /* EDITOR PART */
     public function GetItems($name)
     {
            
           $list =null;
           switch ($name)
           {

                case "Layouts":                    
                    $list= JwtUtil::getSubDirectories($this->rootPath . "Scripts/Layouts");                   
                    array_unshift($list,"Select a layout");
                    break;
                case "Widgets":
                     
                    $list= JwtUtil::getSubDirectories($this->rootPath  . "Scripts/Components");                   
                    array_unshift($list,"Select a widgets");
                    break;
                case "Components":                    
                    $list=JwtUtil::getSubDirectories($this->rootPath  ."Scripts/Directives");
                    array_unshift($list,"Select a component");
                    break;
                case "Modules":                   
                    $list= JwtUtil::getSubDirectories($this->rootPath  . "Scripts/Modules");
                    array_unshift($list,"Select a module");
                    break;
           }
           return $list;
    }

    public function GetItemDetail($name, $mode)
    {
            $list = null;
            switch ($mode)
            {
                case "Base":
                    $list = JwtUtil::getFiles($this->rootPath  . "Scripts/Base");
                    break;
                case "Layouts":
                    $list = JwtUtil::getFiles($this->rootPath  .  "Scripts/Layouts/" . $name);
                    break;
                case "Widgets":
                    $list = JwtUtil::getFiles($this->rootPath  .  "Scripts/Components/" . $name);
                    break;
                case "Components":
                    $list = JwtUtil::getFiles($this->rootPath  .  "Scripts/Directives/" . $name);
                    break;
                case "Modules":
                    $list = JwtUtil::getFiles($this->rootPath  .  "Scripts/Modules/" . $name);
                    break;
            }
            
            $res=array('js'=>array(), 'css'=>array(), 'html'=>array());

            foreach ($list as $value) {
                if(JwtUtil::endsWith($value, ".js")){
                        $res['js'][]=$value;
                }
                else if(JwtUtil::endsWith($value, ".css")){
                     $res['css'][]=$value;
                }
                else if(JwtUtil::endsWith($value, ".html")){
                     $res['html'][]=$value;
                }
            }
            return $res;
    }

     public function GetFileContent($mode, $directoryName, $fileName)
     {
            $path = $this->rootPath;            
            $res = new JResult();
           
            switch ($mode)
            {
                case "Base":
                    $directoryName = "base";
                    $path .= "Scripts/Base/" . $fileName;
                    $res->data = JwtUtil::getContent($path);
                break;

                case "Layouts":
                    $path .= "Scripts/Layouts/$directoryName/$fileName";
                    $res->data = JwtUtil::getContent($path);
                    break;
                case "Components":
                    $path .= "Scripts/Directives/$directoryName/$fileName";
                   $res->data = JwtUtil::getContent($path);
                    break;
                case "Widgets":
                    $path .= "Scripts/Components/$directoryName/$fileName";
                   $res->data = JwtUtil::getContent($path);
                    break;
                case "Modules":
                    $path .= "Scripts/Modules/$directoryName/$fileName";
                    $res->data = JwtUtil::getContent($path);
                    break;
            }
            $res->isSuccess = true;
            $res->locked = FALSE;
                  
            return $res;
    }
     public function SaveFile($mode, $directoryName, $fileName, $content)
        {

            $path = $this->rootPath; 
           
            $res = new JResult();
           
            switch ($mode)
            {

                case "Base":
                    $path .= "Scripts/Base/" . $fileName;
                    JwtUtil::putContent($path, $content);
                    break;
                case "Layouts":
                    $path .=  "Scripts/Layouts/$directoryName/$fileName";
                    JwtUtil::putContent($path, $content);
                    break;
                case "Components":
                    $path .=  "Scripts/Directives/$directoryName/$fileName";                   
                    JwtUtil::putContent($path, $content);
                    break;
                case "Widgets":
                    $path .=  "Scripts/Components/$directoryName/$fileName";
                    JwtUtil::putContent($path, $content);
                    break;
                case "Modules":
                    $path .=  "Scripts/Modules/$directoryName/$fileName";
                    JwtUtil::putContent($path, $content);
                    break;
            }
            $res->isSuccess = true;
            $res->msg = "Successfully saved.";
      
            return $res;
        }
        public function IsExist($name, $mode){
               
            $path =  $this->rootPath; 
            switch ($mode)
            {
                case "Widgets":
                    $path .= "Scripts/Components/" . $name;
                    break;
                case "Components":
                    $path .=  "Scripts/Derictives/" . $name;
                    break;
                case "Modules":
                     $path .=  "Scripts/Modules/" . $name;
                    break;
            }
           
            return  array( 'exist' => JwtUtil::folderExists($path)) ;
        
        }
        public function CreateItem($name, $mode){
           
            $codeGen = new CodeGen();
            $codeGen->has_template_authorization=$this->has_template_authorization;
            $codeGen->app = $this->jwtApp;
            $codeGen->root=$this->rootPath;
            $codeGen->defaultNavigation = $this->defaultNavigation;
            return $codeGen->CreateItem($name, $mode);
        }
}
