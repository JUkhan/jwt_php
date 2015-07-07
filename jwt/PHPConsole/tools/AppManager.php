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
    
    public function addNavigation($navigation){
        try{
            $this->deserialize();
            if (!isset($this->jwtApp->layouts[$navigation->NavigationName]))
            {
                $navigation->_id=JwtUtil::GUID();
                $this->jwtApp->layouts[$navigation->NavigationName] = $navigation;
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
