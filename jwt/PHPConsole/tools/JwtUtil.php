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
    public static function sayHello(){
        return 'Hello Mamma';
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
}
