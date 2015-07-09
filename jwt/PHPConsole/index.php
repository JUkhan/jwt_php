<?php
require_once('/tools/AppManager.php');
require_once('/tools/JwtApp.php');
require_once('/tools/Layout.php');
require_once('/tools/JwtUtil.php');
require_once('/tools/CodeGen.php');
require_once('/tools/Navigation.php');
require_once('/tools/View.php');

$jwtApp=new AppManager();

$layout=new Layout();
$layout->_id="7B1C63C5-CA3B-42CE-85ED-C409C2230303";
$layout->LayoutName='test_3';
//$jwtApp->addLayout($layout);

$nav=new Navigation();
$nav->_id="3E42FEDE-1E1E-49DE-99EA-0ABDB04964E4";
$nav->HasLayout='root';
$nav->NavigationName='Nav1';
$nav->WidgetName='Company_1';

$view=new View();
$view->ViewName="view1";
$view->WidgetName="Company";

$nav->Views[]=$view;

//$jwtApp->addNavigation($nav);
$jwtApp->updateNavigation($nav);
//$jwtApp->updateLayout($layout);
//$jwtApp->removeLayout($layout);

//$jwtApp->generateConfig();
//dirname("Scripts/Components/")
echo realpath(".") ;
//echo basename(__DIR__); will return the current directory name only
//echo basename(__FILE__); will return the current file name only
//Path to script: /data/html/cars/index.php
//echo basename(dirname(__FILE__)); //"cars"
?>