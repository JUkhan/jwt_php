<?php

/**
 * Jwt_Service short summary.
 *
 * Jwt_Service description.
 *
 * @version 1.0
 * @author JasimUddin
 */
class Jwt_Service
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
}
