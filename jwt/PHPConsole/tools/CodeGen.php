<?php

/**
 * CodeGen short summary.
 *
 * CodeGen description.
 *
 * @version 1.0
 * @author Jasim.Uddin
 */
require_once('JwtUtil.php');
class CodeGen
{
    private static $TAB1 = "\t";
    private static $TAB2 = "\t\t";
    private static $TAB3 = "\t\t\t";
    private static $TAB4 = "\t\t\t\t";

    public $app;
    public $root="";
    public $defaultNavigation="";

    public function __construct($jwtApp=null){
        $this->app=$jwtApp;
    }

    public function execute(){
        JwtUtil::makeDirectory($this->root."Scripts");
        JwtUtil::makeDirectory($this->root."Scripts/Components");
        JwtUtil::makeDirectory($this->root."Scripts/Layouts");
    }
}
