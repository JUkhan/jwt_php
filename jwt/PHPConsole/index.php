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

function listFolderFiles($dir){
    $ffs = scandir($dir);
    echo '<ol>';
    foreach($ffs as $ff){
        if($ff != '.' && $ff != '..'){
            echo '<li>'.$ff;
            if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);
            echo '</li>';
        }
    }
    echo '</ol>';
}
listFolderFiles('Scripts');
$jwtApp=new AppManager();

$layout=new Layout();
$layout->LayoutName='root007';
$jwtApp->addLayout($layout);

$jwtApp->generateConfig();
//dirname("Scripts/Components/")
echo realpath(".") ;
//echo basename(__DIR__); will return the current directory name only
//echo basename(__FILE__); will return the current file name only
//Path to script: /data/html/cars/index.php
//echo basename(dirname(__FILE__)); //"cars"
?>