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
    public $Navigations =array();   
    public function execute()
    {
        $codeGen=  new CodeGen($this);
        $codeGen->execute();
    }
}
