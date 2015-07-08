<?php
require_once('/tools/AppManager.php');
require_once('/tools/JwtApp.php');
require_once('/tools/Layout.php');
require_once('/tools/JwtUtil.php');
require_once('/tools/CodeGen.php');
require_once('/tools/Navigation.php');


$jwtApp=new AppManager();

$layout=new Layout();
$layout->LayoutName='root007';
$jwtApp->addLayout($layout);

$nav=new Navigation();
$nav->HasLayout='root007';
$nav->NavigationName='Nav1';
$nav->WidgetName='Home';

$jwtApp->addNavigation($nav);


$jwtApp->generateConfig();
//dirname("Scripts/Components/")
echo realpath(".") ;
//echo basename(__DIR__); will return the current directory name only
//echo basename(__FILE__); will return the current file name only
//Path to script: /data/html/cars/index.php
//echo basename(dirname(__FILE__)); //"cars"
?>