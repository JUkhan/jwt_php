<?php

/**
 * Jwt_Service short summary.
 *
 * Jwt_Service description.
 *
 * @version 1.0
 * @author JasimUddin
 */
class JwtUtil
{   
    public static function log($str){
         JwtUtil::putContent('log.txt', $str);
    }
    public static function putContent($path, $content){        
        $file=fopen($path, "w+");
        if($file==false){
            echo "Error in opening $file ";
            exit();
        }
        fwrite($file, $content);
        fclose($file);
    }
    public static function getContent($path){        
        return file_get_contents($path);
    }

    public static function GUID()
    {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public static function fileExists($fileName){
        return file_exists($fileName);
    }
    public static function folderExists($folderName){
        return is_dir($folderName);        
    }
    public static function makeDirectory($pathName){
        if(!JwtUtil::folderExists($pathName)){
            mkdir($pathName);
        }
    }
    public static function getSubDirectories($path){
        
        $ffs = scandir($path);
        $arr=array();
        foreach($ffs as $ff){
            if($ff != '.' && $ff != '..'){                
                if(is_dir($path.'/'.$ff)) {
                    $arr[]=$ff;
                }
                
            }
        }
        return $arr;
    }
    public static function getFiles($path){       
        $ffs = scandir($path);
        $arr=array();
        foreach($ffs as $ff){
            if($ff != '.' && $ff != '..'){                
                if(!is_dir($path.'/'.$ff)) {
                    $arr[]=$ff;
                }
                
            }
        }
        return $arr;
    }  
    
    public static function IsNullOrEmptyString($question){
        return (!isset($question) || trim($question)==='');
    }

    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    public static function array_find($arr, $callback){
        foreach($arr as $item){
            if($callback($item)==TRUE){
                return $item;
            }
        }
        return null;
    }
    public static function rename($pathx, $newName, $oldName)
    { 
        $path=$pathx . $oldName;
        if (!JwtUtil::folderExists($path)) return;
        if(JwtUtil::fileExists($path ."/". $oldName . ".html")){
            rename($path ."/". $oldName . ".html", $path ."/". $newName . ".html");
            //unlink($path ."/". $oldName . ".html");
        } 
        if(JwtUtil::fileExists($path ."/". $oldName . "Ctrl.js")){
            rename($path ."/". $oldName . "Ctrl.js", $path ."/". $newName . "Ctrl.js");
            //unlink($path ."/". $oldName . "Ctrl.js");
        }  
        if(JwtUtil::fileExists($path ."/". $oldName . "Svc.js")){
            rename($path ."/". $oldName . "Svc.js", $path ."/". $newName . "Svc.js");
            //unlink($path ."/". $oldName . "Svc.js");
        }  
        if(JwtUtil::fileExists($path ."/". $oldName . ".css")){
            rename($path ."/". $oldName . ".css", $path ."/". $newName . ".css");
            //unlink($path ."/". $oldName . ".css");
        }  
        rename($path, $pathx . $newName);
       
    }
}
