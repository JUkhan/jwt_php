<?php
require_once('/tools/Jwt_Service.php');
require_once('/tools/Student.php');
$max=json_encode(new Student);
Jwt_Service::putContent("jwt.json", $max);
$str= stripcslashes( Jwt_Service::getContent('jwt.json'));
$jwt= json_decode($str);
echo $jwt->Name;

?>