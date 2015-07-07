<?php

/**
 * JwtApp short summary.
 *
 * JwtApp description.
 *
 * @version 1.0
 * @author Jasim.Uddin
 */
require_once('CodeGen.php');

class JwtApp
{
    public $Name;

    public function __construct(){

        $this->Name='app';
    }
    public $Layouts =array();
    
    public function addLayout($layout)
    {           
        if (!isset($this->Layouts[$layout->LayoutName]))
        {
            $this->Layouts[$layout->LayoutName] = $layout;
        }
        
    }
   
    public $Navigations =array();
    public function addNavigation($navigation)
    {
        
        if (!isset($this->Navigation[$navigation.NavigationName]))
        {
            $this->Navigations[$navigation.NavigationName] = $navigation;
        }
        
    }
    public function execute()
    {
        $codeGen=  new CodeGen($this);
        $codeGen->execute();

    }
}
