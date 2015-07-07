<?php
require_once('/tools/AppManager.php');
require_once('/tools/JwtApp.php');
require_once('/tools/Layout.php');
require_once('/tools/JwtUtil.php');
require_once('/tools/CodeGen.php');
/*$max=json_encode(new Student);
Jwt_Service::putContent("jwt.json", $max);
$str= stripcslashes( Jwt_Service::getContent('jwt.json'));
$jwt= json_decode($str);*/

$jwtApp=new AppManager();

$layout=new Layout();
$layout->LayoutName='root007';
//$msg= $jwtApp->addLayout($layout);

$jwtApp->generateConfig();

echo "end";

?>