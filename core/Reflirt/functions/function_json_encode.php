<?php

 if(!function_exists('json_encode')){
       function json_encode($value){
               include_once(PHP_CLASS."/3rdparty/json.class.php");
               $json = new Services_JSON();
               return $json->encode($value);
       }
}
?>