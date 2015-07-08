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

    public function __construct($rootPath="", $defaultNavigation = ""){
        $this->rootPath=$rootPath;
        $this->defaultNavigation=$defaultNavigation;
    }
    private function serialize()
    {
        JwtUtil::putContent('jwt.json', json_encode($this->jwtApp));
    }
    private function deserialize()
    {        
        $obj=json_decode(JwtUtil::getContent('jwt.json'), true);
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
            if (!isset($this->jwtApp->Layouts[$layout->LayoutName]))
            {
                $layout->_id=JwtUtil::GUID();
                $this->jwtApp->Layouts[$layout->LayoutName] = $layout;
                $this->serialize();
            }else{
                return "$layout->LayoutName already exist";
            }
            return $layout->_id;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    private function array_find($arr, $id){
        foreach($arr as $item){
            if($item['_id']==$id){
                return $item;
            }
        }
        return null;
    }
    public function updateLayout($layout)
    {           
        try
        { 
            
            $this->deserialize();
            $temp = $this->array_find($this->jwtApp->Layouts, $layout->_id);           
            if ($temp== null)
            {
                return "$layout->LayoutName not exist.";
            }
            $lname=$temp["LayoutName"];
            $flag=FALSE;
            if (!JwtUtil::IsNullOrEmptyString($layout->LayoutName) && ($layout->LayoutName != $temp['LayoutName'])){
                JwtUtil::rename($this->rootPath . "Scripts/Layouts/", $layout->LayoutName, $temp['LayoutName']);
                $flag=TRUE;
            }
            $this->updateParentLayout($temp['LayoutName'], $layout->LayoutName);

            $temp['LayoutName'] = $layout->LayoutName;
            $temp['Extend'] = $layout->Extend;
            $this->jwtApp->Layouts[$lname]=$temp;
            if ($flag){
                $this->jwtApp->Layouts[$layout->LayoutName]=$temp;
                unset($this->jwtApp->Layouts[$lname]);
            }
            $this->serialize();
            return "Successfully Updted.";
            
        }
        catch (Exception $ex)
        {
            
            return $ex->getMessage();
        }
    }

    public function updateParentLayout($oldName, $newName)
    {
        
        if ($oldName == $newName) return;
        foreach ($this->jwtApp->Layouts as $item)
        {
            if ($item['Extend'] == $oldName){
                $item['Extend'] = $oldName;
                $this->jwtApp->Layouts[$oldName]=$item;

            }
        }
        
        foreach ($this->jwtApp->Navigations as $item)
        {
            if ($item['HasLayout'] == $oldName){
                $item['HasLayout'] = $newName;
                $this->jwtApp->Navigations[$item["NavigationName"]]=$item;
            }
        }
        
    }
    public function removeLayout($layout){
        $this->deserialize();
        $temp = $this->array_find($this->jwtApp->Layouts, $layout->_id);           
        if ($temp== null)
        {
            return "$layout->LayoutName not exist.";
        }
        $arr=&$this->jwtApp->Layouts;
        unset($arr[$temp['LayoutName']]);
        $this->jwtApp->Layouts=&$arr;
        $this->generateConfig();
        return "Successfully Removed.";
    }
    public function GetLayoutList(){
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
            if (!isset($this->jwtApp->Navigations[$navigation->NavigationName]))
            {
                $navigation->_id=JwtUtil::GUID();
                $this->jwtApp->Navigations[$navigation->NavigationName] = $navigation;
                $this->serialize();
            }else{
                return "$navigation->NavigationName already exist";
            }
            return $navigation->_id;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function generateConfig()
    {
        try
        {                
            $this->deserialize();
            $codeGen = new CodeGen();
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
